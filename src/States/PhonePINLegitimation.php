<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;
use Tualo\Office\OnlineVote\Exceptions\SessionInvalidException;

class PhonePINLegitimation implements State{

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        return $stateMachine->getNextState();
    }

    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        if (($nextState = $stateMachine->checkLogout())!='') return $nextState;
        if (!$stateMachine->voter()->validSession()) throw new SessionInvalidException();
        $nextState = 'Tualo\Office\OnlineVote\States\PhonePINLegitimation';
        if (
            isset($_REQUEST['pin']) && 
            ($_REQUEST['pin']==$stateMachine->voter()->getRequiredPhonePIN())
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