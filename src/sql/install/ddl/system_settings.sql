DELIMITER ;
CREATE TABLE IF NOT EXISTS `system_settings` (
  `system_settings_id` varchar(50) NOT NULL,
  `min_quorum` int(11) DEFAULT 1,
  `property` text DEFAULT NULL,
  `label` varchar(255) DEFAULT 'no label',
  `datatype` varchar(255) DEFAULT 'string',
  `editor` varchar(255) DEFAULT 'textfield',
  PRIMARY KEY (`system_settings_id`)
) ;

DELIMITER //
CREATE OR REPLACE TRIGGER `trigger_system_settings_bi`
BEFORE INSERT
   ON `system_settings` FOR EACH ROW
BEGIN

  IF not canChangeValue(NEW.system_settings_id) THEN
    SIGNAL SQLSTATE '45000' set message_text = 'Die Einstellung darf nicht bearbeitet werden (fehlendes Recht)';
  END IF;
  
END //


CREATE OR REPLACE TRIGGER `trigger_system_settings_bu`
BEFORE UPDATE
   ON `system_settings` FOR EACH ROW
BEGIN

  IF not canChangeValue(NEW.system_settings_id) THEN
    SIGNAL SQLSTATE '45000' set message_text = 'Die Einstellung darf nicht bearbeitet werden (fehlendes Recht)';
  END IF;
  
  SET @trigger_calling = '1';


  DELETE FROM system_settings_suggestion WHERE system_settings_id=new.system_settings_id and  username=getSessionUser();

  INSERT INTO system_settings_suggestion (
    suggestion_id,
    username,
    inserttime,
    system_settings_id,
    property
  ) values (
    uuid(),
    getSessionUser(),
    now(),
    new.system_settings_id,
    new.property
  );
  
  
  IF EXISTS (
    SELECT 
      system_settings.system_settings_id,
      system_settings.min_quorum,
      count(*) c
    FROM 
      system_settings
      JOIN system_settings_suggestion
      ON system_settings.system_settings_id = system_settings_suggestion.system_settings_id
      and system_settings_suggestion.property = new.property
    GROUP BY 
      system_settings_suggestion.system_settings_id,
      system_settings_suggestion.property
    having min_quorum>c
  ) THEN

    SET NEW.property = OLD.property;

    SET @warning='Die nötige Mehrheit für die Änderung ist noch nicht erreicht. Ihre Einstellung wurde daher vorgemerkt.';
    

  ELSE
    SET @trigger_calling='1';
    DELETE FROM system_settings_suggestion WHERE system_settings_id=NEW.system_settings_id;
  END IF;

END //
