<?php

declare(strict_types=1);

namespace Tualo\Office\OnlineVote;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\TualoPGP\TualoApplicationPGP;
use Ramsey\Uuid\Uuid;

class Handshake
{
    public static function parameter():array{
        $result = [];
        $session = App::get('session');
        $db = $session->getDB();
        $o = $db->directMap("
            select if(property<>'',1,0) v,'api' text FROM system_settings WHERE system_settings_id = 'remote-erp/url' 
            union 
            select property v,'api_url' text FROM system_settings WHERE system_settings_id = 'remote-erp/url'
            union
            select property v,'api_token' text FROM system_settings WHERE system_settings_id = 'remote-erp/token'
            union
            select property v,'api_private' text FROM system_settings WHERE system_settings_id = 'erp/privatekey'
            union 
            select stoptime v,'stoptime' from wm_loginpage_settings where stoptime<now() and id = 1
            union 
            select starttime v,'starttime' from wm_loginpage_settings where stoptime<now() and id = 1
        ",[],'text','v');
        if ($o['api']==1){
            $result = $o;
            $result['api_token_url'] = $result['api_url'].'~/'.$result['api_token'].'/' ;
        }
        return $result;
    }

    public static function pingRemote():bool{
        $params = self::parameter();
        if (!isset($params['api_url'])) return false;

        $message = (Uuid::uuid4())->toString();
        $ping_result = APIRequestHelper::query( $params['api_url'].'~/'.$params['token'].'/papervote/ping',[
            'message'=>$message,
            'signature'=>TualoApplicationPGP::sign($params['api_private'],$message)
        ]);
        if (
            ($ping_result==false)||
            (!isset($ping_result['success']))||
            ($ping_result['success']!==true)
        ){
            return false;
        }else{
            return true;
        }
    }
    


}