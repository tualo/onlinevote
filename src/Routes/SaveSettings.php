<?php

namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\OnlineVote\Handshake;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\DS\DSTable;

class SaveSettings extends \Tualo\Office\Basic\RouteWrapper
{

    public static function scope(): string
    {
        return 'onlinevote.savesettings';
    }
    public static function register()
    {
        BasicRoute::add('/onlinevote/savesettings', function () {
            App::contenttype('application/json');
            App::result('success', false);
            try {
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);
                if (is_null($data)) throw new \Exception('Paramterfehler!');

                if ($data['starttime'] >= $data['stoptime']) throw new \Exception('Der Start muss vor dem Ende liegen!');

                $table = DSTable::instance('wm_loginpage_settings');
                $table->insert([
                    'id' => 1,
                    'starttime' => $data['starttime'],
                    'stoptime' => $data['stoptime'],
                    'interrupted' => isset($data['interrupted']) ? $data['interrupted'] : 0,
                ], ['update' => 1]);
                App::result('success', true);
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
        }, ['post'], true, [], self::scope());
    }
}
