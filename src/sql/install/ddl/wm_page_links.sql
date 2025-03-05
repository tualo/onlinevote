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
);

CREATE OR REPLACE VIEW `view_readtable_wm_page_links` AS

select

    `wm_page_links`.`id` AS `id`,

    `wm_page_links`.`title` AS `title`,

    `wm_page_links`.`url` AS `url`,

    `wm_page_links`.`headernav` AS `headernav`,

    `wm_page_links`.`footer1` AS `footer1`,

    `wm_page_links`.`footer2` AS `footer2`,

    `wm_page_links`.`newwindow` AS `newwindow`,

    `wm_page_links`.`position` AS `position`,

    `wm_page_links`.`mainnav` AS `mainnav`,

    if(`wm_page_links`.`newwindow` = 1, '_blank', '_self') AS `vartarget`

from

    `wm_page_links`;