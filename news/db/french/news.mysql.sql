DROP TABLE IF EXISTS `phpboost_news`;
CREATE TABLE `phpboost_news` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `contents` mediumtext,
  `extend_contents` mediumtext,
  `archive` tinyint(1) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '0',
  `start` int(11) NOT NULL default '0',
  `end` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `img` varchar(250) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`),
  FULLTEXT KEY `extend_contents` (`extend_contents`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_news_cat`;
CREATE TABLE `phpboost_news_cat` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(150) NOT NULL default '',
	`contents` text,
	`icon` varchar(255) NOT NULL default '',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_news_cat` (`id`, `name`, `contents`, `icon`) VALUES (1, 'Test', 'Cat&eacute;gorie de test', 'news.png');
INSERT INTO `phpboost_news` (`id`, `idcat`, `title`, `contents`, `extend_contents`, `timestamp`, `visible`, `start`, `end`, `user_id`, `img`, `alt`, `nbr_com`, `lock_com`) VALUES (1, 1, 'PHPBoost 3', 'Votre site boosté par PHPBoost 3 est bien installé. Afin de vous aider à prendre votre site en main, l''accueil de chaque module contient un message pour vous guider pour vos premiers pas. Voici également quelques recommandations supplémentaires que nous vous proposons de lire avec attention : <br />\r\n<br />\r\n<br /><h4 class="stitle1">N''oubliez pas de supprimer le répertoire ''install''</h4><br /><br />\r\nSupprimez le répertoire /install à la racine de votre site pour des raisons de sécurité afin que personne ne puisse recommencer l''installation.<br />\r\n<br />\r\n<br /><h4 class="stitle1">Administrez votre site</h4><br /><br />\r\nAccédez au <a href="/admin/admin_index.php">panneau d''administration de votre site</a> afin de le paramétrer comme vous le souhaitez!  Pour cela : <br />\r\n<br />\r\n<ul class="bb_ul">\r\n	<li class="bb_li"><a href="/admin/admin_maintain.php">Mettez votre site en maintenance</a> en attendant que vous le configuriez à votre guise.\r\n	</li><li class="bb_li">Rendez vous à la <a href="/admin/admin_config.php">Configuration générale du site</a>.\r\n	</li><li class="bb_li"><a href="/admin/admin_modules.php">Configurez les modules</a> disponibles et donnez leur les droits d''accès (si vous n''avez pas installé le pack complet, tous les modules sont disponibles sur le site de <a href="http://www.phpboost.com">phpboost.com</a> dans la section téléchargement).\r\n	</li><li class="bb_li"><a href="/admin/admin_content_config.php">Choisissez le langage de formatage du contenu</a> par défaut du site.\r\n	</li><li class="bb_li"><a href="/admin/admin_members_config.php">Configurez l''inscription des membres</a>.\r\n	</li><li class="bb_li"><a href="/admin/admin_themes.php">Choisissez le thème par défaut de votre site</a> pour changer l''apparence de votre site (vous pouvez en obtenir d''autres sur le site de <a href="http://www.phpboost.com">phpboost.com</a>).\r\n	</li><li class="bb_li"><a href="/news/admin_news_config.php">Modifiez l''édito</a> de votre site.\r\n	</li><li class="bb_li">Avant de donner l''accès de votre site à vos visiteurs, prenez un peu de temps pour y mettre du contenu.\r\n	</li><li class="bb_li">Enfin <a href="/admin/admin_maintain.php">désactivez la maintenance</a> de votre site afin qu''il soit visible par vos visiteurs.<br />\r\n</li></ul><br />\r\n<br />\r\n<br /><h4 class="stitle1">Que faire si vous rencontrez un problème ?</h4><br /><br />\r\nN''hésitez pas à consulter <a href="http://www.phpboost.com/wiki/wiki.php">la documentation de PHPBoost</a> ou de poser vos question sur le <a href="http://www.phpboost.com/forum/index.php">forum d''entraide</a>.<br />\r\n<br />\r\n<br />\r\n<p class="float_right">Toute l''équipe de PHPBoost vous remercie d''utiliser son logiciel pour créer votre site web!</p>', '', unix_timestamp(current_timestamp), 1, 0, 0, 1, '/templates/base/theme/images/phpboost3.png', 'PHPBoost 3.0', 0, 0);