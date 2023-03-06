<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote;
/**
 * 


 */
class Ballotpaper {
    private int $voter_id;
    private int $ballotpaper_id;
    private int $canvote;
    private string $state;
    private array $filled; // [11,54,41]
    
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

    public function save():void{
        // check md5
        // ggf recreate md5
    }

}