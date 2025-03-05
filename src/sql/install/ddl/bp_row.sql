DELIMITER ;
CREATE TABLE IF NOT EXISTS `bp_row` (
  `row_name` varchar(255) NOT NULL,
  `active` tinyint(4) DEFAULT 0,
  `pos` int(11) NOT NULL DEFAULT 0,
  `prefix` varchar(25) DEFAULT '',
  PRIMARY KEY (`row_name`),
  CONSTRAINT `chk_bp_row_row_name` CHECK (`row_name` regexp '^([u0061-u007a]|[u0030-u0039]|-|\\_)*$' > 0)
) ;
