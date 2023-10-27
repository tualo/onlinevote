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

            

            'install/ds_class'    => 'setup ds_class',
            
            'install/wahlgruppe'    => 'setup wahlgruppe',
            'install/wahlgruppe.ds'    => 'setup wahlgruppe.ds',


            'install/wahlbezirk'    => 'setup wahlbezirk',
            'install/wahlbezirk.ds'    => 'setup wahlbezirk.ds',

            'install/stimmzettel'    => 'setup stimmzettel',
            'install/stimmzettel.ds'    => 'setup stimmzettel.ds',

            'install/stimmzettelgruppen'    => 'setup stimmzettelgruppen',
            'install/stimmzettelgruppen.ds'    => 'setup stimmzettelgruppen.ds',


            'install/kandidaten'    => 'setup kandidaten',
            'install/kandidaten.ds'    => 'setup kandidaten.ds',

            'install/kandidaten_bilder_typen'    => 'setup kandidaten_bilder_typen',
            'install/kandidaten_bilder_typen.ds'    => 'setup kandidaten_bilder_typen.ds',

            'install/kandidaten_bilder'    => 'setup kandidaten_bilder',
            'install/kandidaten_bilder.ds'    => 'setup kandidaten_bilder.ds',

            'install/canChangeValue'    => 'setup canChangeValue',
            'install/system_settings_suggestion'    => 'setup system_settings_suggestion',
            'install/system_settings_suggestion.ds'    => 'setup system_settings_suggestion.ds',

            'install/system_settings'    => 'setup system_settings',
            'install/system_settings.ds'    => 'setup system_settings.ds',

            'install/system_settings_user_access'    => 'setup system_settings_user_access',
            'install/system_settings_user_access.ds'    => 'setup system_settings_user_access.ds',
            

            'install/wm_page_links'    => 'setup wm_page_links',
            'install/wm_page_links.ds'    => 'setup wm_page_links.ds',

            'install/voters'    => 'setup voters',
            'install/voters.ds'    => 'setup voters.ds',


            'view_website.ballotpaper' => 'setup view_website.ballotpaper',


            'install/bp_row'    => 'setup bp_row',
            'install/bp_row.ds'    => 'setup bp_row.ds',

            'install/bp_column'    => 'setup bp_column',
            'install/bp_column.ds'    => 'setup bp_column.ds',

            'install/bp_column_definition'    => 'setup bp_column_definition',
            'install/bp_column_definition.ds'    => 'setup bp_column_definition.ds',

            'install/view_bp_fields'    => 'setup view_bp_fields',
            'install/view_bp_fields.ds'    => 'setup view_bp_fields.ds',

            'install/wm_texts'    => 'setup wm_texts',
            'install/wm_texts.ds'    => 'setup wm_texts.ds',

            'install/pgpkeys'    => 'setup pgpkeys',

            'install/ballotbox_blockchain'    => 'setup ballotbox_blockchain',
            'install/ballotbox'    => 'setup ballotbox',
            'install/ballotbox_encrypted'    => 'setup ballotbox_encrypted',

            'install/pgpkeys.readtable'    => 'setup pgpkeys.readtable',
            'install/pgpkeys.ds'    => 'setup pgpkeys.ds',
            
            'install/wm_sync_tables'    => 'setup wm_sync_tables',
            'install/wm_sync_tables.ds'    => 'setup wm_sync_tables.ds',

            'install/stimmzettel_fusstexte'    => 'setup stimmzettel_fusstexte',
            'install/stimmzettel_fusstexte.ds'    => 'setup stimmzettel_fusstexte.ds',
            
            'install/stimmzettel_stimmzettel_fusstexte'    => 'setup stimmzettel_stimmzettel_fusstexte',
            'install/stimmzettel_stimmzettel_fusstexte.ds'    => 'setup stimmzettel_stimmzettel_fusstexte.ds',
            

            'install/ds_files'    => 'setup ds_files',
            'install/ds_files.ds'    => 'setup ds_files.ds',

            
            'install/ds_files_data'    => 'setup ds_files_data',
            'install/ds_files_data.ds'    => 'setup ds_files_data.ds',

                        
            'install/username_count'    => 'setup username_count',
            'install/username_count.ds'    => 'setup username_count.ds',
            
            'install/view_website_candidates'    => 'setup view_website_candidates',

            'install/bp_row.and.column.data'    => 'setup bp_row.and.column.data',

            'install/wm_loginpage_settings'    => 'setup wm_loginpage_settings',
            'install/wm_loginpage_settings.ds'    => 'setup wm_loginpage_settings.ds',



            // immer zum schluss
            'install/ds_fill'    => 'refreshing ds data',
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
