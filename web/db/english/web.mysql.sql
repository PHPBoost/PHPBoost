DROP TABLE IF EXISTS `phpboost_web`;
CREATE TABLE `phpboost_web` (
	`id` int(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`title` varchar(100) NOT NULL default '',
	`contents` text,
	`url` text,
	`compt` int(11) NOT NULL default '0',
	`aprob` tinyint(1) NOT NULL default '1',
	`timestamp` int(11) NOT NULL default '0',
	`users_note` text,
	`nbrnote` mediumint(9) NOT NULL default '0',
	`note` float NOT NULL default '0',
	`nbr_com` int(11) unsigned NOT NULL default '0',
	`lock_com` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `idcat` (`idcat`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_web_cat`;
CREATE TABLE `phpboost_web_cat` (
	`id` int(11) NOT NULL auto_increment,
	`class` int(11) NOT NULL default '0',
	`name` varchar(150) NOT NULL default '',
	`contents` text,
	`icon` varchar(255) NOT NULL default '',
	`aprob` tinyint(1) NOT NULL default '0',
	`secure` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `class` (`class`)
) ENGINE=MyISAM;
