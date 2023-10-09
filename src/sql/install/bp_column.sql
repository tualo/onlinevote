DELIMITER ;
CREATE TABLE IF NOT EXISTS `bp_column` (
  `column_name` varchar(255) NOT NULL,
  `active` tinyint(4) DEFAULT 0,
  `pos` int(11) NOT NULL DEFAULT 0,
  `row_name` varchar(255) DEFAULT 'row-main',
  `prefix` varchar(25) DEFAULT '',
  PRIMARY KEY (`column_name`),
  KEY `fk_bp_column_row_name` (`row_name`),
  CONSTRAINT `fk_bp_column_row_name` FOREIGN KEY (`row_name`) REFERENCES `bp_row` (`row_name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_bp_column_column_name` CHECK (`column_name` regexp '^([u0061-u007a]|[u0030-u0039]|-|\\_)*$' > 0)
) ;
