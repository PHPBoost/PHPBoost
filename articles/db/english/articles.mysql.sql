DROP TABLE IF EXISTS `phpboost_articles_cats`;
CREATE TABLE `phpboost_articles_cats` (
	`id` int(11) NOT NULL auto_increment,
	`id_left` int(11) NOT NULL default '0',
	`id_right` int(11) NOT NULL default '0',
	`level` int(11) NOT NULL default '0',
	`name` varchar(100) NOT NULL default '',
	`contents` text,
	`nbr_articles_visible` mediumint(9) unsigned NOT NULL default '0',
	`nbr_articles_unvisible` mediumint(9) unsigned NOT NULL default '0',
	`icon` varchar(255) NOT NULL default '',
	`aprob` tinyint(1) NOT NULL default '0',
	`auth` text,
	PRIMARY KEY  (`id`),
	KEY `id_left` (`id_left`)
) ENGINE=MyISAM;

INSERT INTO phpboost_articles_cats (`id`, `id_left`, `id_right`, `level`, `name`, `contents`, `nbr_articles_visible`, `nbr_articles_unvisible`, `icon`, `aprob`, `auth`) VALUES ('1', '1', '2', '0', 'Test category', 'Demonstration articles', '0', '0', 'articles.png', '1', 'a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}');

DROP TABLE IF EXISTS `phpboost_articles`;
CREATE TABLE `phpboost_articles` (
	`id` int(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`title` varchar(100) NOT NULL default '',
	`contents` mediumtext NOT NULL,
	`icon` varchar(255) NOT NULL default '',
	`timestamp` int(11) NOT NULL default '0',
	`visible` tinyint(1) NOT NULL default '0',
	`start` int(11) NOT NULL default '0',
	`end` int(11) NOT NULL default '0',
	`user_id` int(11) NOT NULL default '0',
	`views` mediumint(9) NOT NULL default '0',
	`users_note` text,
	`nbrnote` mediumint(9) NOT NULL default '0',
	`note` float NOT NULL default '0',
	`nbr_com` int(11) unsigned NOT NULL default '0',
	`lock_com` tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	KEY `idcat` (`idcat`),
    FULLTEXT KEY `title` (`title`),
    FULLTEXT KEY `contents` (`contents`)
) ENGINE=MyISAM;
