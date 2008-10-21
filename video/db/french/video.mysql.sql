DROP TABLE IF EXISTS `phpboost_video`;
CREATE TABLE `phpboost_video` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '0',
  `contents` text NOT NULL,
  `url` text NOT NULL,
  `infos` smallint(6) NOT NULL default '0',
  `counter` int(11) unsigned NOT NULL default '0',
  `users_note` text NOT NULL,
  `nbrnote` int(11) unsigned NOT NULL default '0',
  `note` float NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_video_cat`;
CREATE TABLE `phpboost_video_cat` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `c_order` int(11) unsigned NOT NULL default '0',
  `auth` text NOT NULL,
  `name` varchar(255) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '0',
  `display_mode` tinyint(2) NOT NULL default '0',
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL default '',
  `num_video` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;