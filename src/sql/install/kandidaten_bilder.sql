DELIMITER ;
CREATE TABLE IF NOT EXISTS `kandidaten_bilder` (
  `id` varchar(36) DEFAULT NULL,
  `kandidat` varchar(12) NOT NULL,
  `typ` varchar(12) NOT NULL,
  `file_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`kandidat`,`typ`),
  KEY `typ` (`typ`),
  CONSTRAINT `kandidaten_bilder_ibfk_1` FOREIGN KEY (`kandidat`) REFERENCES `kandidaten` (`ridx`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kandidaten_bilder_ibfk_2` FOREIGN KEY (`typ`) REFERENCES `kandidaten_bilder_typen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ;
