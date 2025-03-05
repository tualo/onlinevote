DELIMITER ;
CREATE TABLE IF NOT EXISTS `stimmzettel_fusstexte` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;
