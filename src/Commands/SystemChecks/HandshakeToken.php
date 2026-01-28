<?php

namespace Tualo\Office\OnlineVote\Commands\SystemChecks;

use Tualo\Office\Basic\FormatedCommandLineOutput;
use Tualo\Office\Basic\ISystemCheck;
use Tualo\Office\Basic\TualoApplication as App;

class HandshakeToken extends FormatedCommandLineOutput implements ISystemCheck
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
        return 'onlinevote handshake token';
    }

    public static function testSessionDB(array $config): int
    {
        return 0;
    }

    public static function test(array $config): int
    {

        $clientdb = App::get('clientDB');
        if (is_null($clientdb)) return 1;

        self::formatPrintLn(['blue'], 'HandshakeToken SystemCheck (Onlinevote):');
        $templateSql = 'select * from view_session_oauth_check where path="/onlinevote/*" and validuntil > (select stoptime + interval + 10 day from wm_loginpage_settings where id = 1)';
        $data = $clientdb->direct($templateSql);
        if (count($data) == 0) {
            self::formatPrintLn(['red'], 'No active/valid auth tokens found , please run sync handshake.');
            return 2;
        }

        self::formatPrintLn(['green'], 'Active auth tokens found.');




        return 0;
    }
}
