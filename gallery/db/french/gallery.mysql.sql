DROP TABLE IF EXISTS `phpboost_gallery`;
CREATE TABLE `phpboost_gallery` (
	`id` int(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`name` varchar(255) NOT NULL default '',
	`path` varchar(255) NOT NULL default '',
	`width` mediumint(9) NOT NULL default '0',
	`height` mediumint(9) NOT NULL default '0',
	`weight` mediumint(9) NOT NULL default '0',
	`user_id` int(11) NOT NULL default '0',
	`aprob` tinyint(1) NOT NULL default '0',
	`views` int(11) NOT NULL default '0',
	`timestamp` int(11) NOT NULL default '0',
	`users_note` text,
	`nbrnote` mediumint(9) NOT NULL default '0',
	`note` float NOT NULL default '0',
	`nbr_com` int(11) unsigned NOT NULL default '0',
	`lock_com` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `idcat` (`idcat`)
) ENGINE=MyISAM;
INSERT INTO `phpboost_gallery` VALUES (1, 1, 'PHPBoost 3!', 'phpboost3.jpg', 320, 264, 15614, 1, 1, 0, unix_timestamp(current_timestamp), '', 0, 0, 0, 0);

DROP TABLE IF EXISTS `phpboost_gallery_cats`;
CREATE TABLE `phpboost_gallery_cats` (
	`id` int(11) NOT NULL auto_increment,
	`id_left` int(11) NOT NULL default '0',
	`id_right` int(11) NOT NULL default '0',
	`level` int(11) NOT NULL default '0',
	`name` varchar(150) NOT NULL default '',
	`contents` text,
	`nbr_pics_aprob` mediumint(9) unsigned NOT NULL default '0',
	`nbr_pics_unaprob` mediumint(9) unsigned NOT NULL default '0',
	`status` tinyint(1) NOT NULL default '0',
	`aprob` tinyint(1) NOT NULL default '1',
	`auth` text,
	PRIMARY KEY	(`id`),
	KEY `id_left` (`id_left`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_gallery_cats` VALUES (1, 1, 2, 0, 'Test', 'Galerie de test', 1, 0, 1, 1, 'a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:3;}');