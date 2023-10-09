DELIMITER ;
CREATE TABLE IF NOT EXISTS `ballotbox_encrypted` (
  `id` varchar(50) NOT NULL,
  `keyname` varchar(50) NOT NULL,
  `ballotpaper` text NOT NULL,
  `saveerror` tinyint(4) DEFAULT 0,
  `isvalid` tinyint(4) DEFAULT 0,
  `stimmzettel` varchar(10) DEFAULT '',
  PRIMARY KEY (`id`,`keyname`),
  KEY `idx_ballotbox_encrypted_pgpkeys` (`keyname`),
  CONSTRAINT `fk_ballotbox_encrypted_pgpkeys` FOREIGN KEY (`keyname`) REFERENCES `pgpkeys` (`keyname`) ON DELETE CASCADE ON UPDATE CASCADE
) ;
