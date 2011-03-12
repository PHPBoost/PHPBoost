DROP TABLE IF EXISTS `phpboost_articles_cats`;
CREATE TABLE `phpboost_articles_cats` (
	`id` int(11) NOT NULL auto_increment,
	`id_left` int(11) NOT NULL default '0',
	`id_right` int(11) NOT NULL default '0',
	`level` int(11) NOT NULL default '0',
	`name` varchar(100) NOT NULL default '',
	`contents` text,
	`nbr_articles_visible` mediumint(9) unsigned NOT NULL default '0',
	`nbr_articles_unvisible` mediumint(9) unsigned NOT NULL default '0',
	`icon` varchar(255) NOT NULL default '',
	`aprob` tinyint(1) NOT NULL default '0',
	`auth` text,
	PRIMARY KEY  (`id`),
	KEY `id_left` (`id_left`)
) ENGINE=MyISAM;

INSERT INTO phpboost_articles_cats (`id`, `id_left`, `id_right`, `level`, `name`, `contents`, `nbr_articles_visible`, `nbr_articles_unvisible`, `icon`, `aprob`, `auth`) VALUES ('1', '1', '2', '0', 'Catégorie de test', 'Articles de démonstration', '1', '0', 'articles.png', '1', 'a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}');

DROP TABLE IF EXISTS `phpboost_articles`;
CREATE TABLE `phpboost_articles` (
	`id` int(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`title` varchar(100) NOT NULL default '',
	`contents` mediumtext NOT NULL,
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
	PRIMARY KEY  (`id`),
	KEY `idcat` (`idcat`),
    FULLTEXT KEY `title` (`title`),
    FULLTEXT KEY `contents` (`contents`)
) ENGINE=MyISAM;


INSERT INTO `phpboost_articles` (`id`, `idcat`, `title`, `contents`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`) VALUES
(1, 1, 'Débuter avec le module Articles', 'Ce bref article va vous donner quelques conseils simples pour prendre en main ce module.<br />\r\n<br />\r\n<ul class="bb_ul">\r\n	<li class="bb_li">Pour configurer votre module, <a href="/articles/admin_articles_config.php">cliquez ici</a>\r\n	</li><li class="bb_li">Pour ajouter des catégories : <a href="/admin_articles_cat_add.php">cliquez ici</a> (les catégories et sous catégories sont à l''infini)\r\n	</li><li class="bb_li">Pour ajouter un article, vous avez 2 solutions  (les 2 arrivent au même lien)<br />\r\n		<ul class="bb_ul">\r\n			<li class="bb_li">Dans la catégorie souhaitée, cliquez sur le bouton ''Ajout''\r\n			</li><li class="bb_li"><a href="/articles/admin_articles_add.php">Cliquez ici</a> pour l''ajouter via le panneau d''administration du module.<br />\r\n		</li></ul>\r\n	</li><li class="bb_li">Pour mettre en page vos articles, vous pouvez utiliser le langage bbcode ou l''éditeur WYSIWYG (cf cet <a href="http://www.phpboost.com/articles/articles-6-61+mise-en-page-du-contenu.php">article</a>)<br />\r\n</li></ul><br />\r\n<br />\r\nPour en savoir plus, n''hésitez pas à consulter la documentation du module sur le site de <a href="http://www.phpboost.com">PHPBoost</a>.<br />\r\n<br />\r\n<br />\r\nBonne utilisation de ce module.', '', 1242496981, 1, 0, 0, 1, 3, '0', 0, 0, 0, 0);

