<?php

namespace Tualo\Office\PaperVote\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;

class Query implements IRoute
{
    public static $querySQL = "
select
    wahlberechtigte_anlage.*,


    wahlberechtigte.id wahlberechtigte_id,
    wahlschein.id wahlschein_id, 
    wahlschein.wahlscheinnummer wahlschein_wahlscheinnummer,
    wahlschein.blocknumber,  					



    stimmzettel.name `stimmzettel_name`,  -- RLF
    stimmzettel.typtitel `stimmzettel_typtitel`,  -- RLF

    wahlgruppe.name `wahlgruppe_name`, -- RLF
    wahlbezirk.name `wahlbezirk_name`, -- RLF
    wahlschein.id `wahlschein_id`, -- RLF

    '' `leerzeile`,  -- RLF
    '' `leerzeile2`, -- RLF
    '' `leerzeile3`,  -- RLF

    concat('<br/><h4>Historie</h4><br/>',
        group_concat(
            concat(wahlschein_hstr.hstr_datetime,' - ',wahlschein_hstr.wahlscheinstatus_name,' - ',wahlschein_hstr.hstr_sessionuser)
            order by hstr_datetime
            separator '<br/>'
        )
    ) hstr,

    concat('<b>Status: ',wahlscheinstatus.name ,'</b>') last_state_text,

    wahlschein.stimmzettel,
    wahlschein.abgabetyp,
    wahlschein.wahlscheinstatus,
    wahltyp.id wahltyp_id,

    getBallotpaper(wahlschein.stimmzettel) displ_stimmzettel_name,
    concat('<b>',getBallotpaper(wahlschein.stimmzettel) ,'</b>') displ_stimmzettel_name_bold,
    concat('<b>',stimmzettel.typtitel ,'</b>') stimmzettel_typtitel_bold

from

    wahlschein
    join wahlberechtigte 
        on wahlberechtigte.id = wahlschein.wahlberechtigte
    join wahlberechtigte_anlage  
        on wahlberechtigte.identnummer = wahlberechtigte_anlage.identnummer
        and wahlschein.stimmzettel = wahlberechtigte_anlage.stimmzettel
    join stimmzettel 
        on stimmzettel.id = wahlschein.stimmzettel
    join wahltyp 
        on wahltyp.id = stimmzettel.wahltyp
    join wahlgruppe on wahlgruppe.id = stimmzettel.wahlgruppe
    join wahlbezirk on wahlbezirk.id = stimmzettel.wahlbezirk
    join wahlscheinstatus 
                on wahlschein.wahlscheinstatus = wahlscheinstatus.id
    join (
        select 
            wahlschein_hstr.*,
            wahlscheinstatus.name wahlscheinstatus_name
        from 
            wahlschein_hstr 
            join wahlscheinstatus 
                on wahlschein_hstr.wahlscheinstatus = wahlscheinstatus.id
    ) wahlschein_hstr 
        on wahlschein_hstr.id = wahlschein.id
        and wahlschein_hstr.stimmzettel = wahlschein.stimmzettel

where 
    #search_field={barcode}
group by
    wahlschein.id, 
    wahlschein.stimmzettel";

    public static function prepareQuerySQL($db)
    {
        $fld = $db->singleValue('select group_concat(concat(table_name,".`",column_name,"`") separator ",") fld from ds_column where table_name="wahlberechtigte_anlage"
         and column_name not in (
                "stimmzettel",
                "wahlscheinnummer",
                "pwhash",
                "username",
                "password",
                "passwort",
                "wahlberechtigte_id",
                "wahlschein_id",
                "wahlschein_wahlscheinnummer",
                "leerzeile",
                "leerzeile2",
                "leerzeile3",
                "blocknumber",
                "stimmzettel_name",
                "stimmzettel_typtitel",
                "wahlgruppe_name",
                "wahlbezirk_name",

                "abgabetyp",
                "wahlscheinstatus",
                "hstr",
                "displ_stimmzettel_name",
                "wahltyp_id",
                "last_state_text",
                "displ_stimmzettel_name_bold",
                "stimmzettel_typtitel_bold"

        ) ', [], 'fld');
        return str_replace('wahlberechtigte_anlage.*', $fld, Query::$querySQL);
    }

    public static function wzb($db, $data)
    {
        foreach ($data as &$item) {
            $item['wahlzeichnungsberechtigter'] = $db->direct('select * from wahlzeichnungsberechtigter  where wahlberechtigte = {wahlberechtigte_id}', $item);
        }
        return $data;
    }

    public static function register()
    {
        BasicRoute::add('/onlinevote/state/completed', function ($matches) {
            $db = App::get('session')->getDB();
            $data = $db->directMap('
                select "used" key, count(*) v from voters
                union
                select "completed" key, count(*) v from voters where completed=1
            ', [], 'key', 'v');
            App::result('data',  $data);
            App::result('success', count($data) > 0);
            App::contenttype('application/json');
        }, ['get', 'post'], true);
        BasicRoute::add('/papervote/(?P<type>(identnummer|wahlschein))/(?P<barcode>[\w\-\_\d]+)', function ($matches) {
            try {
                $db = App::get('session')->getDB();
                App::contenttype('application/json');
                $sql = str_replace(
                    '#search_field',
                    ($matches['type'] == 'identnummer') ? 'wahlberechtigte_anlage.identnummer' : 'wahlschein.wahlscheinnummer',
                    Query::prepareQuerySQL($db)
                );
                $data = Query::wzb($db, $db->direct($sql, $matches));
                App::result('data',  $data);
                App::result('success', count($data) > 0);
                App::result('msg', (count($data) == 0) ? 'Der Wähler wurde nicht gefunden.' : '');
            } catch (\Exception $e) {

                App::result('last_sql', $db->last_sql);
                App::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}
