DELIMITER ;
CREATE TABLE IF NOT EXISTS `system_settings_suggestion` (
  `suggestion_id` varchar(50) NOT NULL,
  `system_settings_id` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `inserttime` datetime NOT NULL,
  `property` text DEFAULT NULL,
  PRIMARY KEY (`suggestion_id`)
) ;
