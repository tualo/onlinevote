<?php
namespace Tualo\Office\OnlineVote\Middlewares;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\IMiddleware;

class InitApiUse /*extends CMSMiddleWare*/{
    public static function run(&$request,&$result){
        @session_start();
        $db = App::get('session')->getDB();
        $_SESSION['api'] = 0;
        if ($db->singleValue("select if(property<>'',1,0) v FROM system_settings WHERE system_settings_id = 'erp/url'  ",[],'v')=='1'){
            $_SESSION['api']=1;
            $_SESSION['api_url']=$db->singleValue("select property v FROM system_settings WHERE system_settings_id = 'erp/url'  ",[],'v');
            $_SESSION['api_private']=$db->singleValue("select property v FROM system_settings WHERE system_settings_id = 'erp/privatekey'  ",[],'v');
        }
        session_commit();
    }
}