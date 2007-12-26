DROP TABLE IF EXISTS `phpboost_news`;
CREATE TABLE `phpboost_news` (
	`id` int(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`title` varchar(100) NOT NULL default '',
	`contents` text NOT NULL,
	`extend_contents` text NOT NULL,
	`archive` tinyint(1) NOT NULL default '0',
	`timestamp` int(11) NOT NULL default '0',
	`visible` tinyint(1) NOT NULL default '0',
	`start` int(11) NOT NULL default '0',
	`end` int(11) NOT NULL default '0',
	`user_id` int(11) NOT NULL default '0',
	`img` varchar(255) NOT NULL default '',
	`alt` varchar(255) NOT NULL default '',
	`nbr_com` int(11) unsigned NOT NULL default '0',
	`lock_com` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `idcat` (`idcat`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_news_cat`;
CREATE TABLE `phpboost_news_cat` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(150) NOT NULL default '',
	`contents` text NOT NULL,
	`icon` varchar(255) NOT NULL default '',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_news_cat` (`id`, `name`, `contents`, `icon`) VALUES (1, 'Test', 'Cat&eacute;gorie de test', 'news.png');
INSERT INTO `phpboost_news` (`id`, `idcat`, `title`, `contents`, `extend_contents`, `archive`, `timestamp`, `visible`, `start`, `end`, `user_id`, `img`, `alt`, `nbr_com`, `lock_com`) VALUES (1, 1, 'Test', 'News de test', 'Suite de la news de test', 0, unix_timestamp(current_timestamp), 1, 0, 0, 1, '', '', 0, 0);
INSERT INTO `phpboost_configs` (`name`, `value`) VALUES ('news', 'a:11:{s:4:"type";i:1;s:11:"activ_pagin";i:1;s:11:"activ_edito";i:1;s:15:"pagination_news";i:5;s:15:"pagination_arch";i:10;s:9:"activ_com";i:1;s:10:"activ_icon";i:1;s:8:"nbr_news";s:1:"0";s:10:"nbr_column";i:1;s:5:"edito";s:22:"Bienvenue sur le site!";s:11:"edito_title";s:22:"Bienvenue sur le site!";}');