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
                ini_set('memory_limit', '4096M');
                TualoApplication::result('state', __LINE__);
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
                TualoApplication::result('state', __LINE__);
               
                $remote_data = [];
                $table_list = $db->direct('select table_name from wm_sync_tables order by position asc');
                foreach ($table_list as $table_row) {
                    set_time_limit(300);
                    $tablename = $table_row['table_name'];
                    $remote_data[$tablename] = APIRequestHelper::query($url . '/ds/' . $tablename . '/read?limit=1000000');
                    if($remote_data[$tablename]===false){
                        throw new \Exception('error on '.$tablename);
                    }
                    $db->direct('select table_name from `ds` limit 1');
                }
                TualoApplication::result('state', __LINE__);

                $db->autocommit(false);
                foreach ($table_list as $table_row) {
                    if ($table_row['table_name'] == 'ds_files'){
                        $db->direct('delete from `' . $table_row['table_name'] . '_data` where file_id in (select file_id from `' . $table_row['table_name'] . '` where table_name="kandidaten_bilder") ');
                        $db->direct('delete from `' . $table_row['table_name'] . '` where table_name="kandidaten_bilder"');
                    }else if ($table_row['table_name'] == 'ds_files_data'){

                    }else{
                        $db->direct('delete from `' . $table_row['table_name'] . '`');
                    }
                    $db->direct('select table_name from `ds` limit 1');
                }
                TualoApplication::result('state', __LINE__);
                foreach ($table_list as $table_row) {
                    if ($table_row['table_name'] == 'ds_files_data'){
                        
                    }else{
                        $table = DSTable::instance($table_row['table_name']);
                        $table->insert($remote_data[$table_row['table_name']]['data']);
                    }
                    $db->direct('select table_name from `ds` limit 1');
                }
                TualoApplication::result('state', __LINE__);

                foreach ($table_list as $table_row) {
                    if ($table_row['table_name'] == 'ds_files_data'){
                        foreach($remote_data[$table_row['table_name']]['data'] as $row){
                            try{
                                $sql = 'replace into ds_files_data (file_id,data) values ({file_id},{data})';
                                $db->direct($sql,$row);
                            }catch(Exception $e){
                                
                            }
                            $db->direct('select table_name from `ds` limit 1');
                        }
                    }
                }
                TualoApplication::result('state', __LINE__);

                $db->direct("update stimmzettel set farbe='rgb(101,172,101)' where farbe is null");
                $db->commit();
                TualoApplication::result('state', __LINE__);


                TualoApplication::result('success', true);
                
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}
