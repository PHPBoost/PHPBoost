DROP TABLE IF EXISTS `phpboost_wiki_articles`;
CREATE TABLE `phpboost_wiki_articles` (
	`id` int(11) NOT NULL auto_increment,
	`id_contents` int(11) NOT NULL default '0',
	`title` varchar(250) NOT NULL default '',
	`encoded_title` varchar(250) NOT NULL default '',
	`hits` int(11) NOT NULL default '0',
	`id_cat` int(11) NOT NULL default '0',
	`is_cat` tinyint(1) NOT NULL default '0',
	`defined_status` smallint(6) NOT NULL default '0',
	`undefined_status` text,
	`redirect` int(11) NOT NULL default '0',
	`auth` text,
	`nbr_com` int(11) unsigned NOT NULL default '0',
	`lock_com` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`encoded_title`),
	KEY `id` (`id`),
	FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_wiki_cats`;
CREATE TABLE `phpboost_wiki_cats` (
	`id` int(11) NOT NULL auto_increment,
	`id_parent` int(11) NOT NULL default '0',
	`article_id` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_wiki_contents`;
CREATE TABLE `phpboost_wiki_contents` (
	`id_contents` int(11) NOT NULL auto_increment,
	`id_article` int(11) NOT NULL default '0',
	`menu` text,
	`content` mediumtext,
	`activ` tinyint(1) NOT NULL default '0',
	`user_id` int(11) NOT NULL default '0',
	`user_ip` varchar(50) NOT NULL default '',
	`timestamp` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id_contents`),
	FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_wiki_favorites`;
CREATE TABLE `phpboost_wiki_favorites` (
	`id` int(11) NOT NULL auto_increment,
	`user_id` int(11) NOT NULL default '0',
	`id_article` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;
