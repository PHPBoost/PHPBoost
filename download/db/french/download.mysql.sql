DROP TABLE IF EXISTS `phpboost_download_cat`;
CREATE TABLE `phpboost_download_cat` (
	`id` int(11) NOT NULL auto_increment,
	`class` int(11) NOT NULL default '0',
	`name` varchar(150) NOT NULL default '',
	`contents` text NOT NULL,
	`icon` varchar(255) NOT NULL default '',
	`aprob` tinyint(1) NOT NULL default '0',
	`secure` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `class` (`class`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_download`;
CREATE TABLE `phpboost_download` (
	`id` int(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`title` varchar(100) NOT NULL default '',
	`contents` text NOT NULL,
	`url` text NOT NULL,
	`size` float NOT NULL default '0',
	`compt` int(11) NOT NULL default '0',
	`timestamp` int(11) NOT NULL default '0',
	`visible` tinyint(1) NOT NULL default '0',
	`start` int(11) NOT NULL default '0',
	`end` int(11) NOT NULL default '0',
	`user_id` int(11) NOT NULL default '0',
	`users_note` text NOT NULL,
	`nbrnote` mediumint(9) NOT NULL default '0',
	`note` smallint(6) NOT NULL default '0',
	`nbr_com` int(11) unsigned NOT NULL default '0',
	`lock_com` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `idcat` (`idcat`)
) ENGINE=MyISAM;
