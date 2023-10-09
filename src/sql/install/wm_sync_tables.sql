DELIMITER ;
CREATE TABLE IF NOT EXISTS `wm_sync_tables` (
  `table_name` varchar(128) NOT NULL,
  `position` int(11) DEFAULT 0,
  `last_sync` datetime DEFAULT NULL,
  PRIMARY KEY (`table_name`)
) ;



INSERT IGNORE INTO wm_sync_tables (table_name, position, last_sync) VALUES ('wahlbezirk', 1, null);
INSERT IGNORE INTO wm_sync_tables (table_name, position, last_sync) VALUES ('wahlgruppe', 2, null);
INSERT IGNORE INTO wm_sync_tables (table_name, position, last_sync) VALUES ('stimmzettel', 3, null);
INSERT IGNORE INTO wm_sync_tables (table_name, position, last_sync) VALUES ('stimmzettelgruppen', 4, null);
INSERT IGNORE INTO wm_sync_tables (table_name, position, last_sync) VALUES ('stimmzettel_fusstexte', 5, null);
INSERT IGNORE INTO wm_sync_tables (table_name, position, last_sync) VALUES ('stimmzettel_stimmzettel_fusstexte', 6, null);
INSERT IGNORE INTO wm_sync_tables (table_name, position, last_sync) VALUES ('kandidaten', 7, null);
INSERT IGNORE INTO wm_sync_tables (table_name, position, last_sync) VALUES ('ds_files', 8, null);
INSERT IGNORE INTO wm_sync_tables (table_name, position, last_sync) VALUES ('kandidaten_bilder_typen', 9, null);
INSERT IGNORE INTO wm_sync_tables (table_name, position, last_sync) VALUES ('kandidaten_bilder', 10, null);
