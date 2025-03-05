DELIMITER ;
CREATE TABLE IF NOT EXISTS `system_settings_user_access` (
  `system_settings_id` varchar(50) NOT NULL,
  `groupname` varchar(50) NOT NULL,
  PRIMARY KEY (`system_settings_id`,`groupname`),
  CONSTRAINT `fk_system_settings_user_access_system_settings` FOREIGN KEY (`system_settings_id`) REFERENCES `system_settings` (`system_settings_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ;
