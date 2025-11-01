<?php

namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\TualoPGP\TualoApplicationPGP;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;

class Systemcheck extends \Tualo\Office\Basic\RouteWrapper
{
    public static function scope(): string
    {
        return 'onlinevote.systemcheck';
    }

    public static function register()
    {
        BasicRoute::add('/onlinevote/systemcheck', function () {
            App::contenttype('application/json');
            try {
                App::result('timezone', date_default_timezone_get());
                App::result('success', true);
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
        }, ['get'], App::needsActiveLogin('onlinevote'), [], self::scope());
    }
}
