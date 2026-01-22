<?php

namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;


class Aliases extends \Tualo\Office\Basic\RouteWrapper
{
    public static function scope(): string
    {
        return 'onlinevote.setuphandshake';
    }

    public static function register()
    {
        Route::alias('/onlinevote/votemanager/state', '/votemanager/state');
    }
}
