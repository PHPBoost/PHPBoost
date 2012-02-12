DROP TABLE IF EXISTS `phpboost_media_cat`;
CREATE TABLE `phpboost_media_cat` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `c_order` int(11) unsigned NOT NULL default '0',
  `auth` text,
  `name` varchar(255) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '0',
  `mime_type` tinyint(1) unsigned NOT NULL default '0',
  `active` int(11) unsigned NOT NULL default '0',
  `description` text,
  `image` varchar(255) NOT NULL default '',
  `num_media` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `phpboost_media_cat` VALUES(1, 0, 1, 'a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;}', 'First video category', 1, 2, 7914, 'This category contains video files.', '../media/templates/images/video.png', 3);
INSERT INTO `phpboost_media_cat` VALUES(2, 0, 2, 'a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;}', 'First music category', 1, 1, 8191, 'This category contains music files.', '../media/templates/images/audio.png', 1);

DROP TABLE IF EXISTS `phpboost_media`;
CREATE TABLE `phpboost_media` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `iduser` int(11) unsigned NOT NULL default '1',
  `timestamp` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '0',
  `contents` text,
  `url` text,
  `mime_type` varchar(255) NOT NULL default '0',
  `infos` smallint(6) NOT NULL default '0',
  `width` mediumint(9) unsigned NOT NULL default '100',
  `height` mediumint(9) unsigned NOT NULL default '100',
  `counter` int(11) unsigned NOT NULL default '0',
  `users_note` text,
  `nbrnote` int(11) unsigned NOT NULL default '0',
  `note` float NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `contents` (`contents`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `phpboost_media` VALUES(1, 1, 1, 1242287109, 'First video file', 'Demonstration of a video file.', 'http://www.ptithom.net/documents/phpboost/videos/bdd/sauv_restau_bdd.flv', 'video/x-flv', 2, 640, 438, 0, '', 0, 0, 0, 0);
INSERT INTO `phpboost_media` VALUES(4, 2, 1, 1242287543, 'First music file', 'Demonstration of a music file.', 'http://www.alsacreations.fr/mediaflash/mp3/moldau.mp3', 'audio/mpeg', 2, 0, 0, 0, '', 0, 0, 0, 0);




