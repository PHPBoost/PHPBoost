DROP TABLE IF EXISTS `phpboost_pages`;
CREATE TABLE `phpboost_pages` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `encoded_title` varchar(255) NOT NULL default '',
  `contents` mediumtext,
  `auth` text,
  `is_cat` tinyint(1) NOT NULL default '0',
  `id_cat` int(11) NOT NULL default '0',
  `hits` int(11) NOT NULL default '0',
  `count_hits` tinyint(1) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `activ_com` tinyint(1) NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  `nbr_com` int(11) NOT NULL default '0',
  `redirect` int(11) NOT NULL default '0',
  PRIMARY KEY  (`encoded_title`),
  KEY `id` (`id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`),
  FULLTEXT KEY `all` (`title`,`contents`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_pages_cats`;
CREATE TABLE `phpboost_pages_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_page` int(11) NOT NULL default '0',
  `id_parent` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) ENGINE=MyISAM;
