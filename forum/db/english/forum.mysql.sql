DROP TABLE IF EXISTS `phpboost_forum_alerts`;
CREATE TABLE `phpboost_forum_alerts` (
	`id` mediumint(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`idtopic` int(11) NOT NULL default '0',
	`title` varchar(255) NOT NULL default '',
	`contents` text,
	`user_id` int(11) NOT NULL default '0',
	`status` tinyint(1) NOT NULL default '0',
	`idmodo` int(11) NOT NULL default '0',
	`timestamp` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `idtopic` (`idtopic`,`user_id`,`idmodo`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_forum_cats`;
CREATE TABLE `phpboost_forum_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_left` int(11) NOT NULL default '0',
  `id_right` int(11) NOT NULL default '0',
  `level` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `subname` varchar(150) NOT NULL default '',
  `nbr_topic` mediumint(9) NOT NULL default '0',
  `nbr_msg` mediumint(9) NOT NULL default '0',
  `last_topic_id` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  `aprob` tinyint(1) NOT NULL default '0',
  `auth` text,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `last_topic_id` (`last_topic_id`),
  KEY `id_left` (`id_left`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_forum_history`;
CREATE TABLE `phpboost_forum_history` (
	`id` int(11) NOT NULL auto_increment,
	`action` varchar(50) NOT NULL default '',
	`user_id` int(11) NOT NULL default '0',
	`user_id_action` int(11) NOT NULL default '0',
	`url` varchar(255) NOT NULL,
	`timestamp` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `user_id` (`user_id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_forum_msg`;
CREATE TABLE `phpboost_forum_msg` (
	`id` int(11) NOT NULL auto_increment,
	`idtopic` int(11) NOT NULL default '0',
	`user_id` mediumint(9) NOT NULL default '0',
	`contents` text,
	`timestamp` int(11) NOT NULL default '0',
	`timestamp_edit` int(11) NOT NULL default '0',
	`user_id_edit` int(11) NOT NULL default '0',
	`user_ip` varchar(50) NOT NULL default '',
	PRIMARY KEY	(`id`),
	KEY `idtopic` (`idtopic`,`user_id`,`timestamp`),
	FULLTEXT KEY `contenu` (`contents`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_forum_poll`;
CREATE TABLE `phpboost_forum_poll` (
	`id` int(11) NOT NULL auto_increment,
	`idtopic` int(11) NOT NULL default '0',
	`question` varchar(255) NOT NULL default '',
	`answers` text,
	`voter_id` text,
	`votes` text,
	`type` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	UNIQUE KEY `idtopic` (`idtopic`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_forum_topics`;
CREATE TABLE `phpboost_forum_topics` (
	`id` int(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`title` varchar(100) NOT NULL default '',
	`subtitle` varchar(75) NOT NULL default '',
	`user_id` int(11) NOT NULL default '0',
	`nbr_msg` mediumint(9) NOT NULL default '0',
	`nbr_views` mediumint(9) NOT NULL default '0',
	`last_user_id` int(11) NOT NULL default '0',
	`last_msg_id` int(11) NOT NULL default '0',
	`last_timestamp` int(11) NOT NULL default '0',
	`first_msg_id` int(11) NOT NULL default '0',
	`type` tinyint(1) NOT NULL default '0',
	`status` tinyint(1) NOT NULL default '0',
	`aprob` tinyint(1) NOT NULL default '0',
	`display_msg` tinyint(1) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `idcat` (`idcat`,`last_user_id`,`last_timestamp`,`type`),
	FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_forum_track`;
CREATE TABLE `phpboost_forum_track` (
	`id` int(11) NOT NULL auto_increment,
	`idtopic` int(11) NOT NULL default '0',
	`user_id` int(11) NOT NULL default '0',
	`track` tinyint(1) NOT NULL default '0',
	`pm` tinyint(1) NOT NULL default '0',
	`mail` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	UNIQUE KEY `idtopic` (`idtopic`,`user_id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_forum_view`;
CREATE TABLE `phpboost_forum_view` (
	`id` int(11) NOT NULL auto_increment,
	`idtopic` int(11) NOT NULL default '0',
	`last_view_id` int(11) NOT NULL default '0',
	`user_id` int(11) NOT NULL default '0',
	`timestamp` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `idv` (`idtopic`,`user_id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_forum_cats` (`id`, `id_left`, `id_right`, `level`, `name`, `subname`, `nbr_topic`, `nbr_msg`, `last_topic_id`, `status`, `aprob`, `auth`, `url`) VALUES (1, 1, 4, 0, 'Test category', 'Test category', 1, 1, 1, 1, 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:7;}', '');
INSERT INTO `phpboost_forum_cats` (`id`, `id_left`, `id_right`, `level`, `name`, `subname`, `nbr_topic`, `nbr_msg`, `last_topic_id`, `status`, `aprob`, `auth`, `url`) VALUES (2, 2, 3, 1, 'Test forum', 'Test forum', 1, 1, 1, 1, 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}', '');
INSERT INTO `phpboost_forum_topics` (`id`, `idcat`, `title`, `subtitle`, `user_id`, `nbr_msg`, `nbr_views`, `last_user_id`, `last_msg_id`, `last_timestamp`, `first_msg_id`, `type`, `status`, `aprob`, `display_msg`) VALUES (1, 2, 'Test', 'Topic test', 1, 1, 0, 1, 1, unix_timestamp(current_timestamp), 1, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_msg` (`id`, `idtopic`, `user_id`, `contents`, `timestamp`, `timestamp_edit`, `user_id_edit`) VALUES (1, 1, 1, 'Test message on PHPBoost forum' , unix_timestamp(current_timestamp), 0, 0);

INSERT INTO `phpboost_member_extend_cat` (`class`, `name`, `field_name`, `contents`, `field`, `possible_values`, `default_values`, `required`, `display`, `regex`)  VALUES (0, 'last_view_forum', 'last_view_forum', '', 0, '', '', 0, 0, '');
ALTER TABLE `phpboost_member_extend` ADD `last_view_forum` INT( 11 ) NOT NULL DEFAULT '0' AFTER `user_id`;  
