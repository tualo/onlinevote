<?php

namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\TualoPGP\TualoApplicationPGP;
use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\DS\DSCreateRoute;
use Tualo\Office\DS\DSTable;

class SyncRemote implements IRoute
{

    public static function register()
    {
        BasicRoute::add('/onlinevote/syncsetup', function () {
            TualoApplication::contenttype('application/json');
            try {
                $session = TualoApplication::get('session');
                $db = $session->getDB();
                $o = $db->directMap("
                    select if(property<>'',1,0) v,'api' text FROM system_settings WHERE system_settings_id = 'remote-erp/url' 
                    union 
                    select property v,'api_url' text FROM system_settings WHERE system_settings_id = 'remote-erp/url'
                    union
                    select property v,'api_token' text FROM system_settings WHERE system_settings_id = 'remote-erp/token'
                    union
                    select property v,'api_private' text FROM system_settings WHERE system_settings_id = 'erp/privatekey'
                ",[],'text','v');
                
                $url = $_SERVER['SERVER_NAME'];
                if (isset($o['api_url'])) $url = $o['api_url'];
                TualoApplication::result('url', $url);
                TualoApplication::result('token',  isset($o['api_token'])? $o['api_token']:'');

                TualoApplication::result('success', true);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);

        BasicRoute::add('/onlinevote/syncremote', function () {
            TualoApplication::contenttype('application/json');
            try {

                $session = TualoApplication::get('session');
                $config = TualoApplication::get('configuration');
                $db = $session->getDB();
                set_time_limit(300);
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
                /*
        $tablename = 'stimmzettel';
        $stimmzettel = WMRequestHelper::query($url.'/ds/'.$tablename.'/read?limit=1000000');

        $tablename = 'stimmzettelgruppen';
        $stimmzettelgruppen = WMRequestHelper::query($url.'/ds/'.$tablename.'/read?limit=1000000');

        $tablename = 'kandidaten';
        $kandidaten = WMRequestHelper::query($url.'/ds/'.$tablename.'/read?limit=1000000');

        $tablename = 'wahlbezirk';
        $wahlbezirk = WMRequestHelper::query($url.'/ds/'.$tablename.'/read?limit=1000000');
        
        $tablename = 'wahlgruppe';
        $wahlgruppe = WMRequestHelper::query($url.'/ds/'.$tablename.'/read?limit=1000000');
        */

                $remote_data = [];
                $table_list = $db->direct('select table_name from wm_sync_tables order by position asc');
                foreach ($table_list as $table_row) {
                    set_time_limit(300);
                    $tablename = $table_row['table_name'];
                    $remote_data[$tablename] = APIRequestHelper::query($url . '/ds/' . $tablename . '/read?limit=1000000');
                    if($remote_data[$tablename]===false){
                        throw new \Exception('error on '.$tablename);
                    }
                }

                $db->autocommit(false);
                foreach ($table_list as $table_row) {
                    if ($table_row['table_name'] == 'ds_files'){
                        $db->direct('delete from `' . $table_row['table_name'] . '_data` where  file_id in (select file_id from `' . $table_row['table_name'] . '` where table_name="kandidaten_bilder") ');
                        $db->direct('delete from `' . $table_row['table_name'] . '` where table_name="kandidaten_bilder"');
                    }else{
                        $db->direct('delete from `' . $table_row['table_name'] . '`');
                    }
                    $table = DSTable::instance($table_row['table_name']);
                    $table->insert($remote_data[$table_row['table_name']]['data']);
                }
                $db->direct("update stimmzettel set farbe='rgb(101,172,101)' where farbe is null");
                $db->commit();


                TualoApplication::result('success', true);
                
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}
