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
        $result = $o = $db->directMap("
            select if(property<>'',1,0) v,'api' text FROM system_settings WHERE system_settings_id = 'remote-erp/url' 
            union  all
            select property v,'api_url' text FROM system_settings WHERE system_settings_id = 'remote-erp/url'
            union all
            select property v,'api_token' text FROM system_settings WHERE system_settings_id = 'remote-erp/token'
            union all
            select property v,'api_private' text FROM system_settings WHERE system_settings_id = 'erp/privatekey'
            union  all
            select stoptime v,'stoptime' text from wm_loginpage_settings where  id = 1
            union  all
            select starttime v,'starttime' text from wm_loginpage_settings where  id = 1
            union all
            select interrupted v,'interrupted' text from wm_loginpage_settings where  id = 1
        ",[],'text','v');
        if (isset($result['interrupted'])) $result['interrupted'] = $result['interrupted']==1;
        if ($o['api']==1){
            $result = $o;
            $result['api_token_url'] = $result['api_url'].'~/'.$result['api_token'].'/' ;
            
        }
        return $result;
    }

    public static function testRemote():bool{
        $remote_data = [];
        $session = App::get('session');
        $db = $session->getDB();

        $o = $db->directMap("
            select if(property<>'',1,0) v,'api' text FROM system_settings WHERE system_settings_id = 'remote-erp/url' 
            union all
            select property v,'api_url' text FROM system_settings WHERE system_settings_id = 'remote-erp/url'
            union all
            select property v,'api_token' text FROM system_settings WHERE system_settings_id = 'remote-erp/token'
            union all
            select property v,'api_private' text FROM system_settings WHERE system_settings_id = 'erp/privatekey'
        ",[],'text','v');

        $url  = $o['api_url'] . '~/' . $o['api_token'] . '/';
        
        $table_list = $db->direct('select table_name from wm_sync_tables order by position asc');
        foreach ($table_list as $table_row) {
            set_time_limit(300);
            $tablename = $table_row['table_name'];
            $remote_data[$tablename] = APIRequestHelper::query($url . '/ds/' . $tablename . '/read?limit=1');
            if($remote_data[$tablename]===false){
                throw new \Exception('error on '.$tablename);
            }
        }
        $tablename = 'blocked_voters';
        
        $blocked_voters = APIRequestHelper::query($url . 'ds/' . $tablename . '/read?limit=1000000');
        if ($blocked_voters === false) throw new \Exception("Fehler beim Abrufen der blockierten Wähler (" . APIRequestHelper::$last_error_message . ")");

        
        return true;
    }

    public static function pingRemote():bool{
        $params = self::parameter();
        if (!isset($params['api_url'])) return false;
        if (!isset($params['api_token'])) return false;
        if (!isset($params['api_private'])) return false;

        $message = (Uuid::uuid4())->toString();
        $ping_result = APIRequestHelper::query( $params['api_url'].'~/'.$params['api_token'].'/papervote/ping',[
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