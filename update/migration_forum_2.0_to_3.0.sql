ALTER TABLE `phpboost_forum_cats` ADD `url` varchar(255) NOT NULL AFTER `auth` ;
ALTER TABLE `phpboost_forum_cats` CHANGE `auth` `auth` text;

ALTER TABLE `phpboost_forum_history` CHANGE `action` `action` varchar(50) NOT NULL DEFAULT '';
ALTER TABLE `phpboost_forum_history` ADD `user_id_action` int(11) NOT NULL DEFAULT '0' AFTER `user_id` ;
ALTER TABLE `phpboost_forum_history` ADD `url` varchar(255) NOT NULL AFTER `user_id_action` ;

ALTER TABLE `phpboost_forum_msg` CHANGE `contents` `contents` text;

ALTER TABLE `phpboost_forum_alerts` CHANGE `contents` `contents` text;

ALTER TABLE `phpboost_forum_poll` CHANGE `answers` `answers` text;
ALTER TABLE `phpboost_forum_poll` CHANGE `voter_id` `voter_id` text;
ALTER TABLE `phpboost_forum_poll` CHANGE `votes` `votes` text;

ALTER TABLE `phpboost_forum_track` ADD `track` tinyint(1) NOT NULL DEFAULT '0' AFTER `user_id` ;
UPDATE `phpboost_forum_track` SET `track` = 1;