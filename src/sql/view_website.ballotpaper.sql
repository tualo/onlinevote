create or replace view view_website_ballotpaper as select * from stimmzettel;

create or replace view view_website_ballotpaper_groups as 
select stimmzettelgruppen.*,
stimmzettel.id ballotpaper_id
from stimmzettelgruppen join stimmzettel on stimmzettelgruppen.stimmzettel=stimmzettel.ridx;