<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;

class Maintenance implements State{

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        return $stateMachine->getNextState();
    }
    
    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        
        return 'Tualo\Office\OnlineVote\States\Maintenance';
    }
}