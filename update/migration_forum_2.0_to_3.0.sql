ALTER TABLE `phpboost_forum_cats` DROP `type`;
ALTER TABLE `phpboost_forum_cats` ADD `url` varchar(255) NOT NULL AFTER `auth` ;

ALTER TABLE `phpboost_forum_history` CHANGE `action` `action` varchar(50) NOT NULL DEFAULT '';
ALTER TABLE `phpboost_forum_history` ADD `user_id_action` int(11) NOT NULL DEFAULT '0' AFTER `user_id` ;
ALTER TABLE `phpboost_forum_history` ADD `url` varchar(255) NOT NULL AFTER `user_id_action` ;

ALTER TABLE `phpboost_forum_msg` ADD `user_ip` varchar(50) NOT NULL DEFAULT '' AFTER `user_id_edit` ;

ALTER TABLE `phpboost_forum_topics` CHANGE `nbr_vus` `nbr_views`  mediumint(9) NOT NULL DEFAULT '0';

ALTER TABLE `phpboost_forum_track` ADD `track` tinyint(1) NOT NULL DEFAULT '0' AFTER `user_id` ;
UPDATE `phpboost_forum_track` SET `track` = 1;