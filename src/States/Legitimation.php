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
            
            if ( App::configuration('onlinevote','extendedLegitimation','0') == '1' ){
                if (isset($_REQUEST['lastname'])){
                    $stateMachine->voter()->setLastName($_REQUEST['lastname']);
                }
                if (isset($_REQUEST['firstname'])){
                    $stateMachine->voter()->setLastName($_REQUEST['firstname']);
                }
                if (isset($_REQUEST['birthdate'])){
                    $stateMachine->voter()->setBirthdate($_REQUEST['birthdate']);
                }
                if (isset($_REQUEST['confirmed_birthdate'])){
                    $stateMachine->voter()->setConfirmedBirthdate($_REQUEST['confirmed_birthdate']);
                }
            }
            
            if ( App::configuration('onlinevote','phonePINLegitimation','0') == '1' ){
                if (isset($_REQUEST['phonenumber'])){
                    $nextState = 'Tualo\Office\OnlineVote\States\PhonePINLegitimation';
                    $pin = rand(100000,999999);
                    $stateMachine->voter()->setPhonenumber($_REQUEST['phonenumber']);
                    $stateMachine->voter()->setRequiredPhonePIN((string)$pin);
                    if(!class_exists('\Tualo\Office\SMS\SMS')) throw new \Exception('tualo/sms module not installed!');
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