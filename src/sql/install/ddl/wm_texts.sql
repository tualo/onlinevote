DELIMITER ;
CREATE TABLE IF NOT EXISTS `wm_texts` (
  `id` varchar(50) NOT NULL,
  `value_plain` longtext DEFAULT NULL,
  `value_html` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;
