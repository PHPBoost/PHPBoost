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
  KEY `idcat` (`idcat`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `phpboost_media` VALUES (1, 1, 1, 1230294088, 'Installation d''un serveur local sous windows avec WampServer', 'Voici une petite vidéo vous montrant comment installer un serveur local sous windows. Pour ce tutoriel nous utiliserons le logiciel WampServer.<br />\r\nBonne visualisation  <img src="../images/smileys/sourire.gif" alt=":)" class="smiley" />', 'http://www.ptithom.net/documents/phpboost/medias/serveurlocal/serveurlocal.flv', 'video/x-flv', 2, 640, 480, 4, '', 0, 0, 0, 0);
INSERT INTO `phpboost_media` VALUES (2, 1, 1, 1230295852, 'Installation de PHPBoost 2.0', 'Voici une petite vidéo de présentation de l''installation de la version 2.0 de PHPBoost. Elle dure environ 7 minutes. Elle vous décrit toutes les étapes de l''installation. Elle ne remplace pas la version <a href="http://www.phpboost.com/wiki/installation">documentation de l''installation</a>, mais cela vous permet de voir rapidement ce que vous allez devoir faire et vérifier que c''est très simple.<br />\r\n<br />\r\nBonne visualisation  <img src="../images/smileys/sourire.gif" alt=":)" class="smiley" />', 'http://www.ptithom.net/documents/phpboost/medias/installation_2_0/installateur_640_616.flv', 'video/x-flv', 2, 640, 616, 1, '', 0, 0, 0, 0);
INSERT INTO `phpboost_media` VALUES (3, 0, 1, 1230296005, 'Sauvegarde et restauration de sa base de données', 'Il est important de réaliser régulièrement des sauvegardes de sa base de données.<br />\r\n<br />\r\nVoici une petite vidéo vous montrant comment sauvegarder et restaurer sa base de donnée à l''aide de l''utilitaire de PHPBoost.<br />\r\nBonne visualisation <img src="../images/smileys/sourire.gif" alt=":)" class="smiley" />', 'http://www.ptithom.net/documents/phpboost/medias/bdd/sauv_restau_bdd.flv', 'video/x-flv', 2, 640, 638, 1, '', 0, 0, 0, 0);

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

INSERT INTO `phpboost_media_cat` VALUES (1, 0, 1, 'a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;}', 'Vidéo PHPboost', 1, 0, 3823, 'Voici une nouvelle catégorie de PHPBoost, c''''est des présentations vidéos pour vous montrer des démonstrations d''''installations, de mises à jour, etc... Ces vidéos sont au format .FLV, la diffusion est en streaming, c''''est à dire qu''''elles se téléchargeront en même temps que vous les visionnerez.', '../media/media.png', 2);
