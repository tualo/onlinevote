<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;
use Tualo\Office\OnlineVote\States\State;
use Tualo\Office\OnlineVote\WMStateMachine;

class Ballotpaper implements State{

    public function prepare(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        $db = $stateMachine->db();

        $result['bp_columns'] = ['col1','col2' ];


        $result['columns'] = $db->direct('
        select
            bp_column.column_name,
            bp_column.pos,
            json_arrayagg(
                json_object(
                    "column_field",
                    bp_column_definition.column_field,
                    "htmltag",
                    bp_column_definition.htmltag
                ) 
                order by bp_column_definition.pos
            ) definition
            from
                bp_column
                join bp_column_definition 
                    on  bp_column.column_name = bp_column_definition.column_name 
                        and bp_column_definition.active=1  
                        and bp_column.active=1
                join ds_column 
                    on  (ds_column.table_name,ds_column.column_name) = ("view_website_candidates", bp_column_definition.column_field) 
                        and ds_column.existsreal=1

            group by bp_column.column_name
        order by pos');

        $result['ballotpaper'] = $db->singleRow('select * from view_website_ballotpaper where id = {id}',[
            'id'=> $stateMachine->voter()->getCurrentBallotpaper()->getBallotpaperId()
        ]);
        $ballotpaper_groups = $db->direct('select view_website_ballotpaper_groups.*,uuid() x from view_website_ballotpaper_groups where ballotpaper_id = {id} order by id',$result['ballotpaper']);
        foreach($ballotpaper_groups as $key=>$ballotpaper_group){

            try{
                $ballotpaper_groups[$key]['candidates'] = $db->direct('select * from view_website_candidates where stimmzettelgruppen = concat({id},"|0") order by barcode',$ballotpaper_group);
            }catch(\Exception $e){
                WMStateMachine::getInstance()->logger('Ballotpaper(State)')->error($e->getMessage());
            }
        }
        $result['ballotpaper_groups'] = $ballotpaper_groups;
        //print_r($result );exit();

        
        return $stateMachine->getNextState();
    }

    public function transition(&$request,&$result):string {
        $stateMachine = WMStateMachine::getInstance();
        if (($nextState = $stateMachine->checkLogout())!='') return $nextState;
        $nextState = 'Tualo\Office\OnlineVote\States\Ballotpaper';
        // kreuze lesen

        return $nextState;
    }
}