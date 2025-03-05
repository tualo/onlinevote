delimiter ;

CREATE VIEW  if not exists `view_readtable_kandidaten_bilder` AS
select
    `kandidaten_bilder`.`id` AS `id`,
    `kandidaten_bilder`.`kandidat` AS `kandidat`,
    `kandidaten_bilder`.`typ` AS `typ`,
    `kandidaten_bilder`.`file_id`,
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
