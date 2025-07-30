<?php

namespace Tualo\Office\OnlineVote\Commands\SystemChecks;

use Tualo\Office\Basic\FormatedCommandLineOutput;
use Tualo\Office\Basic\ISystemCheck;
use Tualo\Office\Basic\TualoApplication as App;

class BallotBoxesCheck extends FormatedCommandLineOutput implements ISystemCheck
{

    public static function hasClientTest(): bool
    {
        return true;
    }
    
    public static function hasSessionTest(): bool
    {
        return false;
    }    

    public static function getModuleName(): string
    {
        return 'OnlineVote Wahlurnen Check';
    }

    public static function testSessionDB(array $config): int
    {
        return 0;
    }
    public static function test(array $config): int
    {

        $clientdb = App::get('clientDB');
        if (is_null($clientdb)) return 1;

        self::formatPrintLn(['blue'], 'Sind Urnen angelegt? : SystemCheck:');
        $templateSql = 'select * from pgpkeys';
        $data = $clientdb->direct($templateSql);
        $urnen = count($data);
        if ($urnen == 0) {
            self::formatPrintLn(['red'], 'Keine Urnen angelegt!');
        }
        else{
            self::formatPrintLn(['green'], 'Es sind '.$urnen.' Wahlurne(n) angelegt...');
        }
        /* Get State*/
        self::formatPrintLn(['blue'], 'Aktueller Zustand ?');
        $templateSql = 'select phase from voting_state ';
        $data = $clientdb->direct($templateSql);
        if (count($data) <> 1) {
            self::formatPrintLn(['red'], 'Ungültiger Zustand.');
            return 3;
        }
        
        $phase= $data[0]['phase'];
        self::formatPrintLn(['green'], 'Aktueller Zustand: '.$phase);
        if ($phase == 'setup_phase') {
            if ($urnen > 0) {
                self::formatPrintLn(['red'], 'Setup Phase: Achtung es ist/sind bereits '.$urnen.' Urne(n) angelegt.');
            } else {
                self::formatPrintLn(['green'], 'Setup Phase: Keine Urnen angelegt!');
            }
            $templateSql = 'select count(*) v from voters';
            $data = $clientdb->direct($templateSql); 
            if ($data[0]['v'] > 0) {
                self::formatPrintLn(['red'], 'Setup Phase: Achtung es sind bereits '.$data[0]['v'].' Stimmabgaben in Tabelle VOTERS vorhanden.');
            } else {
                self::formatPrintLn(['green'], 'Setup Phase: Keine Stimmabgaben  in Tabelle VOTERS vorhanden!');
            }        

            $templateSql = 'select count(*) v from blocked_voters';
            $data = $clientdb->direct($templateSql); 
            if ($data[0]['v'] > 0) {
                self::formatPrintLn(['red'], 'Setup Phase: Achtung es sind bereits '.$data[0]['v'].' Daten in Tabelle BLOCKED_VOTERS vorhanden.');
            } else {
                self::formatPrintLn(['green'], 'Setup Phase: Keine Daten  in Tabelle BLOCKED_VOTERS vorhanden!');
            }  

            $templateSql = 'select count(*) v from ballotbox';
            $data = $clientdb->direct($templateSql); 
            if ($data[0]['v'] > 0) {
                self::formatPrintLn(['red'], 'Setup Phase: Achtung es sind bereits '.$data[0]['v'].' Stimmabgaben in Tabelle BALLOTBOX vorhanden.');
            } else {
                self::formatPrintLn(['green'], 'Setup Phase: Keine Stimmabgaben  in Tabelle BALLOTBOX vorhanden!');
            }  


            return 4;
        }
        print_r($data);



        return 0;
    }    
}