<?php
namespace Tualo\Office\OnlineVote\Middlewares;

use Exception;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\OnlineVote\Middlewares\Init;

use Tualo\Office\Basic\IMiddleware;

class Login /*extends CMSMiddleWare*/{

    public static function loginGetCredentials($username){
        try{
            $db = App::get('session')->getDB();
            
            $record=false;
            if ($_SESSION['api']==1){
                $url = $_SESSION['api_url'].str_replace('{username}',$username,'/papervote/wmregister/{username}');
                $record = APIRequestHelper::query($url);
            }else{
                App::logger('APIRequestHelper')->error("CHECK ME! ".__FILE__.' ('.__LINE__.')');
                $record = json_decode($db->singleValue('select voterCredential({username}) u',['username'=>$username],'u'),true);
            }
            if ($record===false){
                $_SESSION['pug_session']['error_no']=3;
                $_SESSION['pug_session']['error'][] = 'Der Benutzername oder das Passwort stimmen nicht überein.';
                Init::$next_state = 'error';
                return false;
            }

            if ($record['success']==false){
                $_SESSION['pug_session']['error_no']=4;
                $_SESSION['pug_session']['error'][] = 'Der Benutzername oder das Passwort stimmen nicht überein.';
                Init::$next_state = 'error';
                return false;
            }else{
                $record = $record['data'];
            }

        }catch(\Exception $e){
            App::logger('APIRequestHelper')->error($e->getMessage());
        }
        return $record;
    }

    public static function isNotBlocked($username){

        $db = App::get('session')->getDB();
        $config = App::get('configuration');
        $times = 2;
        if (isset($config['onlinevote'])&&isset($config['onlinevote']['allowed_failures'])) $times = intval($config['onlinevote']['allowed_failures']);

        if (isset( $_SESSION['pug_session']['texts']['blockcount']) ) {
            $times = intval($_SESSION['pug_session']['texts']['blockcount']['value_plain'] );
        }
        $db->direct('delete from username_count where block_until<now()');
        if (
            $db->singleRow('select * from username_count where id = {username} and block_until>now() and num > '.$times,['username'=>$username])!==false
        ){
            $_SESSION['pug_session']['login']=false;
            $_SESSION['pug_session']['error'][] = " ";
            Init::$next_state = 'blocked-user';
            return false;
        }

        
    }

    public static function login($username,$password){
        $db = App::get('session')->getDB();
        App::logger('APIRequestHelper')->info( "login  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
        if (self::isNotBlocked($username)===false) return false;

        // sichertheit erhöhen, durch das abrufen externer daten
        $record = self::loginGetCredentials($username);
        $_SESSION['pug_session']['login_token'] = $record['secret_token'];


        if ($record===false) { 
            App::logger('APIRequestHelper')->warning(   "login FALSE  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
            return false; 
        }

        if (!isset($record['possible_ballotpapers'])) throw new \Exception("possible_ballotpapers is missed");
        if (is_string($record['possible_ballotpapers'])) $record['possible_ballotpapers']=json_decode($record['possible_ballotpapers'],true);

        $_SESSION['pug_session']['available_ballotpapers'] = [];

        foreach($record['possible_ballotpapers'] as $ballotpaper){
            if ($ballotpaper['canvote']!==0){
                $_SESSION['pug_session']['available_ballotpapers'][] = $ballotpaper;
            }else{
                // Harter-Auschluss
                if (isset($ballotpaper['state']) && ($ballotpaper['state']=='5|0')){
                    Init::_initrun($request,$result);
                    $_SESSION['pug_session']['login']=false;
                    $_SESSION['pug_session']['error'][] = "Sie haben bereits teilgenommen.";
                    Init::$next_state = 'new-address';
                    return;
                }else if (isset($ballotpaper['state']) && ($ballotpaper['state']=='16|0')){
                    Init::_initrun($request,$result);
                    $_SESSION['pug_session']['login']=false;
                    $_SESSION['pug_session']['error'][] = "Sie haben bereits teilgenommen.";
                    Init::$next_state = 'new-documents';
                    return;
                }else if (isset($ballotpaper['state']) && ($ballotpaper['state']=='13|0')){
                    Init::_initrun($request,$result);
                    $_SESSION['pug_session']['login']=false;
                    $_SESSION['pug_session']['error'][] = "Sie haben bereits teilgenommen.";
                    Init::$next_state = 'inactive-account';
                    return;
                }
            }
        }

        if (count($_SESSION['pug_session']['available_ballotpapers'])==0){
            Init::_initrun($request,$result);
            $_SESSION['pug_session']['login']=false;
            $_SESSION['pug_session']['error'][] = "Sie haben bereits teilgenommen.";
            Init::$next_state = 'allready-voted';

            //if (false == $db->singleRow('select voter_id from voters where voter_id = {id} and stimmzettel = {stimmzettel} and completed=1',$record)){
            //    Init::$next_state = 'allready-voted-offline';
            //}else{
            //    Init::$next_state = 'allready-voted-online';
            //}
            return;
        }

        if (crypt($password, $record['pwhash']) == $record['pwhash']) {

            return true;  
            /*
            syslog(LOG_WARNING, "pwhash ok  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
            if ($record['allowed']==0){
                syslog(LOG_WARNING, "allowed 0  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
                $_SESSION['pug_session']['error_no']=2;
                $_SESSION['pug_session']['error'][] = 'Der Benutzername oder das Passwort stimmen nicht überein.';
                Init::$next_state = 'error-login';
                return false;                
            }
            if ($record['voted']==1){
                syslog(LOG_WARNING, "voted 1  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
                $_SESSION['pug_session']['error'][] = 'An der Wahl wurde bereits teilgenommen.';
                Init::$next_state = 'error';
                return false;                
            }
            */

            

        }else{
            App::logger('APIRequestHelper')->warning( "WMLoginUserNamePassword pwhash does not match");
            $_SESSION['pug_session']['error_no']=1;
            $_SESSION['pug_session']['error'][] = 'Der Benutzername oder das Passwort stimmen nicht überein.';
            Init::$next_state = 'error-login';
            $mins_time = 1;
            if (isset( $_SESSION['pug_session']['texts']['blocktime']) ) {
                $mins_time = intval($_SESSION['pug_session']['texts']['blocktime']['value_plain'] );
            }
            $db->direct('insert into username_count (id,block_until,num) values ( {username}, date_add(now(), interval '.$mins_time.' minute) ,1) on duplicate key update num=num+1,block_until=values(block_until) ',['username'=>$username]);
            return false;
        }



        
    }

    public static function run(&$request,&$result){
        @session_start();
        $db = App::get('session')->getDB();

        
        if (!isset($_SESSION['current_state'])){ 
            syslog(LOG_WARNING, "current_state state exit  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
            return;
        }
        if (!isset($_SESSION['pug_session'])){ 
            syslog(LOG_WARNING, "pug_session state exit  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
            return; 
        }
        
        if (!isset($_SESSION['pug_session']['usernamefield'])){
            syslog(LOG_WARNING, "usernamefield state exit  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
            return;
        }
        if (!isset($_SESSION['pug_session']['passwordfield'])){ 
            syslog(LOG_WARNING, "passwordfield state exit  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
            return; 
        }
        
        if (
            isset($_REQUEST[$_SESSION['pug_session']['usernamefield']]) &&
            isset($_REQUEST[$_SESSION['pug_session']['passwordfield']])
        ){
            $_SESSION['p1'] = $_REQUEST[$_SESSION['pug_session']['usernamefield']];
            $_SESSION['p2'] = $_REQUEST[$_SESSION['pug_session']['passwordfield']];

            syslog(LOG_WARNING, "user and pw read  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
        }else if (
            isset($_REQUEST['c']) && 
            is_string($_REQUEST['c']) && 
            (preg_match("/[a-z0-9\-\.]+/i",$_REQUEST['c'])>0) &&
            (strlen($_REQUEST['c'])==16)
        ){
            Init::$next_state = 'login';

            $_SESSION['p1'] = substr($_REQUEST['c'],0,8);
            $_SESSION['p2'] = substr($_REQUEST['c'],8,8);
            $_SESSION['pug_session']['p1'] = substr($_REQUEST['c'],0,8);
            $_SESSION['pug_session']['p2'] = substr($_REQUEST['c'],8,8);
        }


        if ( 
            isset($_SESSION['p1']) && isset($_SESSION['p2']) && 
            ( ($_SESSION['current_state'] == 'login' ) || ($_SESSION['current_state'] == 'logoutpage') ) && 
            ( !isset($_REQUEST['asklegitimation']) )){
            if (isset($_REQUEST['accept'])){

                $username = $_SESSION['p1'];
                $password = $_SESSION['p2'];

                if (self::login($username,$password)){

                    // alles ist gut
                    Init::$next_state = $db->singleValue('select value_plain from wm_texts where id=\'after_login_state\'',[],'value_plain');
                    if (Init::$next_state === false) Init::$next_state = 'choose-ballotpaper';


                }else{
                    syslog(LOG_WARNING, "login failed  from ".$_SESSION['IP_ADDRESS']." - ".__LINE__." ".__FILE__." ");
                }
            }
        }else{
            syslog(LOG_WARNING, "not in login state  from ".$_SESSION['IP_ADDRESS']." - ".$_SESSION['current_state']." - ".Init::$next_state." - ".__LINE__." ".__FILE__." ");
            
        }
        session_commit();
        
    }
}
