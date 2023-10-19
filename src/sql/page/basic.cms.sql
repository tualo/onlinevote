DELIMITER ;

INSERT IGNORE INTO `tualocms_page` VALUES
('2236196e-69b7-11ee-bc7c-c6832147e485','/','page','2023-10-13 12:56:00','2099-12-31 23:59:59','Wahl','..');
INSERT IGNORE INTO `tualocms_section` VALUES
('15b53742-69b7-11ee-bc7c-c6832147e485','Wahl','...','2023-01-01 00:00:00','2023-12-31 23:59:59','page-onlinevote');
INSERT IGNORE INTO `tualocms_section_tualocms_page` VALUES
('2236196e-69b7-11ee-bc7c-c6832147e485','15b53742-69b7-11ee-bc7c-c6832147e485',1,'2023-01-01 00:00:00','2099-12-21 23:59:59');
INSERT IGNORE INTO `tualocms_page_middleware` VALUES
('2236196e-69b7-11ee-bc7c-c6832147e485','\\Tualo\\Office\\CMS\\CMSMiddleware\\Markdown',0,'2023-01-01 00:00:00','2099-12-31 23:59:59'),
('2236196e-69b7-11ee-bc7c-c6832147e485','\\Tualo\\Office\\CMS\\CMSMiddleware\\Request',1,'2023-01-01 00:00:00','2099-12-31 23:59:59'),
('2236196e-69b7-11ee-bc7c-c6832147e485','\\Tualo\\Office\\CMS\\CMSMiddleware\\Session',2,'2023-01-01 00:00:00','2099-12-31 23:59:59'),
('2236196e-69b7-11ee-bc7c-c6832147e485','\\Tualo\\Office\\OnlineVote\\CMSMiddleware\\Init',4,'2023-01-01 00:00:00','2099-12-31 23:59:59'),
('2236196e-69b7-11ee-bc7c-c6832147e485','\\Tualo\\Office\\OnlineVote\\CMSMiddleware\\InitApiUse',3,'2023-01-01 00:00:00','2099-12-31 23:59:59');