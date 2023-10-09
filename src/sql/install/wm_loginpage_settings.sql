DELIMITER ;
CREATE TABLE IF NOT EXISTS `wm_loginpage_settings` (
  `id` int(11) NOT NULL,
  `wsregistertoken` varchar(150) DEFAULT NULL,
  `wsapitoken` varchar(150) DEFAULT NULL,
  `smsauth` varchar(150) DEFAULT NULL,
  `tmgurl` varchar(150) DEFAULT NULL,
  `index_redirect` varchar(150) DEFAULT NULL,
  `sendsms` tinyint(4) DEFAULT 1,
  `backendurl` varchar(150) DEFAULT NULL,
  `wszapitoken` varchar(50) DEFAULT NULL,
  `starttime` datetime DEFAULT '2020-01-01 10:10:10',
  `stoptime` datetime DEFAULT '2020-01-01 10:10:10',
  `interrupted` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`)
) ;
