DELIMITER ;
CREATE TABLE IF NOT EXISTS `username_count` (
  `id` varchar(20) NOT NULL,
  `num` int(11) DEFAULT 0,
  `block_until` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;
