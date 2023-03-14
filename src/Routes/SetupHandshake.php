<?php
namespace Tualo\Office\OnlineVote\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\TualoPGP\TualoApplicationPGP;
use Ramsey\Uuid\Uuid;

class SetupHandshake implements IRoute{
 
    public static function register(){
        BasicRoute::add('/onlinevote/setuphandshake',function($matches){
            try{
                App::contenttype('application/json');
                
                $session = App::get('session');
                $db = $session->getDB();

                $token = $session->registerOAuth(
                        $params     =   [],
                        $force      =   true,
                        $anyclient  =   false,
                        $path       =   '/onlinevote/*'
                );

                $session->oauthValidDays($token,365);


                $keys = TualoApplicationPGP::keyGen(2048);
                $publickey = $keys['public'];

                $mesage_to_send = [
                    'publickey' => $publickey,
                    'domain'    => $_REQUEST['domain'],
                    'token'     => $token,
                    'message'   => TualoApplicationPGP::encrypt($publickey,'OK MESSAGE')
                ];
                App::result('data',  $mesage_to_send );
                
                /*
                
                $keyData = $db->singleRow("select publickey from wm_pgp_pubkey where id=0 ",[]);

                $sql='select * from view_voters_by_username_api where username={username}';
                App::contenttype('application/json');
                $data = $db->singleRow($sql,$matches);

                if ($data !== false){
                    if (is_string($data['possible_ballotpapers'])){
                        $data['possible_ballotpapers'] = json_decode($data['possible_ballotpapers'],true);
                    }

                    
                    foreach( $data['possible_ballotpapers'] as &$ballotpaper){
                        $hash = [
                            'voter_id'=>$ballotpaper['voter_id'],
                            'ballotpaper_ridx' => $ballotpaper['ballotpaper_id'].'|0',
                            'secret_token' => Uuid::uuid4()->toString()
                        ];
                        if ($ballotpaper['canvote']==1){
                            $db->direct('
                            insert into wm_wahlschein_register (
                                id,
                                stimmzettel,
                                createdate,
                                token
                            ) values (
                                {voter_id},
                                {ballotpaper_ridx},
                                now(),
                                {secret_token}
                            ) on duplicate key update token=values(token)
                            ',$hash);
                        }
                        $ballotpaper['secret_token'] = TualoApplicationPGP::encrypt($keyData['publickey'],$hash['secret_token']);
                    }
                }

                App::result('data',  $data );
                App::result('success',$data !== false);
                App::result('msg',($data === false)?'Der Wähler wurde nicht gefunden.':'');
                */
            }catch(\Exception $e){

                App::result('last_sql', $db->last_sql);
                App::result('msg', $e->getMessage());
            }
        },['get','post'],true);


        
    }
}