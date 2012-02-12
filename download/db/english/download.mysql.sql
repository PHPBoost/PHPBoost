DROP TABLE IF EXISTS `phpboost_download_cat`;
CREATE TABLE `phpboost_download_cat` (
  `id` int(11) NOT NULL auto_increment,
  `c_order` int(11) NOT NULL default '0',
  `id_parent` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL default '',
  `contents` text,
  `icon` varchar(255) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '1',
  `auth` text,
  `num_files` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `class` (`c_order`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_download`;
CREATE TABLE `phpboost_download` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `short_contents` text,
  `contents` text,
  `url` text,
  `image` varchar(255) NOT NULL default '',
  `size` float NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `release_timestamp` int(11) NOT NULL,
  `visible` tinyint(1) NOT NULL default '0',
  `approved` tinyint(1) unsigned NOT NULL default '0',
  `start` int(11) NOT NULL default '0',
  `end` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `users_note` text,
  `nbrnote` mediumint(9) NOT NULL default '0',
  `note` float NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  `force_download` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`),
  FULLTEXT KEY `short_contents` (`short_contents`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `phpboost_download_cat` (`id`, `c_order`, `id_parent`, `name`, `contents`, `icon`, `visible`, `auth`, `num_files`) VALUES
(1, 1, 0, 'First category', 'Demonstration of a download module category', 'download.png', 1, '', 1);

INSERT INTO `phpboost_download` (`id`, `idcat`, `title`, `short_contents`, `contents`, `url`, `image`, `size`, `count`, `timestamp`, `release_timestamp`, `visible`, `approved`, `start`, `end`, `user_id`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `force_download`) VALUES
(1, 1, 'PHPBoost 3.0 Poster', '', 'Presentation of PHPBoost 3.0 Poster.', '/templates/base/theme/images/phpboost3.jpg', '', 14.9, 11, 1242424801, 1242424801, 1, 1, 0, 0, 0, '', 0, 0, 0, 0, 1);
