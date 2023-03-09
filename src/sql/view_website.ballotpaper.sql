create or replace view view_website_ballotpaper as select * from stimmzettel;

create or replace view view_website_ballotpaper_groups as 
select stimmzettelgruppen.*,
stimmzettel.id ballotpaper_id
from stimmzettelgruppen join stimmzettel on stimmzettelgruppen.stimmzettel=stimmzettel.ridx;


create index idx_ds_renderer_stylesheet_classname on ds_renderer_stylesheet(classname);
alter table ds_renderer_stylesheet drop primary key;
alter table ds_renderer_stylesheet add primary key(classname,`group`);


create index idx_ds_renderer_stylesheet_attributes_classname_attribute on ds_renderer_stylesheet(classname,attribute);
alter table  ds_renderer_stylesheet_attributes drop primary key;
alter table  ds_renderer_stylesheet_attributes add `group` integer;
update ds_renderer_stylesheet_attributes set `group` = (select max(`group`) from  ds_renderer_stylesheet where ds_renderer_stylesheet.classname = ds_renderer_stylesheet_attributes.classname) where `group` is null;
alter table  ds_renderer_stylesheet_attributes add primary key(classname,attribute,`group`);