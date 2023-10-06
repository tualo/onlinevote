DELIMITER ;
INSERT INTO `ds` (`table_name`, `title`, `reorderfield`, `use_history`, `searchfield`, `displayfield`, `sortfield`, `searchany`, `hint`, `overview_tpl`, `sync_table`, `writetable`, `globalsearch`, `listselectionmodel`, `sync_view`, `syncable`, `cssstyle`, `read_table`, `existsreal`, `class_name`, `special_add_panel`, `read_filter`, `listxtypeprefix`, `phpexporter`, `phpexporterfilename`, `combined`, `allowForm`, `alternativeformxtype`, `character_set_name`, `default_pagesize`, `listviewbaseclass`, `showactionbtn`) VALUES ('wm_page_links','Links','position',0,'title','title','position',1,'','','','',0,'tualomultirowmodel','',0,'','view_readtable_wm_page_links',1,'Unklassifiziert',NULL,NULL,'listview','XlsxWriter','{GUID}',0,1,'','',100,'Tualo.DataSets.ListView',1) ON DUPLICATE KEY UPDATE title=VALUES(title), reorderfield=VALUES(reorderfield), use_history=VALUES(use_history), searchfield=VALUES(searchfield), displayfield=VALUES(displayfield), sortfield=VALUES(sortfield), searchany=VALUES(searchany), hint=VALUES(hint), overview_tpl=VALUES(overview_tpl), sync_table=VALUES(sync_table), writetable=VALUES(writetable), globalsearch=VALUES(globalsearch), listselectionmodel=VALUES(listselectionmodel), sync_view=VALUES(sync_view), syncable=VALUES(syncable), cssstyle=VALUES(cssstyle), alternativeformxtype=VALUES(alternativeformxtype), read_table=   VALUES(read_table), class_name=VALUES(class_name), special_add_panel=VALUES(special_add_panel), existsreal=VALUES(existsreal), character_set_name=VALUES(character_set_name), read_filter=VALUES(read_filter), listxtypeprefix=VALUES(listxtypeprefix), phpexporter=VALUES(phpexporter), phpexporterfilename=VALUES(phpexporterfilename), combined=VALUES(combined), default_pagesize=VALUES(default_pagesize), allowForm=VALUES(allowForm), listviewbaseclass=VALUES(listviewbaseclass), showactionbtn=VALUES(showactionbtn), modelbaseclass=VALUES(modelbaseclass);
REPLACE INTO `ds_column` (`table_name`, `column_name`, `default_value`, `default_max_value`, `default_min_value`, `is_primary`, `update_value`, `is_nullable`, `is_referenced`, `referenced_table`, `referenced_column_name`, `data_type`, `column_key`, `column_type`, `character_maximum_length`, `numeric_precision`, `numeric_scale`, `character_set_name`, `privileges`, `existsreal`, `deferedload`, `writeable`, `note`, `hint`) VALUES ('wm_page_links','footer1',NULL,10000000,0,0,NULL,'YES','NO',NULL,NULL,'tinyint','','tinyint(4)',NULL,3,0,NULL,'select,insert,update,references',1,0,1,'',NULL),
('wm_page_links','footer2',NULL,10000000,0,0,NULL,'YES','NO',NULL,NULL,'tinyint','','tinyint(4)',NULL,3,0,NULL,'select,insert,update,references',1,0,1,'',NULL),
('wm_page_links','headernav',NULL,10000000,0,0,NULL,'YES','NO',NULL,NULL,'tinyint','','tinyint(4)',NULL,3,0,NULL,'select,insert,update,references',1,0,1,'',NULL),
('wm_page_links','id','{:uuid()}',10000000,0,1,'','NO','NO','','','varchar','PRI','varchar(36)',36,NULL,NULL,'utf8mb3','select,insert,update,references',1,0,1,'\'\'',NULL),
('wm_page_links','mainnav',NULL,10000000,0,0,NULL,'YES','NO',NULL,NULL,'tinyint','','tinyint(4)',NULL,3,0,NULL,'select,insert,update,references',1,0,1,'',NULL),
('wm_page_links','newwindow',NULL,10000000,0,0,NULL,'YES','NO',NULL,NULL,'tinyint','','tinyint(4)',NULL,3,0,NULL,'select,insert,update,references',1,0,1,'',NULL),
('wm_page_links','position',NULL,10000000,0,0,NULL,'YES','NO',NULL,NULL,'int','','int(11)',NULL,10,0,NULL,'select,insert,update,references',1,0,1,'',NULL),
('wm_page_links','title',NULL,10000000,0,0,NULL,'NO','NO',NULL,NULL,'varchar','','varchar(255)',255,NULL,NULL,'utf8mb3','select,insert,update,references',1,0,1,'',NULL),
('wm_page_links','url',NULL,10000000,0,0,NULL,'NO','NO',NULL,NULL,'varchar','','varchar(255)',255,NULL,NULL,'utf8mb3','select,insert,update,references',1,0,1,'',NULL),
('wm_page_links','vartarget',NULL,10000000,0,0,NULL,'YES',NULL,NULL,NULL,'varchar','','varchar(6)',6,NULL,NULL,'utf8mb3','select,insert,update,references',1,0,0,'',NULL);
REPLACE INTO `ds_column_list_label` (`table_name`, `column_name`, `language`, `label`, `xtype`, `editor`, `position`, `summaryrenderer`, `summarytype`, `hidden`, `active`, `renderer`, `filterstore`, `flex`, `direction`, `align`, `grouped`, `listfiltertype`, `hint`) VALUES ('wm_page_links','footer1','DE','im Fuss 1','checkcolumn','',5,'','',0,1,'','',1.00,'ASC','left',0,'',NULL),
('wm_page_links','footer2','DE','im Fuss 2','checkcolumn','',6,'','',0,1,'','',1.00,'ASC','left',0,'',NULL),
('wm_page_links','headernav','DE','im Header','checkcolumn','',3,'','',0,1,'','',1.00,'ASC','left',0,'',NULL),
('wm_page_links','id','DE','ID','gridcolumn','',0,'','',0,1,'','',1.00,'ASC','left',0,'',NULL),
('wm_page_links','mainnav','DE','im Haupteil','checkcolumn','',4,'','',0,1,'','',1.00,'ASC','left',0,'',NULL),
('wm_page_links','newwindow','DE','als neuen Tab','checkcolumn','',7,'','',0,1,'','',1.00,'ASC','left',0,'',NULL),
('wm_page_links','position','DE','Position','gridcolumn',NULL,8,'','',0,1,'','',1.00,'ASC','left',0,'',NULL),
('wm_page_links','title','DE','Text','gridcolumn',NULL,1,'','',0,1,'','',1.00,'ASC','left',0,'',NULL),
('wm_page_links','url','DE','URL','gridcolumn',NULL,2,'','',0,1,'','',1.00,'ASC','left',0,'',NULL),
('wm_page_links','vartarget','DE','vartarget','gridcolumn','',999,'','',0,1,'','',1.00,'ASC','left',0,'',NULL);
REPLACE INTO `ds_column_form_label` (`table_name`, `column_name`, `language`, `label`, `xtype`, `field_path`, `position`, `hidden`, `active`, `allowempty`, `fieldgroup`, `flex`) VALUES ('wm_page_links','footer1','DE','im Fuss 1','checkbox','Allgemein',5,0,1,1,'',1.00),
('wm_page_links','footer2','DE','im Fuss 2','checkbox','Allgemein',6,0,1,1,'',1.00),
('wm_page_links','headernav','DE','im Header','checkbox','Allgemein',3,0,1,1,'',1.00),
('wm_page_links','id','DE','ID','displayfield','Allgemein',0,0,1,1,'',1.00),
('wm_page_links','mainnav','DE','im Haupteil','checkbox','Allgemein',4,0,1,1,'',1.00),
('wm_page_links','newwindow','DE','als neuen Tab','checkbox','Allgemein',7,0,1,1,'',1.00),
('wm_page_links','position','DE','Position','displayfield','Allgemein',8,1,1,1,'',1.00),
('wm_page_links','title','DE','Text','textfield','Allgemein',1,0,1,1,'',1.00),
('wm_page_links','url','DE','URL','textfield','Allgemein',2,0,1,1,'',1.00);
REPLACE INTO `ds_access` (`role`, `table_name`, `read`, `write`, `delete`, `append`, `existsreal`) VALUES ('administration','wm_page_links',0,1,1,1,0),
('_default_','wm_page_links',1,0,0,0,0);
