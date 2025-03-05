DELIMITER ;
CREATE TABLE IF NOT EXISTS `wahlbezirk` (
  `id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `aktiv` tinyint DEFAULT 1,
  `zuweisungsschluessel` varchar(255) DEFAULT '0',
  `kurzbezeichnung` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),

  `login` varchar(255) NOT NULL,
  `created_at` timestamp not null default current_timestamp,
  `updated_at` timestamp not null default current_timestamp on update current_timestamp

);

DELIMITER //

CREATE OR REPLACE TRIGGER `trigger_wahlbezirk_bi_defaults`
BEFORE INSERT ON `wahlbezirk` FOR EACH ROW
BEGIN
  IF NEW.login IS NULL THEN
    SET NEW.login = getSessionUser();
  END IF;
  IF NEW.created_at IS NULL THEN
    SET NEW.created_at = CURRENT_TIMESTAMP;
  END IF;
END //

CREATE OR REPLACE TRIGGER `trigger_wahlbezirk_bu_defaults`
BEFORE UPDATE ON `wahlbezirk` FOR EACH ROW
BEGIN
  IF NEW.login IS NULL THEN
    SET NEW.login = getSessionUser();
  END IF;
  IF NEW.created_at IS NULL THEN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
  END IF;
END //
