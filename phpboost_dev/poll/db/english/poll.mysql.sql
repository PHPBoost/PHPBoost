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

INSERT INTO phpboost_poll (`question`, `answers`, `votes`, `type`, `archive`, `timestamp`, `visible`, `start`, `end`, `user_id`) VALUES ('Do you like our website?', 'Very Good|Good|Average|Bad|Very Bad', '|||', '1', '0', unix_timestamp(current_timestamp), '1', '0', '0', '1');