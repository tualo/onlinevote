<?php
namespace Tualo\Office\OnlineVote\CMSMiddleware;
use Tualo\Office\OnlineVote\CIDR;
use Tualo\Office\OnlineVote\WMStateMachine;
use Michelf\MarkdownExtra;
use Tualo\Office\Basic\TualoApplication as App;

class Init {
    private static $textsSQL = 'select id,value_plain,value_html from wm_texts where id<>"" ';
    public static $texts=[];
    public static function markdownfn():mixed{
        return function(string $textkey):string{
            $result = MarkdownExtra::defaultTransform( isset(self::$texts[$textkey])?self::$texts[$textkey]:"{$textkey} not defined" );
            if (strpos($result,"<p>")===0) $result = substr( $result ,3,-3);
            return $result;
        };
    }

    public static function textfn():mixed{
        return function(string $textkey):string{
            $result =  isset(self::$texts[$textkey])?self::$texts[$textkey]:"{$textkey} not defined";
            return $result;
        };
    }

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
        unset($_SESSION['voter']);
        unset($_SESSION['wm_state']);
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

        //if (isset($_REQUEST['resetsession'])) @session_destroy();

        
        $db = self::db();
        self::_initrun($request,$result);

        App::timing(self::class.' '.__LINE__);
        InitApiUse::run($request,$result);
        App::timing(self::class.' '.__LINE__);
        $config = App::get('configuration');
        App::timing(self::class.' '.__LINE__);

        
        self::$texts = $result['texts'] = $db->directMap(self::$textsSQL,[],'id','value_plain');
        App::timing(self::class.' '.__LINE__);
        $wmstate = WMStateMachine::getInstance();

        $wmstate->ip($_SERVER['REMOTE_ADDR']);
        $wmstate->setCurrentState($wmstate->getNextState());


        if( $wmstate->getCurrentState()=='') $wmstate->setCurrentState('Tualo\Office\OnlineVote\States\Login');

        try{
            $class = new \ReflectionClass($wmstate->getCurrentState());
            if (!$class->hasMethod('transition')){ 
                App::logger('CMS')->error($wmstate->getCurrentState().' has no run transition');
            }else{
                $state = new ($wmstate->getCurrentState());
                $wmstate->setNextState( $state->transition($request,$result) );
            }
        }catch(\Exception $e ){
            App::logger('OnlineVote')->error($e->getMessage());
        }


        // print_r($result['wm_state']['texts'] );exit();

        /*
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
        */
        //$wmstate->currentState
        $wmstate->usernamefield(true);
        $wmstate->passwordfield(true);


        $result['wms'] =$wmstate;
        $result['md'] = self::markdownfn();
        $result['txt'] = self::textfn();

        $_SESSION['wmstatemachine'] = serialize($wmstate);

        
        
    }
}