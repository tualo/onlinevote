DELIMITER ;
CREATE TABLE IF NOT EXISTS `kandidaten_bilder_typen` (
  `id` varchar(12) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
