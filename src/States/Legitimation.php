<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;

class Legitimation implements State{

    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        if (($logoutState = $stateMachine->checkLogout())!='') return $logoutState;
        /*
        if (count(WMStateMachine::getInstance()->voter()->availableBallotpapers())==1){
            $nextState = 'Tualo\Office\OnlineVote\States\Ballotpaper';
        }else{
            $nextState = 'Tualo\Office\OnlineVote\States\ChooseBallotpaper';
        }
        */
        return 'Tualo\Office\OnlineVote\States\Legitimation';
    }
}