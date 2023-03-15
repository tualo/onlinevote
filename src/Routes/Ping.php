<?php
namespace Tualo\Office\OnlineVote\Routes;

use Exception;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;

class Ping implements IRoute{
 
    public static function register(){
        BasicRoute::add('/onlinevote/ping',function(){
            App::contenttype('application/json');
            App::result('success',true);
        },['get','post'],true);
    }
}