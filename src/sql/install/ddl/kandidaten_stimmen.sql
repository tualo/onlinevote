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
    0 kostenstelle,
    kandidaten_stimmen.stimmen anzahl,
    kandidaten.barcode,
    kandidaten.barcode name,
    kandidaten.vorname,
    kandidaten.nachname,
    kandidaten.titel,
    stimmzettelgruppen.name stimmzettelgruppe
from 
kandidaten_stimmen
join kandidaten on kandidaten.id = kandidaten_stimmen.id
join stimmzettelgruppen on kandidaten.stimmzettelgruppen = stimmzettelgruppen.id;

call fill_ds('view_readtable_kandidaten_stimmen');
call fill_ds_column('view_readtable_kandidaten_stimmen');
