<?php

namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\OnlineVote\Handshake;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;

class Get implements IRoute
{

    public static function register()
    {
        BasicRoute::add('/onlinevote/get/(?P<id>\d+)', function ($matches) {
            App::contenttype('application/json');
            App::result('success', false);
            try {
                $session = App::get('session');
                $db = $session->getDB();


                try {
                    $val =  $db->singleValue(
                        '
                            select contact from voters where contact > now() + interval - 15 minute and voter_id = {id}
                        ',
                        ['id' => $matches['id']],
                        'contact'
                    );
                    if ($val !== false) {
                        App::result('success', false);
                    } else {
                        App::result('success', true);
                    }
                } catch (\Exception $e) {
                }
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}
