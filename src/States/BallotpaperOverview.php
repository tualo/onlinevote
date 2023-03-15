<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\WMStateMachine;
use Tualo\Office\OnlineVote\Exceptions\SessionInvalidException;
use Tualo\Office\OnlineVote\Exceptions\BallotPaperAllreadyVotedException;

class BallotpaperOverview implements State{

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        $result['ballotpaper'] = $db->singleRow('select * from view_website_ballotpaper where id = {id}',[
            'id'=> $stateMachine->voter()->getCurrentBallotpaper()->getBallotpaperId()
        ]);
        $ballotpaper_groups = $db->direct('select view_website_ballotpaper_groups.*,uuid() x from view_website_ballotpaper_groups where ballotpaper_id = {id} order by id',$result['ballotpaper']);
        foreach($ballotpaper_groups as $key=>$ballotpaper_group){
            try{
                $ballotpaper_groups[$key]['candidates'] = $db->direct('select * from view_website_candidates where stimmzettelgruppen = concat({id},"|0") order by barcode',$ballotpaper_group);
            }catch(\Exception $e){
                WMStateMachine::getInstance()->logger('Ballotpaper(State)')->error($e->getMessage());
            }
        }
        $result['ballotpaper_groups'] = $ballotpaper_groups;
        $stateMachine->voter()->getCurrentBallotpaper()->setConfiguration($result['ballotpaper'] ,$result['ballotpaper_groups']);
        return $stateMachine->getNextState();
    }

    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        if (($nextState = $stateMachine->checkLogout())!='') return $nextState;
        if (!$stateMachine->voter()->validSession()) throw new SessionInvalidException();
        if ($stateMachine->voter()->getCurrentBallotpaper()->allreadyVoted()) throw new BallotPaperAllreadyVotedException();

        $nextState = 'Tualo\Office\OnlineVote\States\BallotpaperOverview';
        App::logger('BallotpaperOverview(State)')->debug(json_encode($_REQUEST) );
        if (
            isset($_REQUEST['correct']) && 
            $_REQUEST['correct']=1
        ){
            $nextState = 'Tualo\Office\OnlineVote\States\Ballotpaper';
        }else  if (
            isset($_REQUEST['save']) && 
            $_REQUEST['save']=1
        ){
            App::logger('BallotpaperOverview(State)')->debug('here');
            $stateMachine->voter()->getCurrentBallotpaper()->save( );
            $stateMachine->voter(true);
            $nextState = 'Tualo\Office\OnlineVote\States\SaveCompleted';
        }
        
        App::logger('BallotpaperOverview(State)')->debug("here" );
        return $nextState;
    }
}