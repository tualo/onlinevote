DELIMITER ;
CREATE TABLE IF NOT EXISTS `stimmzettel` (
  `id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `aktiv` tinyint DEFAULT 1,
  `wahlgruppe` integer DEFAULT 0,
  `wahlbezirk` integer DEFAULT 0,
  `wahltyp` integer DEFAULT 0,
  `sitze` integer DEFAULT 0,
  `ungueltig` int(11) DEFAULT 0,
  `anzahl_10` int(11) DEFAULT 0,
  `zaehlung_1` int(11) DEFAULT 0,
  `zaehlung_2` int(11) DEFAULT 0,
  `zaehlung_3` int(11) DEFAULT 0,
  `zaehlung_4` int(11) DEFAULT 0,
  `zaehlung_5` int(11) DEFAULT 0,
  `zaehlung_6` int(11) DEFAULT 0,
  `zaehlung_7` int(11) DEFAULT 0,
  `zaehlung_8` int(11) DEFAULT 0,
  `zaehlung_9` int(11) DEFAULT 0,
  `sitzbindung` int(11) DEFAULT 0,
  `farbe` varchar(100) DEFAULT 'rgb(159, 80, 152)',
  `typtitel` varchar(100) DEFAULT 'Vollversammlungswahl',
  `xlink` varchar(255) DEFAULT NULL,
  `ocr_name` varchar(255) default null,
  `onlinebezeichnung` varchar(255) default null,
  PRIMARY KEY (`id`),
  KEY `fk_stimmzettel_wahlgruppe` (`wahlgruppe`),
  KEY `fk_stimmzettel_wahlbezirk` (`wahlbezirk`),
  CONSTRAINT `fk_stimmzettel_wahlbezirk` FOREIGN KEY (`wahlbezirk`) REFERENCES `wahlbezirk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_stimmzettel_wahlgruppe` FOREIGN KEY (`wahlgruppe`) REFERENCES `wahlgruppe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  `login` varchar(255) NOT NULL,
  `created_at` timestamp not null default current_timestamp,
  `updated_at` timestamp not null default current_timestamp on update current_timestamp
); 

DELIMITER //

CREATE OR REPLACE TRIGGER `trigger_stimmzettel_bi_defaults`
BEFORE INSERT ON `stimmzettel` FOR EACH ROW
BEGIN
  IF NEW.login IS NULL THEN
    SET NEW.login = getSessionUser();
  END IF;
  IF NEW.created_at IS NULL THEN
    SET NEW.created_at = CURRENT_TIMESTAMP;
  END IF;
END //

CREATE OR REPLACE TRIGGER `trigger_stimmzettel_bu_defaults`
BEFORE UPDATE ON `stimmzettel` FOR EACH ROW
BEGIN
  IF NEW.login IS NULL THEN
    SET NEW.login = getSessionUser();
  END IF;
  IF NEW.created_at IS NULL THEN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
  END IF;
END //
