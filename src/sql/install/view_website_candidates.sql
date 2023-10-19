DELIMITER ; 

CREATE OR REPLACE VIEW `view_website_candidates` AS
select
    concat(
        '<strong>',
        if(
            `kandidaten`.`titel` <> ''
            and `kandidaten`.`titel` is not null,
            concat(`kandidaten`.`titel`, ' '),
            ''
        ),
        `kandidaten`.`nachname`,
        ', ',
        `kandidaten`.`vorname`,
        '</strong>'
        ,
        if(
            `kandidaten`.`funktion1` <> ''
            and `kandidaten`.`funktion1` is not null,
            concat('<br/>', `kandidaten`.`funktion1`),
            ''
        ),
        if(
            `kandidaten`.`geburtsjahr` <> ''
            and `kandidaten`.`geburtsjahr` is not null,
            concat('<br/>geb. ', `kandidaten`.`geburtsjahr`),
            ''
        )
    ) AS `col1`,
    concat(
        if(
            `kandidaten`.`firma1` <> ''
            and `kandidaten`.`firma1` is not null,
            concat('', `kandidaten`.`firma1`),
            ''
        ),
        if(
            `kandidaten`.`firma2` <> ''
            and `kandidaten`.`firma2` is not null,
            concat('<br/>', `kandidaten`.`firma2`),
            ''
        ),
        if(
            `kandidaten`.`firma3` <> ''
            and `kandidaten`.`firma3` is not null,
            concat('<br/>', `kandidaten`.`firma3`),
            ''
        ),
        if(
            `kandidaten`.`firma4` <> ''
            and `kandidaten`.`firma4` is not null,
            concat('<br/>', `kandidaten`.`firma4`),
            ''
        ),
        if(
            `kandidaten`.`firma_ort` <> ''
            and `kandidaten`.`firma_ort` is not null,
            concat(
                if(
                    concat(
                        trim(`kandidaten`.`firma1`),
                        trim(`kandidaten`.`firma2`),
                        trim(`kandidaten`.`firma3`),
                        trim(`kandidaten`.`firma4`)
                    ) <> '',
                    '<br/>',
                    ''
                ),
                `kandidaten`.`firma_ort`
            ),
            ''
        ),
        if(
            `kandidaten`.`branche` <> ''
            and `kandidaten`.`branche` is not null,
            concat('<br/>Branche: ', `kandidaten`.`branche`),
            ''
        )
    ) AS `col2`,

    `kandidaten_bilder`.`file_id` AS `kandidaten_bild`,
    `kandidaten_bilder`.`file_id` AS `picture`,
    
    `kandidaten`.`id` AS `id`,
    `kandidaten`.`stimmzettelgruppen` AS `stimmzettelgruppen`,
    `kandidaten`.`barcode` AS `barcode`,
    concat(
        if(
            `kandidaten`.`titel` <> ''
            and `kandidaten`.`titel` is not null,
            concat(`kandidaten`.`titel`, ' '),
            ''
        ),
        `kandidaten`.`nachname`,
        ', ',
        `kandidaten`.`vorname`
    ) AS `person1`,
    ' ' AS `person2`,
    `kandidaten`.`funktion1` AS `person_function`,
    `kandidaten`.`funktion2` AS `person_function2`,
    trim(
        concat(
            `kandidaten`.`firma1`,
            ' ',
            `kandidaten`.`firma2`,
            ' ',
            `kandidaten`.`firma3`,
            ' ',
            `kandidaten`.`firma4`
        )
    ) AS `firm1`,
    `kandidaten`.`firma_strasse` AS `firm2`,
    `kandidaten`.`firma_strasse` AS `f_strasse`,
    concat(
        `kandidaten`.`firma_plz`,
        ' ',
        `kandidaten`.`firma_ort`
    ) AS `zip_town`,
    `kandidaten`.`firma_ort` AS `f_ort`,

    `kandidaten`.`branche` AS `branche`,
    `kandidaten`.`funktion2` AS `bgkl`,
    `kandidaten`.`andrede` AS `kreis`
from
    `kandidaten`
    left join kandidaten_bilder on kandidaten_bilder.kandidat = kandidaten.ridx
        and typ = 1;
        