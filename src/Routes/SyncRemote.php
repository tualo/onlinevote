<?php

namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\TualoPGP\TualoApplicationPGP;
use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\DS\DSCreateRoute;

class SyncRemote implements IRoute
{

    public static function register()
    {
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
                    $tablename = $table_row['table_name'];
                    $remote_data[$tablename] = APIRequestHelper::query($url . '/ds/' . $tablename . '/read?limit=1000000');
                }

                if (
                    ($remote_data['kandidaten'] !== false) &&
                    ($remote_data['stimmzettelgruppen'] !== false)  &&
                    ($remote_data['stimmzettel'] !== false)
                ) {
                    $db->autocommit(false);

                    $db->direct('delete from kandidaten_docdata');
                    $db->direct('delete from kandidaten_doc');
                    /**
             create table wm_sync_tables (
                table_name varchar(128) primary key,
                position integer default 0,
                last_sync datetime default null
            );
            insert into wm_sync_tables (table_name,position) values  
                ('wahlbezirk',1),
                ('wahlgruppe',2),
                ('stimmzettel',3),
                ('stimmzettelgruppen',4),
                ('stimmzettel_fusstexte',5),
                ('stimmzettel_stimmzettel_fusstexte',6),
                ('kandidaten',7)
                     */

                    $table_list = $db->direct('select table_name from wm_sync_tables order by position desc');
                    foreach ($table_list as $table_row) {
                        $db->direct('delete from `' . $table_row['table_name'] . '`');
                    }


                    $table_list = $db->direct('select table_name from wm_sync_tables order by position asc');
                    foreach ($table_list as $table_row) {
                        $tablename = $table_row['table_name'];
                        $res = DSCreateRoute::createRequest($db, $tablename, $remote_data[$tablename]);
                        TualoApplication::result($tablename, count($res['data']));
                    }

                    foreach ($remote_data['kandidaten']['data'] as $row) {
                        $data = APIRequestHelper::raw($url . '/dsfile/inlinebase64/kandidaten/' . $row['kandidaten__portaitbild']);
                        file_put_contents($config['__PORTRAIT_PATH__'] . '/' . $row['kandidaten__portaitbild'] . '.png', base64_decode($data));
                    }


                    $db->commit();

                    TualoApplication::result('success', true);
                } else {
                    TualoApplication::result('success', false);
                    TualoApplication::result('msg', 'something went wrong');
                }
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}
