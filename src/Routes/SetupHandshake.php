<?php
namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\TualoPGP\TualoApplicationPGP;
use Ramsey\Uuid\Uuid;

class SetupHandshake implements IRoute{

    public static function clientAllowed($array,$client){
        foreach($array as $elem){ if($elem['client']==$client) return true; }
        return false;
    }

    public static function remoteLogin(){
        $cookie_file = App::get('tempPath').'/api_cookie';
        if (file_exists($cookie_file)) unlink($cookie_file);

        if (isset($_REQUEST['api_url']))
        if (isset($_REQUEST['api_username']))
        if (isset($_REQUEST['api_password']))
        if (isset($_REQUEST['api_client']))
        if ($api_result = APIRequestHelper::query( $_REQUEST['api_url'], [
            'username' => $_REQUEST['api_username'],
            'password' => $_REQUEST['api_password'],
            'mandant' => $_REQUEST['api_client'],
            'forcelogin' => '1'
        ] )){
            App::result('api_result', $api_result);
            isset($api_result['client']) || throw new \Exception('Der Parameter *client* fehlte in der Antwort');
            isset($api_result['clients']) ||  throw new \Exception('Der Parameter *clients* fehlte in der Antwort');
            isset($api_result['success']) ||  throw new \Exception('Der Parameter *success* fehlte in der Antwort');

            if (!self::clientAllowed($api_result['clients'],$api_result['client'])) throw new \Exception('Der Client ist nicht erlaubt');
            if ($api_result['success']==false) throw new \Exception($api_result['msg'].'-');
            return true;
        }
        return false;
    }
 
    public static function register(){
        BasicRoute::add('/onlinevote/setuphandshake',function($matches){
            try{
                App::contenttype('application/json');

                $session = App::get('session');
                $db = $session->getDB();

                $mesage_to_send = [
                    'domain'    => $_SERVER['SERVER_NAME'],
                    'uri'       => substr($_SERVER['SCRIPT_URI'],0,-1*strlen('/onlinevote/setuphandshake')),
                ];
                if (self::remoteLogin()===true){

                    $token = $session->registerOAuth(
                            $params     =   [],
                            $force      =   true,
                            $anyclient  =   false,
                            $path       =   '/onlinevote/*'
                    );
                    $session->oauthValidDays($token,365);
                    $keys = TualoApplicationPGP::keyGen(2048);
                    $publickey = $keys['public'];
                    $privatekey = $keys['private'];


                    $db->direct("insert into system_settings (system_settings_id,property) values ({system_settings_id},{property}) on duplicate key update property=values(property)",[
                        'system_settings_id'    => 'erp/privatekey',
                        'property'              => $privatekey
                    ]);

                    $mesage_to_send += [
                        'publickey' => $publickey,
                        'token'     => $token,
                        'message'   => TualoApplicationPGP::enarmor(TualoApplicationPGP::encrypt($publickey,$token))
                    ];
                    

                    $api_result = APIRequestHelper::query( $_REQUEST['api_url'].'papervote/setuphandshake', $mesage_to_send );
                    App::result('api_result', $api_result);
                    if ( $api_result ){
                        if (TualoApplicationPGP::decrypt($privatekey,TualoApplicationPGP::unarmor($api_result['message_public']))!=$api_result['token']) throw new \Exception('Problem bei dem Schlüsseltausch (1)');

                        $ping_result = APIRequestHelper::query( $_REQUEST['api_url'].'~/'.$api_result['token'].'/papervote/ping');
                        if (
                            ($ping_result==false)||
                            (!isset($ping_result['success']))||
                            ($ping_result['success']!==true)
                        ){
                            throw new \Exception("Das Briefwahlsystem kann nicht angepingt werden.");
                        }else{
                            $db->direct("insert into system_settings (system_settings_id,property) values ({system_settings_id},{property}) on duplicate key update property=values(property)",[
                                'system_settings_id'    => 'remote-erp/public',
                                'property'              => $api_result['publickey']
                            ]);
    
    
                            $db->direct("insert into system_settings (system_settings_id,property) values ({system_settings_id},{property}) on duplicate key update property=values(property)",[
                                'system_settings_id'    => 'remote-erp/url',
                                'property'              => $_REQUEST['uri']
                            ]);
    
                            $db->direct("insert into system_settings (system_settings_id,property) values ({system_settings_id},{property}) on duplicate key update property=values(property)",[
                                'system_settings_id'    => 'remote-erp/token',
                                'property'              => $api_result['token']
                            ]);
                            App::result('success',  true );
                        }



                        
        

                    }else{
                        App::result('msg', APIRequestHelper::$last_error_message);
                    }
    
                }else{
                    App::result('msg', "remote login failed");
                }


                
                App::result('data',  $mesage_to_send );
                
            }catch(\Exception $e){

                App::result('last_sql', $db->last_sql);
                App::result('msg', $e->getMessage());
            }
        },['get','post'],true);


        
    }
}