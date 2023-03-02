<?php
namespace Tualo\Office\OnlineVote\Middlewares;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\IMiddleware;

class InitApiUse /*extends CMSMiddleWare*/{
    public static function run(&$request,&$result){
        @session_start();
        $db = App::get('session')->getDB();
         
        session_commit();
    }
}