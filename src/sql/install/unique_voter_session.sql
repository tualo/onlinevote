DELIMITER ;
CREATE TABLE IF NOT EXISTS `unique_voter_session` (
  `id` varchar(36) NOT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `create_time` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ;
