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



create table unique_voter_session (
id varchar(36) primary key,
session_id varchar(50),
create_time datetime default current_timestamp
);


CREATE OR REPLACE TRIGGER voters_bi_completed
AFTER INSERT
   ON voters FOR EACH ROW
BEGIN
  IF EXISTS(select voter_id from voters where voter_id=new.voter_id and stimmzettel=new.stimmzettel and completed=1) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'allready voted';
  END IF;
END




alter table wm_wahlschein_register drop wahlscheinnummer;
alter table wm_wahlschein_register drop birthdate_year;
alter table wm_wahlschein_register drop birthdate_month;
alter table wm_wahlschein_register drop birthdate_day;
alter table wm_wahlschein_register drop phone_lc;
alter table wm_wahlschein_register drop phone_number;
alter table wm_wahlschein_register drop pin;
alter table wm_wahlschein_register drop sms_response;
alter table wm_wahlschein_register drop tmg_token;
alter table wm_wahlschein_register drop person;

drop trigger wm_wahlschein_register__au;
drop trigger wm_wahlschein_register__ai;
drop trigger wm_wahlschein_register__bd;




CREATE OR REPLACE FUNCTION `canChangeValue`(in_system_settings_id varchar(50) ) RETURNS tinyint(1)
    DETERMINISTIC
BEGIN
  DECLARE lvl BOOLEAN DEFAULT FALSE;


  IF EXISTS(
      SELECT 
        system_settings_user_access.system_settings_id  
      FROM 
        system_settings_user_access join view_session_groups 
        on system_settings_user_access.groupname = view_session_groups.group 
    WHERE 
      system_settings_user_access.system_settings_id = in_system_settings_id  
    ) THEN
    SET lvl= TRUE;
  ELSE
    IF NOT EXISTS(select system_settings_id from system_settings where system_settings_id=in_system_settings_id ) THEN
      SET lvl= TRUE;
    END IF;
  END IF;
  RETURN (lvl);
END


insert into  system_settings (system_settings_id) values 
	('remote-erp/public'),
  ('remote-erp/url'),
  ('remote-erp/token') 
;
insert into system_settings_user_access(system_settings_id,groupname) values ('remote-erp/public','administration');
insert into system_settings_user_access(system_settings_id,groupname) values ('remote-erp/url','administration');
insert into system_settings_user_access(system_settings_id,groupname) values ('remote-erp/token','administration');