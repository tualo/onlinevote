DELIMITER ;
CREATE TABLE IF NOT EXISTS `kandidaten_bilder_typen` (
  `id` integer NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ;


insert
    ignore into kandidaten_bilder_typen (id, name)
values
    (0, 'Barcode');

insert
    ignore into kandidaten_bilder_typen (id, name)
values
    (1, 'Portrait');