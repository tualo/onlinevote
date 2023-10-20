<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;
use Tualo\Office\OnlineVote\Exceptions\SessionInvalidException;
use Tualo\Office\Basic\TualoApplication as App;

class Legitimation implements State{

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        return $stateMachine->getNextState();
    }

    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        if (($nextState = $stateMachine->checkLogout())!='') return $nextState;
        if (!$stateMachine->voter()->validSession()) throw new SessionInvalidException();
        $nextState = 'Tualo\Office\OnlineVote\States\Legitimation';
        if (
            isset($_REQUEST['legitimation_confirmed']) && 
            ($_REQUEST['legitimation_confirmed']==1)
        ){ 
            $config = App::get('configuration');
            
                if ( isset($config['onlinevote']) 
                    && isset($config['onlinevote']['phonePINLegitimation']) 
                    && $config['onlinevote']['phonePINLegitimation']=='1'
                ){
                    $nextState = 'Tualo\Office\OnlineVote\States\PhonePINLegitimation';
                    if (isset($_REQUEST['phonenumber'])){
                        $pin = rand(100000,999999);
                        $stateMachine->voter()->setPhonenumber($_REQUEST['phonenumber']);
                        $stateMachine->voter()->setRequiredPhonePIN((string)$pin);
                        \Tualo\Office\SMS\SMS::sendMessage("Ihr Online-Wahl-Code lautet: {$pin}",$_REQUEST['phonenumber']);
                    }
                }else{

            
                    if (count(WMStateMachine::getInstance()->voter()->availableBallotpapers())==1){
                        $stateMachine->voter()->selectBallotpaper(0);
                        $nextState = 'Tualo\Office\OnlineVote\States\Ballotpaper';
                    }else{
                        $nextState = 'Tualo\Office\OnlineVote\States\ChooseBallotpaper';
                    }
                }
        } 
        return $nextState;
    }
}