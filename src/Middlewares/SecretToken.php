<?php
namespace Tualo\Office\OnlineVote\Middlewares;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\OnlineVote\Middlewares\Init;

use Tualo\Office\Basic\IMiddleware;

class SelectBallotpaper /*extends CMSMiddleWare*/{
    public static function run(&$request,&$result){

        if (
            isset($_SESSION['pug_session']) && 
            isset($_SESSION['pug_session']['login_token']) && 
            isset($_SESSION['api']) &&
            ($_SESSION['api']==1)
        ){

            $_SESSION['pug_session']['secret_token'] = TualoApplicationPGP::decrypt( 
                $_SESSION['api_private'],
                $_SESSION['pug_session']['login_token']
            );
        }
        
    }
}

