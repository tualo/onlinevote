DELIMITER ;
CREATE TABLE IF NOT EXISTS `wm_sync_tables` (
  `table_name` varchar(128) NOT NULL,
  `position` int(11) DEFAULT 0,
  `last_sync` datetime DEFAULT NULL,
  PRIMARY KEY (`table_name`)
) ;


insert ignore into wm_sync_tables(table_name,position) values ('ds_files',8);
insert ignore into wm_sync_tables(table_name,position) values ('kandidaten',7);
insert ignore into wm_sync_tables(table_name,position) values ('kandidaten_bilder',10);
insert ignore into wm_sync_tables(table_name,position) values ('kandidaten_bilder_typen',9);
insert ignore into wm_sync_tables(table_name,position) values ('stimmzettel',3);
insert ignore into wm_sync_tables(table_name,position) values ('stimmzettelgruppen',4);
insert ignore into wm_sync_tables(table_name,position) values ('stimmzettel_fusstexte',5);
insert ignore into wm_sync_tables(table_name,position) values ('stimmzettel_stimmzettel_fusstexte',6);
insert ignore into wm_sync_tables(table_name,position) values ('wahlbezirk',1);
insert ignore into wm_sync_tables(table_name,position) values ('wahlgruppe',2);
