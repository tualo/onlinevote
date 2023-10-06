DELIMITER ;
CREATE TABLE IF NOT EXISTS `stimmzettelgruppen` (
  `ridx` varchar(12) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT 0,
  `kostenstelle` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `stimmzettel` varchar(12) NOT NULL,
  `sitze` int(11) DEFAULT 1,
  `mindestsitze` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uidx_stimmzettelgruppen_ridx` (`ridx`),
  KEY `idx_stimmzettelgruppen_stimmzettel` (`stimmzettel`),
  CONSTRAINT `fk_stimmzettelgruppen_stimmzettel` FOREIGN KEY (`stimmzettel`) REFERENCES `stimmzettel` (`ridx`) ON DELETE CASCADE ON UPDATE CASCADE
) ;
