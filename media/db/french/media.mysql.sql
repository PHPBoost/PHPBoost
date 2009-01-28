DROP TABLE IF EXISTS `phpboost_media`;
CREATE TABLE `phpboost_media` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `iduser` int(11) unsigned NOT NULL default '1',
  `timestamp` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '0',
  `contents` text NOT NULL,
  `url` text NOT NULL,
  `mime_type` varchar(255) NOT NULL default '0',
  `infos` smallint(6) NOT NULL default '0',
  `width` mediumint(9) unsigned NOT NULL default '100',
  `height` mediumint(9) unsigned NOT NULL default '100',
  `counter` int(11) unsigned NOT NULL default '0',
  `users_note` text NOT NULL,
  `nbrnote` int(11) unsigned NOT NULL default '0',
  `note` float NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `contents` (`contents`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `phpboost_media_cat`;
CREATE TABLE `phpboost_media_cat` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `c_order` int(11) unsigned NOT NULL default '0',
  `auth` text NOT NULL,
  `name` varchar(255) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '0',
  `mime_type` tinyint(1) unsigned NOT NULL default '0',
  `active` int(11) unsigned NOT NULL default '0',
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL default '',
  `num_media` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;