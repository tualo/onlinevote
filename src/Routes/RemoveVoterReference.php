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

class RemoveVoterReference implements IRoute
{

    public static function register()
    {
        BasicRoute::add('/onlinevote/remove_voter_references',function($matches){
            TualoApplication::contenttype('application/json');
            $session = TualoApplication::get('session');
            $db = $session->getDB();
            try{
                if ( $db->singleRow( 'select stoptime from wm_loginpage_settings where stoptime<now() and id = 1',[] ) === false){
                    throw new Exception("Es kann erst nach dem Ende der Wahlfrist entschlüsselt werden");
                }

                if ( $db->singleRow( 'select ts from blocked_synced where ts - interval - 2 minute  > now()  ',[] ) === false){
                    throw new Exception("Bitte zuerst die blockierten Wähler synchronisieren");
                }
        
                $db->direct('update ballotbox set voter_id=null where blocked=0');
                TualoApplication::result('success', true);
            }catch(Exception $e){
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}

