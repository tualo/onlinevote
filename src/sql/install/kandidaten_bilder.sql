DELIMITER;

CREATE TABLE IF NOT EXISTS `kandidaten_bilder` (
  `id` varchar(36) DEFAULT NULL,
  `kandidat` varchar(12) NOT NULL,
  `typ` varchar(12) NOT NULL,
  `file_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`kandidat`, `typ`),
  KEY `typ` (`typ`),
  CONSTRAINT `kandidaten_bilder_ibfk_1` FOREIGN KEY (`kandidat`) REFERENCES `kandidaten` (`ridx`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kandidaten_bilder_ibfk_2` FOREIGN KEY (`typ`) REFERENCES `kandidaten_bilder_typen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE
OR REPLACE VIEW `view_readtable_kandidaten_bilder` AS
select
  `kandidaten_bilder`.`id` AS `id`,
  `kandidaten_bilder`.`kandidat` AS `kandidat`,
  `kandidaten_bilder`.`typ` AS `typ`,
  `ds_files`.`name` AS `__file_name`,
  `ds_files`.`path` AS `path`,
  `ds_files`.`size` AS `__file_size`,
  `ds_files`.`mtime` AS `mtime`,
  `ds_files`.`ctime` AS `ctime`,
  `ds_files`.`type` AS `__file_type`,
  `ds_files`.`file_id` AS `__file_id`,
  `ds_files`.`hash` AS `hash`,
  '' AS `__file_data`
from
  (
    `kandidaten_bilder`
    left join `ds_files` on(
      `kandidaten_bilder`.`file_id` = `ds_files`.`file_id`
    )
  );