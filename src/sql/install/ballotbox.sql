DELIMITER ;
CREATE TABLE IF NOT EXISTS `ballotbox` (
  `id` varchar(50) NOT NULL,
  `keyname` varchar(50) NOT NULL,
  `ballotpaper` text NOT NULL,
  `voter_id` varchar(20) DEFAULT NULL,
  `blocked` tinyint(4) DEFAULT 0,
  `stimmzettel` varchar(15) DEFAULT NULL,
  `saveerror` tinyint(4) DEFAULT 0,
  `saveerrorid` varchar(36) DEFAULT '',
  `isvalid` tinyint(4) DEFAULT 0,
  `stimmzettel_id` varchar(10) DEFAULT '',
  PRIMARY KEY (`id`,`keyname`),
  KEY `idx_ballotbox_pgpkeys` (`keyname`),
  KEY `idx_ballotbox_keyname` (`keyname`),
  KEY `idx_ballotbox_saveerror` (`saveerror`),
  KEY `idx_ballotbox_blocked` (`blocked`),
  KEY `idx_ballotbox_saveerrorid` (`saveerrorid`),
  CONSTRAINT `fk_ballotbox_pgpkeys` FOREIGN KEY (`keyname`) REFERENCES `pgpkeys` (`keyname`) ON DELETE CASCADE ON UPDATE CASCADE
) ;
DELIMITER  //
CREATE OR REPLACE TRIGGER trigger_ballotbox_ai
AFTER INSERT
   ON ballotbox FOR EACH ROW
BEGIN
  DECLARE last_hash varchar(255);
  DECLARE current_hash varchar(255);
  DECLARE max_id BIGINT;

  SELECT max(id) INTO max_id FROM ballotbox_blockchain WHERE keyname=NEW.keyname;
  IF (max_id IS NULL) THEN
    SELECT md5(fingerprint) INTO last_hash FROM pgpkeys WHERE keyname=NEW.keyname;
  ELSE
    SELECT hash_value INTO last_hash FROM ballotbox_blockchain WHERE id=max_id;
  END IF;
  SELECT md5(concat(last_hash,md5(NEW.ballotpaper))) INTO current_hash;
  insert into ballotbox_blockchain (keyname,hash_value,`last_hash`,ballotpaper_id) values (NEW.keyname,current_hash,last_hash,NEW.id);
END //


