DROP TABLE IF EXISTS `phpboost_poll`;
CREATE TABLE `phpboost_poll` (
	`id` int(11) NOT NULL auto_increment,
	`question` varchar(255) NOT NULL default '',
	`answers` text,
	`votes` text,
	`type` tinyint(1) NOT NULL default '0',
	`archive` tinyint(1) NOT NULL default '0',
	`timestamp` int(11) NOT NULL default '0',
	`visible` tinyint(1) NOT NULL default '0',
	`start` int(11) NOT NULL default '0',
	`end` int(11) NOT NULL default '0',
	`user_id` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_poll_ip`;
CREATE TABLE `phpboost_poll_ip` (
	`id` int(11) NOT NULL auto_increment,
	`ip` varchar(50) NOT NULL default '',
	`user_id` int(11) NOT NULL default '0',
	`idpoll` int(11) NOT NULL default '0',
	`timestamp` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;
