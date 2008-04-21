-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Samedi 19 Avril 2008 à 14:38
-- Version du serveur: 4.1.9
-- Version de PHP: 4.3.10
-- 
-- Base de données: `phpboost`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_articles`
-- 

CREATE TABLE `phpboost_articles` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `contents` text NOT NULL,
  `icon` varchar(255) NOT NULL default '',
  `timestamp` int(11) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '0',
  `start` int(11) NOT NULL default '0',
  `end` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `views` mediumint(9) NOT NULL default '0',
  `users_note` text NOT NULL,
  `nbrnote` mediumint(9) NOT NULL default '0',
  `note` smallint(6) NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `phpboost_articles`
-- 

INSERT INTO `phpboost_articles` VALUES (1, 0, 'testfsdds', ' Page1 contents<br />\r\n<br />\r\n<br />\r\n[img]http://127.0.0.1/PHP/PHPBoost/PHPBoost_2.1/articles/articles.png[/img]<br />\r\n*<br />\r\ndgdfg<br />\r\ngfdgdf<br />\r\ng<br />\r\n<br />\r\n<br />\r\ngdfgdfgdf ', '', 1194656401, 1, 0, 0, 1, 55, '1', 1, 8, 1, 0);
INSERT INTO `phpboost_articles` VALUES (2, 1, 'test', ' [page]Introduction[/page]<br />\r\n<br />\r\nPage1 contents<br />\r\nfdfdsf dsfdsfds<strong>fdsfsd</strong><br />\r\n<br />\r\n<br />\r\n[page]Le test[/page]<br />\r\n<br />\r\nPage2 contents<br />\r\n<br />\r\n<br />\r\n[page]Conclusion[/page]<br />\r\nPage3 contents<br />\r\n<br />\r\nsfsd<br />\r\n<span class="notice">gdfgfdgdf <br />\r\ngfdgf gdfgdfg dfgdf</span><br />\r\n<br />\r\n[page]test[/page][page]tsfds[/page][page]fdsf[/page] ', 'articles.png', 1207872781, 1, 0, 0, 1, 172, '1/2', 2, 7, 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_articles_cats`
-- 

CREATE TABLE `phpboost_articles_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_left` int(11) NOT NULL default '0',
  `id_right` int(11) NOT NULL default '0',
  `level` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `contents` text NOT NULL,
  `nbr_articles_visible` mediumint(9) unsigned NOT NULL default '0',
  `nbr_articles_unvisible` mediumint(9) unsigned NOT NULL default '0',
  `icon` varchar(255) NOT NULL default '',
  `aprob` tinyint(1) NOT NULL default '0',
  `auth` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id_left` (`id_left`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `phpboost_articles_cats`
-- 

INSERT INTO `phpboost_articles_cats` VALUES (1, 1, 2, 0, 'fgg', 'gfdgdf', 1, 0, '../news/news.png', 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}');
INSERT INTO `phpboost_articles_cats` VALUES (2, 3, 4, 0, 'etst', 'tsd', 0, 0, 'articles.png', 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}');
INSERT INTO `phpboost_articles_cats` VALUES (3, 5, 6, 0, 'etst', 'tsd', 0, 0, 'articles.png', 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_calendar`
-- 

CREATE TABLE `phpboost_calendar` (
  `id` int(11) NOT NULL auto_increment,
  `timestamp` int(11) NOT NULL default '0',
  `title` varchar(150) NOT NULL default '',
  `contents` text NOT NULL,
  `user_id` int(11) NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `phpboost_calendar`
-- 

INSERT INTO `phpboost_calendar` VALUES (1, 1208560921, 'bcvb', 'bcvbv', 1, 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_com`
-- 

CREATE TABLE `phpboost_com` (
  `idcom` int(11) NOT NULL auto_increment,
  `idprov` int(11) NOT NULL default '0',
  `login` varchar(255) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `contents` text NOT NULL,
  `timestamp` int(11) NOT NULL default '0',
  `script` varchar(20) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`idcom`),
  KEY `idprov` (`idprov`,`script`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- 
-- Contenu de la table `phpboost_com`
-- 

INSERT INTO `phpboost_com` VALUES (1, 1, 'crowkait', 1, 'test', 1194140922, 'news', '');
INSERT INTO `phpboost_com` VALUES (2, 1, 'crowkait', 1, 'ghf <em>h gfhgf</em>', 1197136425, 'news', '');
INSERT INTO `phpboost_com` VALUES (3, 2, 'crowkait', 1, 'fsdfsd  gfd', 1197141621, 'pages', '');
INSERT INTO `phpboost_com` VALUES (4, 1, 'Visiteur', -1, 'gdfgdf', 1198350890, 'news', '');
INSERT INTO `phpboost_com` VALUES (5, 4, 'crowkait', 1, 'fdsfds', 1199309488, 'news', '');
INSERT INTO `phpboost_com` VALUES (6, 1, 'crowkait', 1, 'sfsd', 1203301128, 'articles', '../articles/articles.php?cat=0&id=1&p=&i=0');
INSERT INTO `phpboost_com` VALUES (7, 6, 'crowkait', 1, 'test <strong>gdfgdf</strong>', 1204976789, 'news', '../news/news.php?cat=0&id=6&p=&i=0');
INSERT INTO `phpboost_com` VALUES (8, 6, 'crowkait', 1, 'gfgdf', 1204977442, 'news', '../news/news.php?cat=0&id=6&p=&i=0');
INSERT INTO `phpboost_com` VALUES (10, 6, 'crowkait', 1, 'gfgdf!!', 1204982491, 'news', '../news/news.php');
INSERT INTO `phpboost_com` VALUES (15, 6, 'crowkait', 1, '<span class="text_blockquote">crowkait:</span><div class="blockquote">dfghdf!!! <img src="../images/smileys/clap.gif" alt=":clap" class="smiley" /> yhh</div><strong>ttt</strong>', 1204986146, 'news', '../news/');
INSERT INTO `phpboost_com` VALUES (14, 6, 'crowkait', 1, 'dfghdf!!! <img src="../images/smileys/clap.gif" alt=":clap" class="smiley" /> yhh', 1204986021, 'news', '../news/');
INSERT INTO `phpboost_com` VALUES (18, 6, 'crowkait', 1, '<span class="text_blockquote">crowkait:</span><div class="blockquote">test <img src="../images/smileys/plus1.gif" alt=":+1" class="smiley" /></div> hgfh gdfgdf', 1204988753, 'news', '../includes/com.php');
INSERT INTO `phpboost_com` VALUES (17, 6, 'crowkait', 1, 'test <img src="../images/smileys/plus1.gif" alt=":+1" class="smiley" />', 1204987291, 'news', '../includes/com.php');
INSERT INTO `phpboost_com` VALUES (27, 5, 'crowkait', 1, 'hghgf  <img src="../images/smileys/lu.gif" alt=":lu" class="smiley" />', 1204990909, 'wiki_articles', '../wiki/');
INSERT INTO `phpboost_com` VALUES (25, 2, 'crowkait', 1, 'test', 1204990492, 'web', '../web/');
INSERT INTO `phpboost_com` VALUES (30, 6, 'crowkait', 1, 'cxwcxw', 1204992335, 'news', '../news/news.php?cat=0&amp;id=6&amp;p=&amp;i=0');
INSERT INTO `phpboost_com` VALUES (31, 6, 'crowkait', 1, ' gdfgdf ', 1207732414, 'news', '../news/news.php?cat=0&amp;id=6&amp;p=&amp;i=0');
INSERT INTO `phpboost_com` VALUES (32, 5, 'crowkait', 1, ' test ', 1207736546, 'wiki_articles', '../wiki/property.php?com=5&amp;i=0');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_compteur`
-- 

CREATE TABLE `phpboost_compteur` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(50) NOT NULL default '',
  `time` date NOT NULL default '0000-00-00',
  `total` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=82 ;

-- 
-- Contenu de la table `phpboost_compteur`
-- 

INSERT INTO `phpboost_compteur` VALUES (1, '1', '2008-04-19', 1);
INSERT INTO `phpboost_compteur` VALUES (81, '127.0.0.1', '2008-04-19', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_configs`
-- 

CREATE TABLE `phpboost_configs` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- 
-- Contenu de la table `phpboost_configs`
-- 

INSERT INTO `phpboost_configs` VALUES (1, 'config', 'a:34:{s:11:"server_name";s:16:"http://127.0.0.1";s:11:"server_path";s:26:"/PHP/PHPBoost/PHPBoost_2.1";s:9:"site_name";s:3:"Dev";s:9:"site_desc";s:0:"";s:12:"site_keyword";s:0:"";s:5:"start";i:1194223434;s:7:"version";s:3:"2.0";s:4:"lang";s:6:"french";s:5:"theme";s:8:"phpboost";s:6:"editor";s:7:"tinymce";s:8:"timezone";i:1;s:10:"start_page";s:14:"/news/news.php";s:8:"maintain";i:1198037648;s:14:"maintain_delay";i:1;s:22:"maintain_display_admin";i:1;s:13:"maintain_text";s:0:"";s:7:"rewrite";i:1;s:9:"com_popup";i:0;s:8:"compteur";i:0;s:5:"bench";i:1;s:12:"theme_author";i:0;s:12:"ob_gzhandler";i:1;s:11:"site_cookie";s:7:"session";s:12:"site_session";i:3600;s:18:"site_session_invit";i:300;s:4:"mail";s:15:"crowkait@sd.com";s:10:"activ_mail";i:0;s:4:"sign";s:0:"";s:10:"anti_flood";i:1;s:11:"delay_flood";i:7;s:12:"unlock_admin";s:32:"29331ebd3003dd04ba47205a8077f785";s:6:"pm_max";i:5;s:17:"search_cache_time";i:5;s:14:"search_max_use";i:5;}');
INSERT INTO `phpboost_configs` VALUES (2, 'member', 'a:13:{s:14:"activ_register";i:1;s:7:"msg_mbr";s:0:"";s:12:"msg_register";s:0:"";s:9:"activ_mbr";i:1;s:10:"verif_code";i:1;s:17:"delay_unactiv_max";i:30;s:11:"force_theme";i:0;s:15:"activ_up_avatar";i:1;s:9:"width_max";i:120;s:10:"height_max";i:120;s:10:"weight_max";i:20;s:12:"activ_avatar";i:1;s:10:"avatar_url";s:13:"no_avatar.jpg";}');
INSERT INTO `phpboost_configs` VALUES (3, 'files', 'a:3:{s:10:"size_limit";d:512;s:17:"bandwidth_protect";s:1:"1";s:10:"auth_files";s:45:"a:3:{s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}";}');
INSERT INTO `phpboost_configs` VALUES (4, 'com', 'a:4:{s:8:"com_auth";i:0;s:7:"com_max";i:10;s:14:"forbidden_tags";s:117:"a:7:{i:0;s:3:"swf";i:1;s:5:"movie";i:2;s:5:"sound";i:3;s:4:"code";i:4;s:4:"math";i:5;s:6:"anchor";i:6;s:7:"acronym";}";s:8:"max_link";i:2;}');
INSERT INTO `phpboost_configs` VALUES (5, 'articles', 'a:5:{s:16:"nbr_articles_max";i:10;s:11:"nbr_cat_max";i:2;s:10:"nbr_column";i:2;s:8:"note_max";i:10;s:9:"auth_root";s:59:"a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}";}');
INSERT INTO `phpboost_configs` VALUES (25, 'calendar', 'a:1:{s:13:"calendar_auth";i:2;}');
INSERT INTO `phpboost_configs` VALUES (26, 'download', 'a:4:{s:12:"nbr_file_max";i:10;s:11:"nbr_cat_max";i:10;s:10:"nbr_column";i:2;s:8:"note_max";i:5;}');
INSERT INTO `phpboost_configs` VALUES (8, 'forum', 'a:15:{s:10:"forum_name";s:14:"PHPBoost forum";s:16:"pagination_topic";i:20;s:14:"pagination_msg";i:10;s:9:"view_time";i:2592000;s:11:"topic_track";i:40;s:9:"edit_mark";i:1;s:14:"no_left_column";i:0;s:15:"no_right_column";i:0;s:17:"activ_display_msg";i:1;s:11:"display_msg";s:7:"[Réglé]";s:19:"explain_display_msg";s:12:"Sujet réglé?";s:23:"explain_display_msg_bis";s:16:"Sujet non réglé?";s:22:"icon_activ_display_msg";i:1;s:4:"auth";s:58:"a:4:{s:2:"m1";i:2;s:2:"m2";i:4;s:2:"r1";i:5;s:2:"r2";i:7;}";s:17:"display_connexion";i:1;}');
INSERT INTO `phpboost_configs` VALUES (9, 'gallery', 'a:27:{s:5:"width";i:145;s:6:"height";i:145;s:9:"width_max";i:800;s:10:"height_max";i:600;s:10:"weight_max";i:1024;s:7:"quality";i:80;s:5:"trans";i:40;s:4:"logo";s:8:"logo.jpg";s:10:"activ_logo";i:1;s:7:"d_width";i:5;s:8:"d_height";i:5;s:10:"nbr_column";i:4;s:12:"nbr_pics_max";i:16;s:8:"note_max";i:5;s:11:"activ_title";i:1;s:9:"activ_com";i:1;s:10:"activ_note";i:1;s:15:"display_nbrnote";i:1;s:10:"activ_view";i:1;s:10:"activ_user";i:1;s:12:"limit_member";i:10;s:10:"limit_modo";i:25;s:12:"display_pics";i:3;s:11:"scroll_type";i:0;s:13:"nbr_pics_mini";i:2;s:15:"speed_mini_pics";i:6;s:9:"auth_root";s:59:"a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}";}');
INSERT INTO `phpboost_configs` VALUES (10, 'guestbook', 'a:3:{s:14:"guestbook_auth";i:-1;s:24:"guestbook_forbidden_tags";s:52:"a:3:{i:0;s:3:"swf";i:1;s:5:"movie";i:2;s:5:"sound";}";s:18:"guestbook_max_link";i:2;}');
INSERT INTO `phpboost_configs` VALUES (11, 'news', 'a:13:{s:4:"type";i:0;s:11:"activ_pagin";i:1;s:11:"activ_edito";i:0;s:15:"pagination_news";i:15;s:15:"pagination_arch";i:15;s:9:"activ_com";i:1;s:10:"activ_icon";i:1;s:14:"display_author";i:1;s:12:"display_date";i:1;s:8:"nbr_news";s:1:"4";s:10:"nbr_column";i:1;s:5:"edito";s:22:"Bienvenue sur le site!";s:11:"edito_title";s:22:"Bienvenue sur le site!";}');
INSERT INTO `phpboost_configs` VALUES (12, 'newsletter', 'a:2:{s:11:"sender_mail";s:0:"";s:15:"newsletter_name";s:0:"";}');
INSERT INTO `phpboost_configs` VALUES (13, 'online', 'a:2:{s:16:"online_displayed";i:4;s:20:"display_order_online";s:28:"s.level, s.session_time DESC";}');
INSERT INTO `phpboost_configs` VALUES (14, 'pages', 'a:3:{s:10:"count_hits";i:1;s:9:"activ_com";i:1;s:4:"auth";s:59:"a:4:{s:3:"r-1";i:5;s:2:"r0";i:7;s:2:"r1";i:7;s:2:"r2";i:7;}";}');
INSERT INTO `phpboost_configs` VALUES (15, 'poll', 'a:4:{s:9:"poll_auth";i:0;s:9:"poll_mini";a:2:{i:0;s:1:"1";i:1;s:1:"3";}s:11:"poll_cookie";s:4:"poll";s:18:"poll_cookie_lenght";i:1814400;}');
INSERT INTO `phpboost_configs` VALUES (16, 'shoutbox', 'a:5:{s:16:"shoutbox_max_msg";i:100;s:13:"shoutbox_auth";i:-1;s:23:"shoutbox_forbidden_tags";s:428:"a:26:{i:0;s:5:"title";i:1;s:6:"stitle";i:2;s:5:"style";i:3;s:3:"url";i:4;s:3:"img";i:5;s:5:"quote";i:6;s:4:"hide";i:7;s:4:"list";i:8;s:5:"color";i:9;s:7:"bgcolor";i:10;s:4:"font";i:11;s:4:"size";i:12;s:5:"align";i:13;s:5:"float";i:14;s:3:"sup";i:15;s:3:"sub";i:16;s:6:"indent";i:17;s:3:"pre";i:18;s:5:"table";i:19;s:3:"swf";i:20;s:5:"movie";i:21;s:5:"sound";i:22;s:4:"code";i:23;s:4:"math";i:24;s:6:"anchor";i:25;s:7:"acronym";}";s:17:"shoutbox_max_link";i:2;s:22:"shoutbox_refresh_delay";d:30000;}');
INSERT INTO `phpboost_configs` VALUES (17, 'web', 'a:4:{s:11:"nbr_web_max";i:10;s:11:"nbr_cat_max";i:10;s:10:"nbr_column";i:2;s:8:"note_max";i:5;}');
INSERT INTO `phpboost_configs` VALUES (18, 'wiki', 'a:6:{s:9:"wiki_name";s:4:"Wiki";s:13:"last_articles";i:10;s:12:"display_cats";i:0;s:10:"index_text";s:0:"";s:10:"count_hits";i:1;s:4:"auth";s:71:"a:4:{s:3:"r-1";i:1041;s:2:"r0";i:1495;s:2:"r1";i:4095;s:2:"r2";i:4095;}";}');
INSERT INTO `phpboost_configs` VALUES (27, 'faq', 'a:5:{s:8:"faq_name";s:12:"FAQ PHPBoost";s:8:"num_cols";i:3;s:13:"display_block";b:1;s:11:"global_auth";a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:3;s:2:"r2";i:3;}s:4:"root";a:3:{s:12:"display_mode";i:0;s:4:"auth";a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:3;s:2:"r2";i:3;}s:11:"description";s:30:"Bienvenue sur la FAQ PHPBoost.";}}');
INSERT INTO `phpboost_configs` VALUES (24, 'search', 'a:2:{s:19:"nb_results_per_page";i:15;s:18:"authorised_modules";a:2:{i:0;s:5:"forum";i:1;s:4:"wiki";}}');
INSERT INTO `phpboost_configs` VALUES (20, 'contact', 'a:1:{s:17:"contact_verifcode";i:1;}');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_download`
-- 

CREATE TABLE `phpboost_download` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `short_contents` text NOT NULL,
  `contents` text NOT NULL,
  `url` text NOT NULL,
  `image` varchar(255) NOT NULL default '',
  `size` float NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '0',
  `start` int(11) NOT NULL default '0',
  `end` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `users_note` text NOT NULL,
  `nbrnote` mediumint(9) NOT NULL default '0',
  `note` float NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `phpboost_download`
-- 

INSERT INTO `phpboost_download` VALUES (1, 1, 'test', '', ' test ', 'test', '', 0, 10, 1207761961, 1, 0, 0, 1, '1', 1, 1.75, 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_download_cat`
-- 

CREATE TABLE `phpboost_download_cat` (
  `id` int(11) NOT NULL auto_increment,
  `c_order` int(11) NOT NULL default '0',
  `id_parent` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL default '',
  `contents` text NOT NULL,
  `icon` varchar(255) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '1',
  `auth` text NOT NULL,
  `num_files` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `class` (`c_order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `phpboost_download_cat`
-- 

INSERT INTO `phpboost_download_cat` VALUES (1, 1, 0, 'testr', 'test', 'download.png', 1, 'a:1:{s:2:"r2";i:3;}', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_faq`
-- 

CREATE TABLE `phpboost_faq` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `q_order` int(11) NOT NULL default '0',
  `question` varchar(255) NOT NULL default '',
  `answer` text NOT NULL,
  `user_id` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `question` (`question`),
  FULLTEXT KEY `answer` (`answer`),
  FULLTEXT KEY `question_2` (`question`,`answer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `phpboost_faq`
-- 

INSERT INTO `phpboost_faq` VALUES (1, 0, 2, 'gfdgfd', 'gdfgdf<br />\r\n<br />\r\n<br />\r\nfhgfhgf', 1, 1208455343);
INSERT INTO `phpboost_faq` VALUES (2, 0, 1, 'Tête de con', 'fdsfsdf sdf dsf dfds<br />\r\nfsdfsdf ds ds<br />\r\nf dsfd<br />\r\n<br />\r\nfsdfsd sdfsdfsdfdf sdfdsf ds<br />\r\nfsdf sdfsdfsd fdsfsdfdsfsdfsdf ds fdsfdsfds', 1, 1208460838);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_faq_cats`
-- 

CREATE TABLE `phpboost_faq_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `c_order` int(11) unsigned NOT NULL default '0',
  `auth` text NOT NULL,
  `name` varchar(255) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '0',
  `display_mode` tinyint(2) NOT NULL default '0',
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL default '',
  `num_questions` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `phpboost_faq_cats`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_forum_alerts`
-- 

CREATE TABLE `phpboost_forum_alerts` (
  `id` mediumint(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `idtopic` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `contents` text NOT NULL,
  `user_id` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `idmodo` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idtopic` (`idtopic`,`user_id`,`idmodo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `phpboost_forum_alerts`
-- 

INSERT INTO `phpboost_forum_alerts` VALUES (1, 6, 42, 'test', 'tes', 1, 0, 0, 1203465440);
INSERT INTO `phpboost_forum_alerts` VALUES (2, 13, 41, 'fdsfsd', 'fsdfsd', 1, 1, 1, 1203712689);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_forum_cats`
-- 

CREATE TABLE `phpboost_forum_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_left` int(11) NOT NULL default '0',
  `id_right` int(11) NOT NULL default '0',
  `level` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `subname` varchar(150) NOT NULL default '',
  `nbr_topic` mediumint(9) NOT NULL default '0',
  `nbr_msg` mediumint(9) NOT NULL default '0',
  `last_topic_id` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  `aprob` tinyint(1) NOT NULL default '0',
  `auth` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `last_topic_id` (`last_topic_id`),
  KEY `id_left` (`id_left`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- 
-- Contenu de la table `phpboost_forum_cats`
-- 

INSERT INTO `phpboost_forum_cats` VALUES (1, 1, 10, 0, 'Cat&eacute;gorie de test', 'Cat&eacute;gorie de test', 18, 81, 43, 1, 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:7;}');
INSERT INTO `phpboost_forum_cats` VALUES (2, 2, 9, 1, 'Forum de test', 'Forum de test', 20, 81, 43, 1, 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}');
INSERT INTO `phpboost_forum_cats` VALUES (6, 3, 4, 2, 'test', 'test', 2, 18, 14, 1, 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}');
INSERT INTO `phpboost_forum_cats` VALUES (5, 5, 8, 2, 'hgf', 'hgf', 5, 21, 29, 1, 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}');
INSERT INTO `phpboost_forum_cats` VALUES (12, 11, 14, 0, 'cat2', '', 13, 13, 44, 1, 1, 'a:3:{s:2:"r1";i:2;s:2:"r2";i:7;i:3;i:1;}');
INSERT INTO `phpboost_forum_cats` VALUES (11, 6, 7, 3, 'level3', 'gfd', 1, 7, 15, 1, 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}');
INSERT INTO `phpboost_forum_cats` VALUES (13, 12, 13, 1, 'subcat2', '', 13, 13, 45, 1, 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_forum_history`
-- 

CREATE TABLE `phpboost_forum_history` (
  `id` int(11) NOT NULL auto_increment,
  `action` varchar(25) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `user_id_action` int(11) NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

-- 
-- Contenu de la table `phpboost_forum_history`
-- 

INSERT INTO `phpboost_forum_history` VALUES (7, 'delete_msg', 1, 2, 'topic-10.php#m26', 1197303005);
INSERT INTO `phpboost_forum_history` VALUES (6, 'delete_topic', 1, 2, 'forum-2.php', 1197302342);
INSERT INTO `phpboost_forum_history` VALUES (8, 'delete_topic', 1, 2, 'forum-2.php', 1197303057);
INSERT INTO `phpboost_forum_history` VALUES (9, 'edit_msg', 1, 2, 'topic-5.php#m30', 1197303345);
INSERT INTO `phpboost_forum_history` VALUES (10, 'edit_topic', 1, 2, 'topic-11.php', 1197303561);
INSERT INTO `phpboost_forum_history` VALUES (11, 'delete_msg', 1, 2, 'topic-11.php#m32', 1197303624);
INSERT INTO `phpboost_forum_history` VALUES (13, 'cut_topic', 1, 0, 'topic-12.php', 1197317192);
INSERT INTO `phpboost_forum_history` VALUES (14, 'cut_topic', 1, 0, 'topic-13.php', 1197318767);
INSERT INTO `phpboost_forum_history` VALUES (15, 'cut_topic', 1, 0, 'topic-14.php', 1197323571);
INSERT INTO `phpboost_forum_history` VALUES (16, 'edit_topic', 1, 2, 'topic-12.php', 1197381420);
INSERT INTO `phpboost_forum_history` VALUES (17, 'edit_topic', 1, 2, 'topic-12.php', 1197382319);
INSERT INTO `phpboost_forum_history` VALUES (18, 'edit_topic', 1, 2, 'topic-12.php', 1197382346);
INSERT INTO `phpboost_forum_history` VALUES (19, 'edit_topic', 1, 2, 'topic-12.php', 1197382377);
INSERT INTO `phpboost_forum_history` VALUES (20, 'edit_topic', 1, 2, 'topic-12.php', 1197419730);
INSERT INTO `phpboost_forum_history` VALUES (21, 'cut_topic', 1, 0, 'topic-16.php', 1197422151);
INSERT INTO `phpboost_forum_history` VALUES (22, 'move_topic', 1, 1, 'topic-17.php', 1197422625);
INSERT INTO `phpboost_forum_history` VALUES (23, 'cut_topic', 1, 0, 'topic-20.php', 1197423014);
INSERT INTO `phpboost_forum_history` VALUES (24, 'delete_topic', 1, 2, 'forum-2.php', 1197423136);
INSERT INTO `phpboost_forum_history` VALUES (25, 'cut_topic', 1, 0, 'topic-21.php', 1197423182);
INSERT INTO `phpboost_forum_history` VALUES (26, 'move_topic', 1, 1, 'topic-21.php', 1197423259);
INSERT INTO `phpboost_forum_history` VALUES (27, 'move_topic', 1, 1, 'topic-22.php', 1197423547);
INSERT INTO `phpboost_forum_history` VALUES (28, 'move_topic', 1, 1, 'topic-15.php', 1197423653);
INSERT INTO `phpboost_forum_history` VALUES (29, 'move_topic', 1, 1, 'topic-15.php', 1197423676);
INSERT INTO `phpboost_forum_history` VALUES (30, 'edit_topic', 1, 2, 'topic-12.php', 1197427420);
INSERT INTO `phpboost_forum_history` VALUES (31, 'set_warning_user', 2, 2, 'moderation_forum.php?action=warning&amp;id=2', 1197427613);
INSERT INTO `phpboost_forum_history` VALUES (32, 'set_warning_user', 2, 2, 'moderation_forum.php?action=warning&amp;id=2', 1197427619);
INSERT INTO `phpboost_forum_history` VALUES (33, 'edit_msg', 1, 2, 'topic-22.php#m71', 1197427821);
INSERT INTO `phpboost_forum_history` VALUES (34, 'lock_topic', 1, 0, 'topic-12.php', 1203012546);
INSERT INTO `phpboost_forum_history` VALUES (35, 'unlock_topic', 1, 0, 'topic-12.php', 1203012554);
INSERT INTO `phpboost_forum_history` VALUES (36, 'unlock_topic', 1, 0, 'topic-12.php', 1203012746);
INSERT INTO `phpboost_forum_history` VALUES (37, 'set_warning_user', 1, 2, 'moderation_forum.php?action=warning&amp;id=2', 1203304014);
INSERT INTO `phpboost_forum_history` VALUES (38, 'cut_topic', 1, 0, 'topic-33.php', 1203308577);
INSERT INTO `phpboost_forum_history` VALUES (39, 'cut_topic', 1, 0, 'topic-42.php', 1203464966);
INSERT INTO `phpboost_forum_history` VALUES (40, 'solve_alert', 1, 0, 'moderation_forum.php?action=alert&amp;id=2', 1203712946);
INSERT INTO `phpboost_forum_history` VALUES (41, 'solve_alert', 1, 0, 'moderation_forum.php?action=alert&amp;id=2', 1203713011);
INSERT INTO `phpboost_forum_history` VALUES (42, 'readonly_user', 1, 2, 'moderation_forum.php?action=punish&amp;id=2', 1203714408);
INSERT INTO `phpboost_forum_history` VALUES (43, 'cut_topic', 1, 0, 'topic-44.php', 1204845326);
INSERT INTO `phpboost_forum_history` VALUES (44, 'cut_topic', 1, 0, 'topic-45.php', 1204845560);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_forum_msg`
-- 

CREATE TABLE `phpboost_forum_msg` (
  `id` int(11) NOT NULL auto_increment,
  `idtopic` int(11) NOT NULL default '0',
  `user_id` mediumint(9) NOT NULL default '0',
  `contents` text NOT NULL,
  `timestamp` int(11) NOT NULL default '0',
  `timestamp_edit` int(11) NOT NULL default '0',
  `user_id_edit` int(11) NOT NULL default '0',
  `user_ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idtopic` (`idtopic`,`user_id`,`timestamp`),
  FULLTEXT KEY `contenu` (`contents`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=154 ;

-- 
-- Contenu de la table `phpboost_forum_msg`
-- 

INSERT INTO `phpboost_forum_msg` VALUES (1, 1, 1, 'Message de test sur le forum PHPBoost', 1194096709, 1194644365, 1, '');
INSERT INTO `phpboost_forum_msg` VALUES (2, 1, 1, 'test', 1194143742, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (3, 2, 1, 'test', 1194656377, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (37, 14, 1, 'hgfhg', 1197319717, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (38, 13, 1, 'test', 1197320840, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (40, 14, 1, 'test', 1197323558, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (96, 12, 1, 'f dsfsd', 1199113523, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (34, 12, 2, 'gdfgdfgdfgdf &lt; &gt; &lt;p&gt;fd&lt;/p&gt;<br />\r\n&quot; ''<br />\r\n; é à @ ¤ #~&#164;<br />\r\nç', 1197316612, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (35, 13, 1, '<span style="text-decoration: underline;">Après avoir envoyé</span> <a href="http://www.phpboost.com/forum/topic-1891+recrutement-developpeurs-php.php">le premier test</a> pour le poste de développeurs PHP à <a href="http://www.phpboost.com/member/member-2.php">ben.popeye</a> en message privé. Voici un test avancé de connaissance sur PHPBoost.<br />\r\n<br />\r\n<br />\r\nPour ce test, vous devrez avoir installé PHPBoost 2.0<br />\r\n<span class="notice"><br />\r\n<ul class="bb_ul">\r\n	<li class="bb_li">Le script devra afficher la liste des membres (un système de pagination serait un plus).\r\n	</li><li class="bb_li">Il devra utiliser le moteur de templates\r\n	</li><li class="bb_li">Il devra utiliser les fonctions PHPBoost (templates, requêtes SQL, pagination, etc..)\r\n	</li><li class="bb_li">Vous pouvez vous aider de online/online.php et de member/member.php<br />\r\n</li></ul><br />\r\n</span><br />\r\n<br />\r\n<br />\r\n<span class="warning">Vous devez respecter le style de programmation définit ici: <a href="http://www.phpboost.com/wiki/creer-un-module-additionnel#style-de-programmation">Style de programmation</a></span><br />\r\n<br />\r\n<br />\r\n<span class="question">Merci d''envoyer vos codes corrigés à <a href="http://www.phpboost.com/member/member-2.php">ben.popeye</a> par message privé.</span><br />\r\n<br />\r\n<br />\r\nCe test n''est pas évident, mais est très important car nous montre votre capacité à vous intégrer dans le travail dans l''équipe de développement. On ne vous demande pas un code parfait, vous serez aidé dans l''équipe de développement.', 1197316616, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (36, 13, 1, '<span style="text-decoration: underline;">Après avoir envoyé</span> <a href="http://www.phpboost.com/forum/topic-1891+recrutement-developpeurs-php.php">le premier test</a> pour le poste de développeurs PHP à <a href="http://www.phpboost.com/member/member-2.php">ben.popeye</a> en message privé. Voici un test avancé de connaissance sur PHPBoost.<br />\r\n<br />\r\n<br />\r\nPour ce test, vous devrez avoir installé PHPBoost 2.0<br />\r\n<span class="notice"><br />\r\n<ul class="bb_ul">\r\n	<li class="bb_li">Le script devra afficher la liste des membres (un système de pagination serait un plus).\r\n	</li><li class="bb_li">Il devra utiliser le moteur de templates\r\n	</li><li class="bb_li">Il devra utiliser les fonctions PHPBoost (templates, requêtes SQL, pagination, etc..)\r\n	</li><li class="bb_li">Vous pouvez vous aider de online/online.php et de member/member.php<br />\r\n</li></ul><br />\r\n</span><br />\r\n<br />\r\n<br />\r\n<span class="warning">Vous devez respecter le style de programmation définit ici: <a href="http://www.phpboost.com/wiki/creer-un-module-additionnel#style-de-programmation">Style de programmation</a></span><br />\r\n<br />\r\n<br />\r\n<span class="question">Merci d''envoyer vos codes corrigés à <a href="http://www.phpboost.com/member/member-2.php">ben.popeye</a> par message privé.</span><br />\r\n<br />\r\n<br />\r\nCe test n''est pas évident, mais est très important car nous montre votre capacité à vous intégrer dans le travail dans l''équipe de développement. On ne vous demande pas un code parfait, vous serez aidé dans l''équipe de développement.', 1197318705, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (30, 5, 2, 'test sdgfsd!', 1197303126, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (69, 22, 1, 'test', 1197423313, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (8, 4, 1, 'test', 1195476308, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (9, 5, 1, 'test2', 1195486587, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (98, 12, 1, 'fzefef s', 1199113530, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (11, 7, 1, 'fds', 1195487030, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (94, 12, 1, 'f sdf', 1199113516, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (99, 12, 2, 'test', 1203115752, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (100, 1, 2, 'gfdg dfgfdgdf', 1203115764, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (101, 13, 1, 'fds fsdf ds', 1203118069, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (102, 4, 1, 'jghj ghjgh', 1203118112, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (41, 12, 1, '[code]é[/code]', 1197382642, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (42, 21, 1, 'test', 1197419685, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (45, 21, 1, 'test2', 1197419901, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (142, 14, 1, 'tetr', 1204237337, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (146, 14, 1, 'jghjg', 1204237359, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (52, 15, 1, 'fsdfsd', 1197421439, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (53, 15, 1, 'gfgdf', 1197421443, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (148, 14, 1, 'vcxvxc', 1204237656, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (55, 15, 1, 'hgf', 1197421496, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (57, 7, 1, 'gfdgdf', 1197421569, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (58, 15, 1, 'gfdgfdg', 1197421587, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (62, 15, 1, 'fsdf', 1197422292, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (97, 12, 1, 'vcxsfzfds', 1199113526, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (150, 43, 1, 'vxcvc <strong>fggdf{</strong>', 1204841022, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (70, 22, 1, 'réponse : <br />\r\n<br />\r\n<span class="text_blockquote">Citation:</span><div class="blockquote">Bonjour Thomas<br />\r\n<br />\r\nJe connais PHPBoost, il est dans ma liste des prochain CMS a faire son <br />\r\napparition sur le site. Malheureusement, j''ai pas encore essayer ce CMS, <br />\r\nmais je ferai des test et probablement un site démo lors de <br />\r\nl''inscription sur le site.<br />\r\n<br />\r\nComme vous avez du le remarquer, CMS-Québec fermeras ces portes dans 2 <br />\r\nsemaines, soit le 1 Janvier.<br />\r\n<br />\r\nMais un nouveau site seras en ligne a la meme heures pour remplacer <br />\r\nCMS-Québec, nouveau nom, nouveau CMS, nouveau hébergeur, nouvelle <br />\r\nstructure. Le transfert des donnés n''est pas terminé et ne seras <br />\r\nsurement pas terminé pour cette date mais je mets en ligne quand meme. <br />\r\nLe continuerai le transfert avec le site online.<br />\r\n<br />\r\nJe communiquerai avec vous au mois de Janvier pour plus de renseignement.<br />\r\n<br />\r\nMerci de l''intéret porté a CMS-Québec.<br />\r\n<br />\r\nGuy Vigneault<br />\r\n<a href="http://www.cms-quebec.com">http://www.cms-quebec.com</a><br />\r\n<a href="http://www.leblogueur.com[/quote">]http://www.leblogueur.com</div></a><br />\r\n<br />\r\ndonc on attendra janvier <img src="../images/smileys/sourire.gif" alt=":)" class="smiley" />', 1197423317, 1197752824, 1, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (90, 12, 1, 'test', 1199113477, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (95, 12, 1, 'r zezfd', 1199113519, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (76, 22, 1, 'ytr', 1198338979, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (77, 22, 1, 'ytr', 1198339023, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (78, 21, 1, 'test', 1198339043, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (79, 21, 1, 'bhjyu', 1198339110, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (80, 21, 1, 'bhjyu', 1198339320, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (81, 21, 1, 'bhjyu', 1198339354, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (82, 21, 1, 'bhjyu', 1198339364, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (83, 21, 1, 'bhjyu', 1198339375, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (84, 21, 1, 'bhjyu', 1198339566, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (85, 21, 1, 'y', 1198339865, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (86, 21, 1, 'gfd', 1198339903, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (87, 21, 1, '[code]&lt;?php echo ''fuck''; ?&gt;[/code]<br />\r\n<br />\r\nvcxvxc', 1198458769, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (88, 21, 1, '[code=php]&lt;?php echo ''fuck''; ?&gt;[/code]<br />\r\n<br />\r\n[code=php]<br />\r\n&lt;?php<br />\r\n/*##################################################<br />\r\n *                                sessions.class.php<br />\r\n *                            -------------------<br />\r\n *   begin                : July 04, 2005<br />\r\n *   copyright          : (C) 2005 Viarre Régis<br />\r\n *   email                : crowkait@phpboost.com<br />\r\n *<br />\r\n *   Sessions v4.0.0 <br />\r\n *<br />\r\n###################################################<br />\r\n *<br />\r\n *   This program is free software; you can redistribute it and/or modify<br />\r\n *   it under the terms of the GNU General Public License as published by<br />\r\n *   the Free Software Foundation; either version 2 of the License, or<br />\r\n *   (at your option) any later version.<br />\r\n * <br />\r\n * This program is distributed in the hope that it will be useful,<br />\r\n * but WITHOUT ANY WARRANTY; without even the implied warranty of<br />\r\n * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the<br />\r\n * GNU General Public License for more details.<br />\r\n *<br />\r\n * You should have received a copy of the GNU General Public License<br />\r\n * along with this program; if not, write to the Free Software<br />\r\n * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.<br />\r\n *<br />\r\n###################################################*/<br />\r\n<br />\r\n//Constantes de base.<br />\r\ndefine(''AUTOCONNECT'', true);<br />\r\ndefine(''NO_AUTOCONNECT'', false);<br />\r\n<br />\r\nclass Sessions<br />\r\n{<br />\r\n	var $data = array(); //Tableau contenant les informations de session.<br />\r\n	var $session_mod = 0; //Variable contenant le mode de session à utiliser pour récupérer les infos.<br />\r\n	<br />\r\n	//Constructeur<br />\r\n	function Sessions()<br />\r\n	{	<br />\r\n	}	<br />\r\n<br />\r\n	//Lancement de la session après récupèration des informations par le formulaire de connexion.<br />\r\n	function session_begin($user_id, $password, $level, $session_script, $session_script_get, $session_script_title, $autoconnect = false)<br />\r\n	{<br />\r\n		global $CONFIG, $sql;<br />\r\n		<br />\r\n		$error = '''';<br />\r\n		$cookie_on = false;<br />\r\n		$session_script = addslashes($session_script);<br />\r\n		$session_script_title = addslashes($session_script_title);<br />\r\n		<br />\r\n		########Insertion dans le compteur si l''ip est inconnue.########<br />\r\n		$check_ip = $sql-&gt;query(&quot;SELECT COUNT(*) FROM &quot;.PREFIX.&quot;compteur WHERE ip = ''&quot; . USER_IP . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n		$_include_once = empty($check_ip) &amp;&amp; ($this-&gt;check_robot(USER_IP) === false);<br />\r\n		if( $_include_once )<br />\r\n		{<br />\r\n			//Récupération forcée de la valeur du total de visites, car problème de CAST avec postgresql.<br />\r\n			$sql-&gt;query_inject(&quot;UPDATE &quot;.LOW_PRIORITY.&quot; &quot;.PREFIX.&quot;compteur SET ip = ip + 1, time = &quot; . $sql-&gt;sql_now() . &quot;, total = total + 1 WHERE id = 1&quot;, __LINE__, __FILE__);<br />\r\n			$sql-&gt;query_inject(&quot;INSERT &quot;.LOW_PRIORITY.&quot; INTO &quot;.PREFIX.&quot;compteur (ip, time, total) VALUES(''&quot; . USER_IP . &quot;'', &quot; . $sql-&gt;sql_now() . &quot;, 0)&quot;, __LINE__, __FILE__);<br />\r\n			<br />\r\n			//Mise à jour du last_connect, pour un membre qui vient d''arriver sur le site.<br />\r\n			if( $user_id !== ''-1'' ) <br />\r\n				$sql-&gt;query_inject(&quot;UPDATE &quot;.PREFIX.&quot;member SET last_connect = ''&quot; . time() . &quot;'' WHERE user_id = ''&quot; . $user_id . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n		}<br />\r\n		<br />\r\n		//On lance les stats.<br />\r\n		include_once(''../includes/save_stats.php'');<br />\r\n			<br />\r\n		########Génération d''un ID de session unique########<br />\r\n		$session_uniq_id = md5(uniqid(mt_rand(), true)); //On génère un numéro de session aléatoire.<br />\r\n			<br />\r\n		########Session existe t-elle?#########		<br />\r\n		$this-&gt;session_garbage_collector();	//On nettoie avant les sessions périmées.<br />\r\n<br />\r\n		if( $user_id !== ''-1'' )<br />\r\n		{<br />\r\n			//Suppression de la session visiteur générée avant l''enregistrement!<br />\r\n			$sql-&gt;query_inject(&quot;DELETE FROM &quot;.PREFIX.&quot;sessions WHERE session_ip = ''&quot; . USER_IP . &quot;'' AND user_id = -1&quot;, __LINE__, __FILE__);<br />\r\n			<br />\r\n			//En cas de double connexion, on supprime le cookie et la session associée de la base de données!<br />\r\n			if( isset($_COOKIE[$CONFIG[''site_cookie''] . ''_data'']) ) <br />\r\n				setcookie($CONFIG[''site_cookie''].''_data'', '''', time() - 31536000, ''/'');<br />\r\n			$sql-&gt;query_inject(&quot;DELETE FROM &quot;.PREFIX.&quot;sessions WHERE user_id = ''&quot; . $user_id . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n			<br />\r\n			//Récupération password BDD<br />\r\n			$password_m = $sql-&gt;query(&quot;SELECT password FROM &quot;.PREFIX.&quot;member WHERE user_id = ''&quot; . $user_id . &quot;'' AND user_warning &lt; 100 AND ''&quot; . time() . &quot;'' - user_ban &gt;= 0&quot;, __LINE__, __FILE__);<br />\r\n			<br />\r\n			if( !empty($password) &amp;&amp; $password === $password_m ) //Succès!<br />\r\n			{<br />\r\n				$sql-&gt;query_inject(&quot;INSERT INTO &quot;.PREFIX.&quot;sessions VALUES(''&quot; . $session_uniq_id . &quot;'', ''&quot; . $user_id . &quot;'', ''&quot; . $level . &quot;'', ''&quot; . USER_IP . &quot;'', ''&quot; . time() . &quot;'', ''&quot; . $session_script . &quot;'', ''&quot; . $session_script_get . &quot;'', ''&quot; . $session_script_title . &quot;'', '''')&quot;, __LINE__, __FILE__);				<br />\r\n				$cookie_on = true; //Génération du cookie!			<br />\r\n			}<br />\r\n			else //Session visiteur, echec!<br />\r\n			{<br />\r\n				$sql-&gt;query_inject(&quot;INSERT INTO &quot;.PREFIX.&quot;sessions VALUES(''&quot; . $session_uniq_id . &quot;'', -1, -1, ''&quot; . USER_IP . &quot;'', ''&quot; . time() . &quot;'', ''&quot; . $session_script . &quot;'', ''&quot; . $session_script_get . &quot;'', ''&quot; . $session_script_title . &quot;'', ''0'')&quot;, __LINE__, __FILE__);		<br />\r\n				$delay_ban = $sql-&gt;query(&quot;SELECT user_ban FROM &quot;.PREFIX.&quot;member WHERE user_id = ''&quot; . $user_id . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n				if( (time - $delay_ban) &gt;= 0 )<br />\r\n					$error = ''echec'';<br />\r\n				else<br />\r\n					$error = $delay_ban;		<br />\r\n			}	<br />\r\n		}<br />\r\n		else //Session visiteur valide.<br />\r\n		{<br />\r\n			$sql-&gt;query_inject(&quot;INSERT INTO &quot;.PREFIX.&quot;sessions VALUES(''&quot; . $session_uniq_id . &quot;'', -1, -1, ''&quot; . USER_IP . &quot;'', ''&quot; . time() . &quot;'', ''&quot; . $session_script . &quot;'', ''&quot; . $session_script_get . &quot;'', ''&quot; . $session_script_title . &quot;'', ''0'')&quot;, __LINE__, __FILE__);<br />\r\n		}<br />\r\n		<br />\r\n		########Génération du cookie de session########<br />\r\n		if( $cookie_on === true )<br />\r\n		{<br />\r\n			$data = array();<br />\r\n			$data[''user_id''] = isset($user_id) ? numeric($user_id) : -1;<br />\r\n			$data[''session_id''] = $session_uniq_id;<br />\r\n			<br />\r\n			setcookie($CONFIG[''site_cookie''].''_data'', serialize($data), time() + 31536000, ''/'');<br />\r\n			<br />\r\n			########Génération du cookie d''autoconnection########<br />\r\n			if( $autoconnect === true )<br />\r\n			{<br />\r\n				$session_autoconnect[''user_id''] = $user_id;				<br />\r\n				$session_autoconnect[''pwd''] = $password;<br />\r\n				<br />\r\n				setcookie($CONFIG[''site_cookie''].''_autoconnect'', serialize($session_autoconnect), time() + 31536000, ''/'');<br />\r\n			}<br />\r\n		}<br />\r\n		<br />\r\n		return $error;<br />\r\n	}	<br />\r\n	<br />\r\n	//Récupération de session en autoconnect.<br />\r\n	function get_session_autoconnect($session_script, $session_script_get, $session_script_title)<br />\r\n	{<br />\r\n		global $CONFIG, $sql;<br />\r\n				<br />\r\n		########Cookie Existe?########<br />\r\n		if( isset($_COOKIE[$CONFIG[''site_cookie''].''_autoconnect'']) )<br />\r\n		{<br />\r\n			$session_autoconnect = isset($_COOKIE[$CONFIG[''site_cookie''].''_autoconnect'']) ? unserialize(stripslashes($_COOKIE[$CONFIG[''site_cookie''].''_autoconnect''])) : array();<br />\r\n			$session_autoconnect[''user_id''] = !empty($session_autoconnect[''user_id'']) ? numeric($session_autoconnect[''user_id'']) : ''''; //Validité user id?.				<br />\r\n			$session_autoconnect[''pwd''] = !empty($session_autoconnect[''pwd'']) ? securit($session_autoconnect[''pwd'']) : ''''; //Validité password.<br />\r\n			$level = $sql-&gt;query(&quot;SELECT level FROM &quot;.PREFIX.&quot;member WHERE user_id = ''&quot; . $session_autoconnect[''user_id''] . &quot;'' AND password = ''&quot; . $session_autoconnect[''pwd''] . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n			<br />\r\n			if( !empty($session_autoconnect[''user_id'']) &amp;&amp; !empty($session_autoconnect[''pwd'']) &amp;&amp; isset($level) )<br />\r\n			{<br />\r\n				$error_report = $this-&gt;session_begin($session_autoconnect[''user_id''], $session_autoconnect[''pwd''], $level, $session_script, $session_script_get, $session_script_title); //Lancement d''une session utilisateur.<br />\r\n				<br />\r\n				//Gestion des erreurs pour éviter un brute force.<br />\r\n				if( $error_report === ''echec'' )<br />\r\n				{<br />\r\n					$sql-&gt;query_inject(&quot;UPDATE &quot;.PREFIX.&quot;member SET last_connect=''&quot; . time() . &quot;'', test_connect = test_connect + 1 WHERE user_id=''&quot; . $session_autoconnect[''user_id''] . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n					<br />\r\n					$test_connect = $sql-&gt;query(&quot;SELECT test_connect FROM &quot;.PREFIX.&quot;member WHERE user_id = ''&quot; . $session_autoconnect[''user_id''] . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n					<br />\r\n					setcookie($CONFIG[''site_cookie''].''_autoconnect'', '''', time() - 31536000, ''/''); //On supprime le cookie.<br />\r\n					<br />\r\n					header(''location:'' . HOST . DIR . ''/member/error.php?flood='' . (5 - ($test_connect + 1)));<br />\r\n					exit;<br />\r\n				}<br />\r\n				elseif( is_numeric($error_report) )<br />\r\n				{<br />\r\n					setcookie($CONFIG[''site_cookie''].''_autoconnect'', '''', time() - 31536000, ''/''); //On supprime le cookie.<br />\r\n					<br />\r\n					$error_report = ceil($error_report/60);<br />\r\n					header(''location:'' . HOST . DIR . ''/member/error.php?ban='' . $error_report);<br />\r\n					exit;<br />\r\n				}<br />\r\n				else //Succès on recharge la page.<br />\r\n				{<br />\r\n					//On met à jour la date de dernière connexion. <br />\r\n					$sql-&gt;query_inject(&quot;UPDATE &quot;.PREFIX.&quot;member SET last_connect = ''&quot; . time() . &quot;'' WHERE user_id = ''&quot; . $session_autoconnect[''user_id''] . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n					<br />\r\n					if( QUERY_STRING != '''' )<br />\r\n						header(''location:'' . HOST . SCRIPT . ''?'' . QUERY_STRING);<br />\r\n					else<br />\r\n						header(''location:'' . HOST . SCRIPT);					<br />\r\n					exit;<br />\r\n				}<br />\r\n			}<br />\r\n			else<br />\r\n			{<br />\r\n				return false;<br />\r\n			}<br />\r\n		}	<br />\r\n		return false;<br />\r\n	}<br />\r\n	<br />\r\n	//Récupération des l''identifiants de session.<br />\r\n	function get_session_id()<br />\r\n	{<br />\r\n		global $CONFIG, $sql;<br />\r\n		<br />\r\n		//Suppression d''éventuelles données dans ce tableau.<br />\r\n		$this-&gt;data = array();<br />\r\n		<br />\r\n		$this-&gt;data[''session_id''] = '''';<br />\r\n		$this-&gt;data[''user_id''] = -1;<br />\r\n		$this-&gt;session_mod = 0;<br />\r\n<br />\r\n		########Cookie Existe?########<br />\r\n		if( isset($_COOKIE[$CONFIG[''site_cookie''].''_data'']) )<br />\r\n		{<br />\r\n			//Redirection pour supprimer les variables de session en clair dans l''url.<br />\r\n			if( isset($_GET[''sid'']) &amp;&amp; isset($_GET[''suid'']) )<br />\r\n			{<br />\r\n				$query_string = preg_replace(''`&amp;?sid=(.*)&amp;suid=(.*)`'', '''', QUERY_STRING);<br />\r\n				header(''location:'' . HOST . SCRIPT . (!empty($query_string) ? ''?'' . $query_string : ''''));				<br />\r\n			}<br />\r\n			<br />\r\n			$session_data = isset($_COOKIE[$CONFIG[''site_cookie''].''_data'']) ? unserialize(stripslashes($_COOKIE[$CONFIG[''site_cookie''].''_data''])) : array();<br />\r\n			<br />\r\n			$this-&gt;data[''session_id''] = isset($session_data[''session_id'']) ? securit($session_data[''session_id'']) : ''''; //Validité du session id.<br />\r\n			$this-&gt;data[''user_id''] = isset($session_data[''user_id'']) ? numeric($session_data[''user_id'']) : ''''; //Validité user id?<br />\r\n		}	<br />\r\n		########SID Existe?########<br />\r\n		elseif( isset($_GET[''sid'']) &amp;&amp; isset($_GET[''suid'']) )<br />\r\n		{<br />\r\n			$this-&gt;data[''session_id''] = !empty($_GET[''sid'']) ? securit($_GET[''sid'']) : ''''; //Validité du session id.<br />\r\n			$this-&gt;data[''user_id''] = !empty($_GET[''suid'']) ? numeric($_GET[''suid'']) : ''''; //Validité user id?<br />\r\n			$this-&gt;session_mod = 1;<br />\r\n		}<br />\r\n	}<br />\r\n	<br />\r\n	//Récupération des informations sur le membre.<br />\r\n	function session_info()<br />\r\n	{<br />\r\n		global $sql, $CONFIG;<br />\r\n		<br />\r\n		$this-&gt;get_session_id(); //Récupération des identifiants de session.<br />\r\n				<br />\r\n		########Valeurs à retourner########<br />\r\n		$userdata = array();<br />\r\n		if( $this-&gt;data[''user_id''] !== -1 &amp;&amp; !empty($this-&gt;data[''user_id'']) )<br />\r\n			$userdata = $sql-&gt;query_array(''member'', ''user_id'', ''login'', ''level'', ''user_groups'', ''user_lang'', ''user_theme'', ''user_mail'', ''user_pm'', ''user_editor'', ''user_timezone'', ''user_readonly'', &quot;WHERE user_id=''&quot; . $this-&gt;data[''user_id''] . &quot;''&quot;, __LINE__, __FILE__);		<br />\r\n		<br />\r\n		$this-&gt;data[''user_id''] = isset($userdata[''user_id'']) ? (int)$userdata[''user_id''] : -1;<br />\r\n		$this-&gt;data[''login''] = isset($userdata[''login'']) ? $userdata[''login''] : '''';	<br />\r\n		$this-&gt;data[''level''] = isset($userdata[''level'']) ? (int)$userdata[''level''] : -1;		<br />\r\n		$this-&gt;data[''user_groups''] = isset($userdata[''user_groups'']) ? $userdata[''user_groups''] : '''';<br />\r\n		$this-&gt;data[''user_lang''] = isset($userdata[''user_lang'']) ? $userdata[''user_lang''] : ''''; //Langue membre<br />\r\n		$this-&gt;data[''user_theme''] = isset($userdata[''user_theme'']) ? $userdata[''user_theme''] : ''''; //Thème membre		<br />\r\n		$this-&gt;data[''user_mail''] = isset($userdata[''user_mail'']) ? $userdata[''user_mail''] : '''';<br />\r\n		$this-&gt;data[''user_pm''] = isset($userdata[''user_pm'']) ? $userdata[''user_pm''] : ''0'';	<br />\r\n		$this-&gt;data[''user_readonly''] = isset($userdata[''user_readonly'']) ? $userdata[''user_readonly''] : ''0'';<br />\r\n		$this-&gt;data[''user_editor''] = !empty($userdata[''user_editor'']) ? $userdata[''user_editor''] : $CONFIG[''editor''];<br />\r\n		$this-&gt;data[''user_timezone''] = isset($userdata[''user_timezone'']) ? $userdata[''user_timezone''] : $CONFIG[''timezone''];<br />\r\n	}<br />\r\n	<br />\r\n	//Vérification de la session.<br />\r\n	function session_check($session_script_title)<br />\r\n	{<br />\r\n		global $CONFIG, $sql;<br />\r\n<br />\r\n		$session_script = str_replace(DIR, '''', SCRIPT);<br />\r\n		$session_script_get = QUERY_STRING;<br />\r\n		if( !empty($this-&gt;data[''session_id'']) &amp;&amp; !empty($this-&gt;data[''user_id'']) &amp;&amp; $this-&gt;data[''user_id''] !== -1 )<br />\r\n		{<br />\r\n			//On modifie le session_flag pour forcer mysql à modifier l''entrée, pour prendre en compte la mise à jour par mysql_affected_rows().<br />\r\n			$ressource = $sql-&gt;query_inject(&quot;UPDATE &quot;.LOW_PRIORITY.&quot; &quot;.PREFIX.&quot;sessions SET session_ip = ''&quot; . USER_IP . &quot;'', session_time = ''&quot; . time() . &quot;'', session_script = ''&quot; . addslashes($session_script) . &quot;'', session_script_get = ''&quot; . addslashes($session_script_get) . &quot;'', session_script_title = ''&quot; . addslashes($session_script_title) . &quot;'', session_flag = 1 - session_flag WHERE session_id = ''&quot; . $this-&gt;data[''session_id''] . &quot;'' AND user_id = ''&quot; . $this-&gt;data[''user_id''] . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n			<br />\r\n			if( $sql-&gt;sql_affected_rows($ressource, &quot;SELECT COUNT(*) FROM &quot;.PREFIX.&quot;sessions WHERE session_id = ''&quot; . $this-&gt;data[''session_id''] . &quot;'' AND user_id = ''&quot; . $this-&gt;data[''user_id''] . &quot;''&quot;) == 0 ) //Aucune session lancée.<br />\r\n			{<br />\r\n				if( $this-&gt;get_session_autoconnect($session_script, $session_script_get, $session_script_title) === false )<br />\r\n				{					<br />\r\n					if( isset($_COOKIE[$CONFIG[''site_cookie''].''_data'']) )<br />\r\n					{<br />\r\n						setcookie($CONFIG[''site_cookie''].''_data'', '''', time() - 31536000, ''/''); //Destruction cookie.						<br />\r\n					}	<br />\r\n					<br />\r\n					if( QUERY_STRING != '''' )<br />\r\n						header(''location:'' . HOST . SCRIPT . ''?'' . QUERY_STRING);<br />\r\n					else<br />\r\n						header(''location:'' . HOST . SCRIPT);<br />\r\n					exit;<br />\r\n				}<br />\r\n			}<br />\r\n		}<br />\r\n		else //Visiteur<br />\r\n		{<br />\r\n			//On modifie le session_flag pour forcer mysql à modifier l''entrée, pour prendre en compte la mise à jour par mysql_affected_rows().<br />\r\n			$ressource = $sql-&gt;query_inject(&quot;UPDATE &quot;.LOW_PRIORITY.&quot; &quot;.PREFIX.&quot;sessions SET session_ip = ''&quot; . USER_IP . &quot;'', session_time = ''&quot; . (time() + 1) . &quot;'', session_script = ''&quot; . addslashes($session_script) . &quot;'', session_script_get = ''&quot; . addslashes($session_script_get) . &quot;'', session_script_title = ''&quot; . addslashes($session_script_title) . &quot;'', session_flag = 1 - session_flag WHERE user_id = -1 AND session_ip = ''&quot; . USER_IP . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n			<br />\r\n			if( $sql-&gt;sql_affected_rows($ressource, &quot;SELECT COUNT(*) FROM &quot;.PREFIX.&quot;sessions WHERE user_id = -1 AND session_ip = ''&quot; . USER_IP . &quot;''&quot;) == 0 ) //Aucune session lancée.<br />\r\n			{<br />\r\n				if( isset($_COOKIE[$CONFIG[''site_cookie''].''_data'']) )<br />\r\n				{<br />\r\n					setcookie($CONFIG[''site_cookie''].''_data'', '''', time() - 31536000, ''/''); //Destruction cookie.<br />\r\n				}<br />\r\n				$this-&gt;session_begin(''-1'', '''', ''-1'', $session_script, $session_script_get, $session_script_title); //Session visiteur<br />\r\n				<br />\r\n				if( QUERY_STRING != '''' )<br />\r\n					header(''location:'' . HOST . SCRIPT . ''?'' . QUERY_STRING);<br />\r\n				else<br />\r\n					header(''location:'' . HOST . SCRIPT);	<br />\r\n				exit;<br />\r\n			}<br />\r\n		}<br />\r\n	}<br />\r\n	<br />\r\n	//Suppression des sessions expirée par le garbage collector.<br />\r\n	function session_garbage_collector() <br />\r\n	{<br />\r\n		global $CONFIG, $sql;<br />\r\n			<br />\r\n		$sql-&gt;query_inject(&quot;DELETE <br />\r\n		FROM &quot;.PREFIX.&quot;sessions <br />\r\n		WHERE session_time &lt; ''&quot; . (time() - $CONFIG[''site_session'']) . &quot;'' <br />\r\n		OR (session_time &lt; ''&quot; . (time() - $CONFIG[''site_session_invit'']) . &quot;'' AND user_id = -1)&quot;, __LINE__, __FILE__);<br />\r\n	}<br />\r\n	<br />\r\n	//Fin de la session<br />\r\n	function session_end()<br />\r\n	{<br />\r\n		global $CONFIG, $sql;<br />\r\n			<br />\r\n		$this-&gt;get_session_id();<br />\r\n			<br />\r\n		//On supprime la session de la bdd.<br />\r\n		$sql-&gt;query_inject(&quot;DELETE FROM &quot;.PREFIX.&quot;sessions WHERE session_id = ''&quot; . $this-&gt;data[''session_id''] . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n		<br />\r\n		if( isset($_COOKIE[$CONFIG[''site_cookie''].''_data'']) ) //Session cookie?<br />\r\n			setcookie($CONFIG[''site_cookie''].''_data'', '''', time() - 31536000, ''/''); //On supprime le cookie.		<br />\r\n		<br />\r\n		if( isset($_COOKIE[$CONFIG[''site_cookie''].''_autoconnect'']) )<br />\r\n			setcookie($CONFIG[''site_cookie''].''_autoconnect'', '''', time() - 31536000, ''/''); //On supprime le cookie.<br />\r\n		<br />\r\n		$this-&gt;session_garbage_collector();<br />\r\n	}<br />\r\n	<br />\r\n	<br />\r\n	//Détecte les principaux robots par plage ip, retourne leurs noms, et enregistre le nombre et l''heure de passages dans un fichier texte.<br />\r\n	function check_robot($user_ip)<br />\r\n	{<br />\r\n		if( preg_match(''`(w3c|http://|bot|spider|Gigabot|gigablast.com)+`i'', $_SERVER[''HTTP_USER_AGENT'']) )<br />\r\n			return ''unknow_bot'';<br />\r\n			<br />\r\n		//Chaque ligne représente une plage ip.<br />\r\n		$plage_ip = array(<br />\r\n			''66.249.64.0'' =&gt; ''66.249.95.255'',<br />\r\n			''209.85.128.0'' =&gt; ''209.85.255.255'', <br />\r\n			''65.52.0.0'' =&gt; ''65.55.255.255'',<br />\r\n			''207.68.128.0'' =&gt; ''207.68.207.255'',<br />\r\n			''66.196.64.0'' =&gt; ''66.196.127.255'',<br />\r\n			''68.142.192.0'' =&gt; ''68.142.255.255'',<br />\r\n			''72.30.0.0'' =&gt; ''72.30.255.255'',<br />\r\n			''193.252.148.0'' =&gt; ''193.252.148.255'',<br />\r\n			''66.154.102.0'' =&gt; ''66.154.103.255'',<br />\r\n			''209.237.237.0'' =&gt; ''209.237.238.255'',<br />\r\n			''193.47.80.0'' =&gt; ''193.47.80.255''<br />\r\n		);<br />\r\n<br />\r\n		//Nom des bots associés.<br />\r\n		$array_robots = array(<br />\r\n			''Google bot'',<br />\r\n			''Google bot'',<br />\r\n			''Msn bot'',<br />\r\n			''Msn bot'',<br />\r\n			''Yahoo Slurp'',<br />\r\n			''Yahoo Slurp'',<br />\r\n			''Yahoo Slurp'',<br />\r\n			''Voila'',<br />\r\n			''Gigablast'',<br />\r\n			''Ia archiver'',<br />\r\n			''Exalead''<br />\r\n		);<br />\r\n		<br />\r\n		//Ip de l''utilisateur au format numérique.<br />\r\n		$user_ip = ip2long($user_ip);	<br />\r\n<br />\r\n		//On explore le tableau pour identifier les robots<br />\r\n		$r = 0;<br />\r\n		foreach($plage_ip as $start_ip =&gt; $end_ip)<br />\r\n		{	<br />\r\n			$start_ip = ip2long($start_ip);<br />\r\n			$end_ip = ip2long($end_ip);			<br />\r\n			<br />\r\n			//Comparaison pour chaque partie de l''ip, si l''une d''entre elle est fausse l''instruction est stopée.<br />\r\n			if( $user_ip &gt;= $start_ip &amp;&amp; $user_ip &lt;= $end_ip ) <br />\r\n			{<br />\r\n				//Insertion dans le fichier texte des visites des robots.<br />\r\n				$file_path = ''../cache/robots.txt'';<br />\r\n				if( !file_exists($file_path) ) <br />\r\n				{<br />\r\n					$file = @fopen($file_path, ''w+''); //Si le fichier n''existe pas on le crée avec droit d''écriture et lecture.<br />\r\n					@fwrite($file, serialize(array())); //On insère un tableau vide.<br />\r\n					@fclose($file);<br />\r\n				}<br />\r\n<br />\r\n				if( is_file($file_path) &amp;&amp; is_writable($file_path) ) //Fichier accessible en écriture.<br />\r\n				{<br />\r\n					$robot = $array_robots[$r]; //Nom du robot.<br />\r\n					$time = gmdate_format(''YmdHis'', time(), TIMEZONE_SYSTEM); //Date et heure du dernier passage!<br />\r\n					<br />\r\n					$line = file($file_path);<br />\r\n					$data = unserialize($line[0]); //Renvoi la première ligne du fichier (le array précédement crée).<br />\r\n					<br />\r\n					$array_info = explode(''/'', $data[$robot]); //Récuperation des valeurs.<br />\r\n					if( $array_robots[$r] === $array_info[0] ) //Robo repasse.<br />\r\n					{<br />\r\n						$array_info[1]++; //Nbr de  visite.<br />\r\n						$array_info[2] = $time; //Date Dernière visite<br />\r\n						$data[$robot] = implode(''/'', $array_info);<br />\r\n					}<br />\r\n					else<br />\r\n						$data[$robot] = $robot . ''/1/'' . $time; //Création du array contenant les valeurs.<br />\r\n					<br />\r\n					$file = @fopen($file_path, ''r+'');<br />\r\n					fwrite($file, serialize($data)); //On stock le tableau dans le fichier de données<br />\r\n					fclose($file);<br />\r\n				}			<br />\r\n				return $array_robots[$r]; //On retourne le nom du robot d''exploration.<br />\r\n			}<br />\r\n			$r++;<br />\r\n		}	<br />\r\n		return false;<br />\r\n	}<br />\r\n	<br />\r\n	//Vérifie le niveau d''autorisation.<br />\r\n	function check_auth($userdata, $secure)<br />\r\n	{<br />\r\n		if( isset($userdata[''level'']) &amp;&amp; $userdata[''level''] &gt;= $secure ) <br />\r\n			return true;<br />\r\n		return false;<br />\r\n	}<br />\r\n}<br />\r\n<br />\r\n?&gt;<br />\r\n[/code]<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\nkhjkhjkhj jkhkhj<br />\r\n<br />\r\n<br />\r\nkjhjkhj<br />\r\nhgfh', 1198458796, 1198463522, 1, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (91, 12, 1, 'sdfd', 1199113506, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (92, 12, 1, 'fdsfs', 1199113509, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (93, 12, 1, 'fds fds', 1199113512, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (103, 23, 1, 'f sdfsd', 1203118237, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (104, 23, 2, 'iuiyu', 1203118264, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (105, 23, 1, 'jj', 1203118286, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (106, 12, 2, 'hgfh gf', 1203119673, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (107, 4, 2, 'hgfhgf', 1203119682, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (108, 2, 2, 'hgf', 1203119692, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (109, 21, 2, 'hgfhgf', 1203119703, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (110, 21, 2, 'gdfg', 1203119762, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (111, 22, 2, 'gsdfgf', 1203119771, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (112, 7, 2, 'gsdfgdf', 1203119779, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (113, 7, 2, 'g sdfgdf', 1203119789, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (114, 15, 2, 'gdfgdf', 1203119800, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (115, 24, 2, 'gdfg', 1203120077, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (116, 15, 1, 'hjyhh', 1203120224, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (117, 21, 1, 'gfdgdf', 1203120258, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (118, 25, 1, 'uytu', 1203122335, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (119, 26, 1, 'hgf', 1203122351, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (120, 26, 2, 'gfdgfd', 1203122566, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (121, 27, 2, 'gfd', 1203122574, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (122, 28, 2, 'hgf', 1203122590, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (123, 28, 2, 'ggdf', 1203122840, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (124, 25, 2, 'gdfgdf', 1203122848, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (125, 29, 2, 'fsdfsdfdsfds fsd', 1203124182, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (126, 13, 2, 'gfd', 1203188858, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (127, 13, 2, 'gfdg', 1203189656, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (128, 13, 2, 'mkl', 1203189796, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (129, 30, 2, 'fsdfsd', 1203303212, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (130, 31, 1, 'gdf', 1203307880, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (131, 32, 1, 'gdf', 1203307946, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (133, 34, 1, 'test', 1203442990, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (134, 35, 1, 'test', 1203442997, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (135, 36, 1, 'uhgf', 1203448000, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (136, 37, 1, 'uhgf', 1203448066, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (137, 38, 1, 'fdgfd', 1203463089, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (138, 39, 1, 'fdgfd', 1203464009, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (139, 40, 1, 'fdgfd', 1203464169, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (140, 41, 1, 'fdgfd', 1203464192, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (141, 42, 1, 'test', 1203464447, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (151, 44, 1, 'fdsfsd', 1204845178, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (152, 45, 1, ' thj gfdgdf<strong>g dfgdf</strong> gdfgfdgdf<br />\r\n<br />\r\n<span style="text-decoration: underline;">gdfgdf</span>!! ', 1204845380, 0, 0, '127.0.0.1');
INSERT INTO `phpboost_forum_msg` VALUES (153, 43, 2, ' test!!! ggfdgte <img src="../images/smileys/sourire.gif" alt=":)" class="smiley" /> ', 1205684012, 1205684190, 2, '127.0.0.1');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_forum_poll`
-- 

CREATE TABLE `phpboost_forum_poll` (
  `id` int(11) NOT NULL auto_increment,
  `idtopic` int(11) NOT NULL default '0',
  `question` varchar(255) NOT NULL default '',
  `answers` text NOT NULL,
  `voter_id` text NOT NULL,
  `votes` text NOT NULL,
  `type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `idtopic` (`idtopic`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- 
-- Contenu de la table `phpboost_forum_poll`
-- 

INSERT INTO `phpboost_forum_poll` VALUES (1, 13, 'test', '1|2|3', '0|1', '', 0);
INSERT INTO `phpboost_forum_poll` VALUES (2, 30, 'fsd', '1|2', '0|2|1', '2|0', 0);
INSERT INTO `phpboost_forum_poll` VALUES (3, 31, 'gf', '12|2', '0|2', '1|0', 0);
INSERT INTO `phpboost_forum_poll` VALUES (4, 32, 'gf', '12|2|hgfhgf|hgf', '0|2', '', 1);
INSERT INTO `phpboost_forum_poll` VALUES (5, 35, 'hgf', '1|2|3|4|5|6|7|8|9|10|11|12|14|15|18', '0|1', '', 1);
INSERT INTO `phpboost_forum_poll` VALUES (6, 36, 'gfgfgf', '1|2|3|4', '0|1', '0|1|0|1', 1);
INSERT INTO `phpboost_forum_poll` VALUES (7, 37, 'gfgfgf2', '1|2|3|5', '0|1', '0|0|0|2', 0);
INSERT INTO `phpboost_forum_poll` VALUES (8, 38, 'test', 'hgfh|fh|ghgf', '0', '0|0|0', 0);
INSERT INTO `phpboost_forum_poll` VALUES (9, 41, 'test', 'test1|test2|test4|test5', '0|1', '1|0|0|0', 1);
INSERT INTO `phpboost_forum_poll` VALUES (11, 44, 'hgf', 'hgf', '0|1', '1', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_forum_topics`
-- 

CREATE TABLE `phpboost_forum_topics` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `subtitle` varchar(75) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `nbr_msg` mediumint(9) NOT NULL default '0',
  `nbr_views` mediumint(9) NOT NULL default '0',
  `last_user_id` int(11) NOT NULL default '0',
  `last_msg_id` int(11) NOT NULL default '0',
  `last_timestamp` int(11) NOT NULL default '0',
  `first_msg_id` int(11) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `aprob` tinyint(1) NOT NULL default '0',
  `display_msg` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`,`last_user_id`,`last_timestamp`,`type`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

-- 
-- Contenu de la table `phpboost_forum_topics`
-- 

INSERT INTO `phpboost_forum_topics` VALUES (1, 2, 'Test dqsdqsd sq dqsdsq dqsd qsdazd az qsdz&quot;ad dzedfazds qdqsdazd qsdqsdaz d qsdazddqsd qsdqsd q', 'Sujet de test dqsd qsd qsdsq hgfhgfh gf hgf hgfh gf hgfh gfh gfh gfh gfhgfh', 1, 3, 197, 2, 100, 1203115764, 1, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (2, 2, 'test', '', 1, 2, 15, 2, 108, 1203119692, 3, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (12, 2, 'level1 test', 'J''ai noté quelques petits défauts sans importance, mais à corriger', 2, 13, 134, 2, 106, 1203119673, 34, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (4, 2, 'test', '', 1, 3, 9, 2, 107, 1203119682, 8, 2, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (5, 2, 'test4', '', 1, 2, 11, 2, 30, 1197303126, 9, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (21, 5, 'jhg', '', 1, 16, 138, 1, 117, 1203120258, 42, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (7, 5, 'fds', '', 1, 4, 19, 2, 113, 1203119789, 11, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (13, 2, 'cut2''&quot;', 'desc''&quot;', 1, 7, 155, 2, 128, 1203189796, 35, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (14, 6, 'gfdf', 'gfdgdf', 1, 5, 49, 1, 148, 1204237656, 37, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (15, 11, 'level3 test', '', 1, 7, 38, 1, 116, 1203120224, 52, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (22, 5, 'test', '', 1, 5, 39, 2, 111, 1203119771, 69, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (23, 2, 'fsdf', '', 1, 3, 9, 1, 105, 1203118286, 103, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (24, 2, 'gdf', '', 2, 1, 3, 2, 115, 1203120077, 115, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (25, 2, 'uyt', '', 1, 2, 9, 2, 124, 1203122848, 118, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (26, 2, 'hgf', '', 1, 2, 7, 2, 120, 1203122566, 119, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (27, 2, 'gdf', '', 2, 1, 2, 2, 121, 1203122574, 121, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (28, 2, 'htfgfd', '', 2, 2, 5, 2, 123, 1203122840, 122, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (29, 5, 'Amélioration de la galerie', '', 2, 1, 6, 2, 125, 1203124182, 125, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (30, 13, 'fsdfsd', '', 2, 1, 9, 2, 129, 1203303212, 129, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (31, 13, 'gdf', '', 1, 1, 7, 1, 130, 1203307880, 130, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (32, 13, 'gdf2', '', 1, 1, 31, 1, 131, 1203307946, 131, 0, 1, 0, 1);
INSERT INTO `phpboost_forum_topics` VALUES (34, 13, 'test', '', 1, 1, 2, 1, 133, 1203442990, 133, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (35, 13, 'test', '', 1, 1, 6, 1, 134, 1203442997, 134, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (36, 13, 'hgf', '', 1, 1, 4, 1, 135, 1203448000, 135, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (37, 13, 'hgf2', '', 1, 1, 9, 1, 136, 1203448066, 136, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (38, 13, 'gdf', 'g', 1, 1, 3, 1, 137, 1203463089, 137, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (39, 13, 'gdf3', 'g', 1, 1, 1, 1, 138, 1203464009, 138, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (40, 13, 'gdf3', 'g', 1, 1, 1, 1, 139, 1203464169, 139, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (41, 13, 'gdf3', 'g', 1, 1, 11, 1, 140, 1203464192, 140, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (42, 6, 'poll cut', 'fsdfsd', 1, 1, 10, 1, 141, 1203464447, 141, 0, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (43, 2, 'vxcvxc', '', 1, 2, 52, 2, 153, 1205684012, 150, 1, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (44, 13, 'ghgf hgf', 'hgf', 1, 1, 5, 1, 151, 1204845178, 151, 1, 1, 0, 0);
INSERT INTO `phpboost_forum_topics` VALUES (45, 13, 'gdfg', 'gdfg', 1, 1, 3, 1, 152, 1204845380, 152, 0, 1, 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_forum_track`
-- 

CREATE TABLE `phpboost_forum_track` (
  `id` int(11) NOT NULL auto_increment,
  `idtopic` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `pm` tinyint(1) NOT NULL default '0',
  `mail` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `idtopic` (`idtopic`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `phpboost_forum_track`
-- 

INSERT INTO `phpboost_forum_track` VALUES (2, 12, 1, 0, 0);
INSERT INTO `phpboost_forum_track` VALUES (3, 32, 1, 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_forum_view`
-- 

CREATE TABLE `phpboost_forum_view` (
  `id` int(11) NOT NULL auto_increment,
  `idtopic` int(11) NOT NULL default '0',
  `last_view_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idv` (`idtopic`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

-- 
-- Contenu de la table `phpboost_forum_view`
-- 

INSERT INTO `phpboost_forum_view` VALUES (64, 43, 153, 1, 1206562804);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_gallery`
-- 

CREATE TABLE `phpboost_gallery` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `width` mediumint(9) NOT NULL default '0',
  `height` mediumint(9) NOT NULL default '0',
  `weight` mediumint(9) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `aprob` tinyint(1) NOT NULL default '0',
  `views` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `users_note` text NOT NULL,
  `nbrnote` mediumint(9) NOT NULL default '0',
  `note` float NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- 
-- Contenu de la table `phpboost_gallery`
-- 

INSERT INTO `phpboost_gallery` VALUES (8, 0, 'test', 'banniere_phpboost.jpg', 468, 60, 13535, 1, 1, 0, 1205003043, '1', 1, 4, 0, 0);
INSERT INTO `phpboost_gallery` VALUES (4, 0, 'Test2''fd gfd', 'bfe750bdfb36ccec47f2255470f6a9ba.jpg', 179, 230, 12373, 1, 1, 10, 1197401791, '1/2', 2, 3, 0, 0);
INSERT INTO `phpboost_gallery` VALUES (6, 1, 'jghjh', 'f670a9459d82dc702d42b1e37b0ce336.jpg', 320, 240, 28820, 1, 1, 7, 1197402089, '1', 1, 3, 0, 0);
INSERT INTO `phpboost_gallery` VALUES (9, 0, 'kujku', 'online2.jpg', 107, 128, 4168, 1, 1, 18, 1205003059, '1/2', 2, 3.5, 0, 0);
INSERT INTO `phpboost_gallery` VALUES (10, 0, '', 'offline.jpg', 107, 128, 4163, 1, 1, 1, 1205003072, '1/2', 2, 3, 0, 0);
INSERT INTO `phpboost_gallery` VALUES (11, 0, '', 'banniere_phpboost2.gif', 468, 60, 81128, 1, 1, 2, 1205003090, '1/2', 2, 3.5, 0, 0);
INSERT INTO `phpboost_gallery` VALUES (12, 0, '', 'online2_2ce77.jpg', 107, 128, 4168, 1, 1, 0, 1205009039, '1', 1, 2, 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_gallery_cats`
-- 

CREATE TABLE `phpboost_gallery_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_left` int(11) NOT NULL default '0',
  `id_right` int(11) NOT NULL default '0',
  `level` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL default '',
  `contents` text NOT NULL,
  `nbr_pics_aprob` mediumint(9) unsigned NOT NULL default '0',
  `nbr_pics_unaprob` mediumint(9) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `aprob` tinyint(1) NOT NULL default '1',
  `auth` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id_left` (`id_left`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `phpboost_gallery_cats`
-- 

INSERT INTO `phpboost_gallery_cats` VALUES (1, 1, 2, 0, 'test', 'test', 1, 0, 1, 1, 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_group`
-- 

CREATE TABLE `phpboost_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `img` varchar(255) NOT NULL default '',
  `auth` varchar(255) NOT NULL default '0',
  `members` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `phpboost_group`
-- 

INSERT INTO `phpboost_group` VALUES (1, 'test', 'phpboost.jpg', 'a:3:{s:10:"auth_flood";i:0;s:14:"pm_group_limit";i:-1;s:16:"data_group_limit";d:5120;}', '1|');
INSERT INTO `phpboost_group` VALUES (3, 'test2', '', 'a:3:{s:10:"auth_flood";i:1;s:14:"pm_group_limit";i:75;s:16:"data_group_limit";d:5120;}', '1|2|');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_guestbook`
-- 

CREATE TABLE `phpboost_guestbook` (
  `id` int(11) NOT NULL auto_increment,
  `contents` text NOT NULL,
  `login` varchar(255) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

-- 
-- Contenu de la table `phpboost_guestbook`
-- 

INSERT INTO `phpboost_guestbook` VALUES (1, 'test', 'crowkait', 1, 1194143728);
INSERT INTO `phpboost_guestbook` VALUES (2, '[math]e^{{i}*{pi}}=-1[/math] C''est fou, ce truc !<br />\r\n <img src="../images/smileys/clap.gif" alt=":clap" class="smiley" />  <img src="../images/smileys/clap.gif" alt=":clap" class="smiley" />  <img src="../images/smileys/clap.gif" alt=":clap" class="smiley" />', 'crowkait', 1, 1195345526);
INSERT INTO `phpboost_guestbook` VALUES (6, '<acronym title="blablabla" class="acronym">gdfgfd</acronym>', 'crowkait', 1, 1196461860);
INSERT INTO `phpboost_guestbook` VALUES (4, '<span style="font-family: arial;">test</span> blablabla', 'crowkait', 1, 1195914073);
INSERT INTO `phpboost_guestbook` VALUES (7, '<strong>fsdfsd</strong>fsdfsdtest <em>fsdf</em><br /> <span class="question">gdgdf</span>g<br /> <br /><br /><br /><h4 class="stitle1">gdfgdf</h4><br /><br /><br /><br /> <a href="http://www.phpboost.com/news/news.php">hfghgf</a><br /> <span style="font-size: 20px;">jghjhjh</span><br /> <sup>jghjgh</sup>jghjgh<br /> <p style="text-align:center">hgfhfghfh</p>hfg<br /> <table class="bb_table"><tr class="bb_table_row"><td class="bb_table_col">hgfhfgf</td><td class="bb_table_col">hgfhfdffgf</td></tr></table>', 'teston', 2, 1196471992);
INSERT INTO `phpboost_guestbook` VALUES (10, 'Merci, c''est corrig&amp;eacute;. Si vous trouvez d''autres petits bugs du genre, n''h&amp;eacute;sitez pas &amp;agrave; les signaler. Je viens de mettre &amp;agrave; jour le site donc il peut rester des bugs.', 'crowkait', 1, 1196475985);
INSERT INTO `phpboost_guestbook` VALUES (11, 'Merci, c''est corrig&eacute;. Si vous trouvez d''autres petits bugs du genre, n''h&eacute;sitez pas &agrave; les signaler. Je viens de mettre &agrave; jour le site donc il peut rester des bugs.', 'crowkait', 1, 1196476152);
INSERT INTO `phpboost_guestbook` VALUES (53, '<table class="bb_table" style="margin:auto;width:90%">\r\n<tr class="bb_table_row">\r\n		<th class="bb_table_head" style="width:50%">Noix</th>\r\n		<th class="bb_table_head" style="width:50%">Crotte</th>\r\n	</tr>\r\n<tr class="bb_table_row">\r\n<td class="bb_table_col" style="vertical-align: top;">gdf</td>\r\n<td class="bb_table_col">gdf</td>\r\n</tr>\r\n<tr class="bb_table_row">\r\n<td class="bb_table_col" colspan="2" style="text-align:center;background:#BFD9EF">gdf</td>\r\n</tr>\r\n</table>', 'crowkait', 1, 1197413744);
INSERT INTO `phpboost_guestbook` VALUES (54, '<a href="http://127.0.0.1/PHP/PHPBoost/PHPBoost_2.1/guestbook/guestbook.php">http://127.0.0.1/PHP/PHPBoost/PHPBoost_2.1/guestbook/guestbook.php</a>', 'http://127.0.0.1/PHP/PHPB', -1, 1197467813);
INSERT INTO `phpboost_guestbook` VALUES (55, 'gfdgdfg', 'http://www.google.fr/', -1, 1197467854);
INSERT INTO `phpboost_guestbook` VALUES (56, 'fsdfsd', 'Visiteur', -1, 1197467998);
INSERT INTO `phpboost_guestbook` VALUES (57, '[url=guestbook.php]http://127.0.0.1/PHP/PHPBoost/PHPBoost_2.1/guestbook/guestbook.php[/url]', 'Visiteur', -1, 1197468018);
INSERT INTO `phpboost_guestbook` VALUES (58, 'fsfds<br />\r\n<br />\r\ngdfgdf  <a href="http://www.google.fr/">http://www.google.fr/</a> gfd gfd <br />\r\n<br />', 'Visiteur', -1, 1197468121);
INSERT INTO `phpboost_guestbook` VALUES (60, '&copy; ©<br />\r\n''¤'', ''&amp;#8218;'', ''&#402;'', ''&amp;#8222;'', ''&amp;#8230;'', ''&amp;#8224;'', ''&amp;#8225;'', ''&#710;'', ''&amp;#8240;'',<br />\r\n			''¦'', ''&amp;#8249;'', ''¼'', ''´'', ''&amp;#8216;'', ''&amp;#8217;'', ''&amp;#8220;'', ''&amp;#8221;'', ''&amp;#8226;'',<br />\r\n			''&amp;#8211;'', ''&amp;#8212;'',  ''&#732;'', ''&amp;#8482;'', ''¨'', ''&amp;#8250;'', ''½'', ''¸'', ''¾''', 'crowkait', 1, 1198340358);
INSERT INTO `phpboost_guestbook` VALUES (27, 'dfssd&lt;br&gt; &quot; '' , &lt; &gt; &eacute; &agrave; &ccedil; @ &#8364; &amp; &amp;amp; &amp;amp;eacute;<br />\r\n<br />\r\n&lt;br /&gt;<br />\r\n<br />\r\n&lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                    &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                        &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n                        &lt;br /&gt;<br />\r\n                        &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;       <br />\r\n                        &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;   <br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                        &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                &lt;/form&gt;<br />\r\n<br />\r\n[code]&lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                    &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                        &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n                        &lt;br /&gt;<br />\r\n                        &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;       <br />\r\n                        &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;   <br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                        &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                &lt;/form&gt;[/code]<br />\r\n<br />\r\ng', 'teston', 2, 1196479520);
INSERT INTO `phpboost_guestbook` VALUES (26, 'dfssd&lt;br&gt; &quot; '' , &lt; &gt; &eacute; &agrave; &ccedil; @ &amp;euro; &amp; &amp;amp; &amp;amp;eacute;<br />\r\n<br />\r\n&lt;br /&gt;<br />\r\n<br />\r\n&lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                    &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                        &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n                        &lt;br /&gt;<br />\r\n                        &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;        <br />\r\n                        &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;    <br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                        &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                &lt;/form&gt; <br />\r\n<br />\r\n[code]&lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                    &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                        &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n                        &lt;br /&gt;<br />\r\n                        &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;        <br />\r\n                        &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;    <br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                        &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                &lt;/form&gt;[/code]<br />\r\n<br />\r\ng <br />\r\n<br />', 'crowkait', 1, 1196479425);
INSERT INTO `phpboost_guestbook` VALUES (25, 'dfssd&lt;br&gt; &quot; '' , &lt; &gt; &eacute; &agrave; &ccedil; @ &amp;euro; &amp; &amp;amp; &amp;amp;eacute;<br />\r\n<br />\r\n&lt;br /&gt;<br />\r\n<br />\r\n[code]&lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                    &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                        &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n                        &lt;br /&gt;<br />\r\n                        &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;        <br />\r\n                        &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;    <br />\r\n                    &lt;/p&gt;<br />\r\n                    &lt;p&gt;<br />\r\n                        &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n                    &lt;/p&gt;<br />\r\n                &lt;/form&gt;[/code]<br />\r\n<br />\r\ng <br />\r\n<br />', 'crowkait', 1, 1196479085);
INSERT INTO `phpboost_guestbook` VALUES (28, 'dfssd&lt;br&gt; &quot; '' , &lt; &gt; &eacute; &agrave; &ccedil; @ &amp;euro; &amp; &amp;amp; &amp;amp;eacute;<br />\r\n <br />\r\n &lt;br /&gt;<br />\r\n <br />\r\n &lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n 	 	 	 	 	 &lt;p&gt;<br />\r\n 	 	 	 	 	 &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n 	 	 	 	 	 &lt;/p&gt;<br />\r\n 	 	 	 	 	 &lt;p&gt;<br />\r\n 	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n 	 	 	 	 	 	 &lt;br /&gt;<br />\r\n 	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n 	 	 	 	 	 &lt;/p&gt;<br />\r\n 	 	 	 	 	 &lt;p&gt;	 	 <br />\r\n 	 	 	 	 	 	 &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;	 <br />\r\n 	 	 	 	 	 &lt;/p&gt;<br />\r\n 	 	 	 	 	 &lt;p&gt;<br />\r\n 	 	 	 	 	 	 &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n 	 	 	 	 	 &lt;/p&gt;<br />\r\n 	 	 	 	 &lt;/form&gt; <br />\r\n <br />\r\n [code]&lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n 	 	 	 	 	 &lt;p&gt;<br />\r\n 	 	 	 	 	 &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n 	 	 	 	 	 &lt;/p&gt;<br />\r\n 	 	 	 	 	 &lt;p&gt;<br />\r\n 	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n 	 	 	 	 	 	 &lt;br /&gt;<br />\r\n 	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n 	 	 	 	 	 &lt;/p&gt;<br />\r\n 	 	 	 	 	 &lt;p&gt;	 	 <br />\r\n 	 	 	 	 	 	 &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;	 <br />\r\n 	 	 	 	 	 &lt;/p&gt;<br />\r\n 	 	 	 	 	 &lt;p&gt;<br />\r\n 	 	 	 	 	 	 &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n 	 	 	 	 	 &lt;/p&gt;<br />\r\n 	 	 	 	 &lt;/form&gt;[/code]<br />\r\n <br />\r\n g&nbsp;', 'crowkait', 1, 1196480163);
INSERT INTO `phpboost_guestbook` VALUES (29, 'dfssd&lt;br&gt; &quot; '' , &lt; &gt; &eacute; &agrave; &ccedil; @ &amp;euro; &amp; &amp;amp; &amp;amp;eacute;<br />\r\n<br />\r\n&lt;br /&gt;<br />\r\n<br />\r\n&lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n	 	 	 	 	 &lt;p&gt;<br />\r\n	 	 	 	 	 &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n	 	 	 	 	 &lt;/p&gt;<br />\r\n	 	 	 	 	 &lt;p&gt;<br />\r\n	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n	 	 	 	 	 	 &lt;br /&gt;<br />\r\n	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n	 	 	 	 	 &lt;/p&gt;<br />\r\n	 	 	 	 	 &lt;p&gt;	 	 <br />\r\n	 	 	 	 	 	 &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;	 <br />\r\n	 	 	 	 	 &lt;/p&gt;<br />\r\n	 	 	 	 	 &lt;p&gt;<br />\r\n	 	 	 	 	 	 &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n	 	 	 	 	 &lt;/p&gt;<br />\r\n	 	 	 	 &lt;/form&gt; <br />\r\n<br />\r\n[code]&lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n	 	 	 	 	 &lt;p&gt;<br />\r\n	 	 	 	 	 &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n	 	 	 	 	 &lt;/p&gt;<br />\r\n	 	 	 	 	 &lt;p&gt;<br />\r\n	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n	 	 	 	 	 	 &lt;br /&gt;<br />\r\n	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n	 	 	 	 	 &lt;/p&gt;<br />\r\n	 	 	 	 	 &lt;p&gt;	 	 <br />\r\n	 	 	 	 	 	 &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;	 <br />\r\n	 	 	 	 	 &lt;/p&gt;<br />\r\n	 	 	 	 	 &lt;p&gt;<br />\r\n	 	 	 	 	 	 &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n	 	 	 	 	 &lt;/p&gt;<br />\r\n	 	 	 	 &lt;/form&gt;[/code]<br />\r\n<br />\r\ng&nbsp;<br />\r\n<br />', 'crowkait', 1, 1196480379);
INSERT INTO `phpboost_guestbook` VALUES (30, 'dfssd&lt;br&gt; &quot; '' , &lt; &gt; &eacute; &agrave; &ccedil; @ &euro; &amp; &amp; &amp;eacute;<br />\r\n   <br />\r\n   &lt;br /&gt;<br />\r\n   <br />\r\n   &lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n                       &lt;p&gt;<br />\r\n 	 	 &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n                       &lt;/p&gt;<br />\r\n                       &lt;p&gt;<br />\r\n                           &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n                           &lt;br /&gt;<br />\r\n                           &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n                       &lt;/p&gt;<br />\r\n                       &lt;p&gt;        <br />\r\n                           &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;    <br />\r\n                       &lt;/p&gt;<br />\r\n                       &lt;p&gt;<br />\r\n                           &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n                       &lt;/p&gt;<br />\r\n                   &lt;/form&gt; <br />\r\n   <br />\r\n   [code]&lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n                       &lt;p&gt;<br />\r\n                       &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n                       &lt;/p&gt;<br />\r\n                       &lt;p&gt;<br />\r\n                           &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n                           &lt;br /&gt;<br />\r\n                           &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n                       &lt;/p&gt;<br />\r\n                       &lt;p&gt;        <br />\r\n                           &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;    <br />\r\n                       &lt;/p&gt;<br />\r\n                       &lt;p&gt;<br />\r\n 	 	 &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n                       &lt;/p&gt;<br />\r\n                   &lt;/form&gt;[/code]<br />\r\n   <br />\r\n   g&nbsp;', 'crowkait', 1, 1196480405);
INSERT INTO `phpboost_guestbook` VALUES (37, 'dfssd&lt;br&gt; &quot; '' , &lt; &gt; &eacute; &agrave; &ccedil; @ &euro; &amp; &amp; &eacute;<br />\r\n       <br />\r\n       &lt;br /&gt;<br />\r\n       <br />\r\n       &lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n       	 	 	 	 	 &lt;p&gt;<br />\r\n       	 	 	 	 	 &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n       	 	 	 	 	 &lt;/p&gt;<br />\r\n       	 	 	 	 	 &lt;p&gt;<br />\r\n       	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n       	 	 	 	 	 	 &lt;br /&gt;<br />\r\n       	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n       	 	 	 	 	 &lt;/p&gt;<br />\r\n       	 	 	 	 	 &lt;p&gt;	 	 <br />\r\n       	 	 	 	 	 	 &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;	 <br />\r\n       	 	 	 	 	 &lt;/p&gt;<br />\r\n       	 	 	 	 	 &lt;p&gt;<br />\r\n       	 	 	 	 	 	 &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n       	 	 	 	 	 &lt;/p&gt;<br />\r\n       	 	 	 	 &lt;/form&gt; <br />\r\n       <br />\r\n       [code]&lt;form action=&quot;{ACTION}&quot; method=&quot;post&quot;&gt;<br />\r\n       	 	 	 	 	 &lt;p&gt;<br />\r\n       	 	 	 	 	 &lt;input type=&quot;text&quot; name=&quot;mail_newsletter&quot; maxlength=&quot;50&quot; size=&quot;18&quot; class=&quot;text&quot; value=&quot;{USER_MAIL}&quot; /&gt;<br />\r\n       	 	 	 	 	 &lt;/p&gt;<br />\r\n       	 	 	 	 	 &lt;p&gt;<br />\r\n       	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;subscribe&quot; checked=&quot;checked&quot; /&gt; {SUBSCRIBE}&lt;/label&gt;<br />\r\n       	 	 	 	 	 	 &lt;br /&gt;<br />\r\n       	 	 	 	 	 	 &lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;subscribe&quot; value=&quot;unsubscribe&quot; /&gt; {UNSUBSCRIBE}&lt;/label&gt;<br />\r\n       	 	 	 	 	 &lt;/p&gt;<br />\r\n       	 	 	 	 	 &lt;p&gt;	 	 <br />\r\n       	 	 	 	 	 	 &lt;input type=&quot;submit&quot; value=&quot;{L_SUBMIT}&quot; class=&quot;submit&quot; /&gt;	 <br />\r\n       	 	 	 	 	 &lt;/p&gt;<br />\r\n       	 	 	 	 	 &lt;p&gt;<br />\r\n       	 	 	 	 	 	 &lt;a href=&quot;{ARCHIVES_LINK}&quot; style=&quot; font-size:10px;&quot;&gt;{L_ARCHIVES}&lt;/a&gt;<br />\r\n       	 	 	 	 	 &lt;/p&gt;<br />\r\n       	 	 	 	 &lt;/form&gt;[/code]<br />\r\n       <br />\r\n       g&nbsp;<br />\r\n     <br />\r\n     <strong>gdfgdfgf <em>gdfgdfg&nbsp; <span style="text-decoration: underline;">gfdgdf<strike> gdfgddf </strike>gdfgg </span>dfgdfg</em>dfgdfg</strong>dfgdf<br />\r\n     <br />\r\n     <p style="text-align:center">gdfgdf</p><ul class="bb_ul"><li class="bb_li">gdfgdf</li><li class="bb_li">jfhg</li></ul><ol class="bb_ol"><li class="bb_li">hgfhgf</li><li class="bb_li">gfhfg</li></ol><sub>hfgh</sub>hgfhgfhgfhg<sup>hfghfghhgf</sup>hgfhf<sub>hgfhfhffg</sub><br />\r\n     <br />\r\n   <p style="text-align:center"><img src="../gallery/pics/thumbnails/03a9fe98f340a5ae63021213859cf9ea.jpg" alt="" class="valign_" /></p><br />\r\n     <br />\r\n     <table class="bb_table"><tr class="bb_table_row"><td class="bb_table_col">&nbsp;gdf</td><td class="bb_table_col">&nbsp;gdf</td></tr><tr class="bb_table_row"><td class="bb_table_col">&nbsp;gdf</td><td class="bb_table_col">&nbsp;gdf</td></tr></table><br />\r\n     <table class="bb_table"><tr class="bb_table_row"><td class="bb_table_col" colspan="2">&nbsp;hgf&nbsp;hgf</td></tr><tr class="bb_table_row"><td class="bb_table_col">&nbsp;hgf</td><td class="bb_table_col" rowspan="2">&nbsp;hgfhgf&nbsp;hgf</td></tr><tr class="bb_table_row"><td class="bb_table_col">&nbsp;hgf</td></tr></table>hgfhfghfg<span style="color:#999999;">khjkhj</span><br />\r\n     <br />\r\n     jhg&nbsp;<br />\r\n     <br />\r\n     &nbsp;', 'crowkait', 1, 1196483141);
INSERT INTO `phpboost_guestbook` VALUES (38, 'Tu dois avoir un probl&egrave;me de chmod sur le dossier racine dans lequel est install&eacute; PHPBoost, car ce fichier aurait du &ecirc;tre modifi&eacute;.<br />\r\n <br />\r\n Tu peux mettre ce code pour corriger le probl&egrave;me:<br />\r\n <br />\r\n [code]&lt;?php header(''location: http://dutbas.nix3.fr/news/news.php'');[/code]<br />', 'crowkait', 1, 1196519133);
INSERT INTO `phpboost_guestbook` VALUES (42, 'fds<strong>fsdfsd</strong><br />\r\n<br />\r\n<span style="text-decoration: underline;">gdfgdf<br />\r\nfsdfsd</span><br />\r\n<br />\r\ngdfgdf<br />\r\ngdfgf<br />\r\n<br />\r\ngdfgdfgfd<br />\r\n<br />\r\nhgfhgf&nbsp;<br />\r\n<br />\r\n&nbsp;<br />\r\n<br />', 'crowkait', 1, 1196525898);
INSERT INTO `phpboost_guestbook` VALUES (39, 'Tu dois avoir un probl&egrave;me de chmod sur le dossier racine dans lequel est install&eacute; PHPBoost, car ce fichier aurait du &ecirc;tre modifi&eacute;.<br />\r\n <br />\r\n Tu peux mettre ce code pour corriger le probl&egrave;me:<br />\r\n <br />\r\n [code]&lt;?php header(''location: <a href="http://dutbas.nix3.fr/news/news.php">http://dutbas.nix3.fr/news/news.php</a> ''); ?&gt;[/code]<br />', 'crowkait', 1, 1196519222);
INSERT INTO `phpboost_guestbook` VALUES (40, 'Tu dois avoir un probl&egrave;me de chmod sur le dossier racine dans lequel est install&eacute; PHPBoost, car ce fichier aurait du &ecirc;tre modifi&eacute;.<br />\r\n <br />\r\n Tu peux mettre ce code pour corriger le probl&egrave;me:<br />\r\n <br />\r\n [code]&lt;?php header(''location: <a href="http://dutbas.nix3.fr/news/news.php'');">http://dutbas.nix3.fr/news/news.php'');</a> ?&gt;[/code]<br />', 'crowkait', 1, 1196520452);
INSERT INTO `phpboost_guestbook` VALUES (41, 'Tu dois avoir un probl&egrave;me de chmod sur le dossier racine dans lequel est install&eacute; PHPBoost, car ce fichier aurait du &ecirc;tre modifi&eacute;.<br />\r\n   <br />\r\n   Tu peux mettre ce code pour corriger le probl&egrave;me:<br />\r\n   <br />\r\n   [code]&lt;?php header(''location: <a href="http://dutbas.nix3.fr/news/news.php">http://dutbas.nix3.fr/news/news.php</a>'');[/code]', 'crowkait', 1, 1196521010);
INSERT INTO `phpboost_guestbook` VALUES (43, 'gdfgdfgfdgdf', 'crowkait', 1, 1196529581);
INSERT INTO `phpboost_guestbook` VALUES (44, 'g', 'crowkait', 1, 1196529594);
INSERT INTO `phpboost_guestbook` VALUES (45, 'jhjgh&eacute; gdf', 'crowkait', 1, 1196530477);
INSERT INTO `phpboost_guestbook` VALUES (46, 'test<strong>test <em>test t<span style="text-decoration: underline;">est <strike>test </strike></span></em></strong><br />\r\n   <br />\r\n   <strong>fsdfsd&lt;strong&gt;fdsd</strong>gdf&lt;/strong&gt; gdfgdf<br />\r\n   <br />\r\n   <br />\r\n   <br />\r\n   <p style="text-align:center">fsdfsdfsdfsd</p>gdgdfgdf<br />\r\n   <br />\r\n   <p style="text-align:right">gdfgdf</p>gdfgf<br />\r\n   <br />\r\n   <ul class="bb_ul"><li class="bb_li">gf</li><li class="bb_li">gdf</li></ul><ol class="bb_ol"><li class="bb_li">ggfd</li><li class="bb_li">hg</li></ol><span style="background-color:#999999;">bg<span style="color:#999999;">col</span>or<br />\r\n  <br />\r\n  gdfgdfgdf&nbsp;<span style="color:#999999;">hgfhgfhg</span><br />\r\n  <br />\r\n  hjhg&nbsp;<br />\r\n  <br />\r\n </span>', 'crowkait', 1, 1196549887);
INSERT INTO `phpboost_guestbook` VALUES (48, '<a href="../forum/forum-6+test.php">http://127.0.0.1/PHP/PHPBoost/PHPBoost_2.0/forum/forum-6+test.php</a>', 'crowkait', 1, 1196563337);
INSERT INTO `phpboost_guestbook` VALUES (49, '<span class="text_hide">Caché:</span><div class="hide" onclick="bb_hide(this)"><div class="hide2">fsdf<br />\r\n<br />\r\n<br />\r\nfsdfsd<br />\r\n<br />\r\nfsdfds</div></div><br />\r\n<br />\r\n<span class="text_hide">Caché:</span><div class="hide" onclick="bb_hide(this)"><div class="hide2"><img src="http://127.0.0.1/PHP/PHPBoost/PHPBoost_2.1/gallery/pics/thumbnails/03a9fe98f340a5ae63021213859cf9ea.jpg" alt="" class="valign_" /></div></div>', 'crowkait', 1, 1197304579);
INSERT INTO `phpboost_guestbook` VALUES (50, '[code]Array<br />\r\n(<br />\r\n    [0] =&gt; PHPBoost output debug file<br />\r\n    [1] =&gt; -------------HOST-------------<br />\r\n    [2] =&gt; PHP [4.3.10]<br />\r\n    [3] =&gt; GD [1]<br />\r\n    [4] =&gt; Mod Rewrite [?]<br />\r\n    [5] =&gt; Register globals [0]<br />\r\n    [6] =&gt; Server name [http://127.0.0.1]<br />\r\n    [7] =&gt; Server path [/PHP/PHPBoost/PHPBoost_2.1]<br />\r\n    [8] =&gt; -------------CONFIG-------------<br />\r\n    [9] =&gt; Version [2.0]<br />\r\n    [10] =&gt; Server name [http://127.0.0.1]<br />\r\n    [11] =&gt; Server path [/PHP/PHPBoost/PHPBoost_2.1]<br />\r\n    [12] =&gt; Theme [phpboost]<br />\r\n    [13] =&gt; Lang [french]<br />\r\n    [14] =&gt; Start page [/news/news.php]<br />\r\n    [15] =&gt; Rewrite [1]<br />\r\n    [16] =&gt; Gz [0]<br />\r\n    [17] =&gt; Cookie [session]<br />\r\n    [18] =&gt; Site session [3600]<br />\r\n    [19] =&gt; Site session invit [300]<br />\r\n    [20] =&gt; -------------CHMOD-------------<br />\r\n    [21] =&gt; includes/auth/ [1]<br />\r\n    [22] =&gt; cache/ [1]<br />\r\n    [23] =&gt; upload/ [1]<br />\r\n    [24] =&gt; / [1]<br />\r\n)[code]blablablka [/pre]gfgdfgdf [/pre]', 'Visiteur', -1, 1197412929);
INSERT INTO `phpboost_guestbook` VALUES (51, '[code]Array<br />\r\n(<br />\r\n    [0] =&gt; PHPBoost output debug file<br />\r\n    [1] =&gt; -------------HOST-------------<br />\r\n    [2] =&gt; PHP [4.3.10]<br />\r\n    [3] =&gt; GD [1]<br />\r\n    [4] =&gt; Mod Rewrite [?]<br />\r\n    [5] =&gt; Register globals [0]<br />\r\n    [6] =&gt; Server name [http://127.0.0.1]<br />\r\n    [7] =&gt; Server path [/PHP/PHPBoost/PHPBoost_2.1]<br />\r\n    [8] =&gt; -------------CONFIG-------------<br />\r\n    [9] =&gt; Version [2.0]<br />\r\n    [10] =&gt; Server name [http://127.0.0.1]<br />\r\n    [11] =&gt; Server path [/PHP/PHPBoost/PHPBoost_2.1]<br />\r\n    [12] =&gt; Theme [phpboost]<br />\r\n    [13] =&gt; Lang [french]<br />\r\n    [14] =&gt; Start page [/news/news.php]<br />\r\n    [15] =&gt; Rewrite [1]<br />\r\n    [16] =&gt; Gz [0]<br />\r\n    [17] =&gt; Cookie [session]<br />\r\n    [18] =&gt; Site session [3600]<br />\r\n    [19] =&gt; Site session invit [300]<br />\r\n    [20] =&gt; -------------CHMOD-------------<br />\r\n    [21] =&gt; includes/auth/ [1]<br />\r\n    [22] =&gt; cache/ [1]<br />\r\n    [23] =&gt; upload/ [1]<br />\r\n    [24] =&gt; / [1]<br />\r\n)[/code]blablablka [/pre]gfgdfgdf [/pre]', 'Visiteur', -1, 1197412953);
INSERT INTO `phpboost_guestbook` VALUES (52, '<pre>[code]Array<br />\r\n(<br />\r\n    [0] =&gt; PHPBoost output debug file<br />\r\n    [1] =&gt; -------------HOST-------------<br />\r\n    [2] =&gt; PHP [4.3.10]<br />\r\n    [3] =&gt; GD [1]<br />\r\n    [4] =&gt; Mod Rewrite [?]<br />\r\n    [5] =&gt; Register globals [0]<br />\r\n    [6] =&gt; Server name [http://127.0.0.1]<br />\r\n    [7] =&gt; Server path [/PHP/PHPBoost/PHPBoost_2.1]<br />\r\n    [8] =&gt; -------------CONFIG-------------<br />\r\n    [9] =&gt; Version [2.0]<br />\r\n    [10] =&gt; Server name [http://127.0.0.1]<br />\r\n    [11] =&gt; Server path [/PHP/PHPBoost/PHPBoost_2.1]<br />\r\n    [12] =&gt; Theme [phpboost]<br />\r\n    [13] =&gt; Lang [french]<br />\r\n    [14] =&gt; Start page [/news/news.php]<br />\r\n    [15] =&gt; Rewrite [1]<br />\r\n    [16] =&gt; Gz [0]<br />\r\n    [17] =&gt; Cookie [session]<br />\r\n    [18] =&gt; Site session [3600]<br />\r\n    [19] =&gt; Site session invit [300]<br />\r\n    [20] =&gt; -------------CHMOD-------------<br />\r\n    [21] =&gt; includes/auth/ [1]<br />\r\n    [22] =&gt; cache/ [1]<br />\r\n    [23] =&gt; upload/ [1]<br />\r\n    [24] =&gt; / [1]<br />\r\n)[/code]blablablka </pre><pre>gfgdfgdf </pre>', 'Visiteur', -1, 1197413184);
INSERT INTO `phpboost_guestbook` VALUES (34, '&sbquo;<br />\r\n   &fnof;<br />\r\n   &bdquo;<br />\r\n   &hellip;<br />\r\n   &dagger;<br />\r\n   &Dagger;<br />\r\n   &circ;<br />\r\n   &permil;<br />\r\n   &Scaron;<br />\r\n   &lsaquo;<br />\r\n   &OElig;<br />\r\n   ?<br />\r\n   &#381;<br />\r\n   ?<br />\r\n   ?<br />\r\n   &lsquo;<br />\r\n   &rsquo;<br />\r\n   &ldquo;<br />\r\n   &rdquo;<br />\r\n   &bull;<br />\r\n   &ndash;<br />\r\n   &mdash;<br />\r\n   &tilde;<br />\r\n   &trade;<br />\r\n   &scaron;<br />\r\n   &rsaquo;<br />\r\n   &oelig;<br />\r\n   ?<br />\r\n   &#382;<br />\r\n   &Yuml;<br />\r\n   &nbsp;<br />\r\n   &iexcl;<br />\r\n   &cent;<br />\r\n   &pound;<br />\r\n   &curren;<br />\r\n   &yen;<br />\r\n   &brvbar;<br />\r\n   &sect;<br />\r\n   &uml;<br />\r\n   &copy;<br />\r\n   &ordf;<br />\r\n   &laquo;<br />\r\n   &not;<br />\r\n   &shy;<br />\r\n   &reg;<br />\r\n   &macr;<br />\r\n   &deg;<br />\r\n   &plusmn;<br />\r\n   &sup2;<br />\r\n   &sup3;<br />\r\n   &acute;<br />\r\n   &micro;<br />\r\n   &para;<br />\r\n   &middot;<br />\r\n   &cedil;<br />\r\n   &sup1;<br />\r\n   &ordm;<br />\r\n   &raquo;<br />\r\n   &frac14;<br />\r\n   &frac12;<br />\r\n   &frac34;<br />\r\n   &iquest;<br />\r\n   &Agrave;<br />\r\n   &Aacute;<br />\r\n   &Acirc;<br />\r\n   &Atilde;<br />\r\n   &Auml;<br />\r\n   &Aring;<br />\r\n   &AElig;<br />\r\n   &Ccedil;<br />\r\n   &Egrave;<br />\r\n   &Eacute;<br />\r\n   &Ecirc;<br />\r\n   &Euml;<br />\r\n   &Igrave;<br />\r\n   &Iacute;<br />\r\n   &Icirc;<br />\r\n   &Iuml;<br />\r\n   &ETH;<br />\r\n   &Ntilde;<br />\r\n   &Ograve;<br />\r\n   &Oacute;<br />\r\n   &Ocirc;<br />\r\n   &Otilde;<br />\r\n   &Ouml;<br />\r\n   &times;<br />\r\n   &Oslash;<br />\r\n   &Ugrave;<br />\r\n   &Uacute;<br />\r\n   &Ucirc;<br />\r\n   &Uuml;<br />\r\n   &Yacute;<br />\r\n   &THORN;<br />\r\n   &szlig;', 'crowkait', 1, 1196481770);
INSERT INTO `phpboost_guestbook` VALUES (61, '&copy; ©<br />\r\n''¤'', ''&#8218;'', ''&#402;'', ''&#8222;'', ''&#8230;'', ''&#8224;'', ''&#8225;'', ''&#710;'', ''&#8240;'',<br />\r\n			''¦'', ''&#8249;'', ''¼'', ''´'', ''&#8216;'', ''&#8217;'', ''&#8220;'', ''&#8221;'', ''&#8226;'',<br />\r\n			''&#8211;'', ''&#8212;'',  ''&#732;'', ''&#8482;'', ''¨'', ''&#8250;'', ''½'', ''¸'', ''¾''', 'crowkait', 1, 1198340404);
INSERT INTO `phpboost_guestbook` VALUES (36, '<img src="../images/smileys/clindoeil.gif" alt=";)" class="smiley" />', 'teston', 2, 1196482472);
INSERT INTO `phpboost_guestbook` VALUES (64, 'fsd', 'crowkait', 1, 1198350783);
INSERT INTO `phpboost_guestbook` VALUES (65, '[url=guestbook.php]http://127.0.0.1/PHP/PHPBoost/PHPBoost_2.1/guestbook/guestbook.php[/url]', 'Visiteur', -1, 1198350807);
INSERT INTO `phpboost_guestbook` VALUES (66, 'fsdfsd<br />\r\n[code=php,0,0]&lt;?php echo ''fuck''; ?&gt;[/code]<br />\r\nfsdfsd<br />\r\n<br />\r\n[code=php]&lt;?php echo ''fuck''; ?&gt;[/code]<br />\r\n<br />\r\n[code=php,1]<br />\r\n&lt;?php<br />\r\n/*##################################################<br />\r\n *                                sessions.class.php<br />\r\n *                            -------------------<br />\r\n *   begin                : July 04, 2005<br />\r\n *   copyright          : (C) 2005 Viarre Régis<br />\r\n *   email                : crowkait@phpboost.com<br />\r\n *<br />\r\n *   Sessions v4.0.0 <br />\r\n *<br />\r\n###################################################<br />\r\n *<br />\r\n *   This program is free software; you can redistribute it and/or modify<br />\r\n *   it under the terms of the GNU General Public License as published by<br />\r\n *   the Free Software Foundation; either version 2 of the License, or<br />\r\n *   (at your option) any later version.<br />\r\n * <br />\r\n * This program is distributed in the hope that it will be useful,<br />\r\n * but WITHOUT ANY WARRANTY; without even the implied warranty of<br />\r\n * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the<br />\r\n * GNU General Public License for more details.<br />\r\n *<br />\r\n * You should have received a copy of the GNU General Public License<br />\r\n * along with this program; if not, write to the Free Software<br />\r\n * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.<br />\r\n *<br />\r\n###################################################*/<br />\r\n<br />\r\n//Constantes de base.<br />\r\ndefine(''AUTOCONNECT'', true);<br />\r\ndefine(''NO_AUTOCONNECT'', false);<br />\r\n<br />\r\nclass Sessions<br />\r\n{<br />\r\n	var $data = array(); //Tableau contenant les informations de session.<br />\r\n	var $session_mod = 0; //Variable contenant le mode de session à utiliser pour récupérer les infos.<br />\r\n	<br />\r\n	//Constructeur<br />\r\n	function Sessions()<br />\r\n	{	<br />\r\n	}	<br />\r\n<br />\r\n	//Lancement de la session après récupèration des informations par le formulaire de connexion.<br />\r\n	function session_begin($user_id, $password, $level, $session_script, $session_script_get, $session_script_title, $autoconnect = false)<br />\r\n	{<br />\r\n		global $CONFIG, $sql;<br />\r\n		<br />\r\n		$error = '''';<br />\r\n		$cookie_on = false;<br />\r\n		$session_script = addslashes($session_script);<br />\r\n		$session_script_title = addslashes($session_script_title);<br />\r\n		<br />\r\n		########Insertion dans le compteur si l''ip est inconnue.########<br />\r\n		$check_ip = $sql-&gt;query(&quot;SELECT COUNT(*) FROM &quot;.PREFIX.&quot;compteur WHERE ip = ''&quot; . USER_IP . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n		$_include_once = empty($check_ip) &amp;&amp; ($this-&gt;check_robot(USER_IP) === false);<br />\r\n		if( $_include_once )<br />\r\n		{<br />\r\n			//Récupération forcée de la valeur du total de visites, car problème de CAST avec postgresql.<br />\r\n			$sql-&gt;query_inject(&quot;UPDATE &quot;.LOW_PRIORITY.&quot; &quot;.PREFIX.&quot;compteur SET ip = ip + 1, time = &quot; . $sql-&gt;sql_now() . &quot;, total = total + 1 WHERE id = 1&quot;, __LINE__, __FILE__);<br />\r\n			$sql-&gt;query_inject(&quot;INSERT &quot;.LOW_PRIORITY.&quot; INTO &quot;.PREFIX.&quot;compteur (ip, time, total) VALUES(''&quot; . USER_IP . &quot;'', &quot; . $sql-&gt;sql_now() . &quot;, 0)&quot;, __LINE__, __FILE__);<br />\r\n			<br />\r\n			//Mise à jour du last_connect, pour un membre qui vient d''arriver sur le site.<br />\r\n			if( $user_id !== ''-1'' ) <br />\r\n				$sql-&gt;query_inject(&quot;UPDATE &quot;.PREFIX.&quot;member SET last_connect = ''&quot; . time() . &quot;'' WHERE user_id = ''&quot; . $user_id . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n		}<br />\r\n		<br />\r\n		//On lance les stats.<br />\r\n		include_once(''../includes/save_stats.php'');<br />\r\n			<br />\r\n		########Génération d''un ID de session unique########<br />\r\n		$session_uniq_id = md5(uniqid(mt_rand(), true)); //On génère un numéro de session aléatoire.<br />\r\n			<br />\r\n		########Session existe t-elle?#########		<br />\r\n		$this-&gt;session_garbage_collector();	//On nettoie avant les sessions périmées.<br />\r\n<br />\r\n		if( $user_id !== ''-1'' )<br />\r\n		{<br />\r\n			//Suppression de la session visiteur générée avant l''enregistrement!<br />\r\n			$sql-&gt;query_inject(&quot;DELETE FROM &quot;.PREFIX.&quot;sessions WHERE session_ip = ''&quot; . USER_IP . &quot;'' AND user_id = -1&quot;, __LINE__, __FILE__);<br />\r\n			<br />\r\n			//En cas de double connexion, on supprime le cookie et la session associée de la base de données!<br />\r\n			if( isset($_COOKIE[$CONFIG[''site_cookie''] . ''_data'']) ) <br />\r\n				setcookie($CONFIG[''site_cookie''].''_data'', '''', time() - 31536000, ''/'');<br />\r\n			$sql-&gt;query_inject(&quot;DELETE FROM &quot;.PREFIX.&quot;sessions WHERE user_id = ''&quot; . $user_id . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n			<br />\r\n			//Récupération password BDD<br />\r\n			$password_m = $sql-&gt;query(&quot;SELECT password FROM &quot;.PREFIX.&quot;member WHERE user_id = ''&quot; . $user_id . &quot;'' AND user_warning &lt; 100 AND ''&quot; . time() . &quot;'' - user_ban &gt;= 0&quot;, __LINE__, __FILE__);<br />\r\n			<br />\r\n			if( !empty($password) &amp;&amp; $password === $password_m ) //Succès!<br />\r\n			{<br />\r\n				$sql-&gt;query_inject(&quot;INSERT INTO &quot;.PREFIX.&quot;sessions VALUES(''&quot; . $session_uniq_id . &quot;'', ''&quot; . $user_id . &quot;'', ''&quot; . $level . &quot;'', ''&quot; . USER_IP . &quot;'', ''&quot; . time() . &quot;'', ''&quot; . $session_script . &quot;'', ''&quot; . $session_script_get . &quot;'', ''&quot; . $session_script_title . &quot;'', '''')&quot;, __LINE__, __FILE__);				<br />\r\n				$cookie_on = true; //Génération du cookie!			<br />\r\n			}<br />\r\n			else //Session visiteur, echec!<br />\r\n			{<br />\r\n				$sql-&gt;query_inject(&quot;INSERT INTO &quot;.PREFIX.&quot;sessions VALUES(''&quot; . $session_uniq_id . &quot;'', -1, -1, ''&quot; . USER_IP . &quot;'', ''&quot; . time() . &quot;'', ''&quot; . $session_script . &quot;'', ''&quot; . $session_script_get . &quot;'', ''&quot; . $session_script_title . &quot;'', ''0'')&quot;, __LINE__, __FILE__);		<br />\r\n				$delay_ban = $sql-&gt;query(&quot;SELECT user_ban FROM &quot;.PREFIX.&quot;member WHERE user_id = ''&quot; . $user_id . &quot;''&quot;, __LINE__, __FILE__);<br />\r\n				if( (time - $delay_ban) &gt;= 0 )<br />\r\n					$error = ''echec'';<br />\r\n				else<br />\r\n					$error = $delay_ban;		<br />\r\n			}	<br />\r\n		}<br />\r\n		else //Session visiteur valide.<br />\r\n		{<br />\r\n			$sql-&gt;query_inject(&quot;INSERT INTO &quot;.PREFIX.&quot;sessions VALUES(''&quot; . $session_uniq_id . &quot;'', -1, -1, ''&quot; . USER_IP . &quot;'', ''&quot; . time() . &quot;'', ''&quot; . $session_script . &quot;'', ''&quot; . $session_script_get . &quot;'', ''&quot; . $session_script_title . &quot;'', ''0'')&quot;, __LINE__, __FILE__);<br />\r\n		}<br />\r\n		<br />\r\n		########Génération du cookie de session########<br />\r\n		if( $cookie_on === true )<br />\r\n		{<br />\r\n			$data = array();<br />\r\n			$data[''user_id''] = isset($user_id) ? numeric($user_id) : -1;<br />\r\n			$data[''session_id''] = $session_uniq_id;<br />\r\n			<br />\r\n			setcookie($CONFIG[''site_cookie''].''_data'', serialize($data), time() + 31536000, ''/'');<br />\r\n			<br />\r\n			########Génération du cookie d''autoconnection########<br />\r\n			if( $autoconnect === true )<br />\r\n			{<br />\r\n				$session_autoconnect[''user_id''] = $user_id;				<br />\r\n				$session_autoconnect[''pwd''] = $password;<br />\r\n				<br />\r\n				setcookie($CONFIG[''site_cookie''].''_autoconnect'', serialize($session_autoconnect), time() + 31536000, ''/'');<br />\r\n			}<br />\r\n		}<br />\r\n		<br />\r\n		return $error;<br />\r\n	}	<br />\r\n?&gt;<br />\r\n[/code]<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\nkhjkhjkhj jkhkhj<br />\r\n<br />\r\n<br />\r\nkjhjkhj<br />\r\nhgfh', 'crowkait', 1, 1198465071);
INSERT INTO `phpboost_guestbook` VALUES (67, '<a href="https://www.gfgf.fd">https://www.gfgf.fd</a> <a href="https://www.gfgf.fd">https://www.gfgf.fd</a><br />\r\n<a href="https://www.gfgf.fd">https://www.gfgf.fd</a> bf gdf<br />\r\n<a href="https://www.gfgf.fdfdf">https://www.gfgf.fdfdf</a> dfhttps://www.gfgf.fd fdsfds', 'crowkait', 1, 1201953159);
INSERT INTO `phpboost_guestbook` VALUES (68, '<a href="http://www.gfgf.fd">http://www.gfgf.fd</a> <a href="http://www.gfgf.fd">http://www.gfgf.fd</a> <a href="http://www.gfgf.fd">http://www.gfgf.fd</a>', 'crowkait', 1, 1201953182);
INSERT INTO `phpboost_guestbook` VALUES (69, 'gdfgfd<br />\r\n<a href="http://www.gfgf.fd">http://www.gfgf.fd</a>', 'crowkait', 1, 1201953538);
INSERT INTO `phpboost_guestbook` VALUES (70, 'f', 'crowkait', 1, 1203301296);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_lang`
-- 

CREATE TABLE `phpboost_lang` (
  `id` int(11) NOT NULL auto_increment,
  `lang` varchar(150) NOT NULL default '',
  `activ` tinyint(1) NOT NULL default '0',
  `secure` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `phpboost_lang`
-- 

INSERT INTO `phpboost_lang` VALUES (1, 'french', 1, -1);
INSERT INTO `phpboost_lang` VALUES (2, 'english', 1, -1);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_links`
-- 

CREATE TABLE `phpboost_links` (
  `id` int(11) NOT NULL auto_increment,
  `class` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `activ` tinyint(1) NOT NULL default '0',
  `secure` char(2) NOT NULL default '',
  `added` tinyint(1) NOT NULL default '0',
  `sep` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- 
-- Contenu de la table `phpboost_links`
-- 

INSERT INTO `phpboost_links` VALUES (26, 9, 'FAQ', '../faq/faq.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (2, 26, 'Membres', '../member/member.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (3, 1, 'PHPBoost', '', 1, '-1', 0, 1);
INSERT INTO `phpboost_links` VALUES (4, 19, 'Articles', '../articles/articles.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (5, 18, 'Calendrier', '../calendar/calendar.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (6, 10, 'Contact', '../contact/contact.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (7, 14, 'Téléchargements', '../download/download.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (8, 7, 'Forum', '../forum/index.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (9, 21, 'Galerie', '../gallery/gallery.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (10, 20, 'Livre d''or', '../guestbook/guestbook.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (11, 2, 'Actualités', '../news/news.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (12, 23, 'Sondages', '../poll/poll.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (13, 24, 'Liens web', '../web/web.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (14, 8, 'Documentation', '../wiki/wiki.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (15, 17, 'Liste des portails', '../phpboost/list.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (16, 15, 'Modules', '../phpboost/modules.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (20, 16, 'Site', '', 1, '-1', 0, 1);
INSERT INTO `phpboost_links` VALUES (18, 6, 'Support', '', 1, '-1', 0, 1);
INSERT INTO `phpboost_links` VALUES (19, 11, 'Téléchargements', '', 1, '-1', 0, 1);
INSERT INTO `phpboost_links` VALUES (21, 13, 'Thèmes', 'http://themes.phpboost.com', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (22, 12, 'PHPBoost 2.0', 'http://www.phpboost.com/download/download-2-52+phpboost-2-0.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (23, 3, 'Participer au projet', '../pages/phpboost-recrute', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (24, 4, 'Aider le projet', '../pages/aider-phpboost', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` VALUES (25, 5, 'Démonstration', 'http://demo.phpboost.com', 1, '-1', 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_member`
-- 

CREATE TABLE `phpboost_member` (
  `user_id` int(11) NOT NULL auto_increment,
  `login` varchar(255) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `level` tinyint(1) NOT NULL default '0',
  `user_groups` text NOT NULL,
  `user_lang` varchar(25) NOT NULL default '',
  `user_theme` varchar(50) NOT NULL default '',
  `user_mail` varchar(50) NOT NULL default '',
  `user_show_mail` tinyint(1) NOT NULL default '1',
  `user_editor` varchar(15) NOT NULL default '',
  `user_timezone` tinyint(2) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `user_avatar` varchar(255) NOT NULL default '',
  `user_msg` mediumint(9) NOT NULL default '0',
  `user_local` varchar(50) NOT NULL default '',
  `user_msn` varchar(50) NOT NULL default '',
  `user_yahoo` varchar(50) NOT NULL default '',
  `user_web` varchar(70) NOT NULL default '',
  `user_occupation` varchar(50) NOT NULL default '',
  `user_hobbies` varchar(50) NOT NULL default '',
  `user_desc` text NOT NULL,
  `user_sex` tinyint(1) NOT NULL default '0',
  `user_born` date NOT NULL default '0000-00-00',
  `user_sign` text NOT NULL,
  `user_pm` smallint(6) unsigned NOT NULL default '0',
  `user_warning` smallint(6) NOT NULL default '0',
  `user_readonly` int(11) NOT NULL default '0',
  `last_connect` int(11) NOT NULL default '0',
  `test_connect` tinyint(4) NOT NULL default '0',
  `activ_pass` varchar(30) NOT NULL default '0',
  `new_pass` varchar(50) NOT NULL default '',
  `user_ban` int(11) NOT NULL default '0',
  `user_aprob` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`user_id`),
  KEY `user_id` (`login`,`password`,`level`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `phpboost_member`
-- 

INSERT INTO `phpboost_member` VALUES (1, 'crowkait', '4e65740f918bfcc8fa545c2bb50846cd', 2, '1|3|', 'french', 'phpboost', 'crowkait@hyf.hdf', 1, 'bbcode', 0, 1194096703, '../images/avatars/boostor_mi_g_cro.jpg', 90, '', '', '', '', '', '', '', 0, '0000-00-00', '', 0, 0, 0, 1208558672, 0, '0', '', 0, 1);
INSERT INTO `phpboost_member` VALUES (2, 'teston', '3d334c201172a06a1126f04b84299ad8', 0, '3|', 'french', 'phpboost', 'teston@gd.dg', 0, 'bbcode', 0, 1195258091, '', 32, '', '', '', '', '', '', '', 0, '0000-00-00', '', 2, 10, 0, 1208570852, 0, '0ffeaf245848b22', '', 0, 1);
INSERT INTO `phpboost_member` VALUES (4, 'robairt', '5bc48559987b9c5e4cef7948cff6ef73', 1, '0', 'french', '', 'robairt@robairt.fd', 1, '', 0, 1206129577, '', 0, '', '', '', '', '', '', '', 0, '0000-00-00', '', 0, 40, 0, 1206129641, 0, '', '', 0, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_member_extend`
-- 

CREATE TABLE `phpboost_member_extend` (
  `user_id` int(11) NOT NULL auto_increment,
  `last_view_forum` int(11) NOT NULL default '0',
  `f_test` text NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `phpboost_member_extend`
-- 

INSERT INTO `phpboost_member_extend` VALUES (1, 1197422978, 'Non');
INSERT INTO `phpboost_member_extend` VALUES (3, 0, 'Non');
INSERT INTO `phpboost_member_extend` VALUES (2, 0, 'Non');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_member_extend_cat`
-- 

CREATE TABLE `phpboost_member_extend_cat` (
  `id` int(11) NOT NULL auto_increment,
  `class` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `field_name` varchar(255) NOT NULL default '',
  `contents` text NOT NULL,
  `field` tinyint(1) NOT NULL default '0',
  `possible_values` text NOT NULL,
  `default_values` text NOT NULL,
  `require` tinyint(1) NOT NULL default '0',
  `display` tinyint(1) NOT NULL default '0',
  `regex` varchar(255) NOT NULL default '',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `phpboost_member_extend_cat`
-- 

INSERT INTO `phpboost_member_extend_cat` VALUES (1, 1, 'last_view_forum', 'last_view_forum', '', 0, '', '', 0, 0, '');
INSERT INTO `phpboost_member_extend_cat` VALUES (2, 0, 'test', 'f_test', '', 3, 'Oui|Non', 'Non', 0, 1, '0');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_modules`
-- 

CREATE TABLE `phpboost_modules` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `version` varchar(15) NOT NULL default '',
  `auth` text NOT NULL,
  `activ` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

-- 
-- Contenu de la table `phpboost_modules`
-- 

INSERT INTO `phpboost_modules` VALUES (1, 'articles', '2.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (26, 'calendar', '1.2', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (19, 'contact', '1.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (27, 'download', '1.4', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (5, 'forum', '2.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (6, 'gallery', '2.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (7, 'guestbook', '2.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (8, 'links', '1.5', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (9, 'news', '2.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (10, 'newsletter', '2.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (11, 'online', '2.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (12, 'pages', '2.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (13, 'poll', '2.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (14, 'shoutbox', '2.1', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (15, 'stats', '2.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (16, 'web', '1.4', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (17, 'wiki', '2.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (20, 'connect', '1.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (28, 'faq', '1.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);
INSERT INTO `phpboost_modules` VALUES (25, 'search', '1.0', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_modules_mini`
-- 

CREATE TABLE `phpboost_modules_mini` (
  `id` int(11) NOT NULL auto_increment,
  `class` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL default '',
  `contents` text NOT NULL,
  `location` varchar(20) NOT NULL default '0',
  `auth` text NOT NULL,
  `activ` tinyint(1) NOT NULL default '0',
  `added` tinyint(1) NOT NULL default '0',
  `use_tpl` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- 
-- Contenu de la table `phpboost_modules_mini`
-- 

INSERT INTO `phpboost_modules_mini` VALUES (1, 0, 'connect', 'connect_mini.php', 'subheader', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1, 0, 1);
INSERT INTO `phpboost_modules_mini` VALUES (2, 0, 'gallery', 'gallery_mini.php', 'topcentral', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 0, 0, 1);
INSERT INTO `phpboost_modules_mini` VALUES (3, 1, 'links', 'links_mini.php', 'left', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1, 0, 1);
INSERT INTO `phpboost_modules_mini` VALUES (4, 1, 'newsletter', 'newsletter_mini.php', 'bottomcentral', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1, 0, 1);
INSERT INTO `phpboost_modules_mini` VALUES (5, 2, 'online', 'online_mini.php', 'left', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1, 0, 0);
INSERT INTO `phpboost_modules_mini` VALUES (6, 0, 'poll', 'poll_mini.php', 'right', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 0, 0, 1);
INSERT INTO `phpboost_modules_mini` VALUES (7, 0, 'shoutbox', 'shoutbox_mini.php', 'topfooter', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 0, 0, 1);
INSERT INTO `phpboost_modules_mini` VALUES (15, 1, 'search', 'search_mini.php', 'header', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 1, 0, 0);
INSERT INTO `phpboost_modules_mini` VALUES (19, 4, 'stats', 'stats_mini.php', 'left', 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}', 0, 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_news`
-- 

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
  KEY `idcat` (`idcat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- 
-- Contenu de la table `phpboost_news`
-- 

INSERT INTO `phpboost_news` VALUES (1, 1, 'Test', 'News de test', 'Suite de la news de test', 0, 1194096841, 1, 0, 0, 1, '', '', 3, 0);
INSERT INTO `phpboost_news` VALUES (4, 1, 'reter', 'tertr', '', 0, 1198697761, 1, 0, 0, 1, '', '', 1, 0);
INSERT INTO `phpboost_news` VALUES (5, 1, 'test3', 'fsdf', 'fdsf', 1, 1204246321, 1, 0, 0, 1, '', '', 0, 0);
INSERT INTO `phpboost_news` VALUES (6, 1, 'gdf', 'gdfgg', 'g', 0, 1204247701, 1, 0, 0, 1, '', '', 9, 0);
INSERT INTO `phpboost_news` VALUES (21, 1, 'fds!', ' fsd ', '  ', 0, 1208873461, 0, 0, 0, 1, '', '', 0, 0);
INSERT INTO `phpboost_news` VALUES (19, 1, 'fds!', ' fsd ', '', 0, 1207699329, 0, 0, 0, 1, '', '', 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_news_cat`
-- 

CREATE TABLE `phpboost_news_cat` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `contents` text NOT NULL,
  `icon` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `phpboost_news_cat`
-- 

INSERT INTO `phpboost_news_cat` VALUES (1, 'Test', 'Cat&eacute;gorie de test', 'news.png');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_newsletter`
-- 

CREATE TABLE `phpboost_newsletter` (
  `id` int(11) NOT NULL auto_increment,
  `mail` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `phpboost_newsletter`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_newsletter_arch`
-- 

CREATE TABLE `phpboost_newsletter_arch` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) NOT NULL default '',
  `message` text NOT NULL,
  `timestamp` bigint(20) NOT NULL default '0',
  `type` varchar(10) NOT NULL default '',
  `nbr` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `phpboost_newsletter_arch`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_pages`
-- 

CREATE TABLE `phpboost_pages` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `encoded_title` varchar(255) NOT NULL default '',
  `contents` text NOT NULL,
  `auth` text NOT NULL,
  `is_cat` tinyint(1) NOT NULL default '0',
  `id_cat` int(11) NOT NULL default '0',
  `hits` int(11) NOT NULL default '0',
  `count_hits` tinyint(1) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `activ_com` tinyint(1) NOT NULL default '0',
  `nbr_com` int(11) NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  `redirect` int(11) NOT NULL default '0',
  PRIMARY KEY  (`encoded_title`),
  KEY `id` (`id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`),
  FULLTEXT KEY `all` (`title`,`contents`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `phpboost_pages`
-- 

INSERT INTO `phpboost_pages` VALUES (1, 'test', 'test', 'fds dsfsdfsd sdf ds', '', 0, 0, 6, 1, 1, 1196015404, 1, 0, 0, 0);
INSERT INTO `phpboost_pages` VALUES (2, 'fdsf', 'fdsf', 'fdsf', '', 0, 0, 14, 1, 1, 1197141534, 1, 1, 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_pages_cats`
-- 

CREATE TABLE `phpboost_pages_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_page` int(11) NOT NULL default '0',
  `id_parent` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `phpboost_pages_cats`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_pm_msg`
-- 

CREATE TABLE `phpboost_pm_msg` (
  `id` int(11) NOT NULL auto_increment,
  `idconvers` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `contents` text NOT NULL,
  `timestamp` int(11) NOT NULL default '0',
  `view_status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idconvers` (`idconvers`,`user_id`,`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- 
-- Contenu de la table `phpboost_pm_msg`
-- 

INSERT INTO `phpboost_pm_msg` VALUES (8, 4, 1, 'tessts s', 1198812948, 0);
INSERT INTO `phpboost_pm_msg` VALUES (9, 5, 2, 'fsd', 1198813095, 1);
INSERT INTO `phpboost_pm_msg` VALUES (10, 5, 2, 'fsdfsd', 1198813107, 0);
INSERT INTO `phpboost_pm_msg` VALUES (11, 5, 1, 'fsdfsd', 1198813113, 1);
INSERT INTO `phpboost_pm_msg` VALUES (12, 5, 2, 'test', 1198813142, 0);
INSERT INTO `phpboost_pm_msg` VALUES (13, 5, 2, 'test', 1198813145, 0);
INSERT INTO `phpboost_pm_msg` VALUES (14, 5, 1, 'fdsfds', 1198813151, 1);
INSERT INTO `phpboost_pm_msg` VALUES (18, 8, -1, 'Vous avez été mis en lecture seule par un membre de l''équipe de modération, vous ne pourrez plus poster pendant 1 minute.<br />\r\n<br />\r\n<br />\r\nCeci est un message semi-automatique.', 1203714408, 0);
INSERT INTO `phpboost_pm_msg` VALUES (19, 9, -1, 'Vous avez été mis en lecture seule par un membre de l''équipe de modération, vous ne pourrez plus poster pendant 1 minute.<br />\r\n<br />\r\n<br />\r\nCeci est un message semi-automatique.', 1204363882, 0);
INSERT INTO `phpboost_pm_msg` VALUES (7, 4, 2, 'test', 1198812942, 0);
INSERT INTO `phpboost_pm_msg` VALUES (6, 4, 2, 'test', 1198812921, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_pm_topic`
-- 

CREATE TABLE `phpboost_pm_topic` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(150) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `user_id_dest` int(11) NOT NULL default '0',
  `user_convers_status` tinyint(1) NOT NULL default '0',
  `user_view_pm` int(11) NOT NULL default '0',
  `nbr_msg` int(11) unsigned NOT NULL default '0',
  `last_user_id` int(11) NOT NULL default '0',
  `last_msg_id` int(11) NOT NULL default '0',
  `last_timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`,`user_id_dest`,`user_convers_status`,`last_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- 
-- Contenu de la table `phpboost_pm_topic`
-- 

INSERT INTO `phpboost_pm_topic` VALUES (5, 'fds', 2, 1, 0, 0, 6, 1, 14, 1198813151);
INSERT INTO `phpboost_pm_topic` VALUES (8, 'Sanction', -1, 2, 1, 1, 1, -1, 18, 1203714408);
INSERT INTO `phpboost_pm_topic` VALUES (9, 'Sanction', -1, 2, 1, 1, 1, -1, 19, 1204363882);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_poll`
-- 

CREATE TABLE `phpboost_poll` (
  `id` int(11) NOT NULL auto_increment,
  `question` varchar(255) NOT NULL default '',
  `answers` text NOT NULL,
  `votes` text NOT NULL,
  `type` tinyint(1) NOT NULL default '0',
  `archive` tinyint(1) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '0',
  `start` int(11) NOT NULL default '0',
  `end` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `phpboost_poll`
-- 

INSERT INTO `phpboost_poll` VALUES (1, 'test', '1|2', '3|3', 1, 0, 1197141245, 1, 0, 0, 1);
INSERT INTO `phpboost_poll` VALUES (4, 'test', 'gfgdf|gdfgdf|fsdfds', '12|1|24', 1, 1, 1207672531, 1, 0, 0, 1);
INSERT INTO `phpboost_poll` VALUES (3, 'test''te', 'test''te|gfhgfhgf', '1|1', 1, 0, 1197691141, 1, 0, 0, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_poll_ip`
-- 

CREATE TABLE `phpboost_poll_ip` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(50) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `idpoll` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- 
-- Contenu de la table `phpboost_poll_ip`
-- 

INSERT INTO `phpboost_poll_ip` VALUES (8, '127.0.0.1', 1, 3, 1207667095);
INSERT INTO `phpboost_poll_ip` VALUES (9, '127.0.0.1', 1, 1, 1207667117);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_ranks`
-- 

CREATE TABLE `phpboost_ranks` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `msg` int(11) NOT NULL default '0',
  `icon` varchar(255) NOT NULL default '',
  `special` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `phpboost_ranks`
-- 

INSERT INTO `phpboost_ranks` VALUES (1, 'Administrateur', -2, 'rank_admin.gif', 1);
INSERT INTO `phpboost_ranks` VALUES (2, 'Mod&eacute;rateur', -1, 'rank_modo.gif', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_search_index`
-- 

CREATE TABLE `phpboost_search_index` (
  `id_search` int(11) NOT NULL auto_increment,
  `id_user` int(11) NOT NULL default '0',
  `module` varchar(128) NOT NULL default '0',
  `search` varchar(255) NOT NULL default '',
  `options` varchar(255) NOT NULL default '',
  `last_search_use` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `times_used` int(3) NOT NULL default '0',
  PRIMARY KEY  (`id_search`),
  UNIQUE KEY `id_user` (`id_user`,`module`,`search`,`options`),
  KEY `last_search_use` (`last_search_use`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `phpboost_search_index`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_search_results`
-- 

CREATE TABLE `phpboost_search_results` (
  `id_search` int(11) NOT NULL auto_increment,
  `id_content` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `relevance` decimal(5,2) NOT NULL default '0.00',
  `link` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_search`,`id_content`),
  KEY `relevance` (`relevance`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `phpboost_search_results`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_sessions`
-- 

CREATE TABLE `phpboost_sessions` (
  `session_id` varchar(50) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `level` tinyint(1) NOT NULL default '0',
  `session_ip` varchar(50) NOT NULL default '',
  `session_time` int(11) NOT NULL default '0',
  `session_script` varchar(100) NOT NULL default '0',
  `session_script_get` varchar(100) NOT NULL default '0',
  `session_script_title` varchar(255) NOT NULL default '',
  `session_flag` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`session_id`),
  KEY `user_id` (`user_id`,`session_time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `phpboost_sessions`
-- 

INSERT INTO `phpboost_sessions` VALUES ('ffeb918e25aba1d9321efea64036d8c1', 2, 0, '127.0.0.1', 1208571645, '/member/404.php', '', 'Erreur 404', 0);
INSERT INTO `phpboost_sessions` VALUES ('b51c384254e45adb66eba8fb03e6a563', 1, 2, '127.0.0.1', 1208608687, '/member/404.php', '', 'Erreur 404', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_shoutbox`
-- 

CREATE TABLE `phpboost_shoutbox` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(150) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `level` tinyint(1) NOT NULL default '0',
  `contents` text NOT NULL,
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

-- 
-- Contenu de la table `phpboost_shoutbox`
-- 

INSERT INTO `phpboost_shoutbox` VALUES (32, 'crowkait', 1, 0, 'test', 1198380593);
INSERT INTO `phpboost_shoutbox` VALUES (33, 'crowkait', 1, 0, 'test', 1198688867);
INSERT INTO `phpboost_shoutbox` VALUES (34, 'crowkait', 1, 0, 'nh', 1198691437);
INSERT INTO `phpboost_shoutbox` VALUES (35, 'crowkait', 1, 0, 'htyt', 1198697105);
INSERT INTO `phpboost_shoutbox` VALUES (36, 'crowkait', 1, 0, '!!!', 1198697268);
INSERT INTO `phpboost_shoutbox` VALUES (37, 'crowkait', 1, 0, 'tr!', 1198697787);
INSERT INTO `phpboost_shoutbox` VALUES (39, 'crowkait', 1, 0, 'gfgdf', 1203634135);
INSERT INTO `phpboost_shoutbox` VALUES (40, 'crowkait', 1, 0, 'gfgdf', 1203634184);
INSERT INTO `phpboost_shoutbox` VALUES (41, 'crowkait', 1, 0, 'gfgdf', 1203634240);
INSERT INTO `phpboost_shoutbox` VALUES (28, 'crowkait', 1, 0, 'f<br />\nf', 1198380364);
INSERT INTO `phpboost_shoutbox` VALUES (42, 'crowkait', 1, 2, 'fsdfsd', 1205104463);
INSERT INTO `phpboost_shoutbox` VALUES (43, 'crowkait', 1, 2, 'fsdf fsdfsd fsd', 1205104474);
INSERT INTO `phpboost_shoutbox` VALUES (44, 'Visiteur', -1, -1, 'hgfhgf', 1205518124);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_smileys`
-- 

CREATE TABLE `phpboost_smileys` (
  `idsmiley` int(11) NOT NULL auto_increment,
  `code_smiley` varchar(50) NOT NULL default '',
  `url_smiley` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`idsmiley`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

-- 
-- Contenu de la table `phpboost_smileys`
-- 

INSERT INTO `phpboost_smileys` VALUES (9, ':|', 'waw.gif');
INSERT INTO `phpboost_smileys` VALUES (5, ':siffle', 'siffle.gif');
INSERT INTO `phpboost_smileys` VALUES (18, ':)', 'sourire.gif');
INSERT INTO `phpboost_smileys` VALUES (43, ':lol', 'rire.gif');
INSERT INTO `phpboost_smileys` VALUES (10, ':p', 'tirelangue.gif');
INSERT INTO `phpboost_smileys` VALUES (11, ':(', 'malheureux.gif');
INSERT INTO `phpboost_smileys` VALUES (44, ';)', 'clindoeil.gif');
INSERT INTO `phpboost_smileys` VALUES (22, ':heink', 'heink.gif');
INSERT INTO `phpboost_smileys` VALUES (14, ':D', 'heureux.gif');
INSERT INTO `phpboost_smileys` VALUES (15, ':d', 'content.gif');
INSERT INTO `phpboost_smileys` VALUES (16, ':s', 'incertain.gif');
INSERT INTO `phpboost_smileys` VALUES (19, ':gne', 'pinch.gif');
INSERT INTO `phpboost_smileys` VALUES (21, ':top', 'top.gif');
INSERT INTO `phpboost_smileys` VALUES (24, ':clap', 'clap.gif');
INSERT INTO `phpboost_smileys` VALUES (26, ':hehe', 'hehe.gif');
INSERT INTO `phpboost_smileys` VALUES (27, ':@', 'angry.gif');
INSERT INTO `phpboost_smileys` VALUES (29, ':''(', 'snif.gif');
INSERT INTO `phpboost_smileys` VALUES (30, ':nex', 'nex.gif');
INSERT INTO `phpboost_smileys` VALUES (34, '8-)', 'star.gif');
INSERT INTO `phpboost_smileys` VALUES (32, '|-)', 'nuit.gif');
INSERT INTO `phpboost_smileys` VALUES (35, ':berk', 'berk.gif');
INSERT INTO `phpboost_smileys` VALUES (36, ':gre', 'colere.gif');
INSERT INTO `phpboost_smileys` VALUES (37, ':love', 'love.gif');
INSERT INTO `phpboost_smileys` VALUES (38, ':hum', 'doute.gif');
INSERT INTO `phpboost_smileys` VALUES (39, ':mat', 'mat.gif');
INSERT INTO `phpboost_smileys` VALUES (40, ':miam', 'miam.gif');
INSERT INTO `phpboost_smileys` VALUES (41, ':+1', 'plus1.gif');
INSERT INTO `phpboost_smileys` VALUES (42, ':lu', 'lu.gif');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_stats`
-- 

CREATE TABLE `phpboost_stats` (
  `id` int(11) NOT NULL auto_increment,
  `stats_year` smallint(6) NOT NULL default '0',
  `stats_month` tinyint(4) NOT NULL default '0',
  `stats_day` tinyint(4) NOT NULL default '0',
  `nbr` mediumint(9) NOT NULL default '0',
  `pages` int(11) NOT NULL default '0',
  `pages_detail` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `stats_day` (`stats_day`,`stats_month`,`stats_year`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=78 ;

-- 
-- Contenu de la table `phpboost_stats`
-- 

INSERT INTO `phpboost_stats` VALUES (1, 2007, 11, 3, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (2, 2007, 11, 4, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (3, 2007, 11, 9, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (4, 2007, 11, 10, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (5, 2007, 11, 17, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (6, 2007, 11, 18, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (7, 2007, 11, 19, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (8, 2007, 11, 20, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (9, 2007, 11, 23, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (10, 2007, 11, 24, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (11, 2007, 11, 25, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (12, 2007, 12, 1, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (13, 2007, 12, 2, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (14, 2007, 12, 9, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (15, 2007, 12, 10, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (16, 2007, 12, 11, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (17, 2007, 12, 12, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (18, 2007, 12, 15, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (19, 2007, 12, 16, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (20, 2007, 12, 22, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (21, 2007, 12, 23, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (22, 2007, 12, 24, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (23, 2007, 12, 25, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (24, 2007, 12, 26, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (25, 2007, 12, 27, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (26, 2007, 12, 28, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (27, 2007, 12, 29, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (28, 2007, 12, 30, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (29, 2007, 12, 31, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (30, 2008, 1, 1, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (31, 2008, 1, 2, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (32, 2008, 1, 3, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (33, 2008, 1, 12, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (34, 2008, 1, 14, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (35, 2008, 1, 26, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (36, 2008, 1, 27, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (37, 2008, 1, 31, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (38, 2008, 2, 1, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (39, 2008, 2, 2, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (40, 2008, 2, 7, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (41, 2008, 2, 8, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (42, 2008, 2, 15, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (43, 2008, 2, 16, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (44, 2008, 2, 17, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (45, 2008, 2, 18, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (46, 2008, 2, 19, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (47, 2008, 2, 20, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (48, 2008, 2, 21, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (49, 2008, 2, 22, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (50, 2008, 2, 23, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (51, 2008, 2, 24, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (52, 2008, 2, 28, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (53, 2008, 2, 29, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (54, 2008, 3, 1, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (55, 2008, 3, 6, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (56, 2008, 3, 7, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (57, 2008, 3, 8, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (58, 2008, 3, 9, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (59, 2008, 3, 10, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (60, 2008, 3, 13, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (61, 2008, 3, 14, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (62, 2008, 3, 15, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (63, 2008, 3, 16, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (64, 2008, 3, 21, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (65, 2008, 3, 25, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (66, 2008, 3, 26, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (67, 2008, 3, 27, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (68, 2008, 3, 28, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (69, 2008, 3, 29, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (70, 2008, 4, 5, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (71, 2008, 4, 7, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (72, 2008, 4, 8, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (73, 2008, 4, 9, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (74, 2008, 4, 10, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (75, 2008, 4, 11, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (76, 2008, 4, 17, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');
INSERT INTO `phpboost_stats` VALUES (77, 2008, 4, 18, 1, 1960, 'a:9:{i:23;i:232;i:0;i:120;i:1;i:593;i:2;i:582;i:3;i:360;i:4;i:33;i:13;i:8;i:14;i:17;i:20;i:15;}');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_stats_referer`
-- 

CREATE TABLE `phpboost_stats_referer` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `relative_url` varchar(255) NOT NULL default '',
  `total_visit` int(11) NOT NULL default '0',
  `today_visit` int(11) NOT NULL default '0',
  `yesterday_visit` int(11) NOT NULL default '0',
  `nbr_day` int(11) NOT NULL default '0',
  `last_update` int(11) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `url` (`url`,`relative_url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `phpboost_stats_referer`
-- 

INSERT INTO `phpboost_stats_referer` VALUES (1, 'http://www.phpboost.com', '/news/news.php', 204, 0, 0, 78, 2147483647, 0);
INSERT INTO `phpboost_stats_referer` VALUES (2, 'http://www.phpboost.com', '/forum/forum.php', 104, 0, 0, 78, 2147483647, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_themes`
-- 

CREATE TABLE `phpboost_themes` (
  `id` int(11) NOT NULL auto_increment,
  `theme` varchar(50) NOT NULL default '',
  `activ` tinyint(1) NOT NULL default '0',
  `secure` tinyint(1) NOT NULL default '0',
  `left_column` tinyint(1) NOT NULL default '0',
  `right_column` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `phpboost_themes`
-- 

INSERT INTO `phpboost_themes` VALUES (2, 'phpboost', 1, -1, 1, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_upload`
-- 

CREATE TABLE `phpboost_upload` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `size` float NOT NULL default '0',
  `type` varchar(10) NOT NULL default '',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- 
-- Contenu de la table `phpboost_upload`
-- 

INSERT INTO `phpboost_upload` VALUES (1, 0, 'bouton_dl.jpg', 'eb79d843fc66fb5a992d2f8be4b2717d.jpg', 1, 26.3, 'jpg', 1199056841);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_upload_cat`
-- 

CREATE TABLE `phpboost_upload_cat` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- 
-- Contenu de la table `phpboost_upload_cat`
-- 

INSERT INTO `phpboost_upload_cat` VALUES (1, 0, 1, 'test&amp;fsd&amp;fgd&amp;+gdf');
INSERT INTO `phpboost_upload_cat` VALUES (2, 0, 1, 'dsq&amp;dqs');
INSERT INTO `phpboost_upload_cat` VALUES (3, 0, 1, 'fsd''gdf');
INSERT INTO `phpboost_upload_cat` VALUES (5, 3, 1, 'vxcv');
INSERT INTO `phpboost_upload_cat` VALUES (6, 0, -1, 'test');
INSERT INTO `phpboost_upload_cat` VALUES (7, 6, -1, 'test2');
INSERT INTO `phpboost_upload_cat` VALUES (8, 5, 1, 'test');
INSERT INTO `phpboost_upload_cat` VALUES (9, 5, 1, 'gfgd');

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_verif_code`
-- 

CREATE TABLE `phpboost_verif_code` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` varchar(8) NOT NULL default '',
  `code` varchar(20) NOT NULL default '',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- 
-- Contenu de la table `phpboost_verif_code`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_web`
-- 

CREATE TABLE `phpboost_web` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `contents` text NOT NULL,
  `url` text NOT NULL,
  `compt` int(11) NOT NULL default '0',
  `aprob` tinyint(1) NOT NULL default '1',
  `timestamp` int(11) NOT NULL default '0',
  `users_note` text NOT NULL,
  `nbrnote` mediumint(9) NOT NULL default '0',
  `note` float NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `phpboost_web`
-- 

INSERT INTO `phpboost_web` VALUES (1, 1, 'fsdfsd', 'gfdgdf', 'http://www.phpboost.com/forum/topic-1686+nombre-de-visites-reste-a-1.php', 5, 1, 1196552549, '1', 3, 3.66667, 0, 0);
INSERT INTO `phpboost_web` VALUES (2, 1, 'hgfh', 'hgfhgf', 'hgfh', 0, 1, 1204241157, '1', 2, 2.75, 1, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_web_cat`
-- 

CREATE TABLE `phpboost_web_cat` (
  `id` int(11) NOT NULL auto_increment,
  `class` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL default '',
  `contents` text NOT NULL,
  `icon` varchar(255) NOT NULL default '',
  `aprob` tinyint(1) NOT NULL default '0',
  `secure` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `class` (`class`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `phpboost_web_cat`
-- 

INSERT INTO `phpboost_web_cat` VALUES (1, 1, 'test', '', 'web.png', 1, -1);
INSERT INTO `phpboost_web_cat` VALUES (2, 2, 'ghgf', 'hgfhgf', 'web.png', 1, -1);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_wiki_articles`
-- 

CREATE TABLE `phpboost_wiki_articles` (
  `id` int(11) NOT NULL auto_increment,
  `id_contents` int(11) NOT NULL default '0',
  `title` varchar(250) NOT NULL default '',
  `encoded_title` varchar(250) NOT NULL default '',
  `hits` int(11) NOT NULL default '0',
  `id_cat` int(11) NOT NULL default '0',
  `is_cat` tinyint(1) NOT NULL default '0',
  `defined_status` smallint(6) NOT NULL default '0',
  `undefined_status` text NOT NULL,
  `redirect` int(11) NOT NULL default '0',
  `auth` text NOT NULL,
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`encoded_title`),
  KEY `id` (`id`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `phpboost_wiki_articles`
-- 

INSERT INTO `phpboost_wiki_articles` VALUES (1, 1, 'tesdt', 'tesdt', 1, 0, 0, 0, '', 0, '', 0, 0);
INSERT INTO `phpboost_wiki_articles` VALUES (2, 2, 'gdfgfd', 'gdfgfd', 2, 1, 1, 0, '', 0, '', 0, 0);
INSERT INTO `phpboost_wiki_articles` VALUES (3, 3, 'gdfgf', 'gdfgf', 3, 2, 1, 0, '', 0, '', 0, 0);
INSERT INTO `phpboost_wiki_articles` VALUES (4, 4, 'gfdgdf', 'gfdgdf', 4, 2, 0, 0, '', 0, '', 0, 0);
INSERT INTO `phpboost_wiki_articles` VALUES (5, 6, 'taratata', 'taratata', 27, 2, 0, 0, '', 0, '', 2, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_wiki_cats`
-- 

CREATE TABLE `phpboost_wiki_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `article_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `phpboost_wiki_cats`
-- 

INSERT INTO `phpboost_wiki_cats` VALUES (1, 0, 2);
INSERT INTO `phpboost_wiki_cats` VALUES (2, 1, 3);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_wiki_contents`
-- 

CREATE TABLE `phpboost_wiki_contents` (
  `id_contents` int(11) NOT NULL auto_increment,
  `id_article` int(11) NOT NULL default '0',
  `menu` text NOT NULL,
  `content` text NOT NULL,
  `activ` tinyint(1) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `user_ip` varchar(50) NOT NULL default '',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_contents`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- 
-- Contenu de la table `phpboost_wiki_contents`
-- 

INSERT INTO `phpboost_wiki_contents` VALUES (1, 1, '', 'fdgdf', 1, 1, '127.0.0.1', 1203179831);
INSERT INTO `phpboost_wiki_contents` VALUES (2, 2, '', 'gdfg', 1, 1, '127.0.0.1', 1203179843);
INSERT INTO `phpboost_wiki_contents` VALUES (3, 3, '', 'gdfgdf', 1, 1, '127.0.0.1', 1203179850);
INSERT INTO `phpboost_wiki_contents` VALUES (4, 4, '', 'gdfgdf', 1, 1, '127.0.0.1', 1203179858);
INSERT INTO `phpboost_wiki_contents` VALUES (5, 5, '', 'fdsfsd', 0, 1, '127.0.0.1', 1203179874);
INSERT INTO `phpboost_wiki_contents` VALUES (6, 5, '<ol class="wiki_list_1">\r\n	<li><a href="#creer-un-module-additionnel">Cr&eacute;er un module additionnel</a>\n</li><li><a href="#framework-phpboost">Framework PHPBoost</a>\n<ol class="wiki_list_2">\r\n	<li><a href="#classes">Classes</a>\n</li><li><a href="#fonctions">Fonctions</a>\n</li></ol></li></ol>', 'Vous trouverez ici la documentation nécessaire pour pouvoir développer un module en se basant sur le noyau PHPBoost.<br />\r\n<br />\r\n<br />\r\n<div class="wiki_paragraph1" id="creer-un-module-additionnel">Créer un module additionnel</div><br />\n\r\n<br />\r\nL''article <a href="creer-un-module-additionnel">Créer un module additionnel</a> détaille pas à pas la création d''un module additionnel. Il fait référence aux framework PHPBoost, détaillé dans la partie suivante de cette article. N''hésitez pas à vous y référer régulièrement pour approfondir votre connaissance du framework PHPBoost.<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<div class="wiki_paragraph1" id="framework-phpboost">Framework PHPBoost</div><br />\n\r\n<br />\r\n<div class="wiki_paragraph2" id="classes">Classes</div><br />\n\r\n<br />\r\nLe framework PHPBoost est composée de classes permettant de faire différentes actions assez simplement pour ne pas avoir à réinventer la roue en développant. Voici la liste de celles qui peuvent être utiles lors du développement d''un module, elles sont classées par importance.<br />\r\n<br />\r\n<br />\r\n<div class="wiki_paragraph2" id="fonctions">Fonctions</div><br />\n\r\n<br />\r\nLe framework PHPBoost contient aussi des fonctions dont l''utilité est très variée.', 1, 1, '127.0.0.1', 1203380957);

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_wiki_favorites`
-- 

CREATE TABLE `phpboost_wiki_favorites` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `id_article` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `phpboost_wiki_favorites`
-- 

