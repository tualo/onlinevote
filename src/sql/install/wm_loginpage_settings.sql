DELIMITER  //
CREATE TABLE IF NOT EXISTS `wm_loginpage_settings` (
  `id` int(11) NOT NULL,
  `wsregistertoken` varchar(150) DEFAULT NULL,
  `wsapitoken` varchar(150) DEFAULT NULL,
  `smsauth` varchar(150) DEFAULT NULL,
  `tmgurl` varchar(150) DEFAULT NULL,
  `index_redirect` varchar(150) DEFAULT NULL,
  `sendsms` tinyint(4) DEFAULT 1,
  `backendurl` varchar(150) DEFAULT NULL,
  `wszapitoken` varchar(50) DEFAULT NULL,
  `starttime` datetime DEFAULT '2020-01-01 10:10:10',
  `stoptime` datetime DEFAULT '2020-01-01 10:10:10',
  `interrupted` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`)
) ;

CREATE OR REPLACE TRIGGER `trigger_wm_loginpage_settings_phase_check` BEFORE UPDATE ON `wm_loginpage_settings`
FOR EACH ROW
BEGIN
    
    if (exists(select phase from voting_state where phase in ('production_phase','council_phase'))) then
        if OLD.starttime  <> NEW.starttime  THEN
              signal sqlstate '45000' set message_text = 'Cannot update wm_loginpage_settings from production_phase, from trigger trigger_wm_loginpage_settings_phase_check';
        END IF;

        if OLD.stoptime  <> NEW.stoptime  THEN
              signal sqlstate '45000' set message_text = 'Cannot update wm_loginpage_settings from production_phase, from trigger trigger_wm_loginpage_settings_phase_check';
        END IF;

    end if;

    if NEW.starttime  >= NEW.stoptime  THEN
        signal sqlstate '45000' set message_text = 'Invalid time range';
    END IF;
    if NEW.starttime < NOW() THEN
        signal sqlstate '45000' set message_text = 'Start time must be in the future';
    END IF;
END //