DELIMITER ;
CREATE TABLE IF NOT EXISTS `kandidaten_bilder` (
  `id` varchar(36) DEFAULT NULL,
  `kandidat` integer NOT NULL,
  `typ` integer NOT NULL,
  `file_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),

  KEY `idx_kandidaten_bilder_typ` (`typ`),
  UNIQUE KEY `id_kandidaten_bilder_kandidat_typ` (`kandidat`,`typ`),

  CONSTRAINT `fk_kandidaten_bilder_kandidat` FOREIGN KEY (`kandidat`) REFERENCES `kandidaten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_kandidaten_bilder_typ` FOREIGN KEY (`typ`) REFERENCES `kandidaten_bilder_typen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
)  ;


