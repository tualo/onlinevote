<?php
namespace Tualo\Office\OnlineVote\Checks;

use Tualo\Office\Basic\Middleware\Session;
use Tualo\Office\Basic\PostCheck;
use Tualo\Office\Basic\TualoApplication as App;


class Tables  extends PostCheck {
    
    public static function test(array $config){
        $clientdb = App::get('clientDB');
        if (is_null($clientdb)) return;
        $tables = [
            
            'view_website_candidates'=>[],
            'wm_loginpage_settings'=>[],
            'wm_sync_tables'=>[],
            'wm_texts'=>[ ],
            'view_bp_fields'=>[ ],
            'view_readtable_kandidaten_bilder'=>[ ],
            'kandidaten_bilder_typen'=>[ ],
            'kandidaten_bilder'=>[ ],
            'bp_column_definition'=>[ ],
            'bp_column'=>[ ],
            'bp_row'=>[ ],
            'voters'=>[ ],
            
            'ballotbox_blockchain'=>[ ],
            'ballotbox_encrypted'=>[ ],
            'pgpkeys'=>[ ],
            'blocked_voters' => [ ],
            'blocked_synced' => [ ],
            'stimmzettel_stimmzettel_fusstexte'=>[ ],
            'stimmzettel_fusstexte'=>[ ],
            'username_count'=>[ ],
            
            'kandidaten_stimmen'=>[ ],
            'view_readtable_kandidaten_stimmen'=>[ ],
            'voter_sessions_save_state'=>[ ],
        ];
        self::tableCheck('ds',$tables,
            "please run the following command: `./tm install-sql-onlinevote --client ".$clientdb->dbname."`",
            "please run the following command: `./tm install-sql-onlinevote --client ".$clientdb->dbname."`"

        );
    }
}