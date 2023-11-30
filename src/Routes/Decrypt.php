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

class Decrypt implements IRoute
{

    public static function register()
    {
        BasicRoute::add('/onlinevote/decrypt', function ($matches) {
            TualoApplication::contenttype('application/json');
            $session = TualoApplication::get('session');
            $db = $session->getDB();


            try {

                if ($db->singleRow('select stoptime from wm_loginpage_settings where stoptime<now() and id = 1', []) === false) {
                    throw new Exception("Es kann erst nach dem Ende der Wahlfrist entschlüsselt werden");
                }
                // optional check blocked voters im bw system

                if ($db->singleValue('select count(*) c from ballotbox where voter_id is not null and blocked=0', [], 'c') > 0) {
                    throw new Exception("Es kann noch nicht entschlüsselt werden, es sind noch Wählerzuordnungen vorhanden");
                }
                /*
                $db->direct("call addfieldifnotexists('ballotbox','saveerror','tinyint default 0')");
                $db->direct("call addfieldifnotexists('ballotbox','saveerrorid','varchar(36) default \"\"')");


                $db->direct("call addfieldifnotexists('ballotbox','isvalid','tinyint default 0')");
                $db->direct("call addfieldifnotexists('ballotbox','stimmzettel','varchar(10) default \"\"')");

                $db->direct("call addfieldifnotexists('ballotbox_decrypted','isvalid','tinyint default 0')");
                $db->direct("call addfieldifnotexists('ballotbox_decrypted','stimmzettel','varchar(10) default \"\"')");
                */
                $list = $db->direct('
                select 
                    rand() random,
                    ballotbox.*,
                    pgpkeys.privatekey
                from 
                    ballotbox
                    join pgpkeys
                    on pgpkeys.privatekey<>"" and pgpkeys.keyname = ballotbox.keyname
                where 
                        blocked=0
                    and ballotbox.decrypted=0
                    and voter_id is null
                    and ballotbox.saveerror=0
                order by random asc
                limit 5
                ');

                foreach ($list as $elm) {
                    $decrypted = TualoApplicationPGP::decrypt($elm['privatekey'], TualoApplicationPGP::unarmor($elm['ballotpaper'],'MESSAGE'));
                    $elm['ballotpaper'] = $decrypted;
                    $db->direct('insert ignore into ballotbox_decrypted (keyname,id,ballotpaper,stimmzettel,isvalid ) values ({keyname},{id},{ballotpaper},{stimmzettel},{isvalid} )  ', $elm);
                    $db->direct('update ballotbox set decrypted=1 where id={id} and keyname={keyname}', $elm);
                }

                TualoApplication::result('count', count($list));
                /*
                TualoApplication::result(
                    'total',
                    $db->singleValue(
                        'select count(*) c from (

                                select 
                                pgpkeys.privatekey,
                                ballotbox.*,
                                ballotbox_decrypted.id ballotbox_decrypted_id
                            from 
                                (select * from view_readtable_pgpkeys_intern where invalid=0)  pgpkeys 
                                join ballotbox  
                                    on pgpkeys.privatekey<>"" and pgpkeys.keyname = ballotbox.keyname
                                left join ballotbox_decrypted 
                                    on (ballotbox.`id`,ballotbox.`keyname`) = (ballotbox_decrypted.`id`,ballotbox_decrypted.`keyname`)
                            where 
                                ballotbox.voter_id is null
                                and blocked=0
                                and ballotbox.saveerror=0
                            ) a ',
                        [],
                        'c'
                    )
                );
                */
                

                TualoApplication::result('success', true);
            } catch (Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}
