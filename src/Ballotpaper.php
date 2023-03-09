<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote;

use Tualo\Office\Basic\TualoApplication as App;
use Exception;
use Ramsey\Uuid\Uuid;
/**
 * 


 */
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

    public function setConfiguration(array $config):void{
        $this->config=$config;
    }

    public function max():int{
        return intval($this->config['sitze']);
    }
    public function checkcount():int{
        return count($this->filled );
    }

    public function valid(){
        $this->is_valid=false;;
        foreach($this->filled as $check){
            if (!isset($this->idMap[$check])){
                App::logger('Ballotpaper')->warning( "candidate not found ($check)" );
                return false;
            }
        }
        return $this->is_valid = true;
    }

    /*
        if (isset($kandidaten[$check])){
            if (isset($stimmzettelgruppen[$kandidaten[$check]['stimmzettelgruppen']])){
                $stimmzettelgruppen[$kandidaten[$check]['stimmzettelgruppen']]['__checkcount']++;
            }else{
                syslog(LOG_CRIT,"WM Stimmzettegruppe {$kandidaten[$check]['stimmzettelgruppe']} bei Kandidat ID $check not found");
                WMInit::$next_state = 'error';
                $_SESSION['pug_session']['error'][] = 'Der Kandidat ist nicht für Ihren Stimmzettel zugelassen.';
                return false;
            }
        }else{
            syslog(LOG_CRIT,"WM Kandidate ID $check not found");
            WMInit::$next_state = 'error';
            $_SESSION['pug_session']['error'][] = 'Der Kandidat ist nicht für Ihren Stimmzettel zugelassen.';
            return false;
        }

    }
    */


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
    }

}