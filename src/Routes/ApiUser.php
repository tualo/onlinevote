<?php
namespace Tualo\Office\PaperVote\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;

class ApiUser implements IRoute{
 
    public static function register(){
        BasicRoute::add('/papervote/wmregister/(?P<username>[\w\-\_\d]+)',function($matches){
            try{
                $sql='select * from view_wm_register_api where username={username}';
                $db = App::get('session')->getDB();
                App::contenttype('application/json');
                $data = $db->singleRow($sql,$matches);
                App::result('data',  $data );
                App::result('success',$data !== false);
                App::result('msg',($data === false)?'Der Wähler wurde nicht gefunden.':'');
             
            }catch(\Exception $e){

                App::result('last_sql', $db->last_sql);
                App::result('msg', $e->getMessage());
            }
        },['get','post'],true);


    }
}