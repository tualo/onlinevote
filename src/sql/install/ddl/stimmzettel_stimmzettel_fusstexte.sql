DELIMITER ;
CREATE TABLE IF NOT EXISTS `stimmzettel_stimmzettel_fusstexte` (
  `id_stimmzettel` int(11) NOT NULL,
  `id_stimmzettel_fusstexte` varchar(36) NOT NULL,
  `pos` int(11) DEFAULT 0,
  PRIMARY KEY (`id_stimmzettel`,`id_stimmzettel_fusstexte`),
  KEY `fk_stimmzettel_stimmzettel_fusstexte` (`id_stimmzettel_fusstexte`),
  CONSTRAINT `fk_stimmzettel_fusstexte_stimmzettel` FOREIGN KEY (`id_stimmzettel`) REFERENCES `stimmzettel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_stimmzettel_stimmzettel_fusstexte` FOREIGN KEY (`id_stimmzettel_fusstexte`) REFERENCES `stimmzettel_fusstexte` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ;
