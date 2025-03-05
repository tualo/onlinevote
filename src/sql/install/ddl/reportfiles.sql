DELIMITER;

CREATE TABLE IF NOT EXISTS `reportfiles` (
  `id` varchar(36) DEFAULT NULL,
  `typ` varchar(12) NOT NULL,
  `file_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `typ` (`typ`),
  CONSTRAINT `fk_reportfiles_typ` FOREIGN KEY (`typ`) REFERENCES `reportfiles_typen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE
OR REPLACE VIEW `view_readtable_reportfiles` AS
select
  concat(
    '<a target="_blank" href="./dsfiles/reportfiles/',
    reportfiles.file_id,
    '">Download</a>'
  ) download_url,
  `reportfiles`.`id` AS `id`,
  `reportfiles`.`typ` AS `typ`,
  `ds_files`.`name` AS `__file_name`,
  `ds_files`.`path` AS `path`,
  `ds_files`.`size` AS `__file_size`,
  `ds_files`.`mtime` AS `mtime`,
  `ds_files`.`ctime` AS `ctime`,
  `ds_files`.`type` AS `__file_type`,
  `ds_files`.`file_id` AS `__file_id`,
  `ds_files`.`hash` AS `hash`,
  '' AS `__file_data`,
  rank() over (
    partition by `ds_files`.`hash`
    order by
      `ds_files`.`ctime`
  ) AS `duplicate_file`
from
  (
    `reportfiles`
    left join `ds_files` on(
      `reportfiles`.`file_id` = `ds_files`.`file_id`
    )
  );
