<?php

declare(strict_types=1);

namespace Tualo\Office\OnlineVote;

use Exception;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\Ballotpaper;
use Tualo\Office\OnlineVote\APIRequestHelper;
use Tualo\Office\OnlineVote\Exceptions\VoterUnsyncException;
use Tualo\Office\OnlineVote\Exceptions\SystemSettingPrivateKeyMissed;
use Tualo\Office\TualoPGP\TualoApplicationPGP;

class Voter
{
    public static $deleteBlockedQuery = 'delete from username_count where block_until<now() and id = {username} ';
    public static $isBlockedQuery = 'select id from username_count where id = {username} and block_until>now() and num > {times}';
    public static $countLoginFailures = 'insert into username_count (id,block_until,num) values ({username},DATE_ADD(now(),INTERVAL {minutes} MINUTE),1) on duplicate key update block_until=DATE_ADD(now(),INTERVAL {minutes} MINUTE),num=num+1';



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
    protected array $signing_persons = [];
    protected Ballotpaper $currentBallotpaper;


    private string $pwhash = "";
    private string $username = "";

    private string $id = ""; // kombiniert kennung!


    private string $requiredPhonePIN = "";
    private string $phoneNumber = "";


    private string $firstname = "";
    private string $lastname = "";
    private string $birthdate = "";
    private string $confirmed_birthdate = "";
    private string $allowEditName = "";
    private string $last_state = "unknown";


    private bool $loggedIn = false;
    private bool $groupedVote = false;
    // private array $required_attributes = ['voter_id','ballotpaper_id','canvote','state'];

    public function __construct() {}

    public function fromJSON($json): void
    {
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();

        $this->username = isset($json['username']) ? $json['username'] : '';
        $this->pwhash = isset($json['pwhash']) ? $json['pwhash'] : '';


        $this->id = strval(isset($json['id']) ? $json['id'] : '');
        if (isset($json['possible_ballotpapers']) && is_string($json['possible_ballotpapers'])) {
            $json['possible_ballotpapers'] = json_decode($json['possible_ballotpapers'], true);
        }


        if (isset($json['wahlzeichnungsberechtigter'])) {
            //$json['wahlzeichnungsberechtigter'] = json_decode($json['wahlzeichnungsberechtigter'],true);
            $this->signing_persons = $json['wahlzeichnungsberechtigter'];
        }

        if (isset($json['possible_ballotpapers']) && is_array($json['possible_ballotpapers'])) {
            $this->possible_ballotpapers = [];
            foreach ($json['possible_ballotpapers'] as $ballotpaperJSON) {

                $bp = Ballotpaper::getInstanceFromJSON($ballotpaperJSON);
                $this->addPossibleBallotpaper($bp);
                if ($bp->getCanvote() == 1) {
                    if ($bp->checkLocal()) {
                        App::logger('Voter(function fromJSON)')->error('canvote ist 1, aber die stimmabgabe ist bereits in der urne');
                        throw new VoterUnsyncException('canvote ist 1, aber die stimmabgabe ist bereits in der urne. Wahlschein: ' . $bp->getVoterId());
                    }

                    $vd = $bp->getVoterData();
                    if (isset($vd['einzel']) && ($vd['einzel'] . '' > $this->allowEditName)) $this->allowEditName = $vd['einzel'] . '';

                    $this->addAvailableBallotpaper($bp);
                }
            }
        }
        $this->loggedIn = isset($json['loggedIn']) ? boolval($json['loggedIn']) : false;
        if (isset($json['currentBallotpaper'])) {
            $this->setCurrentBallotpaper(Ballotpaper::getInstanceFromJSON($json['currentBallotpaper']));
        }
        $this->requiredPhonePIN = isset($json['requiredPhonePIN']) ? $json['requiredPhonePIN'] : '';
        $this->phoneNumber = isset($json['phoneNumber']) ? $json['phoneNumber'] : '';

        $this->firstname = isset($json['firstname']) ? $json['firstname'] : '';
        $this->lastname = isset($json['lastname']) ? $json['lastname'] : '';
        $this->birthdate = isset($json['birthdate']) ? $json['birthdate'] : '';
        $this->confirmed_birthdate = isset($json['confirmed_birthdate']) ? $json['confirmed_birthdate'] : '-----';
    }

    public function getLastState(): string
    {
        return $this->last_state;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAllowEditName(): string
    {
        return $this->allowEditName;
    }

    public function getSigners(): array
    {
        return $this->signing_persons;
    }

    public function setPhonenumber(string $val): void
    {
        $this->phoneNumber = $val;
    }
    public function getPhonenumber(): string
    {
        return $this->phoneNumber;
    }

    public function setFirstName(string $val): void
    {
        $this->firstname = $val;
    }
    public function getFirstName(): string
    {
        return $this->firstname;
    }

    public function setLastName(string $val): void
    {
        $this->lastname = $val;
    }
    public function getLastName(): string
    {
        return $this->lastname;
    }

    public function setConfirmedBirthdate(string $val): void
    {
        $this->confirmed_birthdate = $val;
    }
    public function setBirthdate(string $val): void
    {
        $this->birthdate = $val;
    }
    public function getBirthdate(): string
    {
        return $this->birthdate;
    }
    public function comfirmedBirthdate(): bool
    {
        return $this->confirmed_birthdate == $this->birthdate;
    }

    public function getRequiredPhonePIN(): string
    {
        return $this->requiredPhonePIN;
    }
    public function setRequiredPhonePIN(string $val): void
    {
        $this->requiredPhonePIN = $val;
    }


    public function validSession(): bool
    {
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
            'id' => $this->getId(),
            'session_id' => session_id()
        ]);
        return $voter !== false;
    }
    public function registerSession(): void
    {
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        $sql = 'insert into unique_voter_session (id,session_id,create_time) values ({id},{session_id},now()) on duplicate key update session_id=values(session_id), create_time={create_time}';
        $db->direct($sql, [
            'id' => $this->getId(),
            'session_id' => session_id()
        ]);
    }



    /*
    public function legitimze($request):void{
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        $sql = 'insert into unique_voter_session (id,session_id,create_time) values ({id},{session_id},now()) on duplicate key update session_id=values(session_id), create_time={create_time}';
        $db->direct($sql,[
            'id'=>$this->getId(),
            'session_id'=>session_id()
        ]);

        if (isset($_SESSION['api_url']) && isset($_SESSION['pug_session']['secret_token'])){
            $url = $_SESSION['api_url'].'/cmp_wm_ruecklauf/api/extended/'.$_SESSION['pug_session']['secret_token'].'?extended_data='.urlencode(json_encode($data));
            $object = WMRequestHelper::query($url,'./');
        }

    }
    */




    public function login(string $username, string $password): string
    {
        $record = $this->loginGetCredentials($username);

        App::logger('Voter(login)')->info(json_encode($record));
        if ($record !== false) {
            $this->fromJSON($record);

            if (crypt($password, $record['pwhash']) == $record['pwhash']) {
                // todo: check if it is ok to say allready-voted before login and password check
                if (count($this->available_ballotpapers) == 0) {
                    $stateMachine = WMStateMachine::getInstance();
                    $db = $stateMachine->db();

                    $voterVotedOnline = $db->singleRow('
                        select
                            distinct voter_id 
                        from 
                            ballotbox 
                        where 
                            voter_id = {voter_id}
                    ', [
                        'voter_id' => $this->getId()
                    ]);
                    if ($voterVotedOnline === false) {
                        $this->last_state = isset($record['last_wahlscheinstatus']) ? $record['last_wahlscheinstatus'] : 'unknown';
                        return 'allready-voted-offline';
                    } else {
                        return 'allready-voted-online';
                    }
                }
                $this->loggedIn = true;
                $this->registerSession();
                return 'ok';
            }
        }

        $db = WMStateMachine::getInstance()->db();
        $block_minutes = App::configuration('onlinevote', 'block_minutes', 3);
        $db->direct(Voter::$countLoginFailures, ['username' => $username, 'minutes' => $block_minutes]);

        return 'error';
    }

    public function loginGetCredentials($username): mixed
    {
        try {
            $stateMachine = WMStateMachine::getInstance();
            $db = $stateMachine->db();

            $record = false;
            if ($_SESSION['api'] == 1) {
                $privatekey = $db->singleValue("select property FROM system_settings WHERE system_settings_id = 'erp/privatekey'", [], 'property');
                if ($privatekey === false) throw new SystemSettingPrivateKeyMissed("system_settings private key is missed");

                $url = $_SESSION['api_url'] . 'papervote/get';
                $record = APIRequestHelper::query($url, [
                    'username' => $username,
                    'signature' => TualoApplicationPGP::sign($privatekey, $username)
                ]);
            } else {
                // $record = json_decode($db->singleValue('select voterCredential({username}) u',['username'=>$username],'u'),true);
                $record = false;
            }

            if ($record === false) {
                return false;
            } else if ($record['success'] == false) {
                return false;
            } else {
                if (! isset($record['data'])) return false;
                $record = $record['data'];
            }
        } catch (\Exception $e) {
            WMStateMachine::getInstance()->logger('Voter->loginGetCredentials')->error($e->getMessage());
        }
        return $record;
    }
    public function setGroupedVote(bool $val): void
    {
        $this->groupedVote = $val;
    }
    public function getGroupedVote(): bool
    {
        return $this->groupedVote;
    }

    public function isBlocked($username): bool
    {
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();

        $times = App::configuration('onlinevote', 'allowed_failures', 2);
        $db->direct(Voter::$deleteBlockedQuery, ['username' => $username]);
        if (
            $db->singleRow(Voter::$isBlockedQuery, ['username' => $username, 'times' => $times]) !== false
        ) {
            return true;
        }
        return false;
    }

    private function addPossibleBallotpaper(Ballotpaper $ballotpaper): void
    {
        $this->possible_ballotpapers[] = $ballotpaper;
    }

    public function possibleBallotpapers(): array
    {
        return $this->possible_ballotpapers;
    }

    public function availableBallotpapers(int $filter = -1): array
    {
        if ($filter == -1) return $this->available_ballotpapers;
        $list = [];
        foreach ($this->available_ballotpapers as $bp) {
            if ($bp->getBallotpaperId() == $filter) {
                $list[] = $bp;
            }
        }
        return $list;
    }

    public function availableBallotpaperGroups(): array
    {
        $list = [];
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();
        $colors = $db->directMap('select id,farbe  from view_website_ballotpaper', [], 'id', 'farbe');
        foreach ($this->available_ballotpapers as $index => $bp) {
            if (isset($list[$bp->getBallotpaperId()])) {
                $list[$bp->getBallotpaperId()]['count']++;
            } else {
                $list[$bp->getBallotpaperId()] = [
                    'id' => $bp->getBallotpaperId(),
                    'color' => $colors[$bp->getBallotpaperId()],
                    'name' => $bp->getBallotpaperName(),
                    'index' => $index,
                    'count' => 1
                ];
            }
        }
        return $list;
    }

    public function isLoggedIn(): bool
    {
        return $this->loggedIn;
    }


    private function addAvailableBallotpaper(Ballotpaper $ballotpaper): void
    {
        $this->available_ballotpapers[] = $ballotpaper;
    }



    public function getCurrentBallotpaper(): Ballotpaper
    {
        return $this->currentBallotpaper;
    }
    public function setCurrentBallotpaper(Ballotpaper $bp): Ballotpaper
    {
        $bp->register();
        if (App::configuration('logger-options', 'Voter', '0') == '1') WMStateMachine::getInstance()->logger('Voter->setCurrentBallotpaper')->debug("********" . $bp->getVoterId());
        return $this->currentBallotpaper = $bp;
    }
    public function ballotpaper(): Ballotpaper
    {
        return $this->getCurrentBallotpaper();
    }


    public function removeCurrentBallotpaper(): bool
    {
        $bp_list = [];
        foreach ($this->available_ballotpapers as $bp) {
            if ($bp->getVoterId() != $this->currentBallotpaper->getVoterId()) {
                $bp_list[] = $bp;
            }
        }
        $this->available_ballotpapers = $bp_list;
        return true;
    }



    public function selectBallotpaper($index = 0): bool
    {
        if (isset($this->available_ballotpapers[$index])) {
            $this->setCurrentBallotpaper($this->available_ballotpapers[$index]);
            unset($this->available_ballotpapers[$index]);
            return true;
        }
        return false;
    }
}
