DELIMITER;

create
or replace view `view_bp_fields` as
select
    `ds_column`.`column_name` AS `column_name`,
    `ds_column`.`data_type` AS `data_type`
from
    `ds_column`
where
    `ds_column`.`table_name` = 'view_website_candidates'
    and `ds_column`.`existsreal` = 1
;