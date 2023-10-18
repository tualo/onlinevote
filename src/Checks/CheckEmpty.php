<?php
namespace Tualo\Office\OnlineVote\Checks;

use Tualo\Office\Basic\Middleware\Session;
use Tualo\Office\Basic\PostCheck;
use Tualo\Office\Basic\TualoApplication as App;


class CheckEmpty  extends PostCheck {
    
    public static function test(array $config){
        $clientdb = App::get('clientDB');
        if (is_null($clientdb)) return;
        try{
            $res1 = App::get('clientDB')->direct('select * from tualocms_section');
        }catch(\Exception $e){
            $res1 = [];
        }

        try{
            $res2 = App::get('clientDB')->direct('select * from tualocms_page_middleware');
        }catch(\Exception $e){
            $res2 = [];
        }

        try{
            $res3 = App::get('clientDB')->direct('select * from tualocms_page');
        }catch(\Exception $e){
            $res3 = [];
        }

        
        
        if (
            (count($res1)==0) ||
            (count($res2)==0) ||
            (count($res3)==0)
        ) {
            PostCheck::formatPrintLn(['red'],'tualocms_page, tualocms_section or tualocms_page_middleware is empty');
            PostCheck::formatPrintLn(['blue'],'please run the following command: `./tm import-onlinevote-page --client '.$clientdb->dbname.'`');
        }else{
            PostCheck::formatPrintLn(['green'],'tualocms_page, tualocms_section and tualocms_page_middleware is not empty');
        }
    }
}