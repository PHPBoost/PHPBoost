DROP TABLE IF EXISTS `phpboost_articles`;
CREATE TABLE `phpboost_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcat` int(11) NOT NULL DEFAULT '0',
  `id_models` int(11) NOT NULL DEFAULT '1',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` text,
  `contents` mediumtext NOT NULL,
  `sources` text,
  `icon` varchar(255) NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `start` int(11) NOT NULL DEFAULT '0',
  `end` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `views` mediumint(9) NOT NULL DEFAULT '0',
  `users_note` text,
  `nbrnote` mediumint(9) NOT NULL DEFAULT '0',
  `note` float NOT NULL DEFAULT '0',
  `nbr_com` int(11) unsigned NOT NULL DEFAULT '0',
  `lock_com` tinyint(1) NOT NULL DEFAULT '0',
  `auth` text,
  `extend_field` text,
  PRIMARY KEY (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (9,0,1,'dz','','dz','a:0:{}','',1253876941,0,0,0,1,14,'',0,0,0,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (10,12,1,'test article en attente','','dz','a:0:{}','',1253876941,0,0,0,1,2,'',0,0,0,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (13,10,1,'Créer DVD/Blu-ray : 3 logiciels PC en test','','xz','a:0:{}','02450882.jpg',1253877241,1,0,0,1,3,'',0,0,0,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (15,10,1,'Les tuners TNT HD USB au banc d''essai','','d','a:0:{}','02449506.jpg',1253877301,1,0,0,1,2,'',0,0,0,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (16,10,1,'Top des logiciels pour Facebook, Twitter et MySpace','','d','a:0:{}','02458698.jpg',1253877361,1,0,0,1,5,'',0,0,0,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (29,0,1,'dz','','gvzsdc[page]  dzdz[/page]cd','a:0:{}','',1253889481,0,0,0,1,191,'',0,0,3,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (30,0,1,'test autorisation spé','','dzqssz<br />rn<br />rnszsaxqsa','a:2:{i:0;a:2:{s:7:"sources";s:6:"clubic";s:3:"url";s:14:"www.clubic.com";}i:1;a:2:{s:7:"sources";s:1:"z";s:3:"url";s:4:"dacq";}}','02446110.jpg',1253968321,1,0,0,1,14,'',0,0,0,0,'a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}','a:0:{}');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (31,10,1,'Comparatif de six encyclopédies en ligne','','dz','a:0:{}','02462842.jpg',1254022981,1,0,0,1,30,'',0,0,0,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (32,18,2,'AMD Radeon HD 5850 : DirectX 11 à 250 euros','Test de la description d''un article, cela permet un affichage tpl beaucoup plus interressant pour l''utilisateur.','[page]pre[/page]<br />rnVotre site boosté par PHPBoost 3 est bien installé. Afin de vous aider à prendre votre site en main, l''accueil de chaque module contient un message pour vous guider pour vos premiers pas. Voici également quelques recommandations supplémentaires que nous vous proposons de lire avec attention : <br />rn<br />rn<br />rnN''oubliez pas de supprimer le répertoire ''install''<br />rn<br />rnSupprimez le répertoire /install à la racine de votre site pour des raisons de sécurité afin que personne ne puisse recommencer l''installation.<br />rn<br />rn<br />rnAdministrez votre site<br />rn<br />rnAccédez au panneau d''administration de votre site afin de le paramétrer comme vous le souhaitez! Pour cela : <br />rn<br />rn<br />rn&#8226;Mettez votre site en maintenance en attendant que vous le configuriez à votre guise. <br />rn&#8226;Rendez vous à la Configuration générale du site. <br />rn&#8226;Configurez les modules disponibles et donnez leur les droits d''accès (si vous n''avez pas installé le pack complet, tous les modules sont disponibles sur le site de phpboost.com dans la section téléchargement). <br />rn&#8226;Choisissez le langage de formatage du contenu par défaut du site. <br />rn&#8226;Configurez l''inscription des membres. <br />rn&#8226;Choisissez le thème par défaut de votre site pour changer l''apparence de votre site (vous pouvez en obtenir d''autres sur le site de phpboost.com). <br />rn&#8226;Modifiez l''édito de votre site. <br />rn&#8226;Avant de donner l''accès de votre site à vos visiteurs, prenez un peu de temps pour y mettre du contenu. <br />rn&#8226;Enfin désactivez la maintenance de votre site afin qu''il soit visible par vos visiteurs.<br />rn<br />rn<br />rn<br />rn<br />rnQue faire si vous rencontrez un problème ?<br />rn<br />rnN''hésitez pas à consulter la documentation de PHPBoost ou de poser vos question sur le forum d''entraide.<br />rn<br />rn[page]deux[/page]<br />rndzcs<br />rn[page]troisi[/page]<br />rnz<br />rn&amp;efgf<br />rn[page]quatr[/page]<br />rndzcszzzzzzzzz<br />rn[page]cinq[/page]<br />rn<br />rn&amp;efgf<br />rn[page]six[/page]<br />rn<br />rn&amp;efgf<br />rn[page]sept[/page]<br />rndzcs<br />rn[page]huit[/page]<br />rn<br />rn&amp;efgf<br />rn[page]neuf[/page]<br />rndzcs<br />rn[page]dix[/page]<br />rn<br />rn&amp;efgf<br />rn[page]onze[/page]<br />rndzcs<br />rn[page]douze[/page]<br />rn<br />rn&amp;efgf<br />rn[page]treize[/page]<br />rn<br />rn&amp;efgf<br />rn[page]quatorze[/page]<br />rndzcs<br />rn[page]quinze[/page]<br />rn<br />rn&amp;efgf<br />rn[page]seize[/page]<br />rndzcs<br />rn[page]dix[/page]<br />rn<br />rn&amp;efgf','a:0:{}','02464020.jpg',1254024241,1,0,0,1,622,1,1,3,0,0,'','a:1:{s:4:"TYPE";a:2:{s:4:"name";s:4:"TYPE";s:8:"contents";s:0:"";}}');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (33,0,1,'TEST onglet  sans carrousel','','daq<br />rn[page]deczsde[/page]<br />rnczscs<br />rn[page]deczsfzde[/page]<br />rndzdz<br />rn[page] [/page]dz<br />rn<img src="/images/smileys/top.gif" alt=":top" class="smiley" /> z<br />rn<br />rn[page] [/page]s<br />rn<img src="/images/smileys/top.gif" alt=":top" class="smiley" /> z<br />rn[style=notice]dzdz[/styles]','a:0:{}','articles.png',1254022501,1,0,0,1,331,'',0,0,1,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (20,10,1,'zczczcz','','czcz','','',1253877961,0,0,0,-1,0,'',0,0,0,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (25,10,1,'vzsvzs','','czcz','','',1253878501,0,0,0,-1,0,'',0,0,0,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (22,10,1,'cz','','cz','','',1253878141,0,0,0,-1,0,'',0,0,0,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (26,10,1,'Comment partager ses fichiers entre plusieurs PC ?','','[page]test[/page]<br />rndz<br />rn[page]teszt[/page]<br />rnfzcfzf<br />rn<br />rnfzfzfz<br />rnfzfc<br />rnz<br />rnc<br />rncz<br />rncz<br />rncz<br />rncz<br />rncz<br />rn[page]tedzszt[/page]<br />rnfzcfzf<br />rn<br />rnfzfzfz<br />rnfzfc<br />rnz<br />rnc<br />rncz<br />rncz<br />rncz<br />rncz<br />rncz','a:0:{}','02454836.jpg',1253878561,1,0,0,1,83,'',0,0,0,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (27,10,1,'wwwwwwwwww','','wwwwwwwwwwwww','','',1253878561,0,0,0,-1,0,'',0,0,0,0,'','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (34,0,1,'TEST source','description','contenu','a:4:{i:0;a:2:{s:7:"sources";s:6:"google";s:3:"url";s:9:"google.fr";}i:1;a:2:{s:7:"sources";s:6:"clubic";s:3:"url";s:21:"http://www.clubic.com";}i:2;a:2:{s:7:"sources";s:5:"aucun";s:3:"url";s:0:"";}i:3;a:2:{s:7:"sources";s:8:"phpboost";s:3:"url";s:16:"www.phpboost.com";}}','',1254278101,1,0,0,1,23,'',0,0,0,0,'a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}','');
INSERT INTO `phpboost_articles` (`id`, `idcat`, `id_models`, `title`, `description`, `contents`, `sources`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `auth`, `extend_field`) VALUES (35,0,1,'TEST onglet avec carouselle','','[page]un[/page]<br />rncontenu un<br />rn<br />rntest<br />rn[page]deux[/page]<br />rncontenu deux<br />rntest<br />rn[page]trois[/page]<br />rncontenu trois<br />rntest<br />rn[page]quatre[/page]<br />rncontenu quatre<br />rntest<br />rn[page]cinq[/page]<br />rncontenu cinq<br />rntest<br />rn[page]six[/page]<br />rncontenu six<br />rntest<br />rn[page]sept[/page]<br />rncontenu sept<br />rntest<br />rn[page]huit[/page]<br />rncontenu huit<br />rn<br />rntest<br />rn[page]neuf[/page]<br />rncontenu neuf<br />rn<br />rntest<br />rn[page]dix[/page]<br />rncontenu dix<br />rntest<br />rn[page]onze[/page]<br />rncontenu onze<br />rn<br />rntest<br />rn[page]douze[/page]<br />rncontenu douze<br />rn<br />rntest<br />rn[page]treize[/page]<br />rncontenu treize<br />rntest<br />rn[page]quatorze[/page]<br />rncontenu quatorze<br />rn<br />rntest<br />rn[page]quinze[/page]<br />rncontenu quinze<br />rn<br />rntest<br />rn[page]seize[/page]<br />rncontenu seize<br />rn<br />rntest','a:0:{}','',1255185601,1,0,0,1,35,'',0,0,0,0,'','');
DROP TABLE IF EXISTS `phpboost_articles_cats`;
CREATE TABLE `phpboost_articles_cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL DEFAULT '0',
  `id_models` int(11) NOT NULL DEFAULT '1',
  `c_order` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` text,
  `nbr_articles_visible` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `nbr_articles_unvisible` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL DEFAULT '',
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `auth` text,
  `tpl_cat` varchar(100) NOT NULL DEFAULT 'articles_cat.tpl',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_articles_cats` (`id`, `id_parent`, `id_models`, `c_order`, `name`, `description`, `nbr_articles_visible`, `nbr_articles_unvisible`, `image`, `visible`, `auth`, `tpl_cat`) VALUES (10,0,1,1,'TEST  tpl perso','dz',5,4,'articles.png',1,'','articles_cat.tpl');
INSERT INTO `phpboost_articles_cats` (`id`, `id_parent`, `id_models`, `c_order`, `name`, `description`, `nbr_articles_visible`, `nbr_articles_unvisible`, `image`, `visible`, `auth`, `tpl_cat`) VALUES (11,10,1,1,'trucs et astuces','gvsdx',0,0,'http://www.phpboost.com/templates/tornade/images/rss.png',1,'','articles_cat.tpl');
INSERT INTO `phpboost_articles_cats` (`id`, `id_parent`, `id_models`, `c_order`, `name`, `description`, `nbr_articles_visible`, `nbr_articles_unvisible`, `image`, `visible`, `auth`, `tpl_cat`) VALUES (12,0,1,2,'ved','z',0,0,'http://www.phpboost.com/templates/tornade/images/rss.png',1,'','articles_cat.tpl');
INSERT INTO `phpboost_articles_cats` (`id`, `id_parent`, `id_models`, `c_order`, `name`, `description`, `nbr_articles_visible`, `nbr_articles_unvisible`, `image`, `visible`, `auth`, `tpl_cat`) VALUES (13,0,1,3,'TEST CHAMPS SUP','test',1,0,'../articles/articles.png',1,'','articles_cat.tpl');
INSERT INTO `phpboost_articles_cats` (`id`, `id_parent`, `id_models`, `c_order`, `name`, `description`, `nbr_articles_visible`, `nbr_articles_unvisible`, `image`, `visible`, `auth`, `tpl_cat`) VALUES (18,0,2,4,'Test modèles','',0,0,'../articles/articles.png',1,'','articles_cat.tpl');
DROP TABLE IF EXISTS `phpboost_articles_models`;
CREATE TABLE `phpboost_articles_models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `model_default` tinyint(1) NOT NULL DEFAULT '0',
  `description` text,
  `pagination_tab` tinyint(1) NOT NULL DEFAULT '0',
  `extend_field` text,
  `options` text,
  `tpl_articles` varchar(100) NOT NULL DEFAULT 'articles.tpl',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_articles_models` (`id`, `name`, `model_default`, `description`, `pagination_tab`, `extend_field`, `options`, `tpl_articles`) VALUES (1,'Modèle PHPBoost',1,'Modèle PHPBoost',1,'a:0:{}','a:6:{s:4:"note";b:1;s:3:"com";b:1;s:4:"impr";b:1;s:4:"date";b:1;s:6:"author";b:1;s:4:"mail";b:1;}','articles.tpl');
INSERT INTO `phpboost_articles_models` (`id`, `name`, `model_default`, `description`, `pagination_tab`, `extend_field`, `options`, `tpl_articles`) VALUES (2,'Modèle personnalisé',0,'<ul class="bb_ul">rn<li class="bb_li">Utilisation de deux templates personnalisés :<br />rn<ul class="bb_ul">rn<li class="bb_li">un templates redéfinissant la mise en page de la liste des articles dans les catégories.rn</li><li class="bb_li">un templates redéfinissant la mise en page des articles en ajoutant un tableau regroupant toutes les informations de l''article.<br />rn</li></ul>rn</li><li class="bb_li">Suppression des notes pour les articlesrn</li><li class="bb_li">Activation de la pagination par ongletrn</li><li class="bb_li">Ajout d''un champs TYPE<br />rn</li></ul>',1,'a:1:{i:0;a:2:{s:4:"name";s:4:"TYPE";s:4:"type";s:0:"";}}','a:6:{s:4:"note";b:0;s:3:"com";b:1;s:4:"impr";b:1;s:4:"date";b:1;s:6:"author";b:1;s:4:"mail";b:1;}','./models/articles_info_in_tab.tpl');
DROP TABLE IF EXISTS `phpboost_calendar`;
CREATE TABLE `phpboost_calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL,
  `contents` text,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `nbr_com` int(11) unsigned NOT NULL DEFAULT '0',
  `lock_com` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_com`;
CREATE TABLE `phpboost_com` (
  `idcom` int(11) NOT NULL AUTO_INCREMENT,
  `idprov` int(11) NOT NULL DEFAULT '0',
  `login` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `contents` text,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `script` varchar(20) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `user_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`idcom`),
  KEY `idprov` (`idprov`,`script`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_configs`;
CREATE TABLE `phpboost_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL DEFAULT '',
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (1,'config','a:38:{s:11:"server_name";s:16:"http://localhost";s:11:"server_path";s:22:"/phpboost/phpboost-dev";s:9:"site_name";s:12:"Site de test";s:9:"site_desc";s:0:"";s:12:"site_keyword";s:0:"";s:5:"start";i:1268642384;s:7:"version";s:3:"3.0";s:4:"lang";s:6:"french";s:5:"theme";s:4:"base";s:6:"editor";s:6:"bbcode";s:8:"timezone";i:1;s:10:"start_page";s:14:"/news/news.php";s:8:"maintain";i:0;s:14:"maintain_delay";i:1;s:22:"maintain_display_admin";i:1;s:13:"maintain_text";s:40:"Le site est actuellement en maintenance.";s:23:"htaccess_manual_content";s:0:"";s:7:"rewrite";i:0;s:10:"debug_mode";b:1;s:9:"com_popup";i:0;s:8:"compteur";i:0;s:5:"bench";b:1;s:12:"theme_author";i:0;s:12:"ob_gzhandler";i:0;s:11:"site_cookie";s:7:"session";s:12:"site_session";i:3600;s:18:"site_session_invit";i:300;s:8:"mail_exp";s:15:"teston@site.com";s:4:"mail";s:15:"teston@site.com";s:4:"sign";s:31:"Cordialement, l''équipe du site.";s:10:"anti_flood";i:0;s:11:"delay_flood";i:7;s:12:"unlock_admin";s:64:"8ab1b3af6335f3edcd7f81f95666d1734f1672d96291e22226a997f2c58778b2";s:6:"pm_max";i:50;s:17:"search_cache_time";i:30;s:14:"search_max_use";i:100;s:9:"html_auth";a:1:{s:2:"r2";i:1;}s:14:"forbidden_tags";a:0:{}}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (2,'member','a:14:{s:14:"activ_register";i:1;s:7:"msg_mbr";s:169:"Bienvenue sur le site. Vous êtes membre du site, vous pouvez accéder à tous les espaces nécessitant un compte utilisateur, éditer votre profil et voir vos contributions.";s:12:"msg_register";s:156:"Vous vous apprêtez à vous enregistrer sur le site. Nous vous demandons d''être poli et courtois dans vos interventions.<br /> <br /> Merci, l''équipe du site.";s:9:"activ_mbr";i:0;s:10:"verif_code";i:1;s:21:"verif_code_difficulty";i:2;s:17:"delay_unactiv_max";i:20;s:11:"force_theme";i:0;s:15:"activ_up_avatar";i:1;s:9:"width_max";i:120;s:10:"height_max";i:120;s:10:"weight_max";i:20;s:12:"activ_avatar";i:1;s:10:"avatar_url";s:13:"no_avatar.png";}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (3,'uploads','a:4:{s:10:"size_limit";d:512;s:17:"bandwidth_protect";i:1;s:15:"auth_extensions";a:48:{i:0;s:3:"jpg";i:1;s:4:"jpeg";i:2;s:3:"bmp";i:3;s:3:"gif";i:4;s:3:"png";i:5;s:3:"tif";i:6;s:3:"svg";i:7;s:3:"ico";i:8;s:3:"rar";i:9;s:3:"zip";i:10;s:2:"gz";i:11;s:3:"txt";i:12;s:3:"doc";i:13;s:4:"docx";i:14;s:3:"pdf";i:15;s:3:"ppt";i:16;s:3:"xls";i:17;s:3:"odt";i:18;s:3:"odp";i:19;s:3:"ods";i:20;s:3:"odg";i:21;s:3:"odc";i:22;s:3:"odf";i:23;s:3:"odb";i:24;s:3:"xcf";i:25;s:3:"flv";i:26;s:3:"mp3";i:27;s:3:"ogg";i:28;s:3:"mpg";i:29;s:3:"mov";i:30;s:3:"swf";i:31;s:3:"wav";i:32;s:3:"wmv";i:33;s:4:"midi";i:34;s:3:"mng";i:35;s:2:"qt";i:36;s:1:"c";i:37;s:1:"h";i:38;s:3:"cpp";i:39;s:4:"java";i:40;s:2:"py";i:41;s:3:"css";i:42;s:4:"html";i:43;s:3:"xml";i:44;s:3:"ttf";i:45;s:3:"tex";i:46;s:3:"rtf";i:47;s:3:"psd";}s:10:"auth_files";s:32:"a:2:{s:2:"r0";i:1;s:2:"r1";i:1;}";}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (4,'com','a:6:{s:8:"com_auth";i:-1;s:7:"com_max";i:10;s:14:"com_verif_code";i:1;s:25:"com_verif_code_difficulty";i:2;s:14:"forbidden_tags";a:0:{}s:8:"max_link";i:2;}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (5,'writingpad','');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (6,'kernel-modules','O:13:"ModulesConfig":1:{s:34:" AbstractConfigData properties_map";a:1:{s:7:"modules";a:25:{s:8:"articles";O:6:"Module":3:{s:17:" Module module_id";s:8:"articles";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:8:"calendar";O:6:"Module":3:{s:17:" Module module_id";s:8:"calendar";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:7:"contact";O:6:"Module":3:{s:17:" Module module_id";s:7:"contact";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:7:"connect";O:6:"Module":3:{s:17:" Module module_id";s:7:"connect";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:8:"database";O:6:"Module":3:{s:17:" Module module_id";s:8:"database";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:3:"doc";O:6:"Module":3:{s:17:" Module module_id";s:3:"doc";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:8:"download";O:6:"Module":3:{s:17:" Module module_id";s:8:"download";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:3:"faq";O:6:"Module":3:{s:17:" Module module_id";s:3:"faq";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:5:"forum";O:6:"Module":3:{s:17:" Module module_id";s:5:"forum";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:7:"gallery";O:6:"Module":3:{s:17:" Module module_id";s:7:"gallery";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:9:"guestbook";O:6:"Module":3:{s:17:" Module module_id";s:9:"guestbook";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:5:"media";O:6:"Module":3:{s:17:" Module module_id";s:5:"media";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:4:"news";O:6:"Module":3:{s:17:" Module module_id";s:4:"news";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:10:"newsletter";O:6:"Module":3:{s:17:" Module module_id";s:10:"newsletter";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:6:"online";O:6:"Module":3:{s:17:" Module module_id";s:6:"online";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:5:"pages";O:6:"Module":3:{s:17:" Module module_id";s:5:"pages";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:4:"poll";O:6:"Module":3:{s:17:" Module module_id";s:4:"poll";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:7:"sandbox";O:6:"Module":3:{s:17:" Module module_id";s:7:"sandbox";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:6:"search";O:6:"Module":3:{s:17:" Module module_id";s:6:"search";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:7:"sitemap";O:6:"Module":3:{s:17:" Module module_id";s:7:"sitemap";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:8:"shoutbox";O:6:"Module":3:{s:17:" Module module_id";s:8:"shoutbox";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:5:"stats";O:6:"Module":3:{s:17:" Module module_id";s:5:"stats";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:4:"test";O:6:"Module":3:{s:17:" Module module_id";s:4:"test";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:3:"web";O:6:"Module":3:{s:17:" Module module_id";s:3:"web";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}s:4:"wiki";O:6:"Module":3:{s:17:" Module module_id";s:4:"wiki";s:17:" Module activated";b:1;s:22:" Module authorizations";a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;}}}}}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (7,'articles','a:7:{s:16:"nbr_articles_max";i:10;s:11:"nbr_cat_max";i:10;s:10:"nbr_column";i:3;s:8:"note_max";i:5;s:11:"global_auth";a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;}s:4:"mini";s:52:"a:2:{s:12:"nbr_articles";i:5;s:4:"type";s:4:"view";}";s:7:"tpl_cat";s:16:"articles_cat.tpl";}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (8,'calendar','a:1:{s:13:"calendar_auth";i:2;}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (9,'contact','a:2:{s:17:"contact_verifcode";i:1;s:28:"contact_difficulty_verifcode";i:2;}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (10,'download','a:5:{s:12:"nbr_file_max";i:10;s:10:"nbr_column";i:2;s:8:"note_max";i:5;s:13:"root_contents";s:50:"Bienvenue dans l''espace de téléchargement du site!";s:11:"global_auth";a:3:{s:3:"r-1";i:1;s:2:"r0";i:5;s:2:"r1";i:7;}}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (11,'faq','a:5:{s:8:"faq_name";s:12:"FAQ PHPBoost";s:8:"num_cols";i:4;s:13:"display_block";b:0;s:11:"global_auth";a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:3;s:2:"r2";i:3;}s:4:"root";a:3:{s:12:"display_mode";i:0;s:4:"auth";a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:3;s:2:"r2";i:3;}s:11:"description";s:822:"Bienvenue dans la FAQ !<br />\r\n<br />\r\n2 catégories et quelques questions ont été créées pour vous montrer comment fonctionne ce module. Voici quelques conseils pour bien débuter sur ce module.<br />\r\n<br />\r\n<ul class="bb_ul">\r\n	<li class="bb_li">Pour configurer votre module, rendez vous dans l''<a href="/faq/admin_faq.php">administration du module</a>\r\n	</li><li class="bb_li">Pour créer des catégories, <a href="/faq/admin_faq_cats.php?new=1">cliquez ici</a>\r\n	</li><li class="bb_li">Pour créer des questions, rendez vous dans la catégorie souhaitée et cliquez sur ''Gérer la catégorie'' puis ''ajout''</li></ul><br />\r\n<br />\r\nPour personnaliser l''accueil de ce module, <a href="/faq/management.php">cliquez ici</a><br />\r\nPour en savoir plus, n''hésitez pas à consulter la documentation du module sur le site de PHPBoost.";}}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (12,'forum','a:15:{s:10:"forum_name";s:14:"PHPBoost forum";s:16:"pagination_topic";i:20;s:14:"pagination_msg";i:15;s:9:"view_time";i:2592000;s:11:"topic_track";i:40;s:9:"edit_mark";i:1;s:14:"no_left_column";i:0;s:15:"no_right_column";i:0;s:17:"activ_display_msg";i:1;s:11:"display_msg";s:21:"[R&eacute;gl&eacute;]";s:19:"explain_display_msg";s:26:"Sujet r&eacute;gl&eacute;?";s:23:"explain_display_msg_bis";s:30:"Sujet non r&eacute;gl&eacute;?";s:22:"icon_activ_display_msg";i:1;s:4:"auth";s:19:"a:1:{s:2:"r2";i:7;}";s:17:"display_connexion";i:0;}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (13,'gallery','a:27:{s:5:"width";i:150;s:6:"height";i:150;s:9:"width_max";i:800;s:10:"height_max";i:600;s:10:"weight_max";i:1024;s:7:"quality";i:80;s:5:"trans";i:40;s:4:"logo";s:8:"logo.jpg";s:10:"activ_logo";i:1;s:7:"d_width";i:5;s:8:"d_height";i:5;s:10:"nbr_column";i:4;s:12:"nbr_pics_max";i:16;s:8:"note_max";i:5;s:11:"activ_title";i:1;s:9:"activ_com";i:1;s:10:"activ_note";i:1;s:15:"display_nbrnote";i:1;s:10:"activ_view";i:1;s:10:"activ_user";i:1;s:12:"limit_member";i:10;s:10:"limit_modo";i:25;s:12:"display_pics";i:3;s:11:"scroll_type";i:1;s:13:"nbr_pics_mini";i:6;s:15:"speed_mini_pics";i:6;s:9:"auth_root";s:59:"a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}";}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (14,'guestbook','a:5:{s:14:"guestbook_auth";i:-1;s:24:"guestbook_forbidden_tags";s:52:"a:3:{i:0;s:3:"swf";i:1;s:5:"movie";i:2;s:5:"sound";}";s:18:"guestbook_max_link";i:2;s:19:"guestbook_verifcode";i:0;s:30:"guestbook_difficulty_verifcode";i:0;}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (15,'media','a:6:{s:5:"pagin";i:25;s:10:"nbr_column";i:2;s:8:"note_max";i:5;s:5:"width";i:900;s:6:"height";i:600;s:4:"root";a:10:{s:9:"id_parent";i:-1;s:5:"order";i:1;s:4:"name";s:10:"Multimédia";s:4:"desc";s:94:"<p style="text-align:center"><strong>Voici le module Multimédia pour PHPboost 3.0</strong></p>";s:7:"visible";b:1;s:5:"image";s:9:"media.png";s:9:"num_media";i:0;s:9:"mime_type";i:0;s:6:"active";i:4095;s:4:"auth";a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;}}}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (16,'news','a:14:{s:4:"type";i:1;s:9:"activ_com";i:1;s:10:"activ_icon";i:1;s:11:"activ_edito";i:1;s:11:"activ_pagin";i:1;s:12:"display_date";i:1;s:14:"display_author";i:1;s:15:"pagination_news";i:6;s:15:"pagination_arch";i:15;s:10:"nbr_column";i:1;s:8:"nbr_news";s:1:"1";s:11:"global_auth";a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:15;}s:11:"edito_title";s:25:"Bienvenue sur votre site!";s:5:"edito";s:551:"Vous désirez un site dynamique capable de s''adapter à vos besoins ? PHPBoost est fait pour vous !<br />\r\n<br />\r\nVous pourrez à travers une administration intuitive personnaliser entièrement votre site sans connaissances particulières. En effet ce logiciel a été conçu avec la volonté de le rendre utilisable simplement par le plus grand nombre. Prenez le temps de découvrir toutes les fonctionnalités qui vous sont offertes. En cas de problème une communauté grandissante sera toujours là pour vous épauler !<br />\r\n<br />\r\nBienvenue sur votre site !";}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (17,'newsletter','a:2:{s:11:"sender_mail";s:0:"";s:15:"newsletter_name";s:0:"";}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (18,'online','a:2:{s:16:"online_displayed";i:4;s:20:"display_order_online";s:33:"s.level DESC, s.session_time DESC";}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (19,'pages','a:3:{s:10:"count_hits";i:1;s:9:"activ_com";i:1;s:4:"auth";s:59:"a:4:{s:3:"r-1";i:5;s:2:"r0";i:7;s:2:"r1";i:7;s:2:"r2";i:7;}";}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (20,'poll','a:4:{s:9:"poll_auth";i:-1;s:9:"poll_mini";a:1:{i:0;s:1:"1";}s:11:"poll_cookie";s:4:"poll";s:18:"poll_cookie_lenght";i:2592000;}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (21,'search','a:2:{s:19:"nb_results_per_page";i:15;s:20:"unauthorized_modules";a:0:{}}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (22,'shoutbox','a:5:{s:16:"shoutbox_max_msg";i:100;s:13:"shoutbox_auth";i:-1;s:23:"shoutbox_forbidden_tags";s:428:"a:26:{i:0;s:5:"title";i:1;s:6:"stitle";i:2;s:5:"style";i:3;s:3:"url";i:4;s:3:"img";i:5;s:5:"quote";i:6;s:4:"hide";i:7;s:4:"list";i:8;s:5:"color";i:9;s:7:"bgcolor";i:10;s:4:"font";i:11;s:4:"size";i:12;s:5:"align";i:13;s:5:"float";i:14;s:3:"sup";i:15;s:3:"sub";i:16;s:6:"indent";i:17;s:3:"pre";i:18;s:5:"table";i:19;s:3:"swf";i:20;s:5:"movie";i:21;s:5:"sound";i:22;s:4:"code";i:23;s:4:"math";i:24;s:6:"anchor";i:25;s:7:"acronym";}";s:17:"shoutbox_max_link";i:2;s:22:"shoutbox_refresh_delay";d:60000;}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (23,'web','a:4:{s:11:"nbr_web_max";i:10;s:11:"nbr_cat_max";i:10;s:10:"nbr_column";i:2;s:8:"note_max";i:5;}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (24,'wiki','a:6:{s:4:"auth";s:71:"a:4:{s:3:"r-1";i:1041;s:2:"r0";i:1299;s:2:"r1";i:4095;s:2:"r2";i:4095;}";s:9:"wiki_name";s:13:"Wiki PHPBoost";s:13:"last_articles";i:0;s:12:"display_cats";i:0;s:10:"index_text";s:715:"Bienvenue sur le module wiki!<br />\r\n<br />\r\nVoici quelques conseils pour bien débuter sur ce module. <br />\r\n<ul class="bb_ul">\r\n	<li class="bb_li">Pour configurer votre module, rendez vous dans l''<a href="/wiki/admin_wiki.php">administration du module</a>\r\n	</li><li class="bb_li">Pour créer des catégories, <a href="/wiki/post.php?type=cat">cliquez ici</a>\r\n	</li><li class="bb_li">Pour créer des articles, rendez vous <a href="/wiki/post.php">ici</a>\r\n</li></ul><br />\r\nPour personnaliser l''accueil de ce module, <a href="/wiki/admin_wiki.php">cliquez ici</a><br />\r\n<br />\r\nPour en savoir plus, n''hésitez pas à consulter la documentation du module sur le site de <a href="http://www.phpboost.com">PHPBoost</a>.";s:10:"count_hits";i:1;}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (25,'kernel-user-accounts','O:18:"UserAccountsConfig":1:{s:34:" AbstractConfigData properties_map";a:14:{s:20:"registration_enabled";b:1;s:33:"member_accounts_validation_method";i:1;s:15:"welcome_message";s:169:"Bienvenue sur le site. Vous êtes membre du site, vous pouvez accéder à tous les espaces nécessitant un compte utilisateur, éditer votre profil et voir vos contributions.";s:22:"registration_agreement";N;s:28:"registration_captcha_enabled";b:1;s:31:"registration_captcha_difficulty";i:1;s:28:"unactivated_accounts_timeout";i:20;s:17:"force_users_theme";b:0;s:20:"enable_avatar_upload";b:1;s:22:"default_avatar_enabled";b:1;s:18:"default_avatar_url";s:13:"no_avatar.png";s:16:"max_avatar_width";i:120;s:17:"max_avatar_height";i:120;s:17:"max_avatar_weight";i:20;}}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (26,'kernel-last-use-date','O:17:"LastUseDateConfig":1:{s:34:" AbstractConfigData properties_map";a:3:{s:4:"year";s:4:"2010";s:5:"month";s:2:"05";s:3:"day";i:29;}}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (27,'kernel-writing-pad','O:16:"WritingPadConfig":1:{s:34:" AbstractConfigData properties_map";a:1:{s:7:"content";s:65:"Cet emplacement est réservé pour y saisir vos notes personnelles.";}}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (28,'search-config','O:12:"SearchConfig":1:{s:34:" AbstractConfigData properties_map";a:2:{s:10:"weightings";a:0:{}s:22:"unauthorized_providers";a:0:{}}}');
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (29,'kernel-mail-service','O:17:"MailServiceConfig":1:{s:34:" AbstractConfigData properties_map";a:9:{s:8:"use_smtp";b:0;s:9:"smtp_host";s:0:"";s:9:"smtp_port";i:25;s:10:"smtp_login";s:0:"";s:13:"smtp_password";s:0:"";s:13:"smtp_protocol";s:4:"none";s:19:"default_mail_sender";s:0:"";s:20:"administrators_mails";a:0:{}s:14:"mail_signature";s:0:"";}}');
DROP TABLE IF EXISTS `phpboost_download`;
CREATE TABLE `phpboost_download` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcat` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `short_contents` text,
  `contents` text,
  `url` text,
  `image` varchar(255) NOT NULL,
  `size` decimal(10,3) NOT NULL DEFAULT '0.000',
  `count` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `release_timestamp` int(11) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `start` int(11) NOT NULL DEFAULT '0',
  `end` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `users_note` text,
  `nbrnote` int(9) NOT NULL DEFAULT '0',
  `note` decimal(10,3) NOT NULL DEFAULT '0.000',
  `nbr_com` int(11) NOT NULL DEFAULT '0',
  `lock_com` tinyint(1) NOT NULL DEFAULT '0',
  `force_download` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`),
  FULLTEXT KEY `short_contents` (`short_contents`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_download` (`id`, `idcat`, `title`, `short_contents`, `contents`, `url`, `image`, `size`, `count`, `timestamp`, `release_timestamp`, `visible`, `approved`, `start`, `end`, `user_id`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`, `force_download`) VALUES (1,1,'Affiche PHPboost 3.0','','Présentation de l''affiche de la version 3.0 de PHPBoost.','/templates/base/theme/images/phpboost3.jpg','',14.900,11,1242424801,1242424801,1,1,0,0,1,'',0,0.000,0,0,1);
DROP TABLE IF EXISTS `phpboost_download_cat`;
CREATE TABLE `phpboost_download_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL DEFAULT '0',
  `c_order` int(11) NOT NULL DEFAULT '0',
  `auth` text,
  `name` varchar(150) NOT NULL,
  `contents` text,
  `icon` varchar(255) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `num_files` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `class` (`c_order`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_download_cat` (`id`, `id_parent`, `c_order`, `auth`, `name`, `contents`, `icon`, `visible`, `num_files`) VALUES (1,0,1,'','Catégorie de test','Test des téléchargements','download.png',1,1);
DROP TABLE IF EXISTS `phpboost_errors_404`;
CREATE TABLE `phpboost_errors_404` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `requested_url` varchar(255) NOT NULL,
  `from_url` varchar(255) NOT NULL,
  `times` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`requested_url`,`from_url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_events`;
CREATE TABLE `phpboost_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entitled` varchar(255) NOT NULL,
  `description` text,
  `fixing_url` varchar(255) NOT NULL,
  `module` varchar(100) DEFAULT NULL,
  `current_status` tinyint(3) NOT NULL DEFAULT '0',
  `creation_date` int(11) NOT NULL,
  `fixing_date` int(11) DEFAULT NULL,
  `auth` text,
  `poster_id` int(11) DEFAULT NULL,
  `fixer_id` int(11) DEFAULT NULL,
  `id_in_module` int(11) DEFAULT NULL,
  `identifier` varchar(64) DEFAULT NULL,
  `contribution_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` varchar(64) DEFAULT NULL,
  `priority` tinyint(3) unsigned DEFAULT '3',
  `nbr_com` int(10) unsigned DEFAULT '0',
  `lock_com` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type_index` (`type`),
  KEY `identifier_index` (`identifier`),
  KEY `module_index` (`module`),
  KEY `id_in_module_index` (`id_in_module`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_events` (`id`, `entitled`, `description`, `fixing_url`, `module`, `current_status`, `creation_date`, `fixing_date`, `auth`, `poster_id`, `fixer_id`, `id_in_module`, `identifier`, `contribution_type`, `type`, `priority`, `nbr_com`, `lock_com`) VALUES (1,'PHPBoost est disponible dans sa nouvelle version 3.0.3','O:11:"Application":22:{s:2:"id";s:6:"kernel";s:4:"name";s:5:"Noyau";s:8:"language";s:6:"french";s:18:"localized_language";s:8:"Français";s:4:"type";s:6:"kernel";s:10:"repository";s:43:"http://www.phpboost.com/repository/main.xml";s:7:"version";s:5:"3.0.3";s:17:"compatibility_min";s:3:"3.0";s:17:"compatibility_max";N;s:7:"pubdate";O:4:"Date":1:{s:15:" Date timestamp";i:1250978401;}s:8:"priority";i:2;s:15:"security_update";b:0;s:12:"download_url";s:62:"http://www.phpboost.com/download/category-31+distributions.php";s:10:"update_url";s:85:"http://dl.phpboost.com/fr/phpboost/3.0/diff/diff-phpboost-3.0.5-to-phpboost-3.0.6.zip";s:7:"authors";a:3:{i:0;a:2:{s:4:"name";s:13:"Benoît Sautel";s:5:"email";s:23:"ben.popeye@phpboost.com";}i:1;a:2:{s:4:"name";s:12:"Régis Viarre";s:5:"email";s:21:"crowkait@phpboost.com";}i:2;a:2:{s:4:"name";s:12:"Loïc Rouchon";s:5:"email";s:17:"horn@phpboost.com";}}s:11:"description";s:386:"\n				Cette mise à jour contient un grand nombre de corrections de bugs principalement portant\n				sur la gestion des urls, l''envoi de mail, le rewrite dans la recherche ou bien l''inclusion\n				de flux dans les templates.\n				Pour l''installer, remplacer simplement votre répertoire installation de phpboost par le contenu\n				de cette nouvelle version et supprimez le dossier install\n			";s:12:"new_features";a:0:{}s:11:"improvments";a:0:{}s:15:"bug_corrections";a:3:{i:0;s:27:"Meilleure gestion des liens";i:1;s:79:"Autorisation du php dans les templates qui empêchait l''utilisation des flux rss";i:2;s:21:"Envoi de mail corrigé";}s:20:"security_improvments";a:0:{}s:13:"warning_level";N;s:7:"warning";N;}','admin/updates/detail.php?identifier=0063d51583288316867fd52934f3d273',,0,1268642401,,,,,0,'0063d51583288316867fd52934f3d273',1,'updates',2,0,0);
DROP TABLE IF EXISTS `phpboost_faq`;
CREATE TABLE `phpboost_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcat` int(11) NOT NULL DEFAULT '0',
  `q_order` int(11) NOT NULL DEFAULT '0',
  `question` varchar(255) NOT NULL,
  `answer` text,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `question` (`question`),
  FULLTEXT KEY `answer` (`answer`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_faq` (`id`, `idcat`, `q_order`, `question`, `answer`, `user_id`, `timestamp`) VALUES (1,2,1,'Qu''est ce qu''un CMS?','C''est un système de gestion de contenu ou SGC en français (en anglais :  Content Management Systems)',1,1242496334);
INSERT INTO `phpboost_faq` (`id`, `idcat`, `q_order`, `question`, `answer`, `user_id`, `timestamp`) VALUES (2,1,1,'Qu''est-ce que PHPBoost ?','PHPBoost est un CMS (Content Management System ou système de gestion de contenu) français. Ce logiciel permet à n''importe qui de créer son site de façon très simple, tout est assisté. Conçu pour satisfaire les débutants, il devrait aussi ravir les utilisateurs expérimentés qui souhaiteraient pousser son fonctionnement ou encore développer leurs propres modules.',1,1242496518);
DROP TABLE IF EXISTS `phpboost_faq_cats`;
CREATE TABLE `phpboost_faq_cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL DEFAULT '0',
  `c_order` int(11) unsigned NOT NULL DEFAULT '0',
  `auth` text,
  `name` varchar(255) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `display_mode` int(2) NOT NULL DEFAULT '0',
  `description` text,
  `image` varchar(255) NOT NULL,
  `num_questions` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_faq_cats` (`id`, `id_parent`, `c_order`, `auth`, `name`, `visible`, `display_mode`, `description`, `image`, `num_questions`) VALUES (1,0,1,,'PHPBoost',1,0,'Des questions sur PHPBoost?','faq.png',1);
INSERT INTO `phpboost_faq_cats` (`id`, `id_parent`, `c_order`, `auth`, `name`, `visible`, `display_mode`, `description`, `image`, `num_questions`) VALUES (2,0,2,,'Dictionnaire',1,0,'','faq.png',1);
DROP TABLE IF EXISTS `phpboost_forum_alerts`;
CREATE TABLE `phpboost_forum_alerts` (
  `id` mediumint(11) NOT NULL AUTO_INCREMENT,
  `idcat` int(11) NOT NULL DEFAULT '0',
  `idtopic` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `contents` text,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `idmodo` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idtopic` (`idtopic`,`user_id`,`idmodo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_forum_cats`;
CREATE TABLE `phpboost_forum_cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_left` int(11) NOT NULL DEFAULT '0',
  `id_right` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `subname` varchar(150) NOT NULL DEFAULT '',
  `nbr_topic` mediumint(9) NOT NULL DEFAULT '0',
  `nbr_msg` mediumint(9) NOT NULL DEFAULT '0',
  `last_topic_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `aprob` tinyint(1) NOT NULL DEFAULT '0',
  `auth` text,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `last_topic_id` (`last_topic_id`),
  KEY `id_left` (`id_left`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_forum_cats` (`id`, `id_left`, `id_right`, `level`, `name`, `subname`, `nbr_topic`, `nbr_msg`, `last_topic_id`, `status`, `aprob`, `auth`, `url`) VALUES (1,1,4,0,'Cat&eacute;gorie de test','Cat&eacute;gorie de test',1,1,1,1,1,'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:7;}','');
INSERT INTO `phpboost_forum_cats` (`id`, `id_left`, `id_right`, `level`, `name`, `subname`, `nbr_topic`, `nbr_msg`, `last_topic_id`, `status`, `aprob`, `auth`, `url`) VALUES (2,2,3,1,'Forum de test','Forum de test',1,1,1,1,1,'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}','');
DROP TABLE IF EXISTS `phpboost_forum_history`;
CREATE TABLE `phpboost_forum_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(50) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_id_action` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_forum_msg`;
CREATE TABLE `phpboost_forum_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtopic` int(11) NOT NULL DEFAULT '0',
  `user_id` mediumint(9) NOT NULL DEFAULT '0',
  `contents` text,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `timestamp_edit` int(11) NOT NULL DEFAULT '0',
  `user_id_edit` int(11) NOT NULL DEFAULT '0',
  `user_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idtopic` (`idtopic`,`user_id`,`timestamp`),
  FULLTEXT KEY `contenu` (`contents`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_forum_msg` (`id`, `idtopic`, `user_id`, `contents`, `timestamp`, `timestamp_edit`, `user_id_edit`, `user_ip`) VALUES (1,1,1,'Message de test sur le forum PHPBoost',1268642386,0,0,'0.0.0.0');
DROP TABLE IF EXISTS `phpboost_forum_poll`;
CREATE TABLE `phpboost_forum_poll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtopic` int(11) NOT NULL DEFAULT '0',
  `question` varchar(255) NOT NULL DEFAULT '',
  `answers` text,
  `voter_id` text,
  `votes` text,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idtopic` (`idtopic`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_forum_topics`;
CREATE TABLE `phpboost_forum_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcat` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `subtitle` varchar(75) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `nbr_msg` mediumint(9) NOT NULL DEFAULT '0',
  `nbr_views` mediumint(9) NOT NULL DEFAULT '0',
  `last_user_id` int(11) NOT NULL DEFAULT '0',
  `last_msg_id` int(11) NOT NULL DEFAULT '0',
  `last_timestamp` int(11) NOT NULL DEFAULT '0',
  `first_msg_id` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `aprob` tinyint(1) NOT NULL DEFAULT '0',
  `display_msg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idcat` (`idcat`,`last_user_id`,`last_timestamp`,`type`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_forum_topics` (`id`, `idcat`, `title`, `subtitle`, `user_id`, `nbr_msg`, `nbr_views`, `last_user_id`, `last_msg_id`, `last_timestamp`, `first_msg_id`, `type`, `status`, `aprob`, `display_msg`) VALUES (1,2,'Test','Sujet de test',1,1,0,1,1,1268642386,1,0,1,0,0);
DROP TABLE IF EXISTS `phpboost_forum_track`;
CREATE TABLE `phpboost_forum_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtopic` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `track` tinyint(1) NOT NULL DEFAULT '0',
  `pm` tinyint(1) NOT NULL DEFAULT '0',
  `mail` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idtopic` (`idtopic`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_forum_view`;
CREATE TABLE `phpboost_forum_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtopic` int(11) NOT NULL DEFAULT '0',
  `last_view_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idv` (`idtopic`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_gallery`;
CREATE TABLE `phpboost_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcat` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `width` mediumint(9) NOT NULL DEFAULT '0',
  `height` mediumint(9) NOT NULL DEFAULT '0',
  `weight` mediumint(9) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `aprob` tinyint(1) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `users_note` text,
  `nbrnote` mediumint(9) NOT NULL DEFAULT '0',
  `note` float NOT NULL DEFAULT '0',
  `nbr_com` int(11) unsigned NOT NULL DEFAULT '0',
  `lock_com` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idcat` (`idcat`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_gallery` (`id`, `idcat`, `name`, `path`, `width`, `height`, `weight`, `user_id`, `aprob`, `views`, `timestamp`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`) VALUES (1,1,'PHPBoost 3!','phpboost3.jpg',320,264,15614,1,1,0,1268642386,'',0,0,0,0);
DROP TABLE IF EXISTS `phpboost_gallery_cats`;
CREATE TABLE `phpboost_gallery_cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_left` int(11) NOT NULL DEFAULT '0',
  `id_right` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0',
  `name` varchar(150) NOT NULL DEFAULT '',
  `contents` text,
  `nbr_pics_aprob` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `nbr_pics_unaprob` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `aprob` tinyint(1) NOT NULL DEFAULT '1',
  `auth` text,
  PRIMARY KEY (`id`),
  KEY `id_left` (`id_left`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_gallery_cats` (`id`, `id_left`, `id_right`, `level`, `name`, `contents`, `nbr_pics_aprob`, `nbr_pics_unaprob`, `status`, `aprob`, `auth`) VALUES (1,1,2,0,'Test','Galerie de test',1,0,1,1,'a:3:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:3;}');
DROP TABLE IF EXISTS `phpboost_group`;
CREATE TABLE `phpboost_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `img` varchar(255) NOT NULL DEFAULT '',
  `color` varchar(6) NOT NULL DEFAULT '',
  `auth` varchar(255) NOT NULL DEFAULT '0',
  `members` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_guestbook`;
CREATE TABLE `phpboost_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contents` text,
  `login` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_lang`;
CREATE TABLE `phpboost_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(150) NOT NULL DEFAULT '',
  `activ` tinyint(1) NOT NULL DEFAULT '0',
  `secure` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_lang` (`id`, `lang`, `activ`, `secure`) VALUES (1,'french',1,-1);
DROP TABLE IF EXISTS `phpboost_media`;
CREATE TABLE `phpboost_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcat` int(11) NOT NULL DEFAULT '0',
  `iduser` int(11) unsigned NOT NULL DEFAULT '1',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '0',
  `contents` text,
  `url` text,
  `mime_type` varchar(255) NOT NULL DEFAULT '0',
  `infos` smallint(6) NOT NULL DEFAULT '0',
  `width` mediumint(9) unsigned NOT NULL DEFAULT '100',
  `height` mediumint(9) unsigned NOT NULL DEFAULT '100',
  `counter` int(11) unsigned NOT NULL DEFAULT '0',
  `users_note` text,
  `nbrnote` int(11) unsigned NOT NULL DEFAULT '0',
  `note` float NOT NULL DEFAULT '0',
  `nbr_com` int(11) unsigned NOT NULL DEFAULT '0',
  `lock_com` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `contents` (`contents`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_media` (`id`, `idcat`, `iduser`, `timestamp`, `name`, `contents`, `url`, `mime_type`, `infos`, `width`, `height`, `counter`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`) VALUES (1,1,1,1242287109,'Sauvegarde et restauration de sa base de données','Il est important de réaliser régulièrement des sauvegardes de sa base de données.<br />rn<br />rnVoici une petite vidéo vous montrant comment sauvegarder et restaurer sa base de donnée à l''aide de l''utilitaire de PHPBoost.<br />rn<br />rnBonne visualisation  <img src="/images/smileys/sourire.gif" alt=":)" class="smiley" /> .','http://www.ptithom.net/documents/phpboost/videos/bdd/sauv_restau_bdd.flv','video/x-flv',2,640,438,0,'',0,0,0,0);
INSERT INTO `phpboost_media` (`id`, `idcat`, `iduser`, `timestamp`, `name`, `contents`, `url`, `mime_type`, `infos`, `width`, `height`, `counter`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`) VALUES (2,1,1,1242287212,'Télécharger PHPBoost et transférer les fichiers sur votre serveur FTP','Voici une petite vidéo vous montrant comment télécharger et envoyer les fichiers de PHPBoost sur votre serveur FTP.<br />rn<br />rnBonne visualisation  <img src="/images/smileys/sourire.gif" alt=":)" class="smiley" /> .','http://www.ptithom.net/documents/phpboost/videos/transfert_fichiers/transfert_fichiers_serveur.flv','video/x-flv',2,640,598,0,'',0,0,0,0);
INSERT INTO `phpboost_media` (`id`, `idcat`, `iduser`, `timestamp`, `name`, `contents`, `url`, `mime_type`, `infos`, `width`, `height`, `counter`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`) VALUES (3,1,1,1242287275,'Installation de PHPBoost version 3','Vidéo vous expliquant comment installer PHPBoost version 3.','http://www.ptithom.net/documents/phpboost/videos/installateurv3.flv','video/x-flv',2,640,598,0,'',0,0,0,0);
INSERT INTO `phpboost_media` (`id`, `idcat`, `iduser`, `timestamp`, `name`, `contents`, `url`, `mime_type`, `infos`, `width`, `height`, `counter`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`) VALUES (4,2,1,1242287543,'La Moldau (Smetana)','Bed&#345;ich Smetana, né le 2 mars 1824 à Litomy&#353;l et mort le 12 mai 1884 à Prague, est un compositeur tchèque. Il est célèbre pour son poême symphonique Vltava (la Moldau en allemand), le second d''un cycle de six intitulé Má vlast ("Ma Patrie"), ainsi que pour son opéra La Fiancée vendue.','http://www.alsacreations.fr/mediaflash/mp3/moldau.mp3','audio/mpeg',2,0,0,0,'',0,0,0,0);
DROP TABLE IF EXISTS `phpboost_media_cat`;
CREATE TABLE `phpboost_media_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL DEFAULT '0',
  `c_order` int(11) unsigned NOT NULL DEFAULT '0',
  `auth` text,
  `name` varchar(255) NOT NULL DEFAULT '',
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `mime_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `active` int(11) unsigned NOT NULL DEFAULT '0',
  `description` text,
  `image` varchar(255) NOT NULL DEFAULT '',
  `num_media` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_media_cat` (`id`, `id_parent`, `c_order`, `auth`, `name`, `visible`, `mime_type`, `active`, `description`, `image`, `num_media`) VALUES (1,0,1,'a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;}','Vidéos de PHPboost',1,2,7914,'Cette catégorie contient des tutoriels vidéos afin de vous montrer certains actions que vous pourrez avoir besoin durant l''utilisation de <a href="http://www.phpboost.com/pages/videos-de-demonstration">PHPboost</a>. Ces vidéos sont en streaming..','../media/templates/images/video.png',3);
INSERT INTO `phpboost_media_cat` (`id`, `id_parent`, `c_order`, `auth`, `name`, `visible`, `mime_type`, `active`, `description`, `image`, `num_media`) VALUES (2,0,2,'a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;}','Démonstration',1,1,8191,'Voici une catégorie de démonstration.','../media/templates/images/audio.png',1);
DROP TABLE IF EXISTS `phpboost_member`;
CREATE TABLE `phpboost_member` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `level` tinyint(1) NOT NULL DEFAULT '0',
  `user_groups` text,
  `user_lang` varchar(25) NOT NULL DEFAULT '',
  `user_theme` varchar(50) NOT NULL DEFAULT '',
  `user_mail` varchar(50) NOT NULL DEFAULT '',
  `user_show_mail` tinyint(1) NOT NULL DEFAULT '1',
  `user_editor` varchar(15) NOT NULL DEFAULT '',
  `user_timezone` tinyint(2) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `user_avatar` varchar(255) NOT NULL DEFAULT '',
  `user_msg` mediumint(9) NOT NULL DEFAULT '0',
  `user_local` varchar(50) NOT NULL DEFAULT '',
  `user_msn` varchar(50) NOT NULL DEFAULT '',
  `user_yahoo` varchar(50) NOT NULL DEFAULT '',
  `user_web` varchar(70) NOT NULL DEFAULT '',
  `user_occupation` varchar(50) NOT NULL DEFAULT '',
  `user_hobbies` varchar(50) NOT NULL DEFAULT '',
  `user_desc` text,
  `user_sex` tinyint(1) NOT NULL DEFAULT '0',
  `user_born` date NOT NULL DEFAULT '0000-00-00',
  `user_sign` text,
  `user_pm` smallint(6) unsigned NOT NULL DEFAULT '0',
  `user_warning` smallint(6) NOT NULL DEFAULT '0',
  `user_readonly` int(11) NOT NULL DEFAULT '0',
  `last_connect` int(11) NOT NULL DEFAULT '0',
  `test_connect` tinyint(4) NOT NULL DEFAULT '0',
  `activ_pass` varchar(30) NOT NULL DEFAULT '0',
  `new_pass` varchar(64) NOT NULL DEFAULT '',
  `user_ban` int(11) NOT NULL DEFAULT '0',
  `user_aprob` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `login` (`login`),
  KEY `user_id` (`login`,`password`,`level`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_member` (`user_id`, `login`, `password`, `level`, `user_groups`, `user_lang`, `user_theme`, `user_mail`, `user_show_mail`, `user_editor`, `user_timezone`, `timestamp`, `user_avatar`, `user_msg`, `user_local`, `user_msn`, `user_yahoo`, `user_web`, `user_occupation`, `user_hobbies`, `user_desc`, `user_sex`, `user_born`, `user_sign`, `user_pm`, `user_warning`, `user_readonly`, `last_connect`, `test_connect`, `activ_pass`, `new_pass`, `user_ban`, `user_aprob`) VALUES (1,'teston','837a568aa5767f7fb4e193b14766fd8e9e4c4865538f7412f8e683653d0f46b4',2,'','french','base','teston@site.com',1,'',1,1268642401,'',1,'','','','','','','',0,'0000-00-00','',0,0,0,1275139245,0,0,'',0,1);
INSERT INTO `phpboost_member` (`user_id`, `login`, `password`, `level`, `user_groups`, `user_lang`, `user_theme`, `user_mail`, `user_show_mail`, `user_editor`, `user_timezone`, `timestamp`, `user_avatar`, `user_msg`, `user_local`, `user_msn`, `user_yahoo`, `user_web`, `user_occupation`, `user_hobbies`, `user_desc`, `user_sex`, `user_born`, `user_sign`, `user_pm`, `user_warning`, `user_readonly`, `last_connect`, `test_connect`, `activ_pass`, `new_pass`, `user_ban`, `user_aprob`) VALUES (2,'toto','8b72e58689ba70d7166699a9385a923fdc4ecddfcf3e04ea3f25da2f57f7af3d',0,0,'french','base','toto@site.com',0,'bbcode',1,1273867356,'',0,'','','','http://','','','',0,'0000-00-00','',0,0,0,1273867356,0,'c09cb30ec174e52','',0,0);
DROP TABLE IF EXISTS `phpboost_member_extend`;
CREATE TABLE `phpboost_member_extend` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `last_view_forum` int(11) NOT NULL DEFAULT '0',
  `f_test` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_member_extend` (`user_id`, `last_view_forum`, `f_test`) VALUES (2,0,'t');
DROP TABLE IF EXISTS `phpboost_member_extend_cat`;
CREATE TABLE `phpboost_member_extend_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `field_name` varchar(255) NOT NULL DEFAULT '',
  `contents` text,
  `field` tinyint(1) NOT NULL DEFAULT '0',
  `possible_values` text,
  `default_values` text,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `display` tinyint(1) NOT NULL DEFAULT '0',
  `regex` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_member_extend_cat` (`id`, `class`, `name`, `field_name`, `contents`, `field`, `possible_values`, `default_values`, `required`, `display`, `regex`) VALUES (1,0,'last_view_forum','last_view_forum','',0,'','',0,0,'');
INSERT INTO `phpboost_member_extend_cat` (`id`, `class`, `name`, `field_name`, `contents`, `field`, `possible_values`, `default_values`, `required`, `display`, `regex`) VALUES (2,1,'Test','f_test','',1,'','',1,1,0);
DROP TABLE IF EXISTS `phpboost_menu_configuration`;
CREATE TABLE `phpboost_menu_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `match_regex` mediumtext NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_menu_configuration` (`id`, `name`, `match_regex`, `priority`) VALUES (1,'default','`.*`',1);
DROP TABLE IF EXISTS `phpboost_menus`;
CREATE TABLE `phpboost_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `object` text,
  `class` varchar(64) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `block` tinyint(2) NOT NULL,
  `position` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `block` (`block`),
  KEY `class` (`class`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (1,'articles/articles_mini','O:14:"ModuleMiniMenu":7:{s:24:" ModuleMiniMenu filename";s:13:"articles_mini";s:5:" * id";s:1:"1";s:8:" * title";s:8:"articles";s:7:" * auth";a:3:{s:2:"r1";i:1;s:2:"r0";i:1;s:3:"r-1";i:1;}s:10:" * enabled";b:0;s:8:" * block";s:1:"8";s:11:" * position";s:1:"0";}','moduleminimenu',0,8,0);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (2,'connect/connect_mini','O:14:"ModuleMiniMenu":7:{s:24:" ModuleMiniMenu filename";s:12:"connect_mini";s:5:" * id";s:1:"2";s:8:" * title";s:7:"connect";s:7:" * auth";a:3:{s:2:"r1";i:1;s:2:"r0";i:1;s:3:"r-1";i:1;}s:10:" * enabled";b:1;s:8:" * block";s:1:"2";s:11:" * position";i:0;}','moduleminimenu',1,2,0);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (3,'faq/faq_mini','O:14:"ModuleMiniMenu":7:{s:24:" ModuleMiniMenu filename";s:8:"faq_mini";s:5:" * id";s:1:"3";s:8:" * title";s:3:"faq";s:7:" * auth";a:3:{s:2:"r1";i:1;s:2:"r0";i:1;s:3:"r-1";i:1;}s:10:" * enabled";b:0;s:8:" * block";s:1:"7";s:11:" * position";s:1:"1";}','moduleminimenu',0,7,1);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (4,'gallery/gallery_mini','O:14:"ModuleMiniMenu":7:{s:24:" ModuleMiniMenu filename";s:12:"gallery_mini";s:5:" * id";s:1:"4";s:8:" * title";s:7:"gallery";s:7:" * auth";a:3:{s:2:"r1";i:1;s:2:"r0";i:1;s:3:"r-1";i:1;}s:10:" * enabled";b:0;s:8:" * block";s:1:"8";s:11:" * position";s:1:"0";}','moduleminimenu',0,8,0);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (5,'guestbook/guestbook_mini','O:14:"ModuleMiniMenu":7:{s:24:" ModuleMiniMenu filename";s:14:"guestbook_mini";s:5:" * id";s:1:"5";s:8:" * title";s:9:"guestbook";s:7:" * auth";a:3:{s:2:"r1";i:1;s:2:"r0";i:1;s:3:"r-1";i:1;}s:10:" * enabled";b:0;s:8:" * block";s:1:"7";s:11:" * position";s:1:"1";}','moduleminimenu',0,7,1);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (6,'newsletter/newsletter_mini','O:14:"ModuleMiniMenu":7:{s:24:" ModuleMiniMenu filename";s:15:"newsletter_mini";s:5:" * id";s:1:"6";s:8:" * title";s:10:"newsletter";s:7:" * auth";a:3:{s:2:"r1";i:1;s:2:"r0";i:1;s:3:"r-1";i:1;}s:10:" * enabled";b:1;s:8:" * block";s:1:"5";s:11:" * position";i:0;}','moduleminimenu',1,5,0);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (7,'online/online_mini','O:14:"ModuleMiniMenu":7:{s:24:" ModuleMiniMenu filename";s:11:"online_mini";s:5:" * id";s:1:"7";s:8:" * title";s:6:"online";s:7:" * auth";a:3:{s:2:"r1";i:1;s:2:"r0";i:1;s:3:"r-1";i:1;}s:10:" * enabled";b:0;s:8:" * block";s:1:"8";s:11:" * position";s:1:"0";}','moduleminimenu',0,8,0);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (8,'poll/poll_mini','O:14:"ModuleMiniMenu":7:{s:24:" ModuleMiniMenu filename";s:9:"poll_mini";s:5:" * id";s:1:"8";s:8:" * title";s:4:"poll";s:7:" * auth";a:3:{s:2:"r1";i:1;s:2:"r0";i:1;s:3:"r-1";i:1;}s:10:" * enabled";b:0;s:8:" * block";s:1:"8";s:11:" * position";s:1:"1";}','moduleminimenu',0,8,0);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (9,'search/search_mini','O:14:"ModuleMiniMenu":7:{s:24:" ModuleMiniMenu filename";s:11:"search_mini";s:5:" * id";s:1:"9";s:8:" * title";s:6:"search";s:7:" * auth";a:3:{s:2:"r1";i:1;s:2:"r0";i:1;s:3:"r-1";i:1;}s:10:" * enabled";b:1;s:8:" * block";s:1:"1";s:11:" * position";i:0;}','moduleminimenu',1,1,0);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (10,'shoutbox/shoutbox_mini','O:14:"ModuleMiniMenu":7:{s:24:" ModuleMiniMenu filename";s:13:"shoutbox_mini";s:5:" * id";s:2:"10";s:8:" * title";s:8:"shoutbox";s:7:" * auth";a:3:{s:2:"r1";i:1;s:2:"r0";i:1;s:3:"r-1";i:1;}s:10:" * enabled";b:0;s:8:" * block";s:1:"8";s:11:" * position";s:1:"2";}','moduleminimenu',0,8,0);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (11,'stats/stats_mini','O:14:"ModuleMiniMenu":7:{s:24:" ModuleMiniMenu filename";s:10:"stats_mini";s:5:" * id";s:2:"11";s:8:" * title";s:5:"stats";s:7:" * auth";a:3:{s:2:"r1";i:1;s:2:"r0";i:1;s:3:"r-1";i:1;}s:10:" * enabled";b:0;s:8:" * block";s:1:"7";s:11:" * position";s:1:"1";}','moduleminimenu',0,7,1);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (12,'PHPBoost','O:9:"LinksMenu":12:{s:7:" * type";s:8:"vertical";s:11:" * elements";a:18:{i:0;O:13:"LinksMenuLink":10:{s:6:" * url";s:22:"/articles/articles.php";s:8:" * image";s:27:"/articles/articles_mini.png";s:6:" * uid";i:1765;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:8:"Articles";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:1;O:13:"LinksMenuLink":10:{s:6:" * url";s:18:"/sandbox/index.php";s:8:" * image";s:25:"/sandbox/sandbox_mini.png";s:6:" * uid";i:1766;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:11:"Bac à sable";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:2;O:13:"LinksMenuLink":10:{s:6:" * url";s:22:"/calendar/calendar.php";s:8:" * image";s:27:"/calendar/calendar_mini.png";s:6:" * uid";i:1767;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:10:"Calendrier";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:3;O:13:"LinksMenuLink":10:{s:6:" * url";s:20:"/contact/contact.php";s:8:" * image";s:25:"/contact/contact_mini.png";s:6:" * uid";i:1768;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:7:"Contact";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:4;O:13:"LinksMenuLink":10:{s:6:" * url";s:12:"/faq/faq.php";s:8:" * image";s:17:"/faq/faq_mini.png";s:6:" * uid";i:1769;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:5:"F.A.Q";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:5;O:13:"LinksMenuLink":10:{s:6:" * url";s:16:"/forum/index.php";s:8:" * image";s:21:"/forum/forum_mini.png";s:6:" * uid";i:1770;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:5:"Forum";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:6;O:13:"LinksMenuLink":10:{s:6:" * url";s:20:"/gallery/gallery.php";s:8:" * image";s:25:"/gallery/gallery_mini.png";s:6:" * uid";i:1771;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:7:"Galerie";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:7;O:13:"LinksMenuLink":10:{s:6:" * url";s:12:"/web/web.php";s:8:" * image";s:17:"/web/web_mini.png";s:6:" * uid";i:1772;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:9:"Liens web";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:8;O:13:"LinksMenuLink":10:{s:6:" * url";s:24:"/guestbook/guestbook.php";s:8:" * image";s:29:"/guestbook/guestbook_mini.png";s:6:" * uid";i:1773;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:10:"Livre d''or";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:9;O:13:"LinksMenuLink":10:{s:6:" * url";s:16:"/media/media.php";s:8:" * image";s:21:"/media/media_mini.png";s:6:" * uid";i:1774;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:10:"Multimédia";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:10;O:13:"LinksMenuLink":10:{s:6:" * url";s:14:"/news/news.php";s:8:" * image";s:19:"/news/news_mini.png";s:6:" * uid";i:1775;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:4:"News";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:11;O:13:"LinksMenuLink":10:{s:6:" * url";s:18:"/doc/3.0/index.php";s:8:" * image";s:17:"/doc/doc_mini.png";s:6:" * uid";i:1776;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:32:"PHPBoost framework documentation";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:12;O:13:"LinksMenuLink":10:{s:6:" * url";s:18:"/sitemap/index.php";s:8:" * image";s:25:"/sitemap/sitemap_mini.png";s:6:" * uid";i:1777;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:12:"Plan du site";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:13;O:13:"LinksMenuLink":10:{s:6:" * url";s:18:"/search/search.php";s:8:" * image";s:23:"/search/search_mini.png";s:6:" * uid";i:1778;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:9:"Recherche";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:14;O:13:"LinksMenuLink":10:{s:6:" * url";s:14:"/poll/poll.php";s:8:" * image";s:19:"/poll/poll_mini.png";s:6:" * uid";i:1779;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:8:"Sondages";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:15;O:13:"LinksMenuLink":10:{s:6:" * url";s:14:"/test/test.php";s:8:" * image";s:19:"/test/test_mini.png";s:6:" * uid";i:1780;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:15:"Tests unitaires";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:16;O:13:"LinksMenuLink":10:{s:6:" * url";s:22:"/download/download.php";s:8:" * image";s:27:"/download/download_mini.png";s:6:" * uid";i:1781;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:15:"Téléchargements";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}i:17;O:13:"LinksMenuLink":10:{s:6:" * url";s:14:"/wiki/wiki.php";s:8:" * image";s:19:"/wiki/wiki_mini.png";s:6:" * uid";i:1782;s:8:" * depth";i:1;s:5:" * id";i:0;s:8:" * title";s:4:"Wiki";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}}s:6:" * url";s:1:"/";s:8:" * image";s:0:"";s:6:" * uid";i:1764;s:8:" * depth";i:0;s:5:" * id";i:12;s:8:" * title";s:8:"PHPBoost";s:7:" * auth";N;s:10:" * enabled";b:1;s:8:" * block";i:7;s:11:" * position";i:0;}','linksmenu',1,7,0);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (13,'themeswitcher/themeswitcher','O:8:"MiniMenu":7:{s:23:" MiniMenu function_name";s:32:"menu_themeswitcher_themeswitcher";s:5:" * id";i:0;s:8:" * title";s:27:"themeswitcher/themeswitcher";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}','minimenu',0,0,-1);
INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES (14,'langswitcher/langswitcher','O:8:"MiniMenu":7:{s:23:" MiniMenu function_name";s:30:"menu_langswitcher_langswitcher";s:5:" * id";i:0;s:8:" * title";s:25:"langswitcher/langswitcher";s:7:" * auth";N;s:10:" * enabled";b:0;s:8:" * block";i:0;s:11:" * position";i:-1;}','minimenu',0,0,-1);
DROP TABLE IF EXISTS `phpboost_news`;
CREATE TABLE `phpboost_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcat` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `contents` mediumtext,
  `extend_contents` mediumtext,
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `start` int(11) NOT NULL DEFAULT '0',
  `end` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `img` varchar(255) NOT NULL DEFAULT '',
  `alt` varchar(255) NOT NULL DEFAULT '',
  `nbr_com` int(11) unsigned NOT NULL DEFAULT '0',
  `lock_com` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`),
  FULLTEXT KEY `extend_contents` (`extend_contents`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_news` (`id`, `idcat`, `title`, `contents`, `extend_contents`, `archive`, `timestamp`, `visible`, `start`, `end`, `user_id`, `img`, `alt`, `nbr_com`, `lock_com`) VALUES (1,1,'PHPBoost 3','Votre site boosté par PHPBoost 3 est bien installé. Afin de vous aider à prendre votre site en main, l''accueil de chaque module contient un message pour vous guider pour vos premiers pas. Voici également quelques recommandations supplémentaires que nous vous proposons de lire avec attention : <br />rn<br />rn<br /><h4 class="stitle1">N''oubliez pas de supprimer le répertoire ''install''</h4><br /><br />rnSupprimez le répertoire /install à la racine de votre site pour des raisons de sécurité afin que personne ne puisse recommencer l''installation.<br />rn<br />rn<br /><h4 class="stitle1">Administrez votre site</h4><br /><br />rnAccédez au <a href="/admin/admin_index.php">panneau d''administration de votre site</a> afin de le paramétrer comme vous le souhaitez!  Pour cela : <br />rn<br />rn<ul class="bb_ul">rn	<li class="bb_li"><a href="/admin/admin_maintain.php">Mettez votre site en maintenance</a> en attendant que vous le configuriez à votre guise.rn	</li><li class="bb_li">Rendez vous à la <a href="/admin/admin_config.php">Configuration générale du site</a>.rn	</li><li class="bb_li"><a href="/admin/admin_modules.php">Configurez les modules</a> disponibles et donnez leur les droits d''accès (si vous n''avez pas installé le pack complet, tous les modules sont disponibles sur le site de <a href="http://www.phpboost.com">phpboost.com</a> dans la section téléchargement).rn	</li><li class="bb_li"><a href="/admin/admin_content_config.php">Choisissez le langage de formatage du contenu</a> par défaut du site.rn	</li><li class="bb_li"><a href="/admin/admin_members_config.php">Configurez l''inscription des membres</a>.rn	</li><li class="bb_li"><a href="/admin/admin_themes.php">Choisissez le thème par défaut de votre site</a> pour changer l''apparence de votre site (vous pouvez en obtenir d''autres sur le site de <a href="http://www.phpboost.com">phpboost.com</a>).rn	</li><li class="bb_li"><a href="/news/admin_news_config.php">Modifiez l''édito</a> de votre site.rn	</li><li class="bb_li">Avant de donner l''accès de votre site à vos visiteurs, prenez un peu de temps pour y mettre du contenu.rn	</li><li class="bb_li">Enfin <a href="/admin/admin_maintain.php">désactivez la maintenance</a> de votre site afin qu''il soit visible par vos visiteurs.<br />rn</li></ul><br />rn<br />rn<br /><h4 class="stitle1">Que faire si vous rencontrez un problème ?</h4><br /><br />rnN''hésitez pas à consulter <a href="http://www.phpboost.com/wiki/wiki.php">la documentation de PHPBoost</a> ou de poser vos question sur le <a href="http://www.phpboost.com/forum/index.php">forum d''entraide</a>.<br />rn<br />rn<br />rn<p class="float_right">Toute l''équipe de PHPBoost vous remercie d''utiliser son logiciel pour créer votre site web!</p>','',0,1268642387,1,0,0,1,'/templates/base/theme/images/phpboost3.png','PHPBoost 3.0',0,0);
DROP TABLE IF EXISTS `phpboost_news_cat`;
CREATE TABLE `phpboost_news_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL DEFAULT '0',
  `c_order` int(11) unsigned NOT NULL DEFAULT '0',
  `auth` text,
  `name` varchar(255) NOT NULL DEFAULT '',
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `description` text,
  `image` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_news_cat` (`id`, `id_parent`, `c_order`, `auth`, `name`, `visible`, `description`, `image`) VALUES (1,0,1,'','Catégorie de test',1,'Catégorie de test !','/news/news.png');
DROP TABLE IF EXISTS `phpboost_newsletter`;
CREATE TABLE `phpboost_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_newsletter_arch`;
CREATE TABLE `phpboost_newsletter_arch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `message` text,
  `timestamp` bigint(20) NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT '',
  `nbr` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_pages`;
CREATE TABLE `phpboost_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `encoded_title` varchar(255) NOT NULL DEFAULT '',
  `contents` mediumtext,
  `auth` text,
  `is_cat` tinyint(1) NOT NULL DEFAULT '0',
  `id_cat` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `count_hits` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `activ_com` tinyint(1) NOT NULL DEFAULT '0',
  `lock_com` tinyint(1) NOT NULL DEFAULT '0',
  `nbr_com` int(11) NOT NULL DEFAULT '0',
  `redirect` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`encoded_title`),
  KEY `id` (`id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`),
  FULLTEXT KEY `all` (`title`,`contents`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_pages_cats`;
CREATE TABLE `phpboost_pages_cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_page` int(11) NOT NULL DEFAULT '0',
  `id_parent` int(11) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_pm_msg`;
CREATE TABLE `phpboost_pm_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idconvers` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `contents` text,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `view_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idconvers` (`idconvers`,`user_id`,`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_pm_topic`;
CREATE TABLE `phpboost_pm_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_id_dest` int(11) NOT NULL DEFAULT '0',
  `user_convers_status` tinyint(1) NOT NULL DEFAULT '0',
  `user_view_pm` int(11) NOT NULL DEFAULT '0',
  `nbr_msg` int(11) unsigned NOT NULL DEFAULT '0',
  `last_user_id` int(11) NOT NULL DEFAULT '0',
  `last_msg_id` int(11) NOT NULL DEFAULT '0',
  `last_timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`user_id_dest`,`user_convers_status`,`last_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_poll`;
CREATE TABLE `phpboost_poll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL DEFAULT '',
  `answers` text NOT NULL,
  `votes` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `start` int(11) NOT NULL DEFAULT '0',
  `end` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_poll` (`id`, `question`, `answers`, `votes`, `type`, `archive`, `timestamp`, `visible`, `start`, `end`, `user_id`) VALUES (1,'PHPBoost 3.0','Super site!|Pas mal|Plutôt moyen|Bof','|||',1,0,1268642388,1,0,0,1);
DROP TABLE IF EXISTS `phpboost_poll_ip`;
CREATE TABLE `phpboost_poll_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `idpoll` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_ranks`;
CREATE TABLE `phpboost_ranks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL DEFAULT '',
  `msg` int(11) NOT NULL DEFAULT '0',
  `icon` varchar(255) NOT NULL DEFAULT '',
  `special` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES (1,'Administrateur',-2,'rank_admin.png',1);
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES (2,'Modérateur',-1,'rank_modo.png',1);
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES (3,'Boosteur Inactif',0,'rank_0.png',0);
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES (4,'Booster Fronde',1,'rank_0.png',0);
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES (5,'Booster Minigun',25,'rank_1.png',0);
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES (6,'Booster Fuzil',50,'rank_2.png',0);
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES (7,'Booster Bazooka',100,'rank_3.png',0);
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES (8,'Booster Roquette',250,'rank_4.png',0);
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES (9,'Booster Mortier',500,'rank_5.png',0);
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES (10,'Booster Missile',1000,'rank_6.png',0);
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES (11,'Booster Fusée',1500,'rank_special.png',0);
DROP TABLE IF EXISTS `phpboost_search_index`;
CREATE TABLE `phpboost_search_index` (
  `id_search` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `module` varchar(64) NOT NULL DEFAULT '0',
  `search` varchar(50) NOT NULL DEFAULT '',
  `options` varchar(50) NOT NULL DEFAULT '',
  `last_search_use` int(14) NOT NULL DEFAULT '0',
  `times_used` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_search`),
  UNIQUE KEY `id_user` (`id_user`,`module`,`search`,`options`),
  KEY `last_search_use` (`last_search_use`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_search_results`;
CREATE TABLE `phpboost_search_results` (
  `id_search` int(11) NOT NULL AUTO_INCREMENT,
  `id_content` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `relevance` decimal(5,2) NOT NULL DEFAULT '0.00',
  `link` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_search`,`id_content`),
  KEY `relevance` (`relevance`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_sessions`;
CREATE TABLE `phpboost_sessions` (
  `session_id` varchar(64) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '0',
  `session_ip` varchar(50) NOT NULL DEFAULT '',
  `session_time` int(11) NOT NULL DEFAULT '0',
  `session_script` varchar(100) NOT NULL DEFAULT '0',
  `session_script_get` varchar(100) NOT NULL DEFAULT '0',
  `session_script_title` varchar(255) NOT NULL DEFAULT '',
  `session_flag` tinyint(1) NOT NULL DEFAULT '0',
  `user_theme` varchar(50) NOT NULL DEFAULT '',
  `user_lang` varchar(50) NOT NULL DEFAULT '',
  `modules_parameters` text,
  `token` varchar(64) NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `user_id` (`user_id`,`session_time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_sessions` (`session_id`, `user_id`, `level`, `session_ip`, `session_time`, `session_script`, `session_script_get`, `session_script_title`, `session_flag`, `user_theme`, `user_lang`, `modules_parameters`, `token`) VALUES ('54ce7daece276590edda8c98ed3b6a5a0a59d64c98644e1e3247aeb67ff1c9fc',1,2,'0.0.0.0',1275151223,'/database/admin_database.php','action=backup','Gestion base de données',0,'','','','d4023b44a3991bf0');
DROP TABLE IF EXISTS `phpboost_shoutbox`;
CREATE TABLE `phpboost_shoutbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(150) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '0',
  `contents` text,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_shoutbox` (`id`, `login`, `user_id`, `level`, `contents`, `timestamp`) VALUES (1,'Equipe PHPBoost',-1,-1,'l''équipe de PHPBoost vous souhaite la bienvenue!',1268642388);
DROP TABLE IF EXISTS `phpboost_smileys`;
CREATE TABLE `phpboost_smileys` (
  `idsmiley` int(11) NOT NULL AUTO_INCREMENT,
  `code_smiley` varchar(50) NOT NULL DEFAULT '',
  `url_smiley` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`idsmiley`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (9,':|','waw.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (5,':siffle','siffle.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (18,':)','sourire.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (43,':lol','rire.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (10,':p','tirelangue.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (11,':(','malheureux.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (44,';)','clindoeil.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (22,':heink','heink.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (14,':D','heureux.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (15,':d','content.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (16,':s','incertain.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (19,':gne','pinch.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (21,':top','top.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (24,':clap','clap.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (26,':hehe','hehe.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (27,':@','angry.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (29,':''(','snif.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (30,':nex','nex.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (34,'8-)','star.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (32,'|-)','nuit.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (35,':berk','berk.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (36,':gre','colere.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (37,':love','love.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (38,':hum','doute.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (39,':mat','mat.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (40,':miam','miam.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (41,':+1','plus1.gif');
INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES (42,':lu','lu.gif');
DROP TABLE IF EXISTS `phpboost_stats`;
CREATE TABLE `phpboost_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stats_year` smallint(6) NOT NULL DEFAULT '0',
  `stats_month` tinyint(4) NOT NULL DEFAULT '0',
  `stats_day` tinyint(4) NOT NULL DEFAULT '0',
  `nbr` mediumint(9) NOT NULL DEFAULT '0',
  `pages` int(11) NOT NULL DEFAULT '0',
  `pages_detail` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stats_day` (`stats_day`,`stats_month`,`stats_year`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (1,2010,3,14,1,0,'a:0:{}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (2,2010,3,20,1,18,'a:2:{i:9;i:2;i:15;i:16;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (3,2010,3,21,1,5,'a:2:{i:9;i:4;i:18;i:1;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (4,2010,4,2,1,1,'a:1:{i:13;i:1;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (5,2010,4,3,1,16,'a:7:{i:9;i:1;i:10;i:1;i:12;i:2;i:18;i:1;i:19;i:1;i:20;i:8;i:21;i:2;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (6,2010,4,4,1,3,'a:2:{i:20;i:1;i:21;i:2;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (7,2010,4,9,1,3,'a:3:{i:9;i:1;i:11;i:1;i:18;i:1;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (8,2010,4,16,1,83,'a:6:{i:14;i:7;i:15;i:24;i:16;i:20;i:17;i:2;i:18;i:13;i:19;i:17;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (9,2010,4,23,1,1,'a:1:{i:21;i:1;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (10,2010,5,7,1,0,'a:0:{}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (11,2010,5,12,1,1,'a:1:{i:9;i:1;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (12,2010,5,13,1,1,'a:1:{i:10;i:1;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (13,2010,5,15,2,3,'a:1:{i:21;i:3;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (14,2010,5,20,1,8,'a:1:{i:9;i:8;}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (15,2010,5,22,1,0,'a:0:{}');
INSERT INTO `phpboost_stats` (`id`, `stats_year`, `stats_month`, `stats_day`, `nbr`, `pages`, `pages_detail`) VALUES (16,2010,5,28,2,1,'a:1:{i:22;i:1;}');
DROP TABLE IF EXISTS `phpboost_stats_referer`;
CREATE TABLE `phpboost_stats_referer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL DEFAULT '',
  `relative_url` varchar(255) NOT NULL DEFAULT '',
  `total_visit` int(11) NOT NULL DEFAULT '0',
  `today_visit` int(11) NOT NULL DEFAULT '0',
  `yesterday_visit` int(11) NOT NULL DEFAULT '0',
  `nbr_day` int(11) NOT NULL DEFAULT '0',
  `last_update` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `url` (`url`,`relative_url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_themes`;
CREATE TABLE `phpboost_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme` varchar(50) NOT NULL DEFAULT '',
  `activ` tinyint(1) NOT NULL DEFAULT '0',
  `secure` tinyint(1) NOT NULL DEFAULT '0',
  `left_column` tinyint(1) NOT NULL DEFAULT '0',
  `right_column` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_themes` (`id`, `theme`, `activ`, `secure`, `left_column`, `right_column`) VALUES (1,'base',1,-1,1,0);
DROP TABLE IF EXISTS `phpboost_upload`;
CREATE TABLE `phpboost_upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcat` int(11) NOT NULL DEFAULT '0',
  `name` varchar(150) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `size` float NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_upload_cat`;
CREATE TABLE `phpboost_upload_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_verif_code`;
CREATE TABLE `phpboost_verif_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(15) NOT NULL DEFAULT '',
  `code` varchar(20) NOT NULL DEFAULT '',
  `difficulty` tinyint(1) NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_verif_code` (`id`, `user_id`, `code`, `difficulty`, `timestamp`) VALUES (1,'83a5af21944d21','cf818d17cc57e78579a0',2,1268665087);
DROP TABLE IF EXISTS `phpboost_visit_counter`;
CREATE TABLE `phpboost_visit_counter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL DEFAULT '',
  `time` date NOT NULL DEFAULT '0000-00-00',
  `total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_visit_counter` (`id`, `ip`, `time`, `total`) VALUES (1,3,'2010-05-29',1);
INSERT INTO `phpboost_visit_counter` (`id`, `ip`, `time`, `total`) VALUES (20,'0.0.0.0','2010-05-29',0);
DROP TABLE IF EXISTS `phpboost_web`;
CREATE TABLE `phpboost_web` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcat` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `contents` text,
  `url` text,
  `compt` int(11) NOT NULL DEFAULT '0',
  `aprob` tinyint(1) NOT NULL DEFAULT '1',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `users_note` text,
  `nbrnote` mediumint(9) NOT NULL DEFAULT '0',
  `note` float NOT NULL DEFAULT '0',
  `nbr_com` int(11) unsigned NOT NULL DEFAULT '0',
  `lock_com` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idcat` (`idcat`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_web` (`id`, `idcat`, `title`, `contents`, `url`, `compt`, `aprob`, `timestamp`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`) VALUES (1,1,'PHPBoost','<p class="float_right"><img src="../templates/base/theme/images/phpboost_box_3_0.png" alt="" class="valign_" /></p><br />rnPHPBoost est un CMS (Content Managing System ou système de gestion de contenu) français. Ce logiciel permet à n''importe qui de créer son site de façon très simple, tout est assisté. Conçu pour satisfaire les débutants, il devrait aussi ravir les utilisateurs expérimentés qui souhaiteraient pousser son fonctionnement ou encore développer leurs propres modules.<br />rnPHPBoost est un logiciel libre distribué sous la licence GPL.<br />rn<br />rnComme son nom l''indique, PHPBoost utilise le PHP comme langage de programmation principal, mais, comme toute application Web, il utilise du XHTML et des CSS pour la mise en forme des pages, du JavaScript pour ajouter une touche dynamique sur les pages, ainsi que du SQL pour effectuer des opérations dans la base de données. Il s''installe sur un serveur Web et se paramètre à distance.<br />rn<br />rnComme pour une grande majorité de logiciels libres, la communauté de PHPBoost lui permet d''avoir à la fois une fiabilité importante car beaucoup d''utilisateurs ont testé chaque version et les ont ainsi approuvées. Il bénéficie aussi par ailleurs d''une évolution rapide car nous essayons d''être le plus possible à l''écoute des commentaires et des propositions de chacun. Même si tout le monde ne participe pas à son développement, beaucoup de gens nous ont aidés, rien qu''en nous donnant des idées, nous suggérant des modifications, des fonctionnalités supplémentaires.<br />rn<br />rnSi vous ne deviez retenir que quelques points essentiels sur le projet, ce seraient ceux-ci :<br />rn<br />rn    * Projet Open Source sous licence GNU/GPL<br />rn    * Code XHTML 1.0 strict et sémantique<br />rn    * Multilangue<br />rn    * Facilement personnalisable grâce aux thèmes et templates<br />rn    * Gestion fine des droits et des groupes multiples pour chaque utilisateur<br />rn    * Url rewriting<br />rn    * Installation et mise à jour automatisées des modules et du noyau<br />rn    * Aide au développement de nouveaux modules grâce au framework de PHPBoost','http://www.phpboost.com',0,1,1234956484,0,0,0,0,0);
DROP TABLE IF EXISTS `phpboost_web_cat`;
CREATE TABLE `phpboost_web_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL DEFAULT '0',
  `name` varchar(150) NOT NULL DEFAULT '',
  `contents` text,
  `icon` varchar(255) NOT NULL DEFAULT '',
  `aprob` tinyint(1) NOT NULL DEFAULT '0',
  `secure` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `class` (`class`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_web_cat` (`id`, `class`, `name`, `contents`, `icon`, `aprob`, `secure`) VALUES (1,1,'Catégorie de test','Liens de test','web.png',1,-1);
DROP TABLE IF EXISTS `phpboost_wiki_articles`;
CREATE TABLE `phpboost_wiki_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_contents` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `encoded_title` varchar(250) NOT NULL DEFAULT '',
  `hits` int(11) NOT NULL DEFAULT '0',
  `id_cat` int(11) NOT NULL DEFAULT '0',
  `is_cat` tinyint(1) NOT NULL DEFAULT '0',
  `defined_status` smallint(6) NOT NULL DEFAULT '0',
  `undefined_status` text,
  `redirect` int(11) NOT NULL DEFAULT '0',
  `auth` text,
  `nbr_com` int(11) unsigned NOT NULL DEFAULT '0',
  `lock_com` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`encoded_title`),
  KEY `id` (`id`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_wiki_cats`;
CREATE TABLE `phpboost_wiki_cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL DEFAULT '0',
  `article_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_wiki_contents`;
CREATE TABLE `phpboost_wiki_contents` (
  `id_contents` int(11) NOT NULL AUTO_INCREMENT,
  `id_article` int(11) NOT NULL DEFAULT '0',
  `menu` text,
  `content` mediumtext,
  `activ` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_ip` varchar(50) NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_contents`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `phpboost_wiki_favorites`;
CREATE TABLE `phpboost_wiki_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `id_article` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
