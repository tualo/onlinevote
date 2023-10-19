DELIMITER ;

INSERT IGNORE INTO SESSIONDB.`macc_groups` VALUES
('wahl_administration',1,NULL,'unkategorisiert'),
('wahl_auswertungen',1,NULL,'unkategorisiert'),
('wahl_auszaehlung',1,NULL,'unkategorisiert'),
('wahl_nachzaehlung',1,NULL,'unkategorisiert'),
('wahl_ruecklauf',1,NULL,'unkategorisiert');

INSERT IGNORE INTO SESSIONDB.`macc_menu` VALUES
('0bd562a6-5b84-11ee-86a9-c6832147e485','Wahlbezirk','',NULL,'',5,NULL,'e101dd3a-5b82-11ee-86a9-c6832147e485',0,1,'fa fa-map','#ds/wahlbezirk'),
('173aa82e-6e3d-11ee-883c-c6832147e484','Onlinewahl-Einstellungen','',NULL,'',0,NULL,'',0,1,'fa fa-globe',''),
('28208bd4-5b84-11ee-86a9-c6832147e485','Abgabetyp','',NULL,'',1,NULL,'e101dd3a-5b82-11ee-86a9-c6832147e485',0,1,'entypo et-hand','#ds/abgabetyp'),
('2859604e-6e3f-11ee-883c-c6832147e484','Stimmzettelzeilen','',NULL,'',0,NULL,'173aa82e-6e3d-11ee-883c-c6832147e484',0,1,'fa fa-list','#ds/bp_row'),
('3244ea30-6e3d-11ee-883c-c6832147e484','Synchronisation','',NULL,'',0,NULL,'173aa82e-6e3d-11ee-883c-c6832147e484',0,1,'typcn typcn-arrow-sync','#onlinevote/syncform'),
('3fca8a32-5b84-11ee-86a9-c6832147e485','Wahlscheinstatus','',NULL,'',2,NULL,'e101dd3a-5b82-11ee-86a9-c6832147e485',0,1,'entypo et-text-document-inverted','#ds/wahlscheinstatus'),
('5c60bf04-5b84-11ee-86a9-c6832147e485','Wahltyp','',NULL,'',0,NULL,'e101dd3a-5b82-11ee-86a9-c6832147e485',0,1,'x-fa fa-circle','#ds/wahltyp'),
('705d1d7c-5b84-11ee-86a9-c6832147e485','Stimmzettelgruppen','',NULL,'',3,NULL,'e101dd3a-5b82-11ee-86a9-c6832147e485',0,1,'fa fa-file-text','#ds/stimmzettelgruppen'),
('8af26750-5b84-11ee-86a9-c6832147e485','Stimmzettel','',NULL,'',4,NULL,'e101dd3a-5b82-11ee-86a9-c6832147e485',0,1,'fa fa-file-text','#ds/stimmzettel'),
('a820c524-5b84-11ee-86a9-c6832147e485','Kandidaten','',NULL,'',7,NULL,'e101dd3a-5b82-11ee-86a9-c6832147e485',0,1,'fa fa-user','#ds/kandidaten'),
('e101dd3a-5b82-11ee-86a9-c6832147e485','Wahlgrundeinstellungen','',NULL,'',3,NULL,'',0,1,'fa fa-wrench',''),
('e332b13c-6e3e-11ee-883c-c6832147e484','Urnen','',NULL,'',0,NULL,'173aa82e-6e3d-11ee-883c-c6832147e484',0,1,'entypo et-box','#onlinevote/ballotbox'),
('f7d51756-5b83-11ee-86a9-c6832147e485','Wahlgruppen','',NULL,'',6,NULL,'e101dd3a-5b82-11ee-86a9-c6832147e485',0,1,'typcn typcn-group','#ds/wahlgruppe');


INSERT IGNORE INTO SESSIONDB.`rolle_menu` VALUES
('0bd562a6-5b84-11ee-86a9-c6832147e485','wahl_administration',NULL),
('173aa82e-6e3d-11ee-883c-c6832147e484','wahl_administration',NULL),
('28208bd4-5b84-11ee-86a9-c6832147e485','wahl_administration',NULL),
('2859604e-6e3f-11ee-883c-c6832147e484','wahl_administration',NULL),
('3244ea30-6e3d-11ee-883c-c6832147e484','wahl_administration',NULL),
('3fca8a32-5b84-11ee-86a9-c6832147e485','wahl_administration',NULL),
('5c60bf04-5b84-11ee-86a9-c6832147e485','wahl_administration',NULL),
('705d1d7c-5b84-11ee-86a9-c6832147e485','wahl_administration',NULL),

('8af26750-5b84-11ee-86a9-c6832147e485','wahl_administration',NULL),
('a820c524-5b84-11ee-86a9-c6832147e485','wahl_administration',NULL),
('e101dd3a-5b82-11ee-86a9-c6832147e485','wahl_administration',NULL),
('e332b13c-6e3e-11ee-883c-c6832147e484','wahl_administration',NULL),
('f7d51756-5b83-11ee-86a9-c6832147e485','wahl_administration',NULL);
