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
                App::result('interrupted',isset($param['interrupted'])?$param['interrupted']:false);
                App::result('timezone',date_default_timezone_get());
                App::result('php_time',date('Y-m-d H:i:s'));
                App::result('db_time',$db->singleValue('select now() as n',[],'n'));

                try{
                    App::result('active_voters',$db->singleValue('
                    select count(*) c from voters where contact > now() + interval - 15 minute and completed=0
                    ',[],'c'));
                }catch(\Exception $e){

                }

                try{
                    Handshake::pingRemote() || throw new \Exception('Der Remote Server ist nicht erreichbar');
                    App::result('remoteError',false);
                }catch(\Exception $e){
                    App::result('remoteError',true);
                }
                App::result('success',true);
             }catch(\Exception $e){
                 App::result('msg', $e->getMessage());
             }
        },['get','post'],true);
    }
}