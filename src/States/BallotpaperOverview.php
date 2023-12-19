<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\WMStateMachine;
use Tualo\Office\OnlineVote\Exceptions\SessionInvalidException;
use Tualo\Office\OnlineVote\Exceptions\BallotPaperAllreadyVotedException;
use Tualo\Office\OnlineVote\Exceptions\BallotPaperIsSavingException;
use Ramsey\Uuid\Uuid;
class BallotpaperOverview implements State{

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        if (!$stateMachine->voter()->validSession()) throw new SessionInvalidException();
        if ($stateMachine->voter()->getCurrentBallotpaper()->checkLocal()) throw new BallotPaperAllreadyVotedException();


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


    public function transition_loop($stateMachine, &$request,&$result):string {
            $ballotpaperId = $stateMachine->voter()->getCurrentBallotpaper()->getBallotpaperId();
            $storedVotes = $stateMachine->voter()->getCurrentBallotpaper()->getVotes();
            $stateMachine->voter()->getCurrentBallotpaper()->save( );

            $stateMachine->voter()->removeCurrentBallotpaper();
            if (count($stateMachine->voter()->availableBallotpapers())==0){
                $stateMachine->voter(true);
                $nextState = 'Tualo\Office\OnlineVote\States\SaveCompleted';
            }else{
                if (
                    $stateMachine->voter()->getGroupedVote() &&
                    count($stateMachine->voter()->availableBallotpapers($ballotpaperId))>0
                ){
                    set_time_limit(60);
                    $hashMap = $stateMachine->voter()->getCurrentBallotpaper()->getHashMap();
                    $idMap = $stateMachine->voter()->getCurrentBallotpaper()->getIdMap();

                    $config = $stateMachine->voter()->getCurrentBallotpaper()->getConfiguration();
                    $configgroups = $stateMachine->voter()->getCurrentBallotpaper()->getConfigurationGroups();

                    App::logger('BallotpaperOverview(State)')->debug('getGroupedVote is true, '.count($stateMachine->voter()->availableBallotpapers($ballotpaperId)).' availableBallotpapers');

                    $stateMachine->voter()->setCurrentBallotpaper($stateMachine->voter()->availableBallotpapers($ballotpaperId)[0]);
                    App::logger('BallotpaperOverview(State)')->debug('setCurrentBallotpaper to '.$stateMachine->voter()->getCurrentBallotpaper()->getBallotpaperId());
                    
                    $stateMachine->voter()->getCurrentBallotpaper()->setConfiguration($config  ,$configgroups);
                    $stateMachine->voter()->getCurrentBallotpaper()->setVotesIntern($hashMap,$idMap,$storedVotes);

                    App::logger('BallotpaperOverview(State)')->debug('setVotesIntern to '.print_r($storedVotes,true));

                    return $this->transition_loop($stateMachine,$request,$result);
                }
                $nextState = 'Tualo\Office\OnlineVote\States\SaveCompletedChooseBallotpaper';
            }
            return $nextState;
    }

    public function lockedSaving($stateMachine){
        $db = $stateMachine->db();
        $locked = $db->singleRow('
            select 
                session_id 
            from 
                voter_sessions_save_state 
            where 
                session_id={session_id} 
        ',  [
            'session_id' => session_id()
        ]);
        return ($locked!==false);
    }

    public function lockSaving($stateMachine){
        $db = $stateMachine->db();
        $db->direct('
            replace voter_sessions_save_state (session_id,created_at) values ({session_id},now())
        ',  [
            'session_id' => session_id()
        ]);
    }

    public function unlockSaving($stateMachine){
        $db = $stateMachine->db();
        $db->direct('
            delete from voter_sessions_save_state  where session_id = {session_id} 
        ',  [
            'session_id' => session_id()
        ]);
    }
    

    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        if (($nextState = $stateMachine->checkLogout())!='') return $nextState;
        if (!$stateMachine->voter()->validSession()) throw new SessionInvalidException();
        if ($stateMachine->voter()->getCurrentBallotpaper()->checkLocal()) throw new BallotPaperAllreadyVotedException();

        $nextState = 'Tualo\Office\OnlineVote\States\BallotpaperOverview';
        App::logger('BallotpaperOverview(State)')->debug(json_encode($_REQUEST) );
        if (
            isset($_REQUEST['correct']) && $_REQUEST['correct']==1
        ){
            $nextState = 'Tualo\Office\OnlineVote\States\Ballotpaper';
        }else  if (
            isset($_REQUEST['save']) && $_REQUEST['save']==1
        ){
            if ( $this->lockedSaving($stateMachine)  ){ 
                App::logger('BallotpaperOverview(SAVING)')->warning('saving_ballotpaper_loop_id (before error)');
                throw new BallotPaperIsSavingException(); 
            }
            $this->lockSaving($stateMachine);
            // sleep(10);

            App::logger('BallotpaperOverview(SAVING)')->warning('start transition loop ');
            $nextState = $this->transition_loop($stateMachine,$request,$result);
            App::logger('BallotpaperOverview(SAVING)')->warning('stop transition loop ');

            $this->unlockSaving($stateMachine);
            
        }
        
        App::logger('BallotpaperOverview(State)')->debug("here" );
        return $nextState;
    }
}