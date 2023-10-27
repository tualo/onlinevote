<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States\failures;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;

class SessionInvalid implements State{

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
       return $stateMachine->getNextState();
    }

    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        if (($nextState = $stateMachine->checkLogout())!='') return $nextState;
        $stateMachine->voter(true);
        $nextState = 'Tualo\Office\OnlineVote\States\Login';
        return $nextState;
    }
}