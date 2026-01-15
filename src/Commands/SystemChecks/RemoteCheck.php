<?php

namespace Tualo\Office\OnlineVote\Commands\SystemChecks;

use Tualo\Office\Basic\SystemCheck;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\OnlineVote\Handshake;

class RemoteCheck extends SystemCheck
{
    public static function makeResolvedCurlRequest($url, $ip): array|bool
    {
        $ch = curl_init();
        // resolve domain to 127.0.0.1
        $headers = [
            'Host: ' . parse_url($url, PHP_URL_HOST),
            'Connection: close',
        ];

        $use_url = str_replace(parse_url($url, PHP_URL_HOST), $ip, $url);
        curl_setopt($ch, CURLOPT_URL, $use_url);


        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        if (is_null($json)) {
            self::formatPrintLn(['red'], 'Fehler beim Parsen der Antwort: ' . json_last_error_msg());
            return false;
        }
        if (isset($json['success']) && $json['success'] === true) {
            self::formatPrintLn(['green'], 'Remote System Check erfolgreich: ' . $json['msg']);
        } else {
            self::formatPrintLn(['red'], 'Remote System Check fehlgeschlagen: ' . $json['msg']);
        }

        return $json;
    }

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


        // ToDo:  Noch offen prüfen der Remote Tokens gültigkeit, Beide Seiten!!!!
        self::formatPrintLn(['red'], 'ToDo:  Noch offen prüfen der Remote Tokens gültigkeit, Beide Seiten!!!!');

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


        if (App::configuration('onlinevote', 'public_domain', '') == '') {
            self::formatPrintLn(['red'], 'public_domain nicht gesetzt');
            $return_value += 1;
        } else {



            if (App::configuration('onlinevote', 'base_path', '') == '') {
                self::formatPrintLn(['red'], 'base_path nicht gesetzt');
                $return_value += 1;
            } else {
                if (App::configuration('onlinevote', 'allowed_cidrs', '') == '') {
                    self::formatPrintLn(['red'], 'allowed_cidrs nicht gesetzt');
                    $return_value += 1;
                } else {
                    $protokoll = App::configuration('onlinevote', 'public_domain_protocol', 'https');
                    self::formatPrintLn(['green'], 'public_domain: ' . App::configuration('onlinevote', 'public_domain', ''));
                    if (
                        $systemcheck = self::makeResolvedCurlRequest($protokoll . '://' . App::configuration('onlinevote', 'public_domain', '') . '/' . App::configuration('onlinevote', 'base_path', '') . '/onlinevote/systemcheck', '127.0.0.1')
                    ) {


                        if ($systemcheck['timezone'] != $expected_timezone) {
                            if ($phase == 'setup_phase') {
                                self::formatPrintLn(['yellow'], 'Zeitzone ist nicht ' . $expected_timezone . ', Setup Phase');
                            } else {
                                self::formatPrintLn(['red'], 'Zeitzone ist nicht ' . $expected_timezone);

                                $return_value += 1;
                            }
                        } else {
                            self::formatPrintLn(['green'], 'Zeitzone ist ' . $expected_timezone);
                        }
                    } else {
                        self::formatPrintLn(['red'], 'Remote System Check fehlgeschlagen');
                        $return_value += 1;
                    }
                }
            }
        }


        /*
        
        */

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
