<?php
namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\Basic\RouteSecurityHelper;

class JsLoader implements IRoute{
    public static function register(){
        BasicRoute::add('/jsonlinevote/(?P<file>[\w.\/\-]+).js',function($matches){
            RouteSecurityHelper::serveSecureStaticFile(
                $matches['file'] . '.js',
                dirname(__DIR__, 1) . '/js/lazy/',
                ['js'],
                ['application/javascript']
            );
            /*
            App::contenttype('application/javascript');
            if (file_exists(dirname(__DIR__,1).'/js/lazy/'.$matches['file'].'.js')){
                App::etagFile( dirname(__DIR__,1).'/js/lazy/'.$matches['file'].'.js', true);
                BasicRoute::$finished = true;
                http_response_code(200);
            } */           
        },['get'],false);

        BasicRoute::add('/onlinevote/loader.js',function($matches){
            App::contenttype('application/javascript');
            $list = [
                "js/Syncform.js",
                "js/openpgp/openpgp.min.js",
                "js/models/Viewport.js",
                "js/controller/Viewport.js",
                "js/Viewport.js",
                "js/Routes.js"
            ];
            $content = '';
            foreach( $list as $item ){
                $content .= file_get_contents( dirname(__DIR__,1).'/'.$item ).PHP_EOL.PHP_EOL;
            }
            App::body( $content );
        },array('get'),false);

    }
}