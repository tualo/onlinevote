<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote;

use Tualo\Office\Basic\TualoApplication as App;

use Tualo\Office\TualoPGP\TualoApplicationPGP;
use Ramsey\Uuid\Uuid;

use Tualo\Office\OnlineVote\Exceptions\SystemBallotpaperSaveException;
use Tualo\Office\OnlineVote\Exceptions\RemoteBallotpaperSaveException;
use Tualo\Office\OnlineVote\Exceptions\BallotPaperAllreadyVotedException;
use Tualo\Office\OnlineVote\Exceptions\SessionBallotpaperSaveException;
use Tualo\Office\OnlineVote\Exceptions\RemoteBallotpaperApiException;

class Ballotpaper {
    private int $voter_id;
    private int $ballotpaper_id;
    private string $ballotpaper_name;
    private array $voter_data;
    private int $canvote;
    private string $state;
    private string $secret_token;
    private array $filled=[];
    private bool $is_valid =false;

    private array $idMap = [];
    private array $hashMap = [];
    private array $candidates = [];
    private array $config = [];
    private array $configgroups = [];
    
    private static array $required_attributes = ['voter_id','ballotpaper_id','canvote','state','ballotpaper_name','voter_data'];

    public static function getInstanceFromJSON($json):Ballotpaper {
        $instance = new self();
        foreach(self::$required_attributes as $key){
            if (!isset($json[$key])){ 
                throw new \Exception("attribute {$key} is missed");
            }
        }
        $instance->setVoterId(intval($json['voter_id']));
        $instance->setBallotpaperId(intval($json['ballotpaper_id']));
        $instance->setBallotpaperName(($json['ballotpaper_name']));
        $instance->setVoterData(is_string($json['voter_data'])?json_decode($json['voter_data'],true):$json['voter_data']);
        $instance->setCanvote(intval($json['canvote']));
        $instance->setState($json['state']);



        if (isset($json['secret_token'])){
            $instance->setSecretToken( (string)TualoApplicationPGP::decrypt( $_SESSION['api_private'],TualoApplicationPGP::unarmor($json['secret_token'])) ) ;
            if ($instance->getSecretToken()==''){
                App::logger('Ballotpaper(function getInstanceFromJSON)')->error('secret token is '.$instance->getSecretToken());
            }else{
                App::logger('Ballotpaper(function getInstanceFromJSON)')->info('secret token is '.$instance->getSecretToken());
            }
        }


        if (isset($json['filled'])) $instance->setFilled($json['filled']);

        return $instance;
    }

    protected function setVoterId(int $voter_id){  $this->voter_id = $voter_id; }
    protected function setBallotpaperId(int $ballotpaper_id){  $this->ballotpaper_id = $ballotpaper_id; }
    protected function setBallotpaperName(string $ballotpaper_name){  $this->ballotpaper_name = $ballotpaper_name; }
    protected function setVoterData(array $voter_data){  $this->voter_data = $voter_data; }
    protected function setCanvote(int $canvote){  $this->canvote = $canvote; }
    protected function setState(string $state){  $this->state = $state; }
    protected function setFilled(array $filled){  $this->filled = $filled; }
    protected function setSecretToken(string $secret_token){  $this->secret_token = $secret_token; }

    public function getVoterId( ):int{ return $this->voter_id; }
    public function getBallotpaperId( ):int{ return $this->ballotpaper_id; }
    public function getBallotpaperName( ):string{ return $this->ballotpaper_name; }
    public function getVoterData( ):array{ return $this->voter_data; }
    public function getCanvote( ):int{ return $this->canvote; }
    public function getState( ):string{ return $this->state; }
    public function getSecretToken( ):string{ return $this->secret_token; }

    public function setPossibleCandidates(array $candidates):void{
        $this->candidates=$candidates;
        $this->setupHashMap();
    }

    public function setConfiguration(array $config,array $configgroups):void{
        $candidates = [];
        foreach($configgroups as &$group){ 
            // if (!isset($group['candidates'])) $group['candidates']=[];
            //$candidates=$group['candidates'];
            $candidates=array_merge($candidates,$group['candidates']);
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
        $this->is_valid=false;
        foreach($this->configgroups as $index => $group) $this->configgroups[$index]['__checkcount']=0;
        App::logger('Ballotpaper')->debug( '+++++ filled'.print_r($this->filled,true) );
        App::logger('Ballotpaper')->debug( '+++++ idMap'.print_r($this->idMap,true) );
        App::logger('Ballotpaper')->debug( '+++++ hashMap'.print_r($this->hashMap,true) );
        foreach($this->filled as $check){
            App::logger('Ballotpaper')->debug( '+++++ check'.$check );
            if (!isset($this->idMap) || !isset($this->idMap[$check])){
                App::logger('Ballotpaper')->warning( "candidate not found ($check)" );
                return false;
            }
            foreach($this->configgroups as $index => $group){
                if (in_array($check,$this->configgroups[$index]['candidates_by_id'])) $this->configgroups[$index]['__checkcount']++;
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
        return in_array($var,$this->filled);
    }
    
    public function setVotes(array $candidates){
        $this->filled=[];

        App::logger('Ballotpaper')->debug(  json_encode($candidates) );
        foreach( $candidates as $candidate){
            if (!isset($this->hashMap[$candidate])){ 
                App::logger('Ballotpaper')->error( "candidate not found" );  
                throw new \Exception("candidate not found"); 
            }
            if (in_array($this->hashMap[$candidate],$this->filled)){ 
                App::logger('Ballotpaper')->error( "candidate allready in list" );  
                throw new \Exception("candidate allready in list");
            }
            $this->filled[]=intval($this->hashMap[$candidate]);
            App::logger('Ballotpaper')->debug(  json_encode($this->filled) );
        }
    }

    public function getVotes():array{
        return $this->filled;
    }
    public function getHashMap(): array{
        return $this->hashMap;
    }
    public function getIdMap(): array{
        return $this->idMap;
    }
    public function setVotesIntern(array $hashmap,array $idmap, array $candidates){
        $this->idMap = $idmap;
        $this->hashMap = $hashmap;
        $this->filled=$candidates;
    }


    public function register():void{
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        $sql = 'insert into voters (
            voter_id,
            stimmzettel,
            session_id,
            completed
        ) values (
            {voter_id},
            {stimmzettel},
            {session_id},
            {completed}
        ) on 
            duplicate key 
            update session_id=values(session_id)
        ';
        $db->direct($sql,[
            'voter_id'      =>  $this->getVoterId(),
            'stimmzettel'   =>  ((string)$this->getBallotpaperId()).'|0',
            'session_id'    =>  session_id(),
            'completed'     =>  0
        ]);
    }

    public function checkLocal():bool{
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        $voter = $db->singleRow('
        select
            voter_id 
        from 
            voters 
        where 
            voter_id        =   {voter_id}
            and stimmzettel =   {stimmzettel_id}
            and completed   =   1
        ',  [
            'voter_id'=>$this->getVoterId(),
            'stimmzettel_id'=>((string)$this->getBallotpaperId()).'|0'
        ] );
        return $voter !== false;
    }


    public function checkRemote():bool{
        if ($_SESSION['api']==1){
            $stateMachine = WMStateMachine::getInstance();
            $db = $stateMachine->db();
            
            $url = $_SESSION['api_url'].'papervote/check';
            $record = APIRequestHelper::query($url,[
                'voter_id' =>$this->getVoterId(),
                'ballotpaper_id' => $this->getBallotpaperId(),
                'signature' => TualoApplicationPGP::sign($_SESSION['api_private'],(string)$this->getVoterId())
            ]);
            return $record!==false;
        }else{
            return true;
        }
    }

    public function save():void{
        // check md5
        // ggf recreate md5

        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
       

        if ($this->checkRemote()===false){ 
            App::logger('Ballotpaper(function save)')->info('Laut RemoteSystem bereits gewählt'.$this->getVoterId());
            throw new BallotPaperAllreadyVotedException('Laut RemoteSystem bereits gewählt');
        }

        if ($stateMachine->voter()->validSession()===false){ 
            App::logger('Ballotpaper(function save)')->info('Ihre Sitzung ist nicht mehr gültig. (neue Anmeldung vorhanden)'.$db->last_sql);
            throw new SessionBallotpaperSaveException('Ihre Sitzung ist nicht mehr gültig. (neue Anmeldung vorhanden)');
        }
        
        if ($this->checkLocal()===true){ 
            $txt = 'Die Sitzung ist nicht mehr gültig, Sie haben bereits bereits gewählt.'.((string)$this->getBallotpaperId()).'|0'.'##'.$this->getVoterId();
            App::logger('Ballotpaper(function save)')->debug($txt );
            App::logger('Ballotpaper(function save)')->debug( $db->last_sql );
            throw new BallotPaperAllreadyVotedException($txt );
        }

        
        try{

            $db->direct('start transaction;');
            $pgpkeys = $db->direct('select * from pgpkeys');
            foreach($pgpkeys as $keyitem){
                $hash = $keyitem;

                $hash['ballotpaper']    =   TualoApplicationPGP::enarmor(TualoApplicationPGP::encrypt( $keyitem['publickey'], json_encode($this->filled)),'MESSAGE');
                $hash['stimmzettel_id'] =   $this->getBallotpaperId();
                $hash['stimmzettel']    =   $this->getBallotpaperId().'|0';
                $hash['isvalid']        =   $this->valid()?'1':'0';
                $hash['token']          =   $this->getSecretToken();
                $hash['voter_id']       =   $this->getVoterId();

                $db->direct('
                insert into ballotbox 
                    (id,keyname,ballotpaper,voter_id,stimmzettel_id,stimmzettel,isvalid) 
                values 
                    ({token},{keyname},{ballotpaper},{voter_id},{stimmzettel_id},{stimmzettel},{isvalid})',
                $hash);
            }
            if ($_SESSION['api']==1){

                $url = $_SESSION['api_url'].'papervote/set';
                $record = APIRequestHelper::query($url,[
                    'voter_id' =>$this->getVoterId(),
                    'ballotpaper_id' => $this->getBallotpaperId(),
                    'signature' => TualoApplicationPGP::sign($_SESSION['api_private'],(string)$this->getVoterId())
                ]);
                if ($record===false) throw new RemoteBallotpaperApiException('Der Vorgang konnte nicht abgeschlossen werden');
                if ($record['success']==false) throw new RemoteBallotpaperSaveException($record['msg']);

            }
            $db->direct('update voters set completed = 1 where voter_id = {voter_id} and stimmzettel = {stimmzettel_id}',[
                'voter_id'      =>  (string)$this->getVoterId(),
                'stimmzettel_id'=>  ((string)$this->getBallotpaperId()).'|0'
            ]);
            $db->direct('commit;');

        }catch(\Exception $e){
            $db->direct('rollback;');
            throw new SystemBallotpaperSaveException($e->getMessage());
        }

    }

}