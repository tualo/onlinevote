DELIMITER ;
REPLACE INTO `ds` (`table_name`, `title`, `reorderfield`, `use_history`, `searchfield`, `displayfield`, `sortfield`, `searchany`, `hint`, `overview_tpl`, `sync_table`, `writetable`, `globalsearch`, `listselectionmodel`, `sync_view`, `syncable`, `cssstyle`, `alternativeformxtype`, `read_table`, `class_name`, `special_add_panel`, `existsreal`, `character_set_name`, `read_filter`, `listxtypeprefix`, `phpexporter`, `phpexporterfilename`, `combined`, `default_pagesize`, `allowForm`, `listviewbaseclass`, `showactionbtn`, `modelbaseclass`) VALUES ('wm_sync_tables','WM: Sync Tables','position',NULL,'table_name','table_name','position',NULL,NULL,NULL,NULL,NULL,NULL,'rowmodel',NULL,NULL,NULL,NULL,NULL,'Wahlsystem',NULL,1,NULL,NULL,NULL,'XlsxWriter','wm_sync_tables {DATE} {TIME}',0,1000,1,'Tualo.DataSets.ListView',1,'Tualo.DataSets.model.Basic') ;

REPLACE INTO `ds_column` (`table_name`, `column_name`, `default_value`, `default_max_value`, `default_min_value`, `update_value`, `is_primary`, `syncable`, `referenced_table`, `referenced_column_name`, `is_nullable`, `is_referenced`, `writeable`, `note`, `data_type`, `column_key`, `column_type`, `character_maximum_length`, `numeric_precision`, `numeric_scale`, `character_set_name`, `privileges`, `existsreal`, `deferedload`, `hint`, `fieldtype`) VALUES ('wm_sync_tables','last_sync',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,'YES',NULL,1,NULL,'datetime','','datetime',NULL,NULL,NULL,NULL,'select,insert,update,references',1,NULL,NULL,''),
('wm_sync_tables','position','0',NULL,NULL,NULL,NULL,0,NULL,NULL,'YES',NULL,1,NULL,'int','','int(11)',NULL,10,0,NULL,'select,insert,update,references',1,NULL,NULL,''),
('wm_sync_tables','table_name',NULL,NULL,NULL,NULL,1,0,NULL,NULL,'NO',NULL,1,NULL,'varchar','PRI','varchar(128)',128,NULL,NULL,'utf8mb4','select,insert,update,references',1,NULL,NULL,'');
REPLACE INTO `ds_column_list_label` (`table_name`, `column_name`, `language`, `label`, `xtype`, `editor`, `position`, `summaryrenderer`, `renderer`, `summarytype`, `hidden`, `active`, `filterstore`, `grouped`, `flex`, `direction`, `align`, `listfiltertype`, `hint`) VALUES ('wm_sync_tables','last_sync','DE','letze Sync.','gridcolumn',NULL,2,'','','',0,1,'',0,1.00,'ASC','left','',NULL),
('wm_sync_tables','position','DE','Position','gridcolumn',NULL,1,'','','',0,1,'',0,1.00,'ASC','left','',NULL),
('wm_sync_tables','table_name','DE','Tabelle','column_ds_tabelle',NULL,0,'','','',0,1,'',0,1.00,'ASC','left','',NULL);
REPLACE INTO `ds_column_form_label` (`table_name`, `column_name`, `language`, `label`, `xtype`, `field_path`, `position`, `hidden`, `active`, `allowempty`, `fieldgroup`, `flex`, `hint`) VALUES ('wm_sync_tables','last_sync','DE','letze Sync.','displaydatefield','Allgemein/Angaben',2,0,1,NULL,NULL,NULL,'\'\''),
('wm_sync_tables','position','DE','Position','displayfield','Allgemein/Angaben',1,0,1,NULL,NULL,NULL,'\'\''),
('wm_sync_tables','table_name','DE','Tabelle','combobox_ds_tabelle','Allgemein/Angaben',0,0,1,NULL,NULL,NULL,'\'\'');

INSERT
    IGNORE INTO `ds_access` (
        `role`,
        `table_name`,
        `read`,
        `write`,
        `delete`,
        `append`,
        `existsreal`
    )
VALUES
    ('administration', 'wm_sync_tables', 1, 1, 1, 1, 0);