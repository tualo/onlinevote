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
use phpseclib3\Crypt\PrivateKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\Common\PrivateKey;

class KeyUpload extends \Tualo\Office\Basic\RouteWrapper
{

    public static function register()
    {
        BasicRoute::add('/onlinevote/pgp/upload', function () {
            TualoApplication::contenttype('application/json');

            //$tablename = $matches['tablename'];
            $session = TualoApplication::get('session');
            $db = $session->getDB();
            try {

                $stop_offset = intval(TualoApplication::configuration('onlinevote', 'backgroundStopSeconds', '0'));
                // use interval add secnid
                if ($db->singleRow('select stoptime from wm_loginpage_settings where stoptime<now() and id = 1', []) === false) {
                    throw new Exception("Der Schlüssel kann erst nach dem Ende der Wahlfrist hochgeladen werden");
                }


                if (class_exists("Tualo\Office\TualoPGP\TualoApplicationPGP")) {

                    function error2txt($error)
                    {
                        switch ($error) {
                            case UPLOAD_ERR_INI_SIZE:
                                return "UPLOAD_ERR_INI_SIZE: Die Datei ist zu gro&szlig";
                                break;
                            case UPLOAD_ERR_FORM_SIZE:
                                return "UPLOAD_ERR_FORM_SIZE: Die Datei ist zu gro&szlig";
                                break;
                            case UPLOAD_ERR_PARTIAL:
                                return "UPLOAD_ERR_PARTIAL: Die Datei wurde nur teilweise hochgeladen";
                                break;
                            case UPLOAD_ERR_NO_FILE:
                                return "UPLOAD_ERR_NO_FILE: Es wurde keine Datei hochgeladen";
                                break;
                            case 0:
                                return " ";
                                break;
                            default:
                                return "Unbekannter Fehler";
                                break;
                        }
                    }

                    $error = "";
                    if (isset($_FILES['userfile'])) {
                        $sfile = $_FILES['userfile']['tmp_name'];
                        $name = $_FILES['userfile']['name'];
                        $type = $_FILES['userfile']['type'];
                        $error = $_FILES['userfile']['error'];
                        if ($error == UPLOAD_ERR_OK) {

                            if (file_exists(TualoApplication::get('tempPath') . '/upload.asc')) {
                                unlink(TualoApplication::get('tempPath') . '/upload.asc');
                            }
                            move_uploaded_file($sfile, TualoApplication::get('tempPath') . '/upload.asc');
                            $file = file_get_contents(TualoApplication::get('tempPath') . '/upload.asc');
                            unlink(TualoApplication::get('tempPath') . '/upload.asc');

                            $rsaKey = RSA::load($file);
                            if (!$rsaKey instanceof PrivateKey) throw new \Exception("Der Schlüssel kann nicht verwendet werden");


                            // TualoApplicationPGP::enarmor(TualoApplicationPGP::encrypt( $keyitem['publickey'], json_encode($this->filled)),'MESSAGE');

                            $fingerprints = [$rsaKey->getPublicKey()->getFingerprint('md5')]; // TualoApplicationPGP::fingerprint($file);
                            TualoApplication::result('fingerprint', $fingerprints);



                            $found = false;
                            $list = $db->direct('select fingerprint from pgpkeys', []);
                            foreach ($list as $element) {
                                if (in_array($element['fingerprint'], $fingerprints)) {
                                    $found = $element;
                                }
                            }
                            if ($found === false) {
                                throw new Exception("Fingerprint does not match");
                            }

                            $db->direct('update pgpkeys set privatekey = {privatekey} where fingerprint = {fingerprint} and privatekey=\'\' ', ['fingerprint' => $found['fingerprint'], 'privatekey' => $file]);
                            TualoApplication::result('success', true);
                        }
                    }

                    /*
            $key = OpenPGP_Message::parse(file_get_contents(dirname(__FILE__) . '/../tests/data/helloKey.gpg'));
            $data = new OpenPGP_LiteralDataPacket('This is text.', array('format' => 'u', 'filename' => 'stuff.txt'));
            $encrypted = OpenPGP_Crypt_Symmetric::encrypt($key, new OpenPGP_Message(array($data)));
            */

                    /*
            $keygen = TualoApplicationPGP::keyGen(2048,$db->singleValue('select concat(@sessionuserfullname," ",@sessionuser) u ',[],'u') );
            //TualoApplication::result('private', $keygen['private']);
            //TualoApplication::result('public', $keygen['public']);
            TualoApplication::result('success', true);


            $sql = 'insert into pgpkeys (keyname,publickey,fingerprint,privatekey) values ({keyname},{publickey},{fingerprint},{privatekey}) on duplicate key update keyname=values(keyname)';
            $keyname = $keygen['public'][0]->fingerprint();
            $db->direct($sql,[
                'keyname'=>$db->singleValue('select concat(@sessionuser) u ',[],'u'),
                'fingerprint'=>$keyname,
                'publickey' => OpenPGP::enarmor($keygen['public']->to_bytes(), "PGP PUBLIC KEY BLOCK"),
                'privatekey' => OpenPGP::enarmor($keygen['private']->to_bytes(), "PGP PRIVATE KEY BLOCK")
            ]);
            TualoApplication::result('public', OpenPGP::enarmor($keygen['public']->to_bytes(), "PGP PUBLIC KEY BLOCK") );
            TualoApplication::result('private', OpenPGP::enarmor($keygen['private']->to_bytes(), "PGP PRIVATE KEY BLOCK") );
            */
                } else {
                    throw new \Exception('Tualo\Office\TualoPGP\TualoApplicationPGP missing');
                }
            } catch (Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}
