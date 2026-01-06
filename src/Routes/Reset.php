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

class Reset extends \Tualo\Office\Basic\RouteWrapper
{

    public static function scope(): string
    {
        return 'onlinevote.resetballotbox';
    }
    public static function register()
    {
        BasicRoute::add('/onlinevote/ballotboxreset', function ($matches) {
            TualoApplication::contenttype('application/json');
            $session = TualoApplication::getSession();
            $db = $session->getDB();
            try {

                if (!$session->isMaster()) {
                    throw new Exception("Nur Master-Nutzern ist es erlaubt die Wahl zurückzusetzen");
                }

                if ($db->singleRow('select starttime from wm_loginpage_settings where starttime>now() and id = 1', []) === false) {
                    throw new Exception("Es kann nur vor Beginn der Wahlfrist zurückgesetzt werden");
                }

                $db->direct('start transaction;');
                $db->execute('delete from ballotbox');
                $db->execute('truncate ballotbox_blockchain');
                $db->execute('delete from voters');
                $db->execute('delete from kandidaten_stimmen');
                $db->execute('delete from voter_sessions_save_state');
                $db->execute('delete from blocked_voters');
                $db->execute('delete from unique_voter_session');
                $db->execute('delete from ballotbox_decrypted');
                $db->direct('commit;');



                TualoApplication::result('success', true);
            } catch (Exception $e) {
                $db->direct('rollback;');
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get'], true,   [], self::scope());
    }
}
