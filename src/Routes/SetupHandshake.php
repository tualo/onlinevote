<?php
namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\TualoPGP\TualoApplicationPGP;
use Ramsey\Uuid\Uuid;

class SetupHandshake implements IRoute{

    public static function remoteLogin(){
        $cookie_file = App::get('tempPath').'/api_cookie';
        if (file_exists($cookie_file)) unlink($cookie_file);

        if ($api_result = APIRequestHelper::query( $_REQUEST['api_url'], [
            'username' => $_REQUEST['api_username'],
            'password' => $_REQUEST['api_password'],
            'mandant' => $_REQUEST['api_client'],
            'forcelogin' => '1'
        ] )){
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

                    $mesage_to_send = [
                        'publickey' => $publickey,
                        'domain'    => $_SERVER['SERVER_NAME'],
                        'uri'       => substr($_SERVER['SCRIPT_URI'],0,-1*strlen('/onlinevote/setuphandshake')),
                        'token'     => $token,
                        'message'   => TualoApplicationPGP::encrypt($publickey,$token)
                    ];
                    
                    if ($api_result = APIRequestHelper::query( $_REQUEST['api_url'].'/papervote/setuphandshake', $mesage_to_send )){
                        if (TualoApplicationPGP::decrypt($privatekey,$api_result['message'])!=$token) throw new \Exception('Problem bei dem Schlüsseltausch');

                    }
    
                }


                
                App::result('data',  $mesage_to_send );
                
            }catch(\Exception $e){

                App::result('last_sql', $db->last_sql);
                App::result('msg', $e->getMessage());
            }
        },['get','post'],true);


        
    }
}