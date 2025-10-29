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

class Count extends \Tualo\Office\Basic\RouteWrapper
{

    public static function register()
    {
        BasicRoute::add('/onlinevote/counts', function ($matches) {
            TualoApplication::contenttype('application/json');
            $session = TualoApplication::get('session');
            $db = $session->getDB();
            try {

                if ($db->singleRow('select stoptime from wm_loginpage_settings where stoptime<now() and id = 1', []) === false) {
                    throw new Exception("Es kann erst nach dem Ende der Wahlfrist ausgezählt werden");
                }

                $candidates = $db->direct('select id,0 stimmen from kandidaten', [], 'id');

                $list = $db->direct('select ballotpaper from  ballotbox_decrypted  where keyname in (select min(keyname) from ballotbox_decrypted) and isvalid=1');

                foreach ($list as $elm) {
                    $x = json_decode($elm['ballotpaper'], true);
                    foreach ($x as $id) {
                        if (!isset($candidates[$id]['stimmen'])) $candidates[$id]['stimmen'] = 0;
                        $candidates[$id]['stimmen']++;
                    }
                }
                $db->direct('create table if not exists kandidaten_stimmen (id integer primary key, stimmen integer default 0 )');
                $db->direct('delete from  kandidaten_stimmen  ');
                foreach ($candidates as $k => $v) {
                    $sql = 'insert into kandidaten_stimmen (id,stimmen) values ({id},{stimmen}) ';
                    $db->direct($sql, $v);
                }
                TualoApplication::result('stimmen', $candidates);
                TualoApplication::result('success', true);
            } catch (Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}
