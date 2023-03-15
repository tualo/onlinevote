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
            try{

                if (
                     isset($_REQUEST['signature']) &&
                     isset($_REQUEST['message'])
                 ){
                     $db = App::get('session')->getDB();
                     $pem = $db->singleValue("select property FROM system_settings WHERE system_settings_id = 'remote-erp/public'",[],'property');
                     App::result('success',true);
                     App::result('signature_success',TualoApplicationPGP::verify($pem,$_REQUEST['message'], $_REQUEST['signature']) );
                 }else{
                     App::result('success',true);
                 }
 
             }catch(\Exception $e){
 
                 App::result('last_sql', $db->last_sql);
                 App::result('msg', $e->getMessage());
             }
        },['get','post'],true);
    }
}