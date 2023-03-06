<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;

class Login implements State {

    


    public static function login($username,$password,&$nextState):bool{
        $stateMachine = WMStateMachine::getInstance();
        $stateMachine->logger('Login(State)')->info( "login  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
        
        if ($stateMachine->voter()->isBlocked($username)===true){ 
            $nextState = 'Tualo\Office\OnlineVote\States\BlockedUser';
            $stateMachine->logger('Login(State)')->info( "login blocked {$username} - ".__LINE__." ".__FILE__." ");
            return false; 
        }

        $res = $stateMachine->voter(true)->login($username,$password);
        if ($res==='ok'){
            $stateMachine->logger('Login(State)')->debug( "login ok {$username} - ".__LINE__." ".__FILE__." ");
            return true;
        }else if ($res==='allready-voted'){
            $nextState = 'Tualo\Office\OnlineVote\States\AllreadyVoted';
            $stateMachine->logger('Login(State)')->info( "login AllreadyVoted {$username} - ".__LINE__." ".__FILE__." ");
            return false;
        }else {
            $stateMachine->logger('Login(State)')->info( "login Error {$username} - ".__LINE__." ".__FILE__." ");
            $nextState = 'Tualo\Office\OnlineVote\States\Error';
            return false;
        }
    }

    public function transition(&$request,&$result):string {
        $nextState = 'Tualo\Office\OnlineVote\States\Login';
        $stateMachine = WMStateMachine::getInstance();

        if (
            isset($_REQUEST[$stateMachine->usernamefield]) &&
            isset($_REQUEST[$stateMachine->passwordfield])
        ){
            $result['p1'] = $_REQUEST[$stateMachine->usernamefield];
            $result['p2'] = $_REQUEST[$stateMachine->passwordfield];
            $stateMachine->logger('Login(State)')->info("user and pw read  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
        }else if (
            isset($_REQUEST['c']) && 
            is_string($_REQUEST['c']) && 
            (preg_match("/[a-z0-9\-\.]+/i",$_REQUEST['c'])>0) &&
            (strlen($_REQUEST['c'])==16)
        ){
            $result['p1'] = substr($_REQUEST['c'],0,8);
            $result['p2'] = substr($_REQUEST['c'],8,8);
        }


        if ( 
            isset($result['p1']) && 
            isset($result['p2']) && 
            isset($_REQUEST['accept']) &&
            ($_REQUEST['accept']==1)
        ){
            $username = $result['p1'];
            $password = $result['p2'];
            if (self::login($username,$password,$nextState)){
                // hier müsste die Legitimation kommen, ggf mit Bestätigung für verschiedenen Unternehmen
                if (count(WMStateMachine::getInstance()->voter()->availableBallotpapers())==1){
                    $nextState = 'Tualo\Office\OnlineVote\States\Ballotpaper';
                }else{
                    $nextState = 'Tualo\Office\OnlineVote\States\ChooseBallotpaper';
                }
            }else{
                $stateMachine->logger('Login(State)')->warning( "login failed  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." " );
            }
        }else{
            $stateMachine->logger('Login(State)')->warning( "not in login state  from ".$_SESSION['IP_ADDRESS']." - ".$stateMachine->getCurrentState()." - ".$stateMachine->getNextState()." - ".__LINE__." ".__FILE__." ");
            
        }

        return $nextState;
    }
}