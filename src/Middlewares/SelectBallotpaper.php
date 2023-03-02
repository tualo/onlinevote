<?php
namespace Tualo\Office\OnlineVote\Middlewares;

use Exception;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\OnlineVote\Middlewares\Init;

use Tualo\Office\Basic\IMiddleware;

class SelectBallotpaper /*extends CMSMiddleWare*/{
    public static function run(&$request,&$result){

        if (!isset($_SESSION['current_state'])) return;
        if (!isset($_SESSION['pug_session'])) return;

        $_SESSION['pug_session']['single_vote']=1;

        if ( (Init::$next_state == 'choose-ballotpaper') && (count($_SESSION['pug_session']['available_ballotpapers'])==1)) {
            // wenn der nächste zustand die auswahl sein soll, und es nur einen sz gibt können wir gleich wechseln.
            $_SESSION['pug_session']['voter_id'] = $_SESSION['pug_session']['available_ballotpapers'][0]['voter_id'];
            $_SESSION['pug_session']['stimmzettel_id'] = $_SESSION['pug_session']['available_ballotpapers'][0]['stimmzettel'];
            $_SESSION['pug_session']['ballotpaper_id'] =$_SESSION['pug_session']['available_ballotpapers'][0]['ballotpaper_id'];
            Init::$next_state = 'ballotpaper';
        }
        
        if ( $_SESSION['current_state'] == 'choose-ballotpaper' ){
            if (isset($_REQUEST['choose-index'])){
                // ggf kennung merken, wenn gesammelt für einen sz type abgegeben wird.
                App::logger('SelectBallotpaper')->info(" ggf kennung merken, wenn gesammelt für einen sz type abgegeben wird ");
                if (isset($_REQUEST['single_vote']) && ($_REQUEST['single_vote']=='0')) $_SESSION['pug_session']['single_vote']=0;

                $_SESSION['pug_session']['voter_id'] =          $_SESSION['pug_session']['available_ballotpapers'][$_REQUEST['choose-index']]['voter_id'];
                $_SESSION['pug_session']['stimmzettel_id'] =    $_SESSION['pug_session']['available_ballotpapers'][$_REQUEST['choose-index']]['stimmzettel'];
                $_SESSION['pug_session']['ballotpaper_id'] =    $_SESSION['pug_session']['available_ballotpapers'][$_REQUEST['choose-index']]['ballotpaper_id'];
                Init::$next_state = 'ballotpaper';
            }else{
                // nix machen
                App::logger('SelectBallotpaper')->warning(   " scheint neu geladen zu haben ");
                Init::$next_state = 'choose-ballotpaper';
            }
        }
    }
}

