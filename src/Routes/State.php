<?php
namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\OnlineVote\Handshake;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;

class State implements IRoute{
 
    public static function register(){
        BasicRoute::add('/onlinevote/state',function(){
            App::contenttype('application/json');
            App::result('success',false);
            try{
                $session = App::get('session');
                $db = $session->getDB();

                $param = Handshake::parameter();
                App::result('starttime',isset($param['starttime'])?$param['starttime']:null);
                App::result('stoptime',isset($param['stoptime'])?$param['stoptime']:null);
                App::result('timezone',date_default_timezone_get());
                App::result('php_time',date('Y-m-d H:i:s'));
                App::result('db_time',$db->singleValue('select now() as n',[],'n'));


                Handshake::pingRemote() || throw new \Exception('Der Remote Server ist nicht erreichbar');
                App::result('success',true);
             }catch(\Exception $e){
                 App::result('msg', $e->getMessage());
             }
        },['get','post'],true);
    }
}