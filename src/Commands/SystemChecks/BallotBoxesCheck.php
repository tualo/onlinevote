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

        $templateSql = 'select phase from voting_state ';
        $data = $clientdb->direct($templateSql);
        $phase= $data[0]['phase'];

        $templateSql = 'select count(*) v from voters';
        $data = $clientdb->direct($templateSql);
        $voters = $data[0]['v'];

        $templateSql = 'select count(*) v from voters where completed = 1';
        $data = $clientdb->direct($templateSql);
        $voteCompleted = $data[0]['v'];

        $voteNotCompleted = $voters - $voteCompleted;

        $templateSql = 'select count(*) v from blocked_voters';
        $data = $clientdb->direct($templateSql);
        $blockedVoters = $data[0]['v'];

        $templateSql = 'select count(*) v from ballotbox';
        $data = $clientdb->direct($templateSql); 
        $ballots= $data[0]['v'];

        /* Get State*/
        self::formatPrintLn(['blue'], 'Aktueller Zustand ?');

        if (count($data) <> 1) {
            self::formatPrintLn(['red'], '    Ungültiger Zustand. ABBRUCH !!!');
            return 3;
        }
    
        self::formatPrintLn(['green'], '    Aktueller Zustand: '.$phase);
        if ($phase == 'setup_phase') {


            if ($urnen > 0) {
                self::formatPrintLn(['red'], '    Setup Phase: Achtung es ist/sind bereits '.$urnen.' Urne(n) angelegt.');
            } else {
                self::formatPrintLn(['green'], '    Setup Phase: Keine Urnen angelegt!');
            }

            if ($voters > 0) {
                self::formatPrintLn(['red'], '    Setup Phase: Achtung es sind bereits '.$voters.' Stimmabgaben in Tabelle VOTERS vorhanden.');
                self::formatPrintLn(['red'], '    Setup Phase: '.$voteCompleted.' Stimmabgaben  mit completed = 1 und '.$voteNotCompleted.' completed = 0 vorhanden.');
            } else {
                self::formatPrintLn(['green'], '    Setup Phase: Keine Stimmabgaben  in Tabelle VOTERS vorhanden!');
            }        


            if ($blockedVoters > 0) {
                self::formatPrintLn(['red'], '    Setup Phase: Achtung es sind bereits '.$blockedVoters.' Daten in Tabelle BLOCKED_VOTERS vorhanden.');
            } else {
                self::formatPrintLn(['green'], '    Setup Phase: Keine Daten  in Tabelle BLOCKED_VOTERS vorhanden!');
            }  


            if ($ballots > 0) {
                self::formatPrintLn(['red'], '    Setup Phase: Achtung es sind bereits '.$ballots.' Stimmabgaben in Tabelle BALLOTBOX vorhanden.');
            } else {
                self::formatPrintLn(['green'], '    Setup Phase: Keine Stimmabgaben  in Tabelle BALLOTBOX vorhanden!');
            }  


            return 4;
        }
        if ($phase == 'test_phase') {
            self::formatPrintLn(['blue'], 'Wahlurnen Check');
            if ($urnen > 0) {
                self::formatPrintLn(['green'], '    Test Phase: Es ist/sind bereits '.$urnen.' Urne(n) angelegt.');
            } else {
                self::formatPrintLn(['red'], '    Test Phase: Keine Urnen angelegt!');
            }
            self::formatPrintLn(['blue'], 'Wahlurnen + Stimmabgaben Check');
            if ($voters > 0 && $urnen == 0) { // Votes vorhanden aber keine Urne
                self::formatPrintLn(['red'], '    Test Phase: Achtung es sind bereits '.$voters.' Stimmabgaben in Tabelle "VOTERS" - ABER keine Wahlurne vorhanden.');
            } else {
                self::formatPrintLn(['green'], '    Test Phase: Achtung es sind bereits '.$voters.' Stimmabgaben in Tabelle VOTERS vorhanden.');
                self::formatPrintLn(['green'], '    Test Phase: '.$voteCompleted.' Stimmabgaben  mit completed = 1 und '.$voteNotCompleted.' completed = 0 vorhanden.');
            }        
            if ($blockedVoters > 0) {
                self::formatPrintLn(['green'], '    Test Phase: Achtung es sind '.$blockedVoters.' Daten in Tabelle BLOCKED_VOTERS vorhanden.');
            } else {
                self::formatPrintLn(['green'], '    Test Phase: Keine Daten  in Tabelle BLOCKED_VOTERS vorhanden!');
            }  
            if ($ballots > 0) {
                self::formatPrintLn(['green'], '    Test Phase: Achtung es sind bereits '.$ballots.' Stimmabgaben in Tabelle BALLOTBOX vorhanden.');
            } else {
                self::formatPrintLn(['green'], '    Test Phase: Keine Stimmabgaben  in Tabelle BALLOTBOX vorhanden!');
            }  
            // Logik Check
            self::formatPrintLn(['blue'], 'Logik Check');
            if  (($voteCompleted > 0) && ($urnen > 0) && ($ballots/$urnen <> $voteCompleted)) {
                self::formatPrintLn(['red'], '    Test Phase: Achtung es sind '.$voteCompleted.' Stimmabgaben in Tabelle VOTERS - ABER '.$ballots.' Stimmabgaben in '.$urnen.' Urnen.');
            } else {
                self::formatPrintLn(['green'], '    Test Phase: OK. '.$voteCompleted.' Kompl. Stimmabgaben in Tabelle VOTERS und '.$ballots.' Stimmabgaben in '.$urnen.' Urnen.');
            }
            return 4;
        }
        print_r($data);



        return 0;
    }    
}