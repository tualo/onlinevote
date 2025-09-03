<?php

namespace Tualo\Office\OnlineVote;

use Tualo\Office\Basic\TualoApplication as App;


class APIRequestHelper
{
    public static $last_error_nr = 0;
    public static $last_http_code = 0;
    public static $last_error_message = '';
    public static $last_data = '';
    public static $last_rawdata = '';
    public static $usecookie = false;

    public static function enableCookie($val = true)
    {
        self::$usecookie = $val;
    }


    public static function query($url,  $post = NULL)
    {
        self::$last_data = self::raw($url,  $post);
        if (self::$last_data === false) return false;

        if (App::configuration('logger-options', 'APIRequestHelper', '0') == '1')
            App::logger('APIRequestHelper')->debug(self::$last_data);
        $object = json_decode(self::$last_data, true);
        if (
            !is_null($object) &&
            isset($object["success"]) &&
            ($object["success"] === true)

        ) {
            return $object;
        } else if (
            !is_null($object) &&
            isset($object["success"]) &&
            isset($object["msg"]) &&
            ($object["success"] === false)

        ) {
            self::$last_error_message = $object["msg"];
            App::logger('APIRequestHelper')->error(self::$last_error_message);
            return false;
        } else {
            self::log('error', 'Code 2' . "\API:" . $url . "\nResult:" . self::$last_data);

            // App::result('last_http_code', self::$last_http_code);
            // App::result('last_data', self::$last_data);

            self::$last_error_nr = -100;
            self::$last_error_message = "Backend server false response";
            App::logger('APIRequestHelper')->error("Backend server false response $url " . self::$last_data);
            return false;
        }
    }

    public static function raw($url,  $post = NULL)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        // fix link 11 missed acceptance
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

        if (!is_null($post)) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        if (self::$usecookie) {
            $cookie_file = App::get('tempPath') . '/api_cookie';
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
            if (file_exists($cookie_file)) curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        }


        $data = curl_exec($ch);
        self::$last_rawdata = $data;
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        App::result('url',  $url);

        curl_close($ch);
        self::$last_http_code = $httpCode;
        if ($httpCode !== 200) {
            self::$last_error_nr = $httpCode;
            self::$last_error_message = "Backend server problems";
            App::logger('APIRequestHelper')->error("httpCode $httpCode query $url ");
            if ($httpCode === 0) {
                throw new \Exception("Could not connect to backend server");
            }
            return false;
        }
        return $data;
    }


    public static function querySingleItem($url)
    {
        $object = self::query($url);
        if (
            isset($object["data"]) &&
            count($object["data"]) == 1
        ) {
            return $object["data"][0];
        } else {
            App::logger('APIRequestHelper')->error('Code 3' . "\API:" . $url . "\nResult:" . json_encode($object));
            self::$last_error_nr = -101;
            self::$last_error_message = "Backend server false response (querySingleItem) ";
            return false;
        }
    }


    public static function log($type, $data)
    {
        $db = App::get('session')->getDB();
        try {
            /* 
            create table wm_loginpage_logfile (id varchar(36) primary key,type varchar(10),createtime timestamp,uri varchar(255), data longtext);
            */
            $db->direct(
                '
                    insert into wm_loginpage_logfile (
                        id,
                        createtime,
                        uri,
                        type,
                        data
                    ) values (
                        uuid(),
                        current_timestamp,
                        {uri},
                        {type},
                        {data}
                        
                    )
                ',
                array(
                    'uri' => $_SERVER['REQUEST_URI'],
                    'data' => $data,
                    'type' => $type
                )
            );
        } catch (\Exception $e) {
            App::logger('APIRequestHelper')->error($e->getMessage());
        }
    }
}
