<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\Ballotpaper;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\OnlineVote\Exceptions\VoterUnsyncException;

class Voter {
    public static $deleteBlockedQuery = 'delete from username_count where block_until<now() and id = {username} ';
    public static $isBlockedQuery = 'select id from username_count where id = {username} and block_until>now() and num > {times}';

    

    private static ?Voter $instance = null;
    public static function getInstance(): Voter
    {
      if (self::$instance === null) {
            self::$instance = new self();
      }
      return self::$instance;
    }




    
    protected array $available_ballotpapers = [];
    protected array $possible_ballotpapers = [];
    protected Ballotpaper $currentBallotpaper;


    private string $pwhash = "";
    private string $username = "";
    private string $secret = "";
    private string $id = ""; // kombiniert kennung!

    private bool $loggedIn = false;
    // private array $required_attributes = ['voter_id','ballotpaper_id','canvote','state'];

    public function __construct(){

    }

    public function fromJSON($json):void {
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();

        $this->username = isset($json['username'])?$json['username']:'';
        $this->pwhash = isset($json['pwhash'])?$json['pwhash']:'';
        $this->secret = isset($json['secret'])?$json['secret']:'';
        $this->id = isset($json['id'])?$json['id']:'';
        if (isset($json['possible_ballotpapers']) && is_string($json['possible_ballotpapers'])){
            $json['possible_ballotpapers'] = json_decode($json['possible_ballotpapers'],true);
        }

        if (isset($json['possible_ballotpapers']) && is_array($json['possible_ballotpapers']) ){
            $this->possible_ballotpapers=[];
            foreach($json['possible_ballotpapers'] as $ballotpaperJSON){
                 
                $bp = Ballotpaper::getInstanceFromJSON($ballotpaperJSON);
                $this->addPossibleBallotpaper( Ballotpaper::getInstanceFromJSON($ballotpaperJSON)  );

                if ($bp->getCanvote()==1){ 
                    // gegen voter prüfen
                    // wenn dort bereits completed ->>> ABBRUCH, schwerer Fehler

                    $voter = $db->singleRow('
                        select
                            voter_id 
                        from 
                            voters 
                        where 
                            voter_id        =   {voter_id}
                            and stimmzettel =   {stimmzettel_id}
                            and completed   =   1
                    ', [
                        'voter_id' => $bp->getVoterId(),
                        'stimmzettel_id'=> $bp->getBallotpaperId()
                    ]);
                    if ($voter!==false){ 
                        App::logger('Voter(function fromJSON)')->error('canvote ist 1, aber in voter(tabelle) completed');
                        throw new VoterUnsyncException('canvote ist 1, aber in voter(tabelle) completed. Wahlschein: '.$bp->getVoterId());
                    }

                    $this->addAvailableBallotpaper( $bp ); 
                }
            }
           
        }
        $this->loggedIn = isset($json['loggedIn'])?boolval($json['loggedIn']):false;
        if (isset($json['currentBallotpaper'])){
            $this->setCurrentBallotpaper( Ballotpaper::getInstanceFromJSON($json['currentBallotpaper']) );
        }
    }

    public function getId():string{
        return $this->id;
    }
    public function getSecretToken():string{
        return $this->secret;
    }
    

    
    public function validSession():bool{
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        $voter = $db->singleRow('
            select 
                session_id 
            from 
                unique_voter_session 
            where 
                id = {id}  
                and  session_id={session_id} 
        ',  [
            'id'=>$this->getId(),
            'session_id'=>session_id()
        ] );
        return $voter !== false;
    }
    public function registerSession():void{
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        $sql = 'insert into unique_voter_session (id,session_id,create_time) values ({id},{session_id},now()) on duplicate key update session_id=values(session_id), create_time={create_time}';
        $db->direct($sql,[
            'id'=>$this->getId(),
            'session_id'=>session_id()
        ]);
    }

    public function login(string $username,string $password):string{
        $record = $this->loginGetCredentials($username);

        App::logger('Voter(login)')->info( json_encode($record) );
        if ($record!==false){

            $this->fromJSON($record);
            if (count($this->available_ballotpapers)==0) return 'allready-voted';
            if (crypt($password, $record['pwhash']) == $record['pwhash']) {
                $this->loggedIn = true;
                $this->registerSession();
                return 'ok';
            }
        }
        return 'error';
    }
    public function loginGetCredentials($username):mixed{
        try{
            $record=false;
            if ($_SESSION['api']==1){
                $url = $_SESSION['api_url'].str_replace('{username}',$username,'papervote/wmregister/{username}');
                $record = APIRequestHelper::query($url);
                
            }else{
                // $record = json_decode($db->singleValue('select voterCredential({username}) u',['username'=>$username],'u'),true);
                $record = false;
            }
            if ($record===false){
                return false;
            }else if ($record['success']==false){
                return false;
            }else{
                $record = $record['data'];
            }

        }catch(\Exception $e){
            WMStateMachine::getInstance()->logger('Voter->loginGetCredentials')->error($e->getMessage());
        }
        return $record;
    }

    public function isBlocked($username):bool{
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        $config = $stateMachine->config();

        $times = 2;
        if (
            isset($config['onlinevote']) && 
            isset($config['onlinevote']['allowed_failures'])
        ) $times = intval($config['onlinevote']['allowed_failures']);

        $db->direct(Voter::$deleteBlockedQuery,['username'=>$username]);
        if (
            $db->singleRow(Voter::$isBlockedQuery,['username'=>$username,'times'=>$times])!==false
        ){
            
            return true;
        }
        return false;
    }

    private function addPossibleBallotpaper(Ballotpaper $ballotpaper):void{
        $this->possible_ballotpapers[] = $ballotpaper;
    }

    public function possibleBallotpapers():array{
        return $this->possible_ballotpapers;
    }

    public function availableBallotpapers():array{
        return $this->available_ballotpapers;
    }

    public function isLoggedIn():bool{
        return $this->loggedIn;
    }

    
    private function addAvailableBallotpaper(Ballotpaper $ballotpaper):void{
        $this->available_ballotpapers[] = $ballotpaper;
    }

    

    public function getCurrentBallotpaper():Ballotpaper{
        return $this->currentBallotpaper;
    }
    public function setCurrentBallotpaper(Ballotpaper $bp):Ballotpaper{
        $bp->register();
        return $this->currentBallotpaper=$bp;
    }
    public function ballotpaper():Ballotpaper { return $this->getCurrentBallotpaper(); }

    public function selectBallotpaper($index=0):bool{
        if (isset($this->available_ballotpapers[$index])){
            $this->setCurrentBallotpaper($this->available_ballotpapers[$index]);
            return true;
        }
        return false;
    }

}