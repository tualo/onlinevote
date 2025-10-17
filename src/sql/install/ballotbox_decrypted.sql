DELIMITER ;
CREATE TABLE IF NOT EXISTS `ballotbox_decrypted` (
  `id` varchar(50) NOT NULL,
  `keyname` varchar(50) NOT NULL,
  `ballotpaper` text NOT NULL,
  `saveerror` tinyint(4) DEFAULT 0,
  `isvalid` tinyint(4) DEFAULT 0,
  `stimmzettel` integer not null,
  PRIMARY KEY (`id`,`keyname`),
  KEY `idx_ballotbox_decrypted_pgpkeys` (`keyname`),
  CONSTRAINT `fk_ballotbox_decrypted_pgpkeys` FOREIGN KEY (`keyname`) REFERENCES `pgpkeys` (`keyname`) ON DELETE CASCADE ON UPDATE CASCADE
) ;



call addFieldIfNotExists("ballotbox","decrypted","tinyint default 0") ;
call addFieldIfNotExists("ballotbox_decrypted","ts","timestamp default current_timestamp") ;

call `create_index`(database(),'ballotbox','idx_ballotbox_decrypted','decrypted');
