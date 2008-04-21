DROP TABLE IF EXISTS `phpboost_news`;
CREATE TABLE `phpboost_news` (
	`id` int(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`title` varchar(100) NOT NULL default '',
	`contents` text NOT NULL,
	`extend_contents` text NOT NULL,
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
	KEY `idcat` (`idcat`),
	FULLTEXT KEY `title` (`title`),
	FULLTEXT KEY `contents` (`contents`),
	FULLTEXT KEY `extend_contents` (`extend_contents`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_news_cat`;
CREATE TABLE `phpboost_news_cat` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(150) NOT NULL default '',
	`contents` text NOT NULL,
	`icon` varchar(255) NOT NULL default '',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_news_cat` (`id`, `name`, `contents`, `icon`) VALUES (1, 'Test', 'Test category', 'news.png');
INSERT INTO `phpboost_news` (`id`, `idcat`, `title`, `contents`, `extend_contents`, `timestamp`, `visible`, `start`, `end`, `user_id`, `img`, `alt`, `nbr_com`, `lock_com`) VALUES (1, 1, 'Test', 'Test of news', 'End of the news', unix_timestamp(current_timestamp), 1, 0, 0, 1, '', '', 0, 0);