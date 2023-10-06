<?php
namespace Tualo\Office\OnlineVote\Commands;
use Garden\Cli\Cli;
use Garden\Cli\Args;
use phpseclib3\Math\BigInteger\Engines\PHP;
use Tualo\Office\Basic\ICommandline;
use Tualo\Office\ExtJSCompiler\Helper;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\PostCheck;

class InstallMainSQLCommandline implements ICommandline{

    public static function getCommandName():string { return 'install-sql-onlinevote';}

    public static function setup(Cli $cli){
        $cli->command(self::getCommandName())
            ->description('installs needed sql procedures for papervote module')
            ->opt('client', 'only use this client', true, 'string');
            
    }

   
    public static function setupClients(string $msg,string $clientName,string $file,callable $callback){
        $_SERVER['REQUEST_URI']='';
        $_SERVER['REQUEST_METHOD']='none';
        App::run();

        $session = App::get('session');
        $sessiondb = $session->db;
        $dbs = $sessiondb->direct('select username dbuser, password dbpass, id dbname, host dbhost, port dbport from macc_clients ');
        foreach($dbs as $db){
            if (($clientName!='') && ($clientName!=$db['dbname'])){ 
                continue;
            }else{
                App::set('clientDB',$session->newDBByRow($db));
                PostCheck::formatPrint(['blue'],$msg.'('.$db['dbname'].'):  ');
                $callback($file);
                PostCheck::formatPrintLn(['green'],"\t".' done');

            }
        }
    }

    public static function run(Args $args){

        $files = [
            'default-styles' => 'setup default-styles',
            'middlewares' => 'setup cms middlewares ',
            'view_website.ballotpaper' => 'setup view_website.ballotpaper',

            'canChangeValue'    => 'setup canChangeValue',
            'system_settings_suggestion'    => 'setup system_settings_suggestion',
            'system_settings_suggestion.ds'    => 'setup system_settings_suggestion.ds',

            'system_settings'    => 'setup system_settings',
            'system_settings.ds'    => 'setup system_settings.ds',

            'wm_page_links'    => 'setup wm_page_links',
            'wm_page_links.ds'    => 'setup wm_page_links.ds',

        ];


        foreach($files as $file=>$msg){
            $installSQL = function(string $file){

                $filename = dirname(__DIR__).'/sql/'.$file.'.sql';
                $sql = file_get_contents($filename);
                $sql = preg_replace('!/\*.*?\*/!s', '', $sql);
                $sql = preg_replace('#^\s*\-\-.+$#m', '', $sql);

                $sinlgeStatements = App::get('clientDB')->explode_by_delimiter($sql);
                foreach($sinlgeStatements as $commandIndex => $statement){
                    try{
                        App::get('clientDB')->execute($statement);
                        App::get('clientDB')->moreResults();
                    }catch(\Exception $e){
                        echo PHP_EOL;
                        PostCheck::formatPrintLn(['red'], $e->getMessage().': commandIndex => '.$commandIndex);
                    }
                }
            };
            $clientName = $args->getOpt('client');
            if( is_null($clientName) ) $clientName = '';
            self::setupClients($msg,$clientName,$file,$installSQL);
        }


    }
}
