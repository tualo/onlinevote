DELIMITER ;
CREATE TABLE IF NOT EXISTS `ds_renderer_stylesheet` (
  `classname` varchar(150) NOT NULL,
  `group` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`classname`,`group`),
  KEY `idx_ds_renderer_stylesheet_group` (`group`),
  KEY `idx_ds_renderer_stylesheet_classname` (`classname`),
  CONSTRAINT `fk_ds_renderer_stylesheet_group` FOREIGN KEY (`group`) REFERENCES `ds_renderer_stylesheet_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ;
