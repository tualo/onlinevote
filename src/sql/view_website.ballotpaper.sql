delimiter ; 

create or replace view view_website_ballotpaper as select * from stimmzettel;

create or replace view view_website_ballotpaper_groups as 
select stimmzettelgruppen.*,
stimmzettel.id ballotpaper_id
from stimmzettelgruppen join stimmzettel on stimmzettelgruppen.stimmzettel=stimmzettel.ridx;



create table if not exists unique_voter_session (
id varchar(36) primary key,
session_id varchar(50),
create_time datetime default current_timestamp
);

delimiter //

CREATE OR REPLACE TRIGGER voters_bi_completed
AFTER INSERT
   ON voters FOR EACH ROW
BEGIN
  IF EXISTS(select voter_id from voters where voter_id=new.voter_id and stimmzettel=new.stimmzettel and completed=1) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'allready voted';
  END IF;
END //




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
END //

delimiter ; 

insert ignore into  system_settings (system_settings_id) values 
	('remote-erp/public'),
  ('remote-erp/url'),
  ('remote-erp/token') 
;
insert ignore into system_settings_user_access(system_settings_id,groupname) values ('remote-erp/public','administration');
insert ignore into system_settings_user_access(system_settings_id,groupname) values ('remote-erp/url','administration');
insert ignore into system_settings_user_access(system_settings_id,groupname) values ('remote-erp/token','administration');