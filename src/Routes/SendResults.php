<?php

namespace Tualo\Office\OnlineVote\Routes;

use Exception;
use Tualo\Office\TualoPGP\TualoApplicationPGP;
use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\DS\DSCreateRoute;
use Tualo\Office\DS\DSTable;

class SendResults implements IRoute
{

    public static function register()
    {
        BasicRoute::add('/onlinevote/sendresults', function ($matches) {
            TualoApplication::contenttype('application/json');
            $session = TualoApplication::get('session');
            $db = $session->getDB();
            try {
                $o = $db->directMap("
                    select if(property<>'',1,0) v,'api' text FROM system_settings WHERE system_settings_id = 'remote-erp/url' 
                    union 
                    select property v,'api_url' text FROM system_settings WHERE system_settings_id = 'remote-erp/url'
                    union
                    select property v,'api_token' text FROM system_settings WHERE system_settings_id = 'remote-erp/token'
                    union
                    select property v,'api_private' text FROM system_settings WHERE system_settings_id = 'erp/privatekey'
                ", [], 'text', 'v');
                $url  = $o['api_url'] . '~/' . $o['api_token'] . '/';
                
                $tablename = 'kandidaten_stimmen';
                $table = DSTable::instance($tablename);
                $data = $table->read()->get();
                $remote_data = APIRequestHelper::query($url . '/ds/onlinekandidaten/create',$data);
                if($remote_data===false){
                    throw new \Exception('error on '.$tablename);
                }
                TualoApplication::result('remote_data', $remote_data);
                TualoApplication::result('success', true);
            } catch (Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);

    }
}