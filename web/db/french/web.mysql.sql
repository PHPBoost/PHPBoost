DROP TABLE IF EXISTS `phpboost_web`;
CREATE TABLE `phpboost_web` (
	`id` int(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`title` varchar(100) NOT NULL default '',
	`contents` text,
	`url` text,
	`compt` int(11) NOT NULL default '0',
	`aprob` tinyint(1) NOT NULL default '1',
	`timestamp` int(11) NOT NULL default '0',
	`users_note` text,
	`nbrnote` mediumint(9) NOT NULL default '0',
	`note` float NOT NULL default '0',
	`nbr_com` int(11) unsigned NOT NULL default '0',
	`lock_com` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `idcat` (`idcat`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_web` VALUES (1, 1, 'PHPBoost', '<p class="float_right"><img src="../templates/base/theme/images/phpboost_box_3_0.png" alt="" class="valign_" /></p><br />\r\nPHPBoost est un CMS (Content Managing System ou système de gestion de contenu) français. Ce logiciel permet à n''importe qui de créer son site de façon très simple, tout est assisté. Conçu pour satisfaire les débutants, il devrait aussi ravir les utilisateurs expérimentés qui souhaiteraient pousser son fonctionnement ou encore développer leurs propres modules.<br />\r\nPHPBoost est un logiciel libre distribué sous la licence GPL.<br />\r\n<br />\r\nComme son nom l''indique, PHPBoost utilise le PHP comme langage de programmation principal, mais, comme toute application Web, il utilise du XHTML et des CSS pour la mise en forme des pages, du JavaScript pour ajouter une touche dynamique sur les pages, ainsi que du SQL pour effectuer des opérations dans la base de données. Il s''installe sur un serveur Web et se paramètre à distance.<br />\r\n<br />\r\nComme pour une grande majorité de logiciels libres, la communauté de PHPBoost lui permet d''avoir à la fois une fiabilité importante car beaucoup d''utilisateurs ont testé chaque version et les ont ainsi approuvées. Il bénéficie aussi par ailleurs d''une évolution rapide car nous essayons d''être le plus possible à l''écoute des commentaires et des propositions de chacun. Même si tout le monde ne participe pas à son développement, beaucoup de gens nous ont aidés, rien qu''en nous donnant des idées, nous suggérant des modifications, des fonctionnalités supplémentaires.<br />\r\n<br />\r\nSi vous ne deviez retenir que quelques points essentiels sur le projet, ce seraient ceux-ci :<br />\r\n<br />\r\n    * Projet Open Source sous licence GNU/GPL<br />\r\n    * Code XHTML 1.0 strict et sémantique<br />\r\n    * Multilangue<br />\r\n    * Facilement personnalisable grâce aux thèmes et templates<br />\r\n    * Gestion fine des droits et des groupes multiples pour chaque utilisateur<br />\r\n    * Url rewriting<br />\r\n    * Installation et mise à jour automatisées des modules et du noyau<br />\r\n    * Aide au développement de nouveaux modules grâce au framework de PHPBoost', 'http://www.phpboost.com', 0, 1, 1234956484, '0', 0, 0, 0, 0);


DROP TABLE IF EXISTS `phpboost_web_cat`;
CREATE TABLE `phpboost_web_cat` (
	`id` int(11) NOT NULL auto_increment,
	`class` int(11) NOT NULL default '0',
	`name` varchar(150) NOT NULL default '',
	`contents` text,
	`icon` varchar(255) NOT NULL default '',
	`aprob` tinyint(1) NOT NULL default '0',
	`secure` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `class` (`class`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_web_cat` VALUES (1, 1, 'Catégorie de test', 'Liens de test', 'web.png', 1, -1);