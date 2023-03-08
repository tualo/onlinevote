<?php
namespace Tualo\Office\OnlineVote\CMSMiddleware;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\IMiddleware;

class InitApiUse /*extends CMSMiddleWare*/{
    public static function run(&$request,&$result){
        @session_start();
        $db = App::get('session')->getDB();
        $_SESSION['api'] = 0;

        $o = $db->directMap("
            select if(property<>'',1,0) v,'api' text FROM system_settings WHERE system_settings_id = 'erp/url' 
            union 
            select property v,'api_url' text FROM system_settings WHERE system_settings_id = 'erp/url'
            union
            select property v,'api_private' text FROM system_settings WHERE system_settings_id = 'erp/privatekey'
        ",[],'text','v');
        if ($o['api']==1){
            $_SESSION['api'] = intval($o['api']);
            $_SESSION['api_url'] = $o['api_url'];
            $_SESSION['api_private'] = $o['api_private'];
        }
        
    }
}