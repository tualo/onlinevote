DELIMITER ;
CREATE TABLE IF NOT EXISTS `ds_files` (
  `file_id` varchar(36) NOT NULL DEFAULT uuid(),
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `mtime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `type` varchar(255) NOT NULL,
  `hash` varchar(36) NOT NULL,
  `login` varchar(255) NOT NULL,
  `table_name` varchar(128) NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `idx_ds_files_table_name` (`table_name`),
  KEY `idx_ds_files_hash` (`hash`),
  KEY `idx_ds_files_login` (`login`)
) ;
