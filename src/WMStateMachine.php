<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote;
use Tualo\Office\OnlineVote\Voter;
use Tualo\Office\Basic\TualoApplication as App;
use Michelf\MarkdownExtra;
use Ramsey\Uuid\Uuid;
class WMStateMachine {
    public static $sessionkey = 'wms';
    public function logger(string $channel){
        return App::logger($channel);
    }
    public function db(){
        return App::get('session')->getDB();
    }
    
    // @deprecated
    public function config(){
        return App::get('configuration');
    }

    private static ?WMStateMachine $instance = null;
    public static function getInstance(): WMStateMachine
    {
      if (self::$instance === null) {
        if (isset($_SESSION['wmstatemachine'])){
            self::$instance = unserialize( $_SESSION['wmstatemachine'] );
        }else{
            self::$instance = new self();
        }
      }
      return self::$instance;
    }


    private string $currentState = '';
    private string $prevState = '';
    private string $nextState = '';
    private string $savedState = '';

    private Voter $_voter;

    private string $_usernamefield = '';
    private string $_passwordfield = '';
    private string $_ip = '';

    public function __construct(){
        
    }

    public function checkLogout():string{
        if (isset($_REQUEST['logout']) && ($_REQUEST['logout']==1)){ 
            $this->saveState();
            return 'Tualo\Office\OnlineVote\States\Logout'; 
        }
        return '';
    }

    public function voter(bool $reset=false):Voter{
        if (!isset($this->_voter)) $reset=true;
        if ($reset===true) $this->_voter = new Voter();
        return $this->_voter;
    }

    public function setNextState(string $state){
        $this->nextState = $state;
        App::logger('WMStateMachine')->info($this->getCurrentState().' to '.$this->getNextState());
    }

    public function setCurrentState(string $state){
        if ($this->currentState!=$this->prevState) $this->prevState = $this->currentState;
        $this->currentState = $state;
    }


    public function getPrevState( ):string{
        return $this->prevState;
    }

    public function getNextState( ):string{
        return $this->nextState;
    }
    public function getCurrentState( ):string{
        return $this->currentState;
    }

    public function getSavedState( ):string{
        return $this->savedState;
    }
    public function saveState( ):string{
        return $this->savedState = $this->currentState;
    }

    public function ip(string $ip=''):string{
        if ($ip!='') $this->_ip=$ip;
        return $this->_ip;
    }

    public function usernamefield(bool $reset=false):string{
        if ($reset===true) $this->_usernamefield=  (Uuid::uuid4())->toString();
        return $this->_usernamefield;
    }

    public function passwordfield(bool $reset=false):string{
        if ($reset===true) $this->_passwordfield =  (Uuid::uuid4())->toString();
        return $this->_passwordfield;
    }

    public function markdown(string $text):string{
        $result = MarkdownExtra::defaultTransform( $text );
        if (strpos($result,"<p>")===0) $result = substr( $result ,3,-3);
        return $result;
    }
}