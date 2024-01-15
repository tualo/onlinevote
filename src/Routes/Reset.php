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

class Reset implements IRoute
{

    public static function register()
    {
        BasicRoute::add('/onlinevote/ballotboxreset', function ($matches) {
            TualoApplication::contenttype('application/json');
            $session = TualoApplication::get('session');
            $db = $session->getDB();
            try {

                if ($db->singleRow('select starttime from wm_loginpage_settings where starttime>now() and id = 1', []) === false) {
                    throw new Exception("Es kann nur vor Beginn der Wahlfrist zurückgesetzt werden");
                }

                $db->execute('delete from ballotbox');
                $db->execute('delete from voters');
                $db->execute('delete from voter_sessions_save_state');
                $db->execute('delete from blocked_voters');
                $db->execute('delete from unique_voter_session');
                $db->execute('delete from ballotbox_decrypted');

                TualoApplication::result('success', true);
            } catch (Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get'], true);
    }
}
