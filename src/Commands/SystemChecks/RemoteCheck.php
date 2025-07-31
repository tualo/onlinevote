<?php

namespace Tualo\Office\OnlineVote\Commands\SystemChecks;

use Tualo\Office\Basic\SystemCheck;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\Handshake;

class RemoteCheck extends SystemCheck
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
        return 'OnlineVote Remotesystem Check';
    }

    public static function testSessionDB(array $config): int
    {
        return 0;
    }
    public static function test(array $config): int
    {

        $clientdb = App::get('clientDB');
        if (is_null($clientdb)) return 1;


        self::formatPrintLn(['blue'], 'Remote System Check:');
        self::intent();

        $return_value = 0;
        $param = Handshake::parameter();


        $phase = $clientdb->singleValue('select phase from voting_state ', [], 'phase');


        if (isset($param['starttime'])) {
            self::formatPrintLn(['green'], 'Startzeit: ' . $param['starttime']);
        } else {
            if ($phase == 'setup_phase') {
                self::formatPrintLn(['yellow'], 'Startzeit nicht gesetzt, Setup Phase');
            } else {
                self::formatPrintLn(['red'], 'Startzeit nicht gesetzt');
                $return_value += 1;
            }
        }

        if (isset($param['stoptime'])) {
            self::formatPrintLn(['green'], 'Stoppzeit: ' . $param['stoptime']);
        } else {
            if ($phase == 'setup_phase') {
                self::formatPrintLn(['yellow'], 'Stoppzeit nicht gesetzt, Setup Phase');
            } else {
                self::formatPrintLn(['red'], 'Stoppzeit nicht gesetzt');
                $return_value += 1;
            }
        }

        $expected_timezone = App::configuration('onlinevote', 'expected_timezone', 'Europe/Berlin');
        if (date_default_timezone_get() != $expected_timezone) {
            if ($phase == 'setup_phase') {
                self::formatPrintLn(['yellow'], 'Zeitzone ist nicht ' . $expected_timezone . ', Setup Phase');
            } else {
                self::formatPrintLn(['red'], 'Zeitzone ist nicht ' . $expected_timezone);

                $return_value += 1;
            }
        } else {
            self::formatPrintLn(['green'], 'Zeitzone ist ' . $expected_timezone);
        }

        try {
            Handshake::pingRemote() || throw new \Exception('Der Remote Server ist nicht erreichbar');
            self::formatPrintLn(['green'], 'Remote Server erreichbar');
        } catch (\Exception $e) {

            if ($phase == 'setup_phase') {
                self::formatPrintLn(['yellow'], 'Remote Server nicht erreichbar, Setup Phase');
            } else {
                self::formatPrintLn(['red'], 'Remote Server nicht erreichbar');
                $return_value += 1;
            }
        }

        self::unintent();
        return $return_value;
    }
}
