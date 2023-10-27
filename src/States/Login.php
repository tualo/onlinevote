<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;
use Tualo\Office\Basic\TualoApplication as App;

use Tualo\Office\OnlineVote\Exceptions\BlockedUser;
use Tualo\Office\OnlineVote\Exceptions\VoterLoginFailed;
use Tualo\Office\OnlineVote\Exceptions\LoginAllreadyVotedOnline;
use Tualo\Office\OnlineVote\Exceptions\LoginAllreadyVotedOffline;


class Login implements State {

    public static function login($username,$password,&$nextState):bool{
        $stateMachine = WMStateMachine::getInstance();
        $stateMachine->logger('Login(State)')->info( "login  from ".$stateMachine->ip()." - ".__LINE__." ".__FILE__." ");
        
        if ($stateMachine->voter()->isBlocked($username)===true){ 
            $stateMachine->logger('Login(State)')->info( "login blocked {$username} - ".__LINE__." ".__FILE__." ");
            throw new BlockedUser();
        }
        $res = $stateMachine->voter(true)->login($username,$password);
        $stateMachine->logger('Login(State)')->warning( "login res ".$res  );
        if ($res=='ok'){
            $stateMachine->logger('Login(State)')->debug( "login ok {$username} - ".__LINE__." ".__FILE__." ");
            return true;
        }else if ($res=='allready-voted-online'){
            $stateMachine->logger('Login(State)')->info( "login AllreadyVoted {$username} - ".__LINE__." ".__FILE__." ");
            throw new LoginAllreadyVotedOnline('login AllreadyVoted online '.$username);
        }else if ($res=='allready-voted-offline'){
            $stateMachine->logger('Login(State)')->info( "login AllreadyVoted {$username} - ".__LINE__." ".__FILE__." ");
            throw new LoginAllreadyVotedOffline('login AllreadyVoted offline '.$username);
        }else {
            $stateMachine->logger('Login(State)')->info( "login Error {$username} - ".__LINE__." ".__FILE__." ");
            throw new VoterLoginFailed('login Error '.$username);
        }
    }

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        return $stateMachine->getNextState();
    }

    public function transition(&$request,&$result):string {
        $nextState = 'Tualo\Office\OnlineVote\States\Login';
        $stateMachine = WMStateMachine::getInstance();


        if (
            isset($_REQUEST[$stateMachine->usernamefield()]) &&
            isset($_REQUEST[$stateMachine->passwordfield()])
        ){
            $result['p1'] = $_REQUEST[$stateMachine->usernamefield()];
            $result['p2'] = $_REQUEST[$stateMachine->passwordfield()];
            $stateMachine->logger('Login(State)')->info("user and pw read  from ".$stateMachine->ip()." - ".__LINE__." ".__FILE__." ");
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
            ($_REQUEST['accept']==1 || $_REQUEST['accept']=='on' )
        ){
            $username = $result['p1'];
            $password = $result['p2'];
            if (self::login($username,$password,$nextState)){
                $nextState = 'Tualo\Office\OnlineVote\States\Legitimation';
                if ( App::configuration('onlinevote','skipLegitimation','0') == '1' ){
                    if (count(WMStateMachine::getInstance()->voter()->availableBallotpapers())==1){
                        $stateMachine->voter()->selectBallotpaper(0);
                        $nextState = 'Tualo\Office\OnlineVote\States\Ballotpaper';
                    }else{
                        $nextState = 'Tualo\Office\OnlineVote\States\ChooseBallotpaper';
                    }
                }
                // hier müsste die Legitimation kommen, ggf mit Bestätigung für verschiedenen Unternehmen
                $stateMachine->logger('Login(State)')->debug( "login successfully from ".$stateMachine->ip()." - ".__LINE__." ".__FILE__." " );
            }else{
                $stateMachine->logger('Login(State)')->warning( "login failed  from ".$stateMachine->ip()." - ".__LINE__." ".__FILE__." " );
                throw new VoterLoginFailed('Laut RemoteSystem bereits gewählt');
            }
        }else{
            $stateMachine->logger('Login(State)')->warning( "not in login state  from ".$stateMachine->ip()." - ".$stateMachine->getCurrentState()." - ".$stateMachine->getNextState()." - ".__LINE__." ".__FILE__." ");
        }
        $stateMachine->logger('Login(State)')->error('remove me in production '." - ".__LINE__." ".__FILE__." ");
        return $nextState;
    }
}