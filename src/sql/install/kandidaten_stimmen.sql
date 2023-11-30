DELIMITER ;

CREATE TABLE IF NOT EXISTS `kandidaten_stimmen` (
  `id` int(11) NOT NULL,
  `stimmen` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ;


create or replace view view_readtable_kandidaten_stimmen as 
select 
    kandidaten_stimmen.id,
    kandidaten_stimmen.stimmen,
    kandidaten.barcode,
    kandidaten.vorname,
    kandidaten.nachname,
    kandidaten.titel,
    stimmzettelgruppen.name stimmzettelgruppe
from 
kandidaten_stimmen
join kandidaten on kandidaten.id = kandidaten_stimmen.id
join stimmzettelgruppen on kandidaten.stimmzettelgruppen = stimmzettelgruppen.ridx;
