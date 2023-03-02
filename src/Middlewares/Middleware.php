<?php

namespace Tualo\Office\OnlineVote\Middlewares;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\IMiddleware;

class Middleware implements IMiddleware{
    public static function register(){
        App::use('OnlineVote',function(){
          
        },-100);
    }
}