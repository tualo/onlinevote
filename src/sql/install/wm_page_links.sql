DELIMITER ;
CREATE TABLE IF NOT EXISTS `wm_page_links` (
  `id` varchar(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `headernav` tinyint(4) DEFAULT 0,
  `footer1` tinyint(4) DEFAULT 0,
  `footer2` tinyint(4) DEFAULT 0,
  `newwindow` tinyint(4) DEFAULT 0,
  `position` int(11) DEFAULT 9999,
  `mainnav` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`)
) ;
