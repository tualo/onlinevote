DELIMITER ;
CREATE TABLE IF NOT EXISTS `wm_sync_tables` (
  `table_name` varchar(128) NOT NULL,
  `position` int(11) DEFAULT 0,
  `last_sync` datetime DEFAULT NULL,
  PRIMARY KEY (`table_name`)
) ;
