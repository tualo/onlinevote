<?php
namespace Tualo\Office\OnlineVote\Middlewares;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\OnlineVote\Middlewares\Init;

use Tualo\Office\Basic\IMiddleware;

class Ballotpaper  {

    public static function save(&$request,&$result){
        $db = App::get('session')->getDB();

        $voter = $db->direct('select session_id from voters where voter_id = {voter_id} and stimmzettel = {stimmzettel_id} and  session_id={session_id} ', 
        [
            'voter_id'          =>  $_SESSION['pug_session']['voter_id'],
            'stimmzettel_id'    =>  $_SESSION['pug_session']['stimmzettel_id'],
            'session_id'=>session_id()
        ] );

        if (count($voter)==0){ 
            $_SESSION['pug_session']['error'][] = "Die Sitzung ist nicht mehr gültig";
            Init::$next_state = 'error';
            return;
        }


        $voter = $db->direct('select voter_id from voters where voter_id = {voter_id}  and stimmzettel = {stimmzettel_id} and completed=1 ', 
        [
            'voter_id'=>$_SESSION['pug_session']['voter_id'],
            'stimmzettel_id'=>$_SESSION['pug_session']['stimmzettel_id'],
            'session_id'=>session_id()
        ] );
        if (count($voter)>0){ 
            $_SESSION['pug_session']['error'][] = "Die Sitzung ist nicht mehr gültig, Sie haben bereits bereits gewählt.";
            Init::$next_state = 'error';
            return;
        }

        
        try{

            $db->direct('start transaction;');
            $pgpkeys = $db->direct('select * from pgpkeys');
            $_SESSION['pug_session']['pgp'] = [];
            $_SESSION['pug_session']['pgp_decrypted'] = [];
            foreach($pgpkeys as $keyitem){
                $hash = $keyitem;
                
                $hash['ballotpaper']=TualoApplicationPGP::encrypt( $keyitem['publickey'], json_encode($_SESSION['pug_session']['ballotpaper']['checks']));
                
                $hash['voter_id']   =$_SESSION['pug_session']['voter_id'];
                $hash['stimmzettel_id']=$_SESSION['pug_session']['stimmzettel_id'];
                $hash['stimmzettel']=$_SESSION['pug_session']['ballotpaper']['id'];
                $hash['isvalid']    =($_SESSION['pug_session']['ballotpaper']['valid']===true)?1:0;
                if (isset($_SESSION['pug_session']['secret_token'])){
                    $hash['token']    = $_SESSION['pug_session']['secret_token'];
                }else{
                    $hash['token']    = generateGUID(10);
                }
                /**
                 * alter table ballotbox add isvalid tinyint default 0;
                 * alter table ballotbox add stimmzettel varchar(10) default '';
                 * alter table ballotbox add stimmzettel_id varchar(10) default '';
                 */

                $db->direct('insert into ballotbox (id,keyname,ballotpaper,voter_id,stimmzettel_id,stimmzettel,isvalid) 
                values ({token},{keyname},{ballotpaper},{voter_id},{stimmzettel_id},{stimmzettel},{isvalid})',$hash);
                unset($hash['privatekey']);
                $_SESSION['pug_session']['pgp'][] = $hash;

                
            }
            if ($_SESSION['api']==1){
                $url = $_SESSION['api_url'].str_replace('{voter_id}',$_SESSION['pug_session']['voter_id'],str_replace('{stimmzettel_id}',$_SESSION['pug_session']['stimmzettel_id'],'cmp_wm_ruecklauf/api/set/{voter_id}/{stimmzettel_id}'));
                $record = APIRequestHelper::query($url,['secret_token'=>$_SESSION['pug_session']['secret_token']]);
                if ($record===false) throw new \Exception('Der Vorgang konnte nicht abgeschlossen werden');
                if ($record['success']==false) throw new \Exception($record['msg']);
            }

            $voter = $db->direct('update voters set completed = 1 where voter_id = {voter_id} and stimmzettel = {stimmzettel_id}',$_SESSION['pug_session']);

            // reduzieren $_SESSION['pug_session']['available_ballotpapers']
            $rest = [];
            foreach($_SESSION['pug_session']['available_ballotpapers'] as $ballotpaper){
                if (!( 
                    ($_SESSION['pug_session']['stimmzettel_id']==$ballotpaper['stimmzettel'])
                    && ($_SESSION['pug_session']['voter_id'] == $ballotpaper['voter_id'])
                )){
                    $rest[]= $ballotpaper;
                }
            }
            $_SESSION['pug_session']['available_ballotpapers']=$rest;

            $db->direct('commit;');

        }catch(\Exception $e){
            Init::$next_state = 'error';
            $_SESSION['pug_session']['error'][] = $e->getMessage();
        }

    }

    
    public static function valid(){
        $db = App::get('session')->getDB();
        $_SESSION['pug_session']['ballotpaper']['valid'] = false;
        $kandidaten = $db->direct('select id,barcode,ridx,stimmzettelgruppen from kandidaten',$_SESSION['pug_session']['ballotpaper'],'id');


        $stimmzettel_sitze = intval($db->singleValue('select sitze from stimmzettel where id={id}',$_SESSION['pug_session']['ballotpaper'],'sitze'));

        $stimmzettelgruppen = $db->direct('
                select 
                    stimmzettelgruppen.ridx,
                    stimmzettelgruppen.id,
                    stimmzettelgruppen.name,
                    stimmzettelgruppen.sitze,
                    stimmzettelgruppen.stimmzettel,
                    0 __checkcount 
                from 
                stimmzettelgruppen where stimmzettel in (select ridx from stimmzettel where id={id})',$_SESSION['pug_session']['ballotpaper'],'ridx');

        foreach($_SESSION['pug_session']['ballotpaper']['checks'] as $check){
            if (isset($kandidaten[$check])){
                if (isset($stimmzettelgruppen[$kandidaten[$check]['stimmzettelgruppen']])){
                    $stimmzettelgruppen[$kandidaten[$check]['stimmzettelgruppen']]['__checkcount']++;
                }else{
                    syslog(LOG_CRIT,"WM Stimmzettegruppe {$kandidaten[$check]['stimmzettelgruppe']} bei Kandidat ID $check not found");
                    Init::$next_state = 'error';
                    $_SESSION['pug_session']['error'][] = 'Der Kandidat ist nicht für Ihren Stimmzettel zugelassen.';
                    return false;
                }
            }else{
                syslog(LOG_CRIT,"WM Kandidate ID $check not found");
                Init::$next_state = 'error';
                $_SESSION['pug_session']['error'][] = 'Der Kandidat ist nicht für Ihren Stimmzettel zugelassen.';
                return false;
            }

        }


        $_SESSION['pug_session']['ballotpaper']['invalid_note'] = '';
        syslog(LOG_CRIT,"prüfe die stimmzettel stimmenanzahl");
        $valid = true;
        $c = 0;
        foreach($stimmzettelgruppen as $stimmzettelgruppe){
            if ($stimmzettelgruppe['__checkcount']>$stimmzettelgruppe['sitze']){
                syslog(LOG_CRIT,"zu viele Stimmen in der Stimmzettelgruppe");
                $valid=false;
            }
            $c+=$stimmzettelgruppe['__checkcount'];
        }
        if ($c > $stimmzettel_sitze) {
            syslog(LOG_CRIT,"zu viele Stimmen auf dem Stimmzettel");
            $_SESSION['pug_session']['ballotpaper']['invalid_note'] = 'zu viele Stimmen auf dem Stimmzettel';
            $valid=false;
        }
        $_SESSION['pug_session']['ballotpaper']['valid'] = $valid;
        return true;

    }

    public static function empty($id){
        $db = CMSMiddlewareWMHelper::$db;
        return [
            'id' => $id,
            'valid' => true,
            'max' => $db->singleValue('select sitze from stimmzettel where id={id}',['id'=>$id],'sitze'),
            'checkcount' => 0,
            'checks' => [],
            'interrupted'=> false
        ];
    }


    public static function isInterrupted( ){
        $db = App::get('session')->getDB();
        if (isset($_SESSION['pug_session']) && (isset($_SESSION['pug_session']['ballotpaper']))){
            $interrupted = $db->singleValue('select unterbrochen from stimmzettel where id={id} ',$_SESSION['pug_session']['ballotpaper'],'unterbrochen');
            if ($interrupted==1) $_SESSION['pug_session']['ballotpaper']['interrupted']=true;
        }
        return $_SESSION['pug_session']['ballotpaper']['interrupted']==true;
    }


    public static function checkCandidateList( ){
        if (!isset($_REQUEST['candidate'])){
            if (isset($_SESSION['pug_session']['ballotpaper']['checks']) && (!isset($_REQUEST['send']))){
                $_REQUEST['candidate'] = $_SESSION['pug_session']['ballotpaper']['checks'];
            }else{
                $_REQUEST['candidate'] = [];
            }
        } 
        if (!is_array($_REQUEST['candidate'])){
            return;
        }
        $_SESSION['pug_session']['ballotpaper']['checkcount'] = count($_REQUEST['candidate']);
        $_SESSION['pug_session']['ballotpaper']['checks']=$_REQUEST['candidate'];
        
    }

    public static function run(&$request,&$result){
        @session_start();
        $db = App::get('session')->getDB();

        
        if (!isset($_SESSION['current_state'])) return;
        if (!isset($_SESSION['pug_session'])) return;
        if (self::isInterrupted()) return;

        if ($_SESSION['current_state'] == 'ballotpaper'){
            self::checkCandidateList();
            if (self::valid()===false) return;
            Init::$next_state = 'overview';
        }else if(
            (($_SESSION['current_state'] == 'ballotpaper') || ($_SESSION['current_state'] == 'overview')) && 
            (isset($_REQUEST['correct']))
        ){
            self::empty($_SESSION['pug_session']['ballotpaper']['id']);
            Init::$next_state = 'ballotpaper';
        }else if ($_SESSION['current_state'] == 'overview'){

            $allow_invalid_ballotpaper_save = ($db->singleValue('select value_plain from wm_texts where id=\'allow_invalid_ballotpaper_save\'',[],'value_plain')==='1');
            if (
                ($allow_invalid_ballotpaper_save===true) || ($_SESSION['pug_session']['ballotpaper']['valid']==true)
            ){
                Init::$next_state = 'error';
                // time to save
                if ($_SESSION['pug_session']['single_vote']==1){
                   Ballotpaper::save($request,$result);
                }else{
                    $list = $_SESSION['pug_session']['available_ballotpapers'];
                    foreach($list as $ballotpaper){
                        if ($ballotpaper['stimmzettel_id'] ==  $_SESSION['pug_session']['stimmzettel_id']){
                            $_SESSION['pug_session']['voter_id'] =          $ballotpaper['voter_id'];
                            $_SESSION['pug_session']['ballotpaper_id'] =    $ballotpaper['ballotpaper_id'];
                            Ballotpaper::save($request,$result);
                        }
                    }
                }



                if (count($_SESSION['pug_session']['available_ballotpapers'])>0){
                    Init::$next_state = 'choose-ballotpaper';
                }else{
                    unset($_SESSION['pug_session']);
                    Init::_initrun($request,$result);
                    $_SESSION['pug_session']['login']=false;
                    Init::$next_state = 'ballotpaper-saved';
                }

                
            
            }else{
                Init::$next_state = 'error';
                $_SESSION['pug_session']['error'][] = 'Zu viele Stimmen auf dem Stimmzettel';
            }

        }


        session_commit();
        
        
    }
}