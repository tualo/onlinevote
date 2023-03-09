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
        $result['rows'] = $db->direct('
        select
                bp_row.pos,
                bp_row.row_name,
                bp_row.prefix,
                
                json_arrayagg(
                    json_object(
                        "column_name",
                        col.column_name,
                        "pos",
                        col.pos,
                        "prefix",
                        col.prefix,
                    "definition",definition
                    ) 
                    order by col.pos
                ) columns
        from
        bp_row
        join (select
                    bp_column.column_name,
                    bp_column.pos,
                    bp_column.row_name,
                    bp_column.prefix,
            
                    json_arrayagg(
                        json_object(
                            "column_field",
                            bp_column_definition.column_field,
                            "htmltag",
                            bp_column_definition.htmltag
                        ) 
                        order by bp_column_definition.pos
                    ) definition
                    from
                        bp_column
                        join bp_column_definition 
                            on  bp_column.column_name = bp_column_definition.column_name 
                                and bp_column_definition.active=1  
                                and bp_column.active=1
                        join ds_column 
                            on  (ds_column.table_name,ds_column.column_name) = ("view_website_candidates", bp_column_definition.column_field) 
                                and ds_column.existsreal=1

                    group by bp_column.column_name
                order by pos
        ) col
        on bp_row.row_name = col.row_name
                        and bp_row.active=1
        
        group by bp_row.row_name
            order by pos
        ');
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
            $result['errorMessage'] = $e->getMessage();
            $wmstate->setNextState( 'Tualo\Office\OnlineVote\States\Error' );
            App::logger('OnlineVote')->error($e->getMessage());
        }


        $wmstate->usernamefield(true);
        $wmstate->passwordfield(true);





        // prepare the next state
        try{
            $class = new \ReflectionClass($wmstate->getNextState());
            if (!$class->hasMethod('prepare')){ 
                App::logger('CMS')->error($wmstate->getNextState().' has no  prepare');
            }else{
                $state = new ($wmstate->getNextState());
                $wmstate->setNextState( $state->prepare($request,$result) );
            }
        }catch(\Exception $e ){
            App::logger('OnlineVote')->error($e->getMessage());
        }

        $result['wms'] =$wmstate;
        $result['md'] = self::markdownfn();
        $result['txt'] = self::textfn();

        $_SESSION['wmstatemachine'] = serialize($wmstate);

        
        
    }
}