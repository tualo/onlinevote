DELIMITER //
CREATE TABLE IF NOT EXISTS `voters` (
  `voter_id` bigint NOT NULL,
  `stimmzettel` int NOT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `completed` tinyint(4) DEFAULT 0,
  `comitted` tinyint(4) DEFAULT 0,
  `contact` datetime DEFAULT current_timestamp,
  PRIMARY KEY (`voter_id`,`stimmzettel`),
  KEY `idx_voters_voter_id_session_id` (`voter_id`,`session_id`)
) //




CREATE or replace TRIGGER voters_bi_completed
AFTER INSERT
   ON voters FOR EACH ROW
BEGIN
  IF EXISTS(select voter_id from voters where voter_id=new.voter_id and stimmzettel=new.stimmzettel and completed=1) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'allready voted';
  END IF;
END //




