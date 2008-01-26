DROP TABLE IF EXISTS `phpboost_poll`;
CREATE TABLE `phpboost_poll` (
	`id` int(11) NOT NULL auto_increment,
	`question` varchar(255) NOT NULL default '',
	`answers` text NOT NULL,
	`votes` text NOT NULL,
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
	`idpoll` int(11) NOT NULL default '0',
	`timestamp` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_configs` (`name`, `value`) VALUES ('poll', 'a:4:{s:9:"poll_auth";i:-1;s:9:"poll_mini";a:0:{}s:11:"poll_cookie";s:4:"poll";s:18:"poll_cookie_lenght";i:2592000;}');
INSERT INTO `phpboost_modules_mini` (`name`, `code`, `contents`, `side`, `secure`, `activ`, `added`) VALUES ('poll', 'include_once(''../poll/poll_mini.php'');', '', 1, -1, 1, 0);
