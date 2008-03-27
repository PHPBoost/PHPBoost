DROP TABLE IF EXISTS `phpboost_shoutbox`;
CREATE TABLE `phpboost_shoutbox` (
	`id` int(11) NOT NULL auto_increment,
	`login` varchar(150) NOT NULL default '',
	`user_id` int(11) NOT NULL default '0',
	`contents` text NOT NULL,
	`timestamp` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM;
