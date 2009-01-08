DROP TABLE IF EXISTS `phpboost_news`;
CREATE TABLE `phpboost_news` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `contents` text NOT NULL,
  `extend_contents` text NOT NULL,
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
	`contents` text NOT NULL,
	`icon` varchar(255) NOT NULL default '',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_news_cat` (`id`, `name`, `contents`, `icon`) VALUES (1, 'Test', 'Cat&eacute;gorie de test', 'news.png');
INSERT INTO `phpboost_news` (`id`, `idcat`, `title`, `contents`, `extend_contents`, `timestamp`, `visible`, `start`, `end`, `user_id`, `img`, `alt`, `nbr_com`, `lock_com`) VALUES (1, 1, 'Bienvenue sur PHPBoost', 'PHPBoost est un CMS français open source. La nouvelle version 3.0 que vous venez d\'installer est le fruit de plus d\'un an de développement. Elle apporte de très nombreuses nouvelles fonctions et améliorations. De nombreux tests ont été faits, ce qui vous garantit performances, robustesse et sécurité pour votre site, y compris sous de fortes charges.  <br />
<br />
PHPBoost est conçu pour gérer la plupart des sites Web : vitrines, communautés, actualités, blogs, et bien d\'autres. Il fournit par défaut de nombreux outils de gestion de contenu (textes, medias, html), des utilisateurs (groupes, autorisations), etc.  <br />
<br />
PHPBoost permet également grâce à son framework aux développeurs et aux webmasters expérimentés d\'étendre facilement les fonctions de leur site, en programmant de nouvelles applications et extensions. En choisissant PHPBoost vous vous assurez également d\'obtenir un suivi en cas de problème, des mises à jour régulières, l\'ajout d\'extensions et nouvelles fonctions est prévu et se fera très facilement. Une documentation complète vous aidera à aborder les différents aspects de PHPBoost. Le projet est soutenu par une communauté d\'utilisateurs grandissante, vous permettant d\'échanger des conseils, astuces et en cas de problème de rapidement trouver une solution.   <br />

<br />
<br />
Merci d\'avoir rejoint la communauté d\'utilisateur de PHPBoost, <br />
Pensez à venir nous visiter en cas de problème ou pour suivre les actualités du projet, sur <a href="http://www.phpboost.com">http://www.phpboost.com</a>
', 'Suite de la news de test', unix_timestamp(current_timestamp), 1, 0, 0, 1, '../templates/base/theme/images/header.jpg', 'PHPBoost 3.0', 0, 0);