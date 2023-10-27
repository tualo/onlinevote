DELIMITER ; 

CREATE OR REPLACE VIEW `view_website_candidates` AS
select
    

    `kandidaten_bilder`.`file_id` AS `kandidaten_bild`,
    ifnull(`kandidaten_bilder`.`file_id`,'none') AS `picture`,
    `kandidaten`.*
from
    `kandidaten`
    left join kandidaten_bilder on kandidaten_bilder.kandidat = kandidaten.ridx
        and typ = 1;
        