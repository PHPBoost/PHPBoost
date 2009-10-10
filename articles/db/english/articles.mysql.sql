DROP TABLE IF EXISTS phpboost_articles, phpboost_articles_cats;

CREATE TABLE `phpboost_articles` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `description` text,
  `contents` mediumtext NOT NULL,
  `sources` text,
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
  `auth` text,
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

CREATE TABLE `phpboost_articles_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `c_order` int(11) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `tpl_articles` varchar(100) NOT NULL default 'articles.tpl',
  `tpl_cat` varchar(100) NOT NULL default 'articles_cat.tpl',
  `description` text,
  `nbr_articles_visible` mediumint(9) unsigned NOT NULL default '0',
  `nbr_articles_unvisible` mediumint(9) unsigned NOT NULL default '0',
  `image` varchar(255) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '0',
  `auth` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

