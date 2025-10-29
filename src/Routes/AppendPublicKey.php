<?php

namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\TualoPGP\TualoApplicationPGP;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\Common\PublicKey;


class AppendPublicKey extends \Tualo\Office\Basic\RouteWrapper
{

    public static function register()
    {
        BasicRoute::add('/onlinevote/append/publickey', function () {
            App::contenttype('application/json');
            try {
                $db = App::get('session')->getDB();
                if (!isset($_REQUEST['publickey']) && !is_string($_REQUEST['publickey'])) throw new \Exception("Ein Attribut wird vermisst");
                if (!isset($_REQUEST['name']) && !is_string($_REQUEST['publickey'])) throw new \Exception("Ein Attribut wird vermisst");
                $rsaKey = PublicKeyLoader::loadPublicKey($_REQUEST['publickey']);
                if (!$rsaKey instanceof PublicKey) throw new \Exception("Der Schlüssel kann nicht verwendet werden");
                if ($db->singleRow('select stoptime from wm_loginpage_settings where starttime > now() and id = 1', []) === false) throw new \Exception("Es kann keine Urne mehr angelegt werden");
                $sql = '
                    insert into 
                        pgpkeys 
                    (   
                        keyname,
                        keyid,
                        fingerprint,
                        username,
                        publickey,
                        privatekey
                    ) 
                        values 
                    (
                        {fingerprint},
                        {keyid},
                        {fingerprint},
                        @sessionuser,
                        {publickey},
                        {privatekey}
                    )
                ';

                $db->direct($sql, [
                    'keyid' =>         $_REQUEST['name'],
                    'fingerprint' =>   $rsaKey->getFingerprint('md5'),
                    'publickey' =>    (string)$rsaKey,
                    'privatekey' =>   ''
                ]);
                App::result('success', true);
            } catch (\Exception $e) {

                App::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}
