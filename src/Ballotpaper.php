<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote;

use Tualo\Office\Basic\TualoApplication as App;

use Tualo\Office\TualoPGP\TualoApplicationPGP;
use Ramsey\Uuid\Uuid;

use Tualo\Office\OnlineVote\Exception\SystemBallotpaperSaveException;
use Tualo\Office\OnlineVote\Exception\RemoteBallotpaperSaveException;
use Tualo\Office\OnlineVote\Exception\RemoteBallotpaperApiException;

class Ballotpaper {
    private int $voter_id;
    private int $ballotpaper_id;
    private int $canvote;
    private string $state;
    private array $filled=[];
    private bool $is_valid =false;

    private array $idMap = [];
    private array $hashMap = [];
    private array $candidates = [];
    private array $config = [];
    private array $configgroups = [];
    
    private static array $required_attributes = ['voter_id','ballotpaper_id','canvote','state'];

    public static function getInstanceFromJSON($json):Ballotpaper {
        $instance = new self();
        foreach(self::$required_attributes as $key){
            if (!isset($json[$key])) throw new \Exception("attribute {$key} is missed");
        }
        $instance->setVoterId(intval($json['voter_id']));
        $instance->setBallotpaperId(intval($json['ballotpaper_id']));
        $instance->setCanvote(intval($json['canvote']));
        $instance->setState($json['state']);

        if (isset($json['filled'])) $instance->setFilled($json['filled']);

        return $instance;
    }

    protected function setVoterId(int $voter_id){  $this->voter_id = $voter_id; }
    protected function setBallotpaperId(int $ballotpaper_id){  $this->ballotpaper_id = $ballotpaper_id; }
    protected function setCanvote(int $canvote){  $this->canvote = $canvote; }
    protected function setState(string $state){  $this->state = $state; }
    protected function setFilled(array $filled){  $this->filled = $filled; }

    public function getVoterId( ):int{ return $this->voter_id; }
    public function getBallotpaperId( ):int{ return $this->ballotpaper_id; }
    public function getCanvote( ):int{ return $this->canvote; }
    public function getState( ):string{ return $this->state; }

    public function setPossibleCandidates(array $candidates):void{
        $this->candidates=$candidates;
        $this->setupHashMap();
    }

    public function setConfiguration(array $config,array $configgroups):void{
        $candidates = [];
        foreach($configgroups as &$group){ 
            $candidates+=$group['candidates'];
            $l=[];
            foreach($group['candidates'] as $candidate) $l[]=$candidate['id'];
            $group['candidates_by_id']=$l;
        }
        $this->config=$config;
        $this->configgroups=$configgroups;
        $this->setPossibleCandidates($candidates);
    }

    public function max():int{
        return intval($this->config['sitze']);
    }
    public function checkcount():int{
        return count($this->filled );
    }

    public function valid(){
        $this->is_valid=false;;
        foreach($this->configgroups as &$group) $group['__checkcount']=0;
        foreach($this->filled as $check){
            if (!isset($this->idMap[$check])){
                App::logger('Ballotpaper')->warning( "candidate not found ($check)" );
                return false;
            }
            foreach($this->configgroups as &$group){
                if (in_array($check,$group['candidates_by_id'])) $group['__checkcount']++;
            }
        }
        $this->is_valid = true;
        $c = 0;
        foreach($this->configgroups as $group){
            if ($group['__checkcount']>$group['sitze']){
                App::logger('Ballotpaper')->info( 'zu viele Stimmen in der Stimmzettelgruppe' );
                $this->is_valid = false;
            }
            $c+=$group['__checkcount'];
        }
        if ($c > $this->config['sitze']) {
            App::logger('Ballotpaper')->info( 'zu viele Stimmen auf dem Stimmzettel' );
            $this->is_valid = false;
        }
        return $this->is_valid;
    }



    public function setupHashMap():void{
        $this->idMap=[];
        $this->hashMap=[];
        foreach($this->candidates as $candidate){
            $hash=(Uuid::uuid4())->toString();
            $this->idMap[$candidate['id']]=$hash;
            $this->hashMap[$hash]=$candidate['id'];
        }
    }

    public function getMapHash( int $var ):string {
        return (string)$this->idMap[$var];
    }
    public function getMapId( string $var ):int {
        return intval($this->hashMap[$var]);
    }

    public function isChecked(int $var){
        # code...
        return in_array($var,$this->filled);
    }
    
    public function setVotes(array $candidates){
        $this->filled=[];

        App::logger('Ballotpaper')->debug(  json_encode($candidates) );
        foreach( $candidates as $candidate){
            if (!isset($this->hashMap[$candidate])){ App::logger('Ballotpaper')->error( "candidate not found" );  throw new \Exception("candidate not found"); }
            if (in_array($this->hashMap[$candidate],$this->filled)){ App::logger('Ballotpaper')->error( "candidate allready in list" );  throw new \Exception("candidate allready in list");}
            $this->filled[]=intval($this->hashMap[$candidate]);
            App::logger('Ballotpaper')->debug(  json_encode($this->filled) );
        }
    }

    public function save():void{
        // check md5
        // ggf recreate md5

        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        $queryHash=[
            'voter_id'=>$this->getVoterId(),
            'stimmzettel_id'=>$this->getBallotpaperId(),
            'session_id'=>session_id()
        ];

        $voter = $db->singleRow('select session_id from voters where voter_id = {voter_id} and stimmzettel = {stimmzettel_id} and  session_id={session_id} ',  $queryHash );

        if ($voter===false){ 
            throw new \Exception('Ihre Sitzung ist nicht mehr gültig');
        }
        $voter = $db->singleRow('select voter_id from voters where voter_id = {voter_id}  and stimmzettel = {stimmzettel_id} and completed=1 ', $queryHash);
        if ($voter===false){ 
            throw new \Exception('Die Sitzung ist nicht mehr gültig, Sie haben bereits bereits gewählt.');
        }

        
        try{

            $db->direct('start transaction;');
            $pgpkeys = $db->direct('select * from pgpkeys');
            foreach($pgpkeys as $keyitem){
                $hash = $keyitem;
                $hash['ballotpaper']=TualoApplicationPGP::encrypt( $keyitem['publickey'], json_encode($this->filled));
                $hash['stimmzettel_id']=$this->getBallotpaperId();
                $hash['stimmzettel']=$this->getBallotpaperId().'|0';
                $hash['isvalid']    =$this->is_valid;
                $hash['token']    = $stateMachine->voter()->getSecretToken();

                $db->direct('insert into ballotbox (id,keyname,ballotpaper,voter_id,stimmzettel_id,stimmzettel,isvalid) 
                values ({token},{keyname},{ballotpaper},{voter_id},{stimmzettel_id},{stimmzettel},{isvalid})',$hash);
                unset($hash['privatekey']);                
            }
            if ($_SESSION['api']==1){
                $url = $_SESSION['api_url']
                    .str_replace('{voter_id}',$queryHash['voter_id'],str_replace('{stimmzettel_id}',$queryHash['stimmzettel_id'],'papervote/api/set/{voter_id}/{stimmzettel_id}'));
                $record = APIRequestHelper::query($url,[
                    'secret_token'=>$_SESSION['pug_session']['secret_token']
                ]);
                if ($record===false) throw new RemoteBallotpaperApiException('Der Vorgang konnte nicht abgeschlossen werden');
                if ($record['success']==false) throw new RemoteBallotpaperSaveException($record['msg']);
            }
            $voter = $db->direct('update voters set completed = 1 where voter_id = {voter_id} and stimmzettel = {stimmzettel_id}',$queryHash);
            $db->direct('commit;');

        }catch(\Exception $e){
            throw new SystemBallotpaperSaveException($e->getMessage());
        }

    }

}