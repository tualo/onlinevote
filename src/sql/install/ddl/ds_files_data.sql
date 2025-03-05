DELIMITER ;
CREATE TABLE IF NOT EXISTS `ds_files_data` (
  `file_id` varchar(36) NOT NULL,
  `data` longtext NOT NULL,
  PRIMARY KEY (`file_id`),
  CONSTRAINT `fk_ds_files_data_file_id` FOREIGN KEY (`file_id`) REFERENCES `ds_files` (`file_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ;
