DELIMITER ;
CREATE TABLE IF NOT EXISTS `pgpkeys` (
  `keyname` varchar(50) NOT NULL,
  `keyid` varchar(255) NOT NULL,
  `fingerprint` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `publickey` text NOT NULL,
  `privatekey` text NOT NULL,
  PRIMARY KEY (`keyname`)
) ;
