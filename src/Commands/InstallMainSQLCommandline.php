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
            'install/data/default-styles' => 'setup install/data/default-styles',
            'install/data/middlewares' => 'setup cms install/data/middlewares ',
            'install/ddl/wahlgruppe'            =>      'setup install/ddl/wahlgruppe',
            'install/ddl/wahlbezirk'            =>      'setup install/ddl/wahlbezirk',
            'install/ddl/stimmzettel'           =>      'setup install/ddl/stimmzettel',
            'install/ddl/stimmzettelgruppen'    =>      'setup install/ddl/stimmzettelgruppen',            
            'install/ddl/kandidaten'            =>      'setup install/ddl/kandidaten',
            'install/ddl/kandidaten_bilder_typen'   =>      'setup install/ddl/kandidaten_bilder_typen',
            'install/ddl/kandidaten_bilder' =>      'setup install/ddl/kandidaten_bilder',
            'install/proc/func_canChangeValue'      =>      'setup install/proc/func_canChangeValue',
            'install/ddl/system_settings_suggestion'        =>      'setup install/ddl/system_settings_suggestion',
            'install/ddl/system_settings'   =>      'setup install/ddl/system_settings',
            'install/ddl/system_settings_user_access'       =>      'setup install/ddl/system_settings_user_access',
            'install/ddl/wm_page_links'     =>      'setup install/ddl/wm_page_links',
            'install/ddl/voters'    =>      'setup install/ddl/voters',
            'install/ddl/bp_row'    =>      'setup install/ddl/bp_row',
            'install/ddl/bp_column' =>      'setup install/ddl/bp_column',
            'install/ddl/bp_column_definition'      =>      'setup install/ddl/bp_column_definition',
            'install/views/view_bp_fields'    =>      'setup install/views/view_bp_fields',
            'install/ddl/wm_texts'  =>      'setup install/ddl/wm_texts',
            'install/ddl/pgpkeys'   =>      'setup install/ddl/pgpkeys',
            'install/ddl/ballotbox_blockchain'      =>      'setup install/ddl/ballotbox_blockchain',
            'install/ddl/ballotbox' =>      'setup install/ddl/ballotbox',
            'install/ddl/ballotbox_decrypted'       =>      'setup install/ddl/ballotbox_decrypted',
            'install/ddl/pgpkeys.readtable' =>      'setup install/ddl/pgpkeys.readtable',
            'install/ddl/wm_sync_tables'    =>      'setup install/ddl/wm_sync_tables',            
            'install/ddl/stimmzettel_fusstexte'     =>      'setup install/ddl/stimmzettel_fusstexte',
            'install/ddl/stimmzettel_stimmzettel_fusstexte' =>      'setup install/ddl/stimmzettel_stimmzettel_fusstexte',
            'install/ddl/ds_files'  =>      'setup install/ddl/ds_files',
            'install/ddl/ds_files_data'     =>      'setup install/ddl/ds_files_data',
            'install/ddl/username_count'   =>      'setup install/ddl/username_count',
            'install/ddl/blocked_voters'    =>      'setup install/ddl/blocked_voters',
            'install/ddl/blocked_synced'    =>      'setup install/ddl/blocked_synced',
            'install/ddl/reportfiles_typen' =>      'setup install/ddl/reportfiles_typen',
            'install/ddl/reportfiles'       =>      'setup install/ddl/reportfiles',
            'install/ddl/voter_sessions_save_state' =>      'setup install/ddl/voter_sessions_save_state',
            'install/ddl/wm_texts'  =>      'setup install/ddl/wm_texts',
            'install/ddl/ds_renderer_stylesheet_attributes' =>      'setup install/ddl/ds_renderer_stylesheet_attributes',
            'install/ddl/ds_renderer_stylesheet'    =>      'setup install/ddl/ds_renderer_stylesheet',
            'install/ddl/wm_sync_tables'    =>      'setup install/ddl/wm_sync_tables',
            'install/ddl/wm_loginpage_settings'     =>      'setup install/ddl/wm_loginpage_settings',
            'install/ddl/kandidaten_stimmen'        =>      'setup install/ddl/kandidaten_stimmen',
            'install/ddl/wm_wahlschein_register'    =>      'setup install/ddl/wm_wahlschein_register',
            'install/ddl/unique_voter_session'      =>      'setup install/ddl/unique_voter_session',
            'install/views/view_ballotbox_decrypted_sum'      =>      'setup install/views/view_ballotbox_decrypted_sum',
            'install/views/view_website_candidates'   =>      'setup install/views/view_website_candidates',

 
            'install/data/voters.ds'        =>      'setup install/data/voters.ds',
            'install/data/ds_files_data.ds' =>      'setup install/data/ds_files_data.ds',
            'install/data/ds_class' =>      'setup install/data/ds_class',            
            'install/data/username_count.ds'        =>      'setup install/data/username_count.ds',
            'install/data/wm_loginpage_settings.ds' =>      'setup install/data/wm_loginpage_settings.ds',
            'install/data/stimmzettel_stimmzettel_fusstexte.ds'     =>      'setup install/data/stimmzettel_stimmzettel_fusstexte.ds',
            'install/data/ds_files.ds'      =>      'setup install/data/ds_files.ds',
            'install/data/stimmzettel.ds'   =>      'setup install/data/stimmzettel.ds',
            'install/data/dashboards'       =>      'setup install/data/dashboards',
            'install/data/system_settings_user_access.ds'   =>      'setup install/data/system_settings_user_access.ds',
            'install/data/bp_row.ds'        =>      'setup install/data/bp_row.ds',
            'install/data/bp_column.ds'     =>      'setup install/data/bp_column.ds',            
            'install/data/pruefziffer.pug.templates'        =>      'setup install/data/pruefziffer.pug.templates',
            'install/data/wahlgruppe.ds'    =>      'setup install/data/wahlgruppe.ds',
            'install/data/ballotbox_blockchain.ds'  =>      'setup install/data/ballotbox_blockchain.ds',
            'install/data/kandidaten_bilder_typen.ds'       =>      'setup install/data/kandidaten_bilder_typen.ds',
            'install/data/ballotbox.ds'     =>      'setup install/data/ballotbox.ds',
            'install/data/bp_row.and.column.data'   =>      'setup install/data/bp_row.and.column.data',
            'install/data/ds_renderer_stylesheet.ds'        =>      'setup install/data/ds_renderer_stylesheet.ds',
            'install/data/wahlbezirk.ds'    =>      'setup install/data/wahlbezirk.ds',
            'install/data/wm_sync_tables_data'      =>      'setup install/data/wm_sync_tables_data',
            'install/data/reportfiles_types.ds'     =>      'setup install/data/reportfiles_types.ds',
            'install/data/kandidaten_stimmen.ds'    =>      'setup install/data/kandidaten_stimmen.ds',
            'install/data/ballotbox_decrypted.ds'   =>      'setup install/data/ballotbox_decrypted.ds',
            'install/data/wm_page_links.ds' =>      'setup install/data/wm_page_links.ds',
            'install/data/ds_renderer_stylesheet_attributes.ds'     =>      'setup install/data/ds_renderer_stylesheet_attributes.ds',
            'install/data/stimmzettel_fusstexte.ds' =>      'setup install/data/stimmzettel_fusstexte.ds',
            'install/data/kandidaten.ds'    =>      'setup install/data/kandidaten.ds',
            'install/data/system_settings.ds'       =>      'setup install/data/system_settings.ds',
            'install/data/stimmzettelgruppen.ds'    =>      'setup install/data/stimmzettelgruppen.ds',
            'install/data/unique_voter_session.ds'  =>      'setup install/data/unique_voter_session.ds',
            'install/data/wm_wahlschein_register.ds'        =>      'setup install/data/wm_wahlschein_register.ds',
            'install/data/wm_texts.ds'      =>      'setup install/data/wm_texts.ds',
            'install/data/kandidaten_bilder.ds'     =>      'setup install/data/kandidaten_bilder.ds',
            'install/data/system_settings_suggestion.ds'    =>      'setup install/data/system_settings_suggestion.ds',
            'install/data/view_ballotbox_decrypted_sum.ds'  =>      'setup install/data/view_ballotbox_decrypted_sum.ds',
            'install/data/reportfiles.ds'   =>      'setup install/data/reportfiles.ds',
            'install/data/view_bp_fields.ds'        =>      'setup install/data/view_bp_fields.ds',
            'install/data/wm_sync_tables.ds'        =>      'setup install/data/wm_sync_tables.ds',
            'install/views/view_website.ballotpaper'   =>      'setup install/views/view_website.ballotpaper',

            'install/views/view_readtable_kandidaten_bilder'   =>      'setup install/views/view_readtable_kandidaten_bilder',

/*
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
            'install/ballotbox_decrypted'    => 'setup ballotbox_decrypted',
            'install/ballotbox_decrypted.ds'    => 'setup ballotbox_decrypted.ds',

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

            'install/blocked_voters'    => 'setup blocked_voters',
            'install/blocked_synced'=> 'setup blocked_synced',

            'install/kandidaten_stimmen'    => 'setup kandidaten_stimmen',
            'install/kandidaten_stimmen.ds'    => 'setup kandidaten_stimmen.ds',

            'install/view_ballotbox_decrypted_sum'    => 'setup view_ballotbox_decrypted_sum',
            'install/view_ballotbox_decrypted_sum.ds'    => 'setup view_ballotbox_decrypted_sum.ds',

            'install/reportfiles_typen'=> 'setup reportfiles_typen',
            //'install/reportfiles_typen.ds'=> 'setup reportfiles_typen.ds',

            'install/reportfiles'    => 'setup reportfiles',
//            'install/reportfiles.ds'    => 'setup reportfiles.ds',

            'install/pruefziffer.pug.templates'    => 'setup pruefziffer.pug.templates',


            'install/voter_sessions_save_state'    => 'setup voter_sessions_save_state',
            'install/dashboards'    => 'setup dashboards',
*/            
            // immer zum schluss
            'install/ds_fill'    => 'refreshing ds data'

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
