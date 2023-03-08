<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;

class Logout implements State{

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        return $stateMachine->getNextState();
    }
    
    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();

        if (
            isset($_REQUEST['logout_confirmed']) && 
            ($_REQUEST['logout_confirmed']==1)
        ){ 
            $stateMachine->voter(true);
            return 'Tualo\Office\OnlineVote\States\Login'; 
        }else if (
            isset($_REQUEST['logout_canceled']) && 
            ($_REQUEST['logout_canceled']==1)
        ){
            return $stateMachine->getSavedState();
        }
        return 'Tualo\Office\OnlineVote\States\Logout';
    }
}