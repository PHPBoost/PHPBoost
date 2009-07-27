ALTER TABLE `phpboost_com` CHANGE `idcom` `idcom` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `phpboost_com` CHANGE `idprov` `idprov` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `phpboost_com` CHANGE `user_id` `user_id` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `phpboost_com` ADD `path` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `script` ;
ALTER TABLE `phpboost_com` ADD `user_ip` VARCHAR( 50 ) NOT NULL DEFAULT '' AFTER `path`; 
ALTER TABLE `phpboost_com` DROP INDEX `idcom`, ADD PRIMARY KEY ( `idcom` ) ;
ALTER TABLE `phpboost_com` ADD INDEX (`idprov` ,`script`);

DROP TABLE IF EXISTS `phpboost_compteur`;

-- Il faudra vérifier les modules à insérer dans cette table!
TRUNCATE `phpboost_configs`;
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES
(1, 'config', 'a:38:{s:11:"server_name";s:16:"http://localhost";s:11:"server_path";s:9:"/phpboost";s:9:"site_name";s:4:"Test";s:9:"site_desc";s:0:"";s:12:"site_keyword";s:0:"";s:5:"start";i:1237569582;s:7:"version";s:4:"3.0b";s:4:"lang";s:6:"french";s:5:"theme";s:4:"base";s:6:"editor";s:6:"bbcode";s:8:"timezone";i:1;s:10:"start_page";s:14:"/news/news.php";s:8:"maintain";i:0;s:14:"maintain_delay";i:1;s:22:"maintain_display_admin";i:1;s:13:"maintain_text";s:40:"Le site est actuellement en maintenance.";s:23:"htaccess_manual_content";s:0:"";s:7:"rewrite";i:0;s:9:"com_popup";i:0;s:8:"compteur";i:0;s:5:"bench";i:0;s:12:"theme_author";i:0;s:12:"ob_gzhandler";i:0;s:11:"site_cookie";s:7:"session";s:12:"site_session";i:3600;s:18:"site_session_invit";i:300;s:8:"mail_exp";s:15:"crowkait@ds.Fsd";s:4:"mail";s:15:"crowkait@ds.Fsd";s:10:"activ_mail";i:0;s:4:"sign";s:31:"Cordialement, l''équipe du site.";s:10:"anti_flood";i:0;s:11:"delay_flood";i:7;s:12:"unlock_admin";s:64:"2189bcc8e11935dbdf403bb7434ba53ceb8a7fe461388096cea65e3e1deba7b3";s:6:"pm_max";i:50;s:17:"search_cache_time";i:30;s:14:"search_max_use";i:100;s:9:"html_auth";a:1:{s:2:"r2";i:1;}s:14:"forbidden_tags";a:0:{}}'),
(2, 'member', 'a:14:{s:14:"activ_register";i:1;s:7:"msg_mbr";s:169:"Bienvenue sur le site. Vous êtes membre du site, vous pouvez accéder à tous les espaces nécessitant un compte utilisateur, éditer votre profil et voir vos contributions.";s:12:"msg_register";s:158:"Vous vous apprêtez à vous enregistrer sur le site. Nous vous demandons d''être poli et courtois dans vos interventions.<br />\r\n<br />\r\nMerci, l''équipe du site.";s:9:"activ_mbr";i:0;s:10:"verif_code";i:1;s:21:"verif_code_difficulty";i:2;s:17:"delay_unactiv_max";i:20;s:11:"force_theme";i:0;s:15:"activ_up_avatar";i:0;s:9:"width_max";i:120;s:10:"height_max";i:120;s:10:"weight_max";i:20;s:12:"activ_avatar";i:0;s:10:"avatar_url";s:13:"no_avatar.png";}'),
(3, 'uploads', 'a:4:{s:10:"size_limit";d:512;s:17:"bandwidth_protect";i:1;s:15:"auth_extensions";a:48:{i:0;s:3:"jpg";i:1;s:4:"jpeg";i:2;s:3:"bmp";i:3;s:3:"gif";i:4;s:3:"png";i:5;s:3:"tif";i:6;s:3:"svg";i:7;s:3:"ico";i:8;s:3:"rar";i:9;s:3:"zip";i:10;s:2:"gz";i:11;s:3:"txt";i:12;s:3:"doc";i:13;s:4:"docx";i:14;s:3:"pdf";i:15;s:3:"ppt";i:16;s:3:"xls";i:17;s:3:"odt";i:18;s:3:"odp";i:19;s:3:"ods";i:20;s:3:"odg";i:21;s:3:"odc";i:22;s:3:"odf";i:23;s:3:"odb";i:24;s:3:"xcf";i:25;s:3:"flv";i:26;s:3:"mp3";i:27;s:3:"ogg";i:28;s:3:"mpg";i:29;s:3:"mov";i:30;s:3:"swf";i:31;s:3:"wav";i:32;s:3:"wmv";i:33;s:4:"midi";i:34;s:3:"mng";i:35;s:2:"qt";i:36;s:1:"c";i:37;s:1:"h";i:38;s:3:"cpp";i:39;s:4:"java";i:40;s:2:"py";i:41;s:3:"css";i:42;s:4:"html";i:43;s:3:"xml";i:44;s:3:"ttf";i:45;s:3:"tex";i:46;s:3:"rtf";i:47;s:3:"psd";}s:10:"auth_files";s:32:"a:2:{s:2:"r0";i:1;s:2:"r1";i:1;}";}'),
(4, 'com', 'a:4:{s:8:"com_auth";i:-1;s:7:"com_max";i:10;s:14:"forbidden_tags";a:0:{}s:8:"max_link";i:2;}'),
(5, 'writingpad', ''),
(6, 'articles', 'a:5:{s:16:"nbr_articles_max";i:10;s:11:"nbr_cat_max";i:10;s:10:"nbr_column";i:2;s:8:"note_max";i:5;s:9:"auth_root";s:59:"a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}";}'),
(7, 'calendar', 'a:1:{s:13:"calendar_auth";i:2;}'),
(8, 'contact', 'a:2:{s:17:"contact_verifcode";i:1;s:28:"contact_difficulty_verifcode";i:2;}'),
(9, 'download', 'a:5:{s:12:"nbr_file_max";i:10;s:10:"nbr_column";i:2;s:8:"note_max";i:5;s:13:"root_contents";s:50:"Bienvenue dans l''espace de téléchargement du site!";s:11:"global_auth";a:3:{s:3:"r-1";i:1;s:2:"r0";i:5;s:2:"r1";i:7;}}'),
(10, 'faq', 'a:5:{s:8:"faq_name";s:12:"FAQ PHPBoost";s:8:"num_cols";i:4;s:13:"display_block";b:0;s:11:"global_auth";a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:3;s:2:"r2";i:3;}s:4:"root";a:3:{s:12:"display_mode";i:0;s:4:"auth";a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:3;s:2:"r2";i:3;}s:11:"description";s:23:"Bienvenue dans la FAQ !";}}'),
(11, 'forum', 'a:15:{s:10:"forum_name";s:14:"PHPBoost forum";s:16:"pagination_topic";i:20;s:14:"pagination_msg";i:15;s:9:"view_time";i:2592000;s:11:"topic_track";i:40;s:9:"edit_mark";i:1;s:14:"no_left_column";i:0;s:15:"no_right_column";i:0;s:17:"activ_display_msg";i:1;s:11:"display_msg";s:21:"[R&eacute;gl&eacute;]";s:19:"explain_display_msg";s:26:"Sujet r&eacute;gl&eacute;?";s:23:"explain_display_msg_bis";s:30:"Sujet non r&eacute;gl&eacute;?";s:22:"icon_activ_display_msg";i:1;s:4:"auth";s:19:"a:1:{s:2:"r2";i:7;}";s:17:"display_connexion";i:0;}'),
(12, 'gallery', 'a:27:{s:5:"width";i:150;s:6:"height";i:150;s:9:"width_max";i:800;s:10:"height_max";i:600;s:10:"weight_max";i:1024;s:7:"quality";i:80;s:5:"trans";i:40;s:4:"logo";s:8:"logo.jpg";s:10:"activ_logo";i:1;s:7:"d_width";i:5;s:8:"d_height";i:5;s:10:"nbr_column";i:4;s:12:"nbr_pics_max";i:16;s:8:"note_max";i:5;s:11:"activ_title";i:1;s:9:"activ_com";i:1;s:10:"activ_note";i:1;s:15:"display_nbrnote";i:1;s:10:"activ_view";i:1;s:10:"activ_user";i:1;s:12:"limit_member";i:10;s:10:"limit_modo";i:25;s:12:"display_pics";i:3;s:11:"scroll_type";i:1;s:13:"nbr_pics_mini";i:6;s:15:"speed_mini_pics";i:6;s:9:"auth_root";s:59:"a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}";}'),
(13, 'guestbook', 'a:5:{s:14:"guestbook_auth";i:-1;s:24:"guestbook_forbidden_tags";s:52:"a:3:{i:0;s:3:"swf";i:1;s:5:"movie";i:2;s:5:"sound";}";s:18:"guestbook_max_link";i:2;s:19:"guestbook_verifcode";i:0;s:30:"guestbook_difficulty_verifcode";i:0;}'),
(14, 'media', 'a:6:{s:5:"pagin";i:25;s:10:"nbr_column";i:2;s:8:"note_max";i:5;s:5:"width";i:900;s:6:"height";i:600;s:4:"root";a:10:{s:9:"id_parent";i:-1;s:5:"order";i:1;s:4:"name";s:10:"Multimédia";s:4:"desc";s:94:"<p style="text-align:center"><strong>Voici le module Multimédia pour PHPboost 3.0</strong></p>";s:7:"visible";b:1;s:5:"image";s:9:"media.png";s:9:"num_media";i:0;s:9:"mime_type";i:0;s:6:"active";i:4095;s:4:"auth";a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;}}}'),
(15, 'news', 'a:13:{s:4:"type";i:1;s:11:"activ_pagin";i:1;s:11:"activ_edito";i:1;s:15:"pagination_news";i:5;s:15:"pagination_arch";i:10;s:9:"activ_com";i:1;s:10:"activ_icon";i:1;s:14:"display_author";i:1;s:12:"display_date";i:1;s:8:"nbr_news";s:1:"2";s:10:"nbr_column";i:1;s:5:"edito";s:22:"Bienvenue sur le site!";s:11:"edito_title";s:22:"Bienvenue sur le site!";}'),
(16, 'newsletter', 'a:2:{s:11:"sender_mail";s:0:"";s:15:"newsletter_name";s:0:"";}'),
(17, 'online', 'a:2:{s:16:"online_displayed";i:4;s:20:"display_order_online";s:33:"s.level DESC, s.session_time DESC";}'),
(18, 'pages', 'a:3:{s:10:"count_hits";i:1;s:9:"activ_com";i:1;s:4:"auth";s:59:"a:4:{s:3:"r-1";i:5;s:2:"r0";i:7;s:2:"r1";i:7;s:2:"r2";i:7;}";}'),
(19, 'poll', 'a:4:{s:9:"poll_auth";i:-1;s:9:"poll_mini";a:1:{i:0;s:1:"1";}s:11:"poll_cookie";s:4:"poll";s:18:"poll_cookie_lenght";i:2592000;}'),
(20, 'search', 'a:2:{s:19:"nb_results_per_page";i:15;s:18:"authorized_modules";a:0:{}}'),
(21, 'shoutbox', 'a:5:{s:16:"shoutbox_max_msg";i:100;s:13:"shoutbox_auth";i:-1;s:23:"shoutbox_forbidden_tags";s:428:"a:26:{i:0;s:5:"title";i:1;s:6:"stitle";i:2;s:5:"style";i:3;s:3:"url";i:4;s:3:"img";i:5;s:5:"quote";i:6;s:4:"hide";i:7;s:4:"list";i:8;s:5:"color";i:9;s:7:"bgcolor";i:10;s:4:"font";i:11;s:4:"size";i:12;s:5:"align";i:13;s:5:"float";i:14;s:3:"sup";i:15;s:3:"sub";i:16;s:6:"indent";i:17;s:3:"pre";i:18;s:5:"table";i:19;s:3:"swf";i:20;s:5:"movie";i:21;s:5:"sound";i:22;s:4:"code";i:23;s:4:"math";i:24;s:6:"anchor";i:25;s:7:"acronym";}";s:17:"shoutbox_max_link";i:2;s:22:"shoutbox_refresh_delay";d:60000;}'),
(22, 'web', 'a:4:{s:11:"nbr_web_max";i:10;s:11:"nbr_cat_max";i:10;s:10:"nbr_column";i:2;s:8:"note_max";i:5;}'),
(23, 'wiki', 'a:6:{s:4:"auth";s:71:"a:4:{s:3:"r-1";i:1041;s:2:"r0";i:1299;s:2:"r1";i:4095;s:2:"r2";i:4095;}";s:9:"wiki_name";s:13:"Wiki PHPBoost";s:13:"last_articles";i:0;s:12:"display_cats";i:0;s:10:"index_text";s:22:"Bienvenue sur le wiki.";s:10:"count_hits";i:1;}');

CREATE TABLE IF NOT EXISTS `phpboost_events` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `phpboost_group` ADD `color` varchar(6) NOT NULL DEFAULT '' AFTER `img` ;
ALTER TABLE `phpboost_group` DROP `auth`;
ALTER TABLE `phpboost_group` ADD `auth` varchar(255) NOT NULL DEFAULT '0' AFTER `color` ;

DROP TABLE IF EXISTS `phpboost_links`;

ALTER TABLE `phpboost_member` CHANGE `password` `password`  varchar(64) NOT NULL DEFAULT '';
ALTER TABLE `phpboost_member` ADD `user_editor` varchar(15) NOT NULL DEFAULT '' AFTER `user_show_mail` ;
ALTER TABLE `phpboost_member` ADD `user_timezone` tinyint(2) NOT NULL DEFAULT '0' AFTER `user_editor` ;
ALTER TABLE `phpboost_member` CHANGE `new_pass` `new_pass`  varchar(64) NOT NULL DEFAULT '';


CREATE TABLE IF NOT EXISTS `phpboost_member_extend` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `last_view_forum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `phpboost_member_extend_cat` CHANGE `require` `required` TINYINT( 1 ) NOT NULL DEFAULT '0';


CREATE TABLE IF NOT EXISTS `phpboost_menus` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

INSERT INTO `phpboost_menus` (`id`, `title`, `object`, `class`, `enabled`, `block`, `position`) VALUES
(1, 'connect/connect_mini', 'O:14:"ModuleMiniMenu":7:{s:8:"filename";s:12:"connect_mini";s:2:"id";i:0;s:5:"title";s:7:"connect";s:4:"auth";a:0:{}s:7:"enabled";b:1;s:5:"block";i:2;s:8:"position";i:0;}', 'moduleminimenu', 1, 2, 0),
(2, 'faq/faq_mini', 'O:14:"ModuleMiniMenu":7:{s:8:"filename";s:8:"faq_mini";s:2:"id";i:0;s:5:"title";s:3:"faq";s:4:"auth";a:0:{}s:7:"enabled";b:1;s:5:"block";i:7;s:8:"position";i:0;}', 'moduleminimenu', 1, 7, 1),
(3, 'gallery/gallery_mini', 'O:14:"ModuleMiniMenu":7:{s:8:"filename";s:12:"gallery_mini";s:2:"id";i:0;s:5:"title";s:7:"gallery";s:4:"auth";a:0:{}s:7:"enabled";b:1;s:5:"block";i:8;s:8:"position";i:0;}', 'moduleminimenu', 1, 8, 0),
(4, 'guestbook/guestbook_mini', 'O:14:"ModuleMiniMenu":7:{s:8:"filename";s:14:"guestbook_mini";s:2:"id";i:0;s:5:"title";s:9:"guestbook";s:4:"auth";a:0:{}s:7:"enabled";b:1;s:5:"block";i:7;s:8:"position";i:1;}', 'moduleminimenu', 1, 7, 2),
(5, 'newsletter/newsletter_mini', 'O:14:"ModuleMiniMenu":7:{s:8:"filename";s:15:"newsletter_mini";s:2:"id";i:0;s:5:"title";s:10:"newsletter";s:4:"auth";a:0:{}s:7:"enabled";b:1;s:5:"block";i:5;s:8:"position";i:0;}', 'moduleminimenu', 1, 5, 0),
(6, 'online/online_mini', 'O:14:"ModuleMiniMenu":7:{s:8:"filename";s:11:"online_mini";s:2:"id";i:0;s:5:"title";s:6:"online";s:4:"auth";a:0:{}s:7:"enabled";b:1;s:5:"block";i:8;s:8:"position";i:1;}', 'moduleminimenu', 1, 8, 1),
(7, 'poll/poll_mini', 'O:14:"ModuleMiniMenu":7:{s:8:"filename";s:9:"poll_mini";s:2:"id";i:0;s:5:"title";s:4:"poll";s:4:"auth";a:0:{}s:7:"enabled";b:1;s:5:"block";i:8;s:8:"position";i:2;}', 'moduleminimenu', 1, 8, 2),
(8, 'search/search_mini', 'O:14:"ModuleMiniMenu":7:{s:8:"filename";s:11:"search_mini";s:2:"id";i:0;s:5:"title";s:6:"search";s:4:"auth";a:0:{}s:7:"enabled";b:1;s:5:"block";i:1;s:8:"position";i:0;}', 'moduleminimenu', 1, 1, 0),
(9, 'shoutbox/shoutbox_mini', 'O:14:"ModuleMiniMenu":7:{s:8:"filename";s:13:"shoutbox_mini";s:2:"id";i:0;s:5:"title";s:8:"shoutbox";s:4:"auth";a:0:{}s:7:"enabled";b:1;s:5:"block";i:8;s:8:"position";i:3;}', 'moduleminimenu', 1, 8, 3),
(10, 'stats/stats_mini', 'O:14:"ModuleMiniMenu":7:{s:8:"filename";s:10:"stats_mini";s:2:"id";i:0;s:5:"title";s:5:"stats";s:4:"auth";a:0:{}s:7:"enabled";b:1;s:5:"block";i:7;s:8:"position";i:2;}', 'moduleminimenu', 1, 7, 3),
(11, 'PHPBoost', 'O:9:"LinksMenu":12:{s:4:"type";s:8:"vertical";s:8:"elements";a:14:{i:0;O:13:"LinksMenuLink":10:{s:3:"url";s:22:"/articles/articles.php";s:5:"image";s:27:"/articles/articles_mini.png";s:3:"uid";i:1765;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:8:"Articles";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:1;O:13:"LinksMenuLink":10:{s:3:"url";s:22:"/calendar/calendar.php";s:5:"image";s:27:"/calendar/calendar_mini.png";s:3:"uid";i:1766;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:10:"Calendrier";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:2;O:13:"LinksMenuLink":10:{s:3:"url";s:20:"/contact/contact.php";s:5:"image";s:25:"/contact/contact_mini.png";s:3:"uid";i:1767;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:7:"Contact";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:3;O:13:"LinksMenuLink":10:{s:3:"url";s:12:"/faq/faq.php";s:5:"image";s:17:"/faq/faq_mini.png";s:3:"uid";i:1768;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:5:"F.A.Q";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:4;O:13:"LinksMenuLink":10:{s:3:"url";s:16:"/forum/index.php";s:5:"image";s:21:"/forum/forum_mini.png";s:3:"uid";i:1769;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:5:"Forum";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:5;O:13:"LinksMenuLink":10:{s:3:"url";s:20:"/gallery/gallery.php";s:5:"image";s:25:"/gallery/gallery_mini.png";s:3:"uid";i:1770;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:7:"Galerie";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:6;O:13:"LinksMenuLink":10:{s:3:"url";s:12:"/web/web.php";s:5:"image";s:17:"/web/web_mini.png";s:3:"uid";i:1771;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:9:"Liens web";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:7;O:13:"LinksMenuLink":10:{s:3:"url";s:24:"/guestbook/guestbook.php";s:5:"image";s:29:"/guestbook/guestbook_mini.png";s:3:"uid";i:1772;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:10:"Livre d''or";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:8;O:13:"LinksMenuLink":10:{s:3:"url";s:16:"/media/media.php";s:5:"image";s:21:"/media/media_mini.png";s:3:"uid";i:1773;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:10:"Multimédia";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:9;O:13:"LinksMenuLink":10:{s:3:"url";s:14:"/news/news.php";s:5:"image";s:19:"/news/news_mini.png";s:3:"uid";i:1774;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:4:"News";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:10;O:13:"LinksMenuLink":10:{s:3:"url";s:18:"/search/search.php";s:5:"image";s:23:"/search/search_mini.png";s:3:"uid";i:1775;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:9:"Recherche";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:11;O:13:"LinksMenuLink":10:{s:3:"url";s:14:"/poll/poll.php";s:5:"image";s:19:"/poll/poll_mini.png";s:3:"uid";i:1776;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:8:"Sondages";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:12;O:13:"LinksMenuLink":10:{s:3:"url";s:22:"/download/download.php";s:5:"image";s:27:"/download/download_mini.png";s:3:"uid";i:1777;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:15:"Téléchargements";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}i:13;O:13:"LinksMenuLink":10:{s:3:"url";s:14:"/wiki/wiki.php";s:5:"image";s:19:"/wiki/wiki_mini.png";s:3:"uid";i:1778;s:5:"depth";i:1;s:2:"id";i:0;s:5:"title";s:4:"Wiki";s:4:"auth";a:0:{}s:7:"enabled";b:0;s:5:"block";i:0;s:8:"position";i:-1;}}s:3:"url";s:1:"/";s:5:"image";s:0:"";s:3:"uid";i:1764;s:5:"depth";i:0;s:2:"id";i:11;s:5:"title";s:8:"PHPBoost";s:4:"auth";a:0:{}s:7:"enabled";b:1;s:5:"block";i:7;s:8:"position";i:0;}', 'linksmenu', 1, 7, 0);

DROP TABLE IF EXISTS `phpboost_modules_mini`;

ALTER TABLE `phpboost_pm_topic` DROP `visible`;

TRUNCATE `phpboost_ranks`;
INSERT INTO `phpboost_ranks` (`id`, `name`, `msg`, `icon`, `special`) VALUES
(1, 'Administrateur', -2, 'rank_admin.png', 1),
(2, 'Modérateur', -1, 'rank_modo.png', 1),
(3, 'Boosteur Inactif', 0, 'rank_0.png', 0),
(4, 'Booster Fronde', 1, 'rank_0.png', 0),
(5, 'Booster Minigun', 25, 'rank_1.png', 0),
(6, 'Booster Fuzil', 50, 'rank_2.png', 0),
(7, 'Booster Bazooka', 100, 'rank_3.png', 0),
(8, 'Booster Roquette', 250, 'rank_4.png', 0),
(9, 'Booster Mortier', 500, 'rank_5.png', 0),
(10, 'Booster Missile', 1000, 'rank_6.png', 0),
(11, 'Booster Fusée', 1500, 'rank_special.png', 0);


CREATE TABLE IF NOT EXISTS `phpboost_search_index` (
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

CREATE TABLE IF NOT EXISTS `phpboost_search_results` (
  `id_search` int(11) NOT NULL AUTO_INCREMENT,
  `id_content` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `relevance` decimal(5,2) NOT NULL DEFAULT '0.00',
  `link` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_search`,`id_content`),
  KEY `relevance` (`relevance`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `phpboost_sessions` CHANGE `session_id` `session_id` varchar(64) NOT NULL DEFAULT '';
ALTER TABLE `phpboost_sessions` ADD `user_theme` varchar(50) NOT NULL DEFAULT '' AFTER `session_flag` ;
ALTER TABLE `phpboost_sessions` ADD `user_lang` varchar(50) NOT NULL DEFAULT '' AFTER `user_theme` ;
ALTER TABLE `phpboost_sessions` ADD `modules_parameters` text NOT NULL AFTER `user_lang` ;
ALTER TABLE `phpboost_sessions` ADD `token` varchar(64) NOT NULL AFTER `modules_parameters` ;

ALTER TABLE `phpboost_stats` ADD `pages` int(11) NOT NULL DEFAULT '0' AFTER `nbr` ;
ALTER TABLE `phpboost_stats` ADD `pages_detail` text NOT NULL AFTER `pages` ;
ALTER TABLE `phpboost_stats` DROP INDEX `stats_day`, ADD UNIQUE `stats_day` ( `stats_day` , `stats_month` , `stats_year` ) ;

CREATE TABLE IF NOT EXISTS `phpboost_stats_referer` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `phpboost_themes` ADD `left_column` tinyint(1) NOT NULL DEFAULT '0' AFTER `secure` ;
ALTER TABLE `phpboost_themes` ADD `right_column` tinyint(1) NOT NULL DEFAULT '0'AFTER `left_column` ;
UPDATE `phpboost_themes` SET `left_column` = 1, `right_column` = 1;

ALTER TABLE `phpboost_verif_code` CHANGE `user_id` `user_id` varchar(15) NOT NULL DEFAULT '';
ALTER TABLE `phpboost_verif_code` ADD `difficulty` TINYINT( 1 ) NOT NULL AFTER `code`;

CREATE TABLE IF NOT EXISTS `phpboost_visit_counter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL DEFAULT '',
  `time` date NOT NULL DEFAULT '0000-00-00',
  `total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2;

INSERT INTO `phpboost_visit_counter` (`id`, `ip`, `time`, `total`) VALUES (1, '1', '2009-03-20', 1);




