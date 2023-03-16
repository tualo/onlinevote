<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\WMStateMachine;
use Tualo\Office\OnlineVote\Exceptions\SessionInvalidException;
use Tualo\Office\OnlineVote\Exceptions\BallotPaperAllreadyVotedException;

class Ballotpaper implements State{

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        if (!$stateMachine->voter()->validSession()) throw new SessionInvalidException();
        if ($stateMachine->voter()->getCurrentBallotpaper()->checkLocal()) throw new BallotPaperAllreadyVotedException();

        $result['ballotpaper'] = $db->singleRow('select * from view_website_ballotpaper where id = {id}',[
            'id'=> $stateMachine->voter()->getCurrentBallotpaper()->getBallotpaperId()
        ]);

        $ballotpaper_groups = $db->direct('select view_website_ballotpaper_groups.*,0 __checkcount from view_website_ballotpaper_groups where ballotpaper_id = {id} order by id',$result['ballotpaper']);
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
        if (!$stateMachine->voter()->validSession()) throw new SessionInvalidException();
        if ($stateMachine->voter()->getCurrentBallotpaper()->checkLocal()) throw new BallotPaperAllreadyVotedException();

        if (($nextState = $stateMachine->checkLogout())!='') return $nextState;
        $nextState = 'Tualo\Office\OnlineVote\States\Ballotpaper';

        if (
            isset($_REQUEST['send']) && 
            $_REQUEST['send']=1
        ){
            // kreuze lesen
            if(!isset($_REQUEST['candidate'])) $_REQUEST['candidate']=[];
            $stateMachine->voter()->getCurrentBallotpaper()->setVotes($_REQUEST['candidate']);
            $nextState = 'Tualo\Office\OnlineVote\States\BallotpaperOverview';
        }
        
        App::logger('Ballotpaper(State)')->debug("here" );
        return $nextState;
    }
}