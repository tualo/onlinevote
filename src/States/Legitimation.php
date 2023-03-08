<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;

class Legitimation implements State{

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        return $stateMachine->getNextState();
    }

    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        if (($nextState = $stateMachine->checkLogout())!='') return $nextState;
        $nextState = 'Tualo\Office\OnlineVote\States\Legitimation';
        if (
            isset($_REQUEST['legitimation_confirmed']) && 
            ($_REQUEST['legitimation_confirmed']==1)
        ){ 
            if (count(WMStateMachine::getInstance()->voter()->availableBallotpapers())==1){
                $stateMachine->voter()->selectBallotpaper(0);
                $nextState = 'Tualo\Office\OnlineVote\States\Ballotpaper';
            }else{
                $nextState = 'Tualo\Office\OnlineVote\States\ChooseBallotpaper';
            }
        } 
        return $nextState;
    }
}