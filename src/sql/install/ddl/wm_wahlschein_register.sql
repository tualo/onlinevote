DELIMITER ;
CREATE TABLE IF NOT EXISTS `wm_wahlschein_register` (
  `wahlscheinnummer` varchar(10) NOT NULL,
  `birthdate_year` varchar(4) NOT NULL,
  `birthdate_month` varchar(4) NOT NULL,
  `birthdate_day` varchar(4) NOT NULL,
  `phone_lc` varchar(5) DEFAULT NULL,
  `phone_number` varchar(25) DEFAULT NULL,
  `pin` varchar(10) DEFAULT NULL,
  `createdate` datetime NOT NULL,
  `sms_response` varchar(255) DEFAULT NULL,
  `tmg_token` varchar(255) DEFAULT NULL,
  `person` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`wahlscheinnummer`)
) ;
 
DELIMITER //

CREATE OR REPLACE TRIGGER `wm_wahlschein_register__ai` AFTER INSERT ON `wm_wahlschein_register` FOR EACH ROW
BEGIN
DECLARE uu_id varchar(36);
SET uu_id = ifnull(@useuuid,uuid());

if ( (@use_hstr_trigger=1) or (@use_hstr_trigger is null) ) THEN
  INSERT INTO `wm_wahlschein_register_hstr`
  (
    hstr_sessionuser,
    hstr_action,
    hstr_revision,
    `wahlscheinnummer`,`birthdate_year`,`birthdate_month`,`birthdate_day`,`phone_lc`,`phone_number`,`pin`,`createdate`,`sms_response`,`tmg_token`,`person`
  )

    SELECT
    ifnull(@sessionuser,'not set'),
    'insert',
    uu_id,
    `wahlscheinnummer`,`birthdate_year`,`birthdate_month`,`birthdate_day`,`phone_lc`,`phone_number`,`pin`,`createdate`,`sms_response`,`tmg_token`,`person`
  FROM
    `wm_wahlschein_register`
  WHERE
    `wahlscheinnummer`=NEW.wahlscheinnummer
  on duplicate key update hstr_action=values(hstr_action),hstr_revision=values(hstr_revision),hstr_datetime=values(hstr_datetime), `wahlscheinnummer`=values(`wahlscheinnummer`), `birthdate_year`=values(`birthdate_year`), `birthdate_month`=values(`birthdate_month`), `birthdate_day`=values(`birthdate_day`), `phone_lc`=values(`phone_lc`), `phone_number`=values(`phone_number`), `pin`=values(`pin`), `createdate`=values(`createdate`), `sms_response`=values(`sms_response`), `tmg_token`=values(`tmg_token`), `person`=values(`person`)
  ;
  END IF;
END //


CREATE OR REPLACE TRIGGER `wm_wahlschein_register__au` AFTER UPDATE ON `wm_wahlschein_register` FOR EACH ROW
      BEGIN
      DECLARE uu_id varchar(36);
        SET uu_id = ifnull(@useuuid,uuid());

      if ( (@use_hstr_trigger=1) or (@use_hstr_trigger is null) ) THEN
        INSERT INTO `wm_wahlschein_register_hstr`
        (
          hstr_sessionuser,
          hstr_action,
          hstr_revision,
          `wahlscheinnummer`,`birthdate_year`,`birthdate_month`,`birthdate_day`,`phone_lc`,`phone_number`,`pin`,`createdate`,`sms_response`,`tmg_token`,`person`
        )

         SELECT
          ifnull(@sessionuser,'not set'),
          'update',
          uu_id,
          `wahlscheinnummer`,`birthdate_year`,`birthdate_month`,`birthdate_day`,`phone_lc`,`phone_number`,`pin`,`createdate`,`sms_response`,`tmg_token`,`person`
        FROM
          `wm_wahlschein_register`
        WHERE
          `wahlscheinnummer`=NEW.wahlscheinnummer
        on duplicate key update hstr_action=values(hstr_action),hstr_revision=values(hstr_revision),hstr_datetime=values(hstr_datetime), `wahlscheinnummer`=values(`wahlscheinnummer`), `birthdate_year`=values(`birthdate_year`), `birthdate_month`=values(`birthdate_month`), `birthdate_day`=values(`birthdate_day`), `phone_lc`=values(`phone_lc`), `phone_number`=values(`phone_number`), `pin`=values(`pin`), `createdate`=values(`createdate`), `sms_response`=values(`sms_response`), `tmg_token`=values(`tmg_token`), `person`=values(`person`)
        ;
        END IF;
END //

CREATE OR REPLACE TRIGGER `wm_wahlschein_register__bd` BEFORE DELETE ON `wm_wahlschein_register` FOR EACH ROW
      BEGIN
      DECLARE uu_id varchar(36);
      SET uu_id = ifnull(@useuuid,uuid());

      if ( (@use_hstr_trigger=1) or (@use_hstr_trigger is null) ) THEN
        INSERT INTO `wm_wahlschein_register_hstr`
        (
          hstr_sessionuser,
          hstr_action,
          hstr_revision,
          `wahlscheinnummer`,`birthdate_year`,`birthdate_month`,`birthdate_day`,`phone_lc`,`phone_number`,`pin`,`createdate`,`sms_response`,`tmg_token`,`person`
        )

         SELECT
          ifnull(@sessionuser,'not set'),
          'delete',
          uu_id,
          `wahlscheinnummer`,`birthdate_year`,`birthdate_month`,`birthdate_day`,`phone_lc`,`phone_number`,`pin`,`createdate`,`sms_response`,`tmg_token`,`person`
        FROM
          `wm_wahlschein_register`
        WHERE
          `wahlscheinnummer`=OLD.wahlscheinnummer
        on duplicate key update hstr_action=values(hstr_action),hstr_revision=values(hstr_revision),hstr_datetime=values(hstr_datetime), `wahlscheinnummer`=values(`wahlscheinnummer`), `birthdate_year`=values(`birthdate_year`), `birthdate_month`=values(`birthdate_month`), `birthdate_day`=values(`birthdate_day`), `phone_lc`=values(`phone_lc`), `phone_number`=values(`phone_number`), `pin`=values(`pin`), `createdate`=values(`createdate`), `sms_response`=values(`sms_response`), `tmg_token`=values(`tmg_token`), `person`=values(`person`)
        ;
        END IF;
END //