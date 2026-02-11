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

class SyncRemote extends \Tualo\Office\Basic\RouteWrapper
{
    private static function writeBallotPaperCSS()
    {
        $session = TualoApplication::get('session');
        $db = $session->getDB();
        $sql = "select group_concat(concat('.ballot-background-',id,'{background-color:',farbe,char(59),'}') separator 'SLASH_N') x from stimmzettel ";
        $sql .= "union select group_concat(concat('.ballot-textcolor-',id,'{color:',farbe,char(59),'}') separator 'SLASH_N') x from stimmzettel";

        $result = $db->singleValue($sql, [], 'x');
        $result = str_replace('SLASH_N', "\n", $result);
        $filename = 'ballotpaper-backgrounds.css';
        $publicpath =  TualoApplication::configuration(
            'tualo-cms',
            'public_path'
        );
        if ($publicpath !== false) {
            $publicpath = rtrim($publicpath, '/');
            $filename = $publicpath . '/' . $filename;
            file_put_contents($filename, $result);
        }
    }

    public static function scope(): string
    {
        return 'onlinevote.sync';
    }

    public static function register()
    {
        BasicRoute::add('/onlinevote/syncsetup', function () {
            TualoApplication::contenttype('application/json');
            try {
                $session = TualoApplication::get('session');
                $db = $session->getDB();
                $o = $db->directMap("
                    select if(property<>'',1,0) v,'api' text FROM system_settings WHERE system_settings_id = 'remote-erp/url' 
                    union all
                    select property v,'api_url' text FROM system_settings WHERE system_settings_id = 'remote-erp/url'
                    union all
                    select property v,'api_token' text FROM system_settings WHERE system_settings_id = 'remote-erp/token'
                    union all
                    select property v,'api_private' text FROM system_settings WHERE system_settings_id = 'erp/privatekey'
                ", [], 'text', 'v');

                $url = $_SERVER['SERVER_NAME'];
                if (isset($o['api_url'])) $url = $o['api_url'];
                TualoApplication::result('url', $url);
                TualoApplication::result('token',  isset($o['api_token']) ? $o['api_token'] : '');

                TualoApplication::result('success', true);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true, [], self::scope());

        BasicRoute::add('/onlinevote/writecss', function () {
            TualoApplication::contenttype('application/json');
            try {
                SyncRemote::writeBallotPaperCSS();
                TualoApplication::result('success', true);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get'], true, [], self::scope());

        BasicRoute::add('/onlinevote/syncremote', function () {
            TualoApplication::contenttype('application/json');
            $start = time();
            try {
                ini_set('memory_limit', '4096M');

                TualoApplication::result('state', __LINE__);
                TualoApplication::result('seconds', time() - $start);
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
                TualoApplication::result('seconds', time() - $start);

                $remote_data = [];
                $table_list = $db->direct('select table_name from wm_sync_tables order by position asc');
                foreach ($table_list as $table_row) {
                    set_time_limit(300);
                    $tablename = $table_row['table_name'];

                    if ($tablename == 'ds_files') {
                        // nicht einlesen, da daten zu groß sein können
                    } else if ($tablename == 'ds_files_data') {
                        // nicht einlesen, da daten zu groß sein können
                    } else {
                        $filter = '';
                        if ($tablename == 'kandidaten_bilder') {
                            // [{"property":"typ" ,"value":"90","operator":"eq"}]
                            $values = TualoApplication::configuration('onlinevote', 'picture_ids', '90');
                            $values = explode(',', preg_replace('/[^\d,]*/', '', $values));
                            $filter = '&filter=' . urlencode('[{"property":"typ" ,"value":' . json_encode($values) . ',"operator":"in"}]');
                        }
                        $remote_data[$tablename] = APIRequestHelper::query($url . 'papervote/' . $tablename . '/read?limit=1000000' . $filter);
                        if ($remote_data[$tablename] === false) {
                            throw new \Exception('error on ' . $tablename . ' ' . APIRequestHelper::$last_error_message);
                        }
                    }
                    $db->direct('select table_name from `ds` limit 1');
                }
                TualoApplication::result('state', __LINE__);
                TualoApplication::result('seconds', time() - $start);

                $db->autocommit(false);
                foreach ($table_list as $table_row) {
                    if ($table_row['table_name'] == 'ds_files') {

                        $run = true;
                        $run_index = 0;
                        while ($run) {
                            $run_index++;
                            $db->direct('delete from `' . $table_row['table_name'] . '_data` where file_id in (select file_id from `' . $table_row['table_name'] . '` where table_name="kandidaten_bilder") limit 10');
                            TualoApplication::result('affected_rows', $db->mysqli->affected_rows);
                            $run = $db->mysqli->affected_rows != 0;
                            $db->commit();
                            if ($run_index > 50000) $run = false;
                        }
                        $db->direct('delete from `' . $table_row['table_name'] . '_data` where file_id in (select file_id from `' . $table_row['table_name'] . '` where table_name="kandidaten_bilder") ');
                        $db->direct('delete from `' . $table_row['table_name'] . '` where table_name="kandidaten_bilder"');
                    } else if ($table_row['table_name'] == 'ds_files_data') {
                    } else {
                        $db->direct('delete from `' . $table_row['table_name'] . '`');
                    }
                    $db->direct('select table_name from `ds` limit 1');
                }
                TualoApplication::result('state', __LINE__);
                TualoApplication::result('seconds', time() - $start);

                foreach ($table_list as $table_row) {
                    $newData = [];
                    if ($table_row['table_name'] != 'ds_files_data') {

                        if (!isset($remote_data[$table_row['table_name']]) || !isset($remote_data[$table_row['table_name']]['data'])) {
                            if ($table_row['table_name'] == 'ds_files') {
                                $remote_data[$table_row['table_name']]['data'] = [];
                            } else {
                                throw new \Exception('no data for ' . $table_row['table_name']);
                            }
                        }
                        foreach ($remote_data[$table_row['table_name']]['data'] as $row) {
                            if (isset($row['__file_name'])) unset($row['__file_name']);
                            if (isset($row['__file_data'])) unset($row['__file_data']);
                            $newData[] = $row;
                        }
                        $remote_data[$table_row['table_name']]['data'] = $newData;
                    }
                }
                TualoApplication::result('state', __LINE__);
                foreach ($table_list as $table_row) {
                    if ($table_row['table_name'] == 'ds_files_data') {
                    } else {
                        TualoApplication::result('state_tn', $table_row['table_name']);
                        TualoApplication::result('state_data', $remote_data[$table_row['table_name']]);
                        $table = DSTable::instance($table_row['table_name']);
                        $table->insert($remote_data[$table_row['table_name']]['data']);
                        if ($table->error()) throw new \Exception($table->errorMessage());
                    }
                    $db->direct('select table_name from `ds` limit 1');
                }

                TualoApplication::result('state', __LINE__);
                TualoApplication::result('seconds', time() - $start);
                $db->commit();

                $liste_der_bilder = $db->direct('select file_id from kandidaten_bilder');


                foreach ($table_list as $table_row) {
                    if ($table_row['table_name'] == 'ds_files') {
                        foreach ($liste_der_bilder as $bild) {
                            $filter = '&filter=' . urlencode('[{"property":"file_id" ,"value":"' . $bild['file_id'] . '","operator":"eq"}]');
                            $records = APIRequestHelper::query($url . '/papervote/ds_files/read?' . $filter);
                            $table = DSTable::instance('ds_files');
                            $table->insert($records['data']);
                            if ($table->error()) throw new \Exception($table->errorMessage());


                            $records = APIRequestHelper::query($url . '/papervote/ds_files_data/read?' . $filter);

                            foreach ($records['data'] as $row) {
                                try {
                                    $sql = 'replace into ds_files_data (file_id,data) values ({file_id},{data})';
                                    $db->direct($sql, $row);
                                } catch (Exception $e) {
                                }
                                $db->direct('select table_name from `ds` limit 1');
                            }

                            /*
                            $table = DSTable::instance('ds_files_data');
                            $table->insert($records['data']);
                            if ($table->error()) throw new \Exception($table->errorMessage());
                            */
                        }
                    }
                    if ($table_row['table_name'] == 'ds_files_data') {

                        /*
                        $lastCount = -1;
                        $currentPage = 0;
                        $tablename = $table_row['table_name'];

                        while ($lastCount != 0) {
                            $currentPage++;
                            $remote_data[$table_row['table_name']]  = APIRequestHelper::query($url . '/papervote/' . $tablename . '/read?page=' . $currentPage . '&limit=10');
                            $lastCount = count($remote_data[$table_row['table_name']]['data']);
                            foreach ($remote_data[$table_row['table_name']]['data'] as $row) {
                                try {
                                    $sql = 'replace into ds_files_data (file_id,data) values ({file_id},{data})';
                                    $db->direct($sql, $row);
                                } catch (Exception $e) {
                                }
                                $db->direct('select table_name from `ds` limit 1');
                            }
                            $db->commit();
                        }
                            */
                    }
                }
                TualoApplication::result('state', __LINE__);
                TualoApplication::result('seconds', time() - $start);

                $db->direct("update stimmzettel set farbe='rgb(101,172,101)' where farbe is null");
                $db->commit();
                TualoApplication::result('state', __LINE__);
                TualoApplication::result('seconds', time() - $start);

                SyncRemote::writeBallotPaperCSS();
                TualoApplication::result('success', true);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true, [], self::scope());
    }
}
