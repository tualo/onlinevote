DELIMITER ;
CREATE TABLE IF NOT EXISTS `wahlbezirk` (
  `ridx` varchar(12) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT 0,
  `kostenstelle` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `aktiv` int(11) DEFAULT 1,
  `insert_date` date NOT NULL,
  `update_date` date NOT NULL,
  `login` varchar(255) NOT NULL,
  `zuweisungsschluessel` varchar(255) DEFAULT '0',
  `insert_time` time DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `kurzbezeichnung` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`kostenstelle`),
  UNIQUE KEY `idx_wahlbezirk` (`ridx`),
  UNIQUE KEY `idx_wahlbezirk_ridx` (`ridx`)
) ;
