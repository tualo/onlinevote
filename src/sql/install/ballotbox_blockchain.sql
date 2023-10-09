DELIMITER ;
CREATE TABLE IF NOT EXISTS `ballotbox_blockchain` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `keyname` varchar(50) NOT NULL,
  `hash_value` varchar(255) NOT NULL,
  `last_hash` varchar(255) NOT NULL,
  `ballotpaper_id` varchar(36) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ballotbox_blockchain_pgpkeys` (`keyname`),
  CONSTRAINT `fk_ballotbox_blockchain_pgpkeys` FOREIGN KEY (`keyname`) REFERENCES `pgpkeys` (`keyname`) ON DELETE CASCADE ON UPDATE CASCADE
);
