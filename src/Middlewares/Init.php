<?php
namespace Tualo\Office\OnlineVote\Middlewares;
use Tualo\Office\OnlineVote\CIDR;
use Tualo\Office\Basic\TualoApplication as App;

class Init /*extends CMSMiddleWare*/{

    public static $current_state = '';
    public static $next_state = '';
    public static function db() { return App::get('session')->getDB(); }

    public static function registerstep($name){
        $db = self::db();
        $key = $db->singleValue('select uuid() u',[],'u');
        
        foreach($_SESSION['pug_session']['step_hash'] as $k=>$n){
            if ($n==$name) unset($_SESSION['pug_session']['step_hash'][$k]);
        }
        $_SESSION['pug_session']['step_hash'][$key]=$name;
        $_SESSION['pug_session']['step_key'][$name]=$key;

        $_SESSION['pug_session']['stepuuid']=$key;

    }


    public static function _initrun(&$request,&$result){
        


        if (!isset($_SESSION['pug_session'])){
            $_SESSION['pug_session'] = [];
            $_SESSION['pug_session']['error'] = [];
            $_SESSION['pug_session']['login']=false;
            $_SESSION['saving']=false;
            
            $_SESSION['pug_session']['login_error']=0;
            $_SESSION['pug_session']['step_hash'] = [];
            $_SESSION['current_state'] = '';
        }
        

        if (
            isset($_REQUEST['uuid']) && 
            is_string($_REQUEST['uuid']) && 
            (preg_match("/[a-z0-9\-\.]+/i",$_REQUEST['uuid'])>0) &&
            isset($_SESSION['pug_session']['step_hash'][$_REQUEST['uuid']])
        ){
            $_SESSION['current_state'] = $_SESSION['pug_session']['step_hash'][$_REQUEST['uuid']];
            syslog(LOG_WARNING, " wm_init set current state by ".$_SESSION['current_state']);
        }else{


        }
        self::$next_state = 'login';
        syslog(LOG_WARNING, " wm_init _initrun ");

    }


    public static function  ipCIDRCheck ($IP, $CIDR) {
        list($net, $mask) = explode("/", $CIDR);
        $ip_net = ip2long($net);
        $ip_mask = ~((1 << (32 - $mask)) - 1);
        $ip_ip = ip2long($IP);
        $ip_ip_net = $ip_ip & $ip_mask;
        return ($ip_ip_net == $ip_net);
    }



    

    public static function run(&$request,&$result){
        @session_start();
        $db = self::db();
        self::_initrun($request,$result);
        $config = App::get('configuration');

        $_SESSION['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];
        if (isset($config['__CMS_ALLOWED_IP_FIELD__'])){
            $_SESSION['IP_ADDRESS'] = isset($_SERVER[$config['__CMS_ALLOWED_IP_FIELD__']])?$_SERVER[$config['__CMS_ALLOWED_IP_FIELD__']]:$_SERVER['REMOTE_ADDR'];
            $allowedcidr = $db->direct('select cidr from allowed_test_ip where current_date <= allowed_until and current_date >= allowed_from',[],'');
            $allowed=false;
            foreach($allowedcidr as $cidr){
                if (CIDR::IPisWithinCIDR($_SESSION['IP_ADDRESS'],$cidr['cidr'])) $allowed=true;
            }
            if ($allowed===false){
                self::$next_state = 'notstarted';
                $_SESSION['current_state']= 'notstarted';
            }
        }
        $_SESSION['pug_session']['error'] = [];
        session_commit();
    }
}