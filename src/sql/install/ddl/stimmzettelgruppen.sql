DELIMITER ;
CREATE TABLE IF NOT EXISTS `stimmzettelgruppen` (
  `id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  
  `stimmzettel` int(11) NOT NULL,
  `sitze` int(11) DEFAULT 1,
  `mindestsitze` int(11) DEFAULT 0,
  `onlinebezeichnung` varchar(255) default null,


  PRIMARY KEY (`id`),
  CONSTRAINT `fk_stimmzettelgruppen_stimmzettel` FOREIGN KEY (`stimmzettel`) REFERENCES `stimmzettel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,

  `login` varchar(255) NOT NULL,
  `created_at` timestamp not null default current_timestamp,
  `updated_at` timestamp not null default current_timestamp on update current_timestamp

);

DELIMITER //

CREATE OR REPLACE TRIGGER `trigger_stimmzettelgruppen_bi_defaults`
BEFORE INSERT ON `stimmzettelgruppen` FOR EACH ROW
BEGIN
  IF NEW.login IS NULL THEN
    SET NEW.login = getSessionUser();
  END IF;
  IF NEW.created_at IS NULL THEN
    SET NEW.created_at = CURRENT_TIMESTAMP;
  END IF;
END //

CREATE OR REPLACE TRIGGER `trigger_stimmzettelgruppen_bu_defaults`
BEFORE UPDATE ON `stimmzettelgruppen` FOR EACH ROW
BEGIN
  IF NEW.login IS NULL THEN
    SET NEW.login = getSessionUser();
  END IF;
  IF NEW.created_at IS NULL THEN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
  END IF;
END //