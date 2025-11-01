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

class SyncBlockedVoters extends \Tualo\Office\Basic\RouteWrapper
{

    public static function scope(): string
    {
        return 'onlinevote.decrypt';
    }
    public static function register()
    {
        BasicRoute::add('/onlinevote/sync_blockedvoters', function ($matches) {
            $session = TualoApplication::get('session');
            $db = $session->getDB();
            try {
                if ($db->singleRow('select stoptime from wm_loginpage_settings where stoptime<now() and id = 1', []) === false) {
                    throw new Exception("Es kann erst nach dem Ende der Wahlfrist entschlüsselt werden");
                }
                $db->autocommit(false);

                $db->direct('delete from blocked_voters');
                $tablename = 'blocked_voters';

                $o = $db->directMap("
                    select if(property<>'',1,0) v,'api' text FROM system_settings WHERE system_settings_id = 'remote-erp/url' 
                    union all
                    select property v,'api_url' text FROM system_settings WHERE system_settings_id = 'remote-erp/url'
                    union all
                    select property v,'api_token' text FROM system_settings WHERE system_settings_id = 'remote-erp/token'
                    union all
                    select property v,'api_private' text FROM system_settings WHERE system_settings_id = 'erp/privatekey'
                ", [], 'text', 'v');

                $url  = $o['api_url'] . '~/' . $o['api_token'] . '/';
                $blocked_voters = APIRequestHelper::query($url . 'papervote/' . $tablename . '/read?limit=1000000');
                if ($blocked_voters === false) throw new Exception("Fehler beim Abrufen der blockierten Wähler (" . APIRequestHelper::$last_error_message . ")");


                $table = DSTable::instance($tablename);
                $table->insert($blocked_voters['data']);
                if ($table->error())
                    throw new Exception("Fehler beim Speichern der blockierten Wähler (" . $table->errorMessage() . ")");

                TualoApplication::result('blocked_voters', count($blocked_voters['data']));

                $db->direct('update ballotbox set blocked=0');
                $db->direct('update ballotbox set blocked=1 where (voter_id,stimmzettel_id) in (select voter_id,stimmzettel from blocked_voters)');
                $db->direct('update ballotbox set blocked=1 where (voter_id,stimmzettel) in (select voter_id,stimmzettel from blocked_voters)');

                /*
                    $c1 = $db->singleValue('select count(*) c from ballotbox where blocked=1 and keyname = (select min(keyname) kn from ballotbox )',[],'c');
                    $c2 = $db->singleValue('select count(*) c from blocked_voters where blocked=0',[],'c');

                    if ($c1!=$c2) throw new Exception("Fehler bei der Synchronisation der blockierten Wähler (c1=$c1, c2=$c2)");
                */

                $db->direct('replace into blocked_synced (id,ts,count) values (1,now(),{count})', ['count' => count($blocked_voters['data'])]);
                $db->commit();
                TualoApplication::result('success', true);
            } catch (Exception $e) {
                $db->rollback();
                TualoApplication::result('msg', $e->getMessage());
            }
            TualoApplication::contenttype('application/json');
        }, ['get', 'post'], true, [], self::scope());
    }
}
