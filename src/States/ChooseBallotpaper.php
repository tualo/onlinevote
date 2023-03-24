<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;
use Tualo\Office\OnlineVote\Exceptions\SessionInvalidException;

class ChooseBallotpaper implements State{

    private function testInt(string $val){
        preg_match('/[^0-9]/', $val, $matches);
        return count($matches)==0;
    }

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        return $stateMachine->getNextState();
    }
    
    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        if (($logoutState = $stateMachine->checkLogout())!='') return $logoutState;
        if (!$stateMachine->voter()->validSession()) throw new SessionInvalidException();

        // singleBallotpaper
        $stateMachine->voter()->setGroupedVote(false);
        if(
            isset($_REQUEST['ballotpaperIndex']) &&
            $this->testInt($_REQUEST['ballotpaperIndex']) &&
            ( $stateMachine->voter()->selectBallotpaper(intval($_REQUEST['ballotpaperIndex'])) )
        ){
            if (isset($_REQUEST['grouped'])&&($_REQUEST['grouped']==1)) $stateMachine->voter()->setGroupedVote(true);
            return 'Tualo\Office\OnlineVote\States\Ballotpaper';
        }
        return 'Tualo\Office\OnlineVote\States\ChooseBallotpaper';
    }
}