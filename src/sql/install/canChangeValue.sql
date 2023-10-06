DELIMITER //
CREATE  OR REPLACE FUNCTION `canChangeValue`(in_system_settings_id varchar(50) ) RETURNS tinyint(1)
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