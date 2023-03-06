<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;

class ChooseBallotpaper implements State{

    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        if (($logoutState = $stateMachine->checkLogout())!='') return $logoutState;
        // singleBallotpaper
        if(
            isset($_REQUEST['ballotpaperIndex']) &&
            is_int($_REQUEST['ballotpaperIndex']) &&
            ( $stateMachine->voter()->selectBallotpaper(intval($_REQUEST['ballotpaperIndex'])) )
        ){
            return 'Tualo\Office\OnlineVote\States\Ballotpaper';
        }
        return 'Tualo\Office\OnlineVote\States\ChooseBallotpaper';
    }
}