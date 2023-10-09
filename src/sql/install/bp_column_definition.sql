DELIMITER ;
CREATE TABLE IF NOT EXISTS `bp_column_definition` (
  `column_field` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `pos` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(4) DEFAULT 0,
  `htmltag` varchar(10) DEFAULT 'p',
  PRIMARY KEY (`column_field`,`column_name`),
  KEY `idx_bp_column_definition_column_name` (`column_name`),
  CONSTRAINT `fk_bp_column_definition_column_name` FOREIGN KEY (`column_name`) REFERENCES `bp_column` (`column_name`) ON DELETE CASCADE ON UPDATE CASCADE
) ;
