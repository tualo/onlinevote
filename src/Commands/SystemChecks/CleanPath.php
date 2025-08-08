<?php

namespace Tualo\Office\OnlineVote\Commands\SystemChecks;

use Tualo\Office\Basic\SystemCheck;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\Handshake;

class CleanPath extends SystemCheck
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
        return 'OnlineVote Clean Path Check';
    }

    public static function testSessionDB(array $config): int
    {
        return 0;
    }
    public static function test(array $config): int
    {

        $clientdb = App::get('clientDB');
        if (is_null($clientdb)) return 1;



        self::intent();

        $return_value = 0;
        $allowd = [

            '/configuration/',
            '/temp/',
            '/cache/',
            '/ext-cache/',
            '/scss/',
            '/node_modules/',
            '/scss_build/',
            '/public/',
            '/ext-build/',
            '/vendor/',
            '/composer.json',
            '/composer.lock',
            '/tm',
            '/index.php',
            '/.ht_access'
        ];
        $dirs = glob(App::get('basePath') . '/*', GLOB_NOSORT | GLOB_MARK);
        $notallowed = array_filter($dirs, function ($dir) use ($allowd) {
            foreach ($allowd as $a) {
                if (strpos($dir, $a) !== false) {
                    return false;
                }
            }
            return true;
        });
        //print_r($notallowed);
        foreach ($notallowed as $dir) {
            if (is_dir($dir)) {
                self::formatPrintLn(['red'], 'Verzeichniss: ' . $dir . ' ist nicht erlaubt!');
                //$return_value = 1;
            } else {
                self::formatPrintLn(['red'], 'Datei: ' . $dir . ' ist nicht erlaubt!');
                //$return_value = 1;
            }
        }

        self::unintent();
        return $return_value;
    }
}
