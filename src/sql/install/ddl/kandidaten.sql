DELIMITER ;
CREATE TABLE IF NOT EXISTS `kandidaten` (
  
  `id` int(11) NOT NULL DEFAULT 0,
  `aktiv` tinyint(4) DEFAULT 0,
  
  `titel` varchar(255) DEFAULT NULL,
  `vorname` varchar(255) DEFAULT NULL,
  `nachname` varchar(255) DEFAULT NULL,
  `telefon` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `www` varchar(255) DEFAULT NULL,


  `barcode` varchar(5) DEFAULT '0',


  `firma1` varchar(255) DEFAULT NULL,
  `firma2` varchar(255) DEFAULT NULL,
  `firma3` varchar(255) DEFAULT NULL,
  `firma4` varchar(255) DEFAULT NULL,
  `firma_strasse` varchar(255) DEFAULT NULL,
  `firma_plz` varchar(255) DEFAULT NULL,
  `firma_ort` varchar(255) DEFAULT NULL,

  `stimmzettelgruppen` integer not null,
  
  `funktion1` varchar(255) DEFAULT NULL,
  `funktion2` varchar(255) DEFAULT NULL,
  `funktion3` varchar(255) DEFAULT NULL,

  `firmen_identnummer` varchar(15) DEFAULT NULL,
  `personen_identnummer` varchar(15) DEFAULT NULL,


  `geburtsdatum` date DEFAULT '1960-01-01',
  `geburtsjahr` varchar(5) DEFAULT '1960',


  `geschlecht` varchar(5) DEFAULT NULL,
  `anrede` varchar(100) default "",
  `branche` varchar(255) DEFAULT NULL,



  
  `kooptiert` tinyint(4) DEFAULT 0,
  `losnummer_stimmzettelgruppe` int(11) DEFAULT 0,
  `losnummer` int(11) DEFAULT 0,
  `ist_gewaehlt` tinyint(4) DEFAULT 0,


  PRIMARY KEY (`id`),
  KEY `fk_kandidaten_stimmzettelgruppen` (`stimmzettelgruppen`),
  CONSTRAINT `fk_kandidaten_stimmzettelgruppen` FOREIGN KEY (`stimmzettelgruppen`) REFERENCES `stimmzettelgruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,


  `login` varchar(255) NOT NULL,
  `created_at` timestamp not null default current_timestamp,
  `updated_at` timestamp not null default current_timestamp on update current_timestamp

);

DELIMITER //

CREATE OR REPLACE TRIGGER `trigger_kandidaten_bi_defaults`
BEFORE INSERT ON `kandidaten` FOR EACH ROW
BEGIN
  IF NEW.login IS NULL THEN
    SET NEW.login = getSessionUser();
  END IF;
  IF NEW.created_at IS NULL THEN
    SET NEW.created_at = CURRENT_TIMESTAMP;
  END IF;
END //

CREATE OR REPLACE TRIGGER `trigger_kandidaten_bu_defaults`
BEFORE UPDATE ON `kandidaten` FOR EACH ROW
BEGIN
  IF NEW.login IS NULL THEN
    SET NEW.login = getSessionUser();
  END IF;
  IF NEW.created_at IS NULL THEN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
  END IF;
END //
