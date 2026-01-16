<?php

namespace Tualo\Office\OnlineVote\Commands\SystemChecks;

use Tualo\Office\Basic\FormatedCommandLineOutput;
use Tualo\Office\Basic\ISystemCheck;
use Tualo\Office\Basic\TualoApplication as App;

class AuthTokens extends FormatedCommandLineOutput implements ISystemCheck
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
        return 'onlinevote auth tokens';
    }

    public static function testSessionDB(array $config): int
    {
        return 0;
    }

    public static function test(array $config): int
    {

        $clientdb = App::get('clientDB');
        if (is_null($clientdb)) return 1;

        self::formatPrintLn(['blue'], 'AuthTokens SystemCheck (Onlinevote):');
        $templateSql = 'select * from view_session_oauth_check where path="/tualocms/page/*" and validuntil > (select stoptime + interval + 10 day from wm_loginpage_settings where id = 1)';
        $data = $clientdb->direct($templateSql);
        if (count($data) == 0) {
            self::formatPrintLn(['red'], 'No active/valid auth tokens found.');
            return 2;
        }

        self::formatPrintLn(['green'], 'Active auth tokens found.');



        if (!file_exists(dirname((string)App::get('basePath')) . '/.htaccess')) {
            self::formatPrintLn(['red'], '.htaccess file not found in base path.');
            return 4;
        }

        $htaccess_content = file_get_contents(dirname((string)App::get('basePath')) . '/.htaccess');
        // parse htaccess content for token

        $token_found = false;
        $token_valid_until = null;
        preg_match('/~\/(?P<token>[a-f0-9\-]{36})\/tualocms\/page\//m', $htaccess_content, $matches);
        self::formatPrintLn(['italic'], "Extracted token from .htaccess: " . (isset($matches['token']) ? $matches['token'] : 'none'));
        if (isset($matches['token'])) {
            $token_value = $matches['token'];

            $templateSql = 'select * from view_session_oauth_check where path="/tualocms/page/*" and id={token} and validuntil  > now()';
            $data = $clientdb->direct($templateSql, ['token' => $token_value]);
            if (count($data) != 0) {
                $token_found = true;
                $token_valid_until = $data[0]['validuntil'];
                self::formatPrintLn(['green'], 'Valid auth token found in .htaccess file, valid until ' . $token_valid_until . '.');
            }


            $templateSql = 'select * from view_session_oauth_check where path="/tualocms/page/*" and validuntil < (select stoptime from wm_loginpage_settings where id = 1)';
            $data = $clientdb->direct($templateSql, ['token' => $token_value]);
            if (count($data) != 0) {
                $token_found = true;
                $token_valid_until = $data[0]['validuntil'];
                self::formatPrintLn(['red'], 'Valid auth token found in .htaccess file, but are tokens about to expire.***');
                return 3;
            }
        }
        if (!$token_found) {
            self::formatPrintLn(['red'], 'No valid auth token found in .htaccess.');
            return 5;
        }


        return 0;
    }
}
