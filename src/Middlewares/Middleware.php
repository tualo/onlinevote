<?php

namespace Tualo\Office\HBKSplit\Middlewares;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\IMiddleware;

class Middleware implements IMiddleware{
    public static function register(){
        App::use('onlinevote',function(){
            try{
                //App::javascript('onlinevote_loader', './onlinevote/loader.js',[],1000);
            }catch(\Exception $e){
                App::set('maintanceMode','on');
                App::addError($e->getMessage());
            }
        },-100);
    }
}