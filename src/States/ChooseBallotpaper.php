<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;
use Tualo\Office\OnlineVote\Exceptions\SessionInvalidException;

class ChooseBallotpaper implements State{

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        return $stateMachine->getNextState();
    }
    
    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        if (($logoutState = $stateMachine->checkLogout())!='') return $logoutState;
        if (!$stateMachine->voter()->validSession()) throw new SessionInvalidException();

        // singleBallotpaper
        if(
            isset($_REQUEST['ballotpaperIndex']) &&
            is_int($_REQUEST['ballotpaperIndex']) &&
            ( $stateMachine->voter()->selectBallotpaper(intval($_REQUEST['ballotpaperIndex'])) )
        ){
            return 'Tualo\Office\OnlineVote\States\Ballotpaper';
        }
        return 'Tualo\Office\OnlineVote\States\ChooseBallotpaper';
    }
}