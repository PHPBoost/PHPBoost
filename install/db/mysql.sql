DROP TABLE IF EXISTS `phpboost_com`;
CREATE TABLE `phpboost_com` (
	`idcom` int(11) NOT NULL auto_increment,
	`idprov` int(11) NOT NULL default '0',
	`login` varchar(255) NOT NULL default '',
	`user_id` int(11) NOT NULL default '0',
	`contents` text NOT NULL,
	`timestamp` int(11) NOT NULL default '0',
	`script` varchar(20) NOT NULL default '',
	PRIMARY KEY	(`idcom`),
	KEY `idprov` (`idprov`,`script`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_compteur`;
CREATE TABLE `phpboost_compteur` (
	`id` int(11) NOT NULL auto_increment,
	`ip` varchar(50) NOT NULL default '',
	`time` date NOT NULL default '0000-00-00',
	`total` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `ip` (`ip`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_compteur` (`id`, `ip`, `time`, `total`) VALUES (1, '', NOW(), 1);

DROP TABLE IF EXISTS `phpboost_configs`;
CREATE TABLE `phpboost_configs` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(150) NOT NULL default '',
	`value` text NOT NULL,
	PRIMARY KEY	(`id`),
	UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES 
(1, 'config', 'a:28:{s:11:"server_name";s:0:"";s:11:"server_path";s:0:"";s:9:"site_name";s:0:"";s:9:"site_desc";s:0:"";s:12:"site_keyword";s:0:"";s:5:"start";s:10:"0";s:7:"version";s:3:"2.0";s:4:"lang";s:6:"french";s:5:"theme";s:4:"main";s:10:"start_page";s:14:"/news/news.php";s:8:"maintain";s:1:"0";s:14:"maintain_delay";s:1:"1";s:13:"maintain_text";s:0:"";s:7:"rewrite";i:0;s:9:"com_popup";s:1:"0";s:8:"compteur";s:1:"0";s:5:"bench";s:1:"1";s:12:"ob_gzhandler";i:0;s:11:"site_cookie";s:7:"session";s:12:"site_session";i:3600;s:18:"site_session_invit";i:300;s:4:"mail";s:0:"";s:10:"activ_mail";s:1:"0";s:4:"sign";s:0:"";s:10:"anti_flood";s:1:"1";s:11:"delay_flood";s:1:"7";s:12:"unlock_admin";s:0:"";s:6:"pm_max";s:2:"50";}'),
(2, 'member', 'a:13:{s:14:"activ_register";i:1;s:7:"msg_mbr";s:0:"";s:12:"msg_register";s:0:"";s:9:"activ_mbr";i:0;s:10:"verif_code";i:1;s:17:"delay_unactiv_max";i:30;s:11:"force_theme";i:0;s:15:"activ_up_avatar";i:1;s:9:"width_max";i:120;s:10:"height_max";i:120;s:10:"weight_max";i:20;s:12:"activ_avatar";i:1;s:10:"avatar_url";s:13:"no_avatar.jpg";}'),
(3, 'files', 'a:3:{s:10:"size_limit";d:512;s:17:"bandwidth_protect";s:1:"1";s:10:"auth_files";s:45:"a:3:{s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}";}'),
(4, 'com', 'a:4:{s:8:"com_auth";i:-1;s:7:"com_max";i:10;s:14:"forbidden_tags";s:99:"a:6:{i:0;s:3:"swf";i:1;s:5:"movie";i:2;s:5:"sound";i:3;s:4:"code";i:4;s:4:"math";i:5;s:6:"anchor";}";s:8:"max_link";i:2;}');


DROP TABLE IF EXISTS `phpboost_group`;
CREATE TABLE `phpboost_group` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(100) NOT NULL default '',
	`img` varchar(255) NOT NULL default '',
	`auth` smallint(6) NOT NULL default '0',
	`members` text NOT NULL,
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_lang`;
CREATE TABLE `phpboost_lang` (
	`id` int(11) NOT NULL auto_increment,
	`lang` varchar(150) NOT NULL default '',
	`activ` tinyint(1) NOT NULL default '0',
	`secure` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_member`;
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
	PRIMARY KEY	(`user_id`),
	KEY `user_id` (`login`,`password`,`level`,`user_id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_member` (login, level, user_aprob) VALUES ('login', 2, 1);


DROP TABLE IF EXISTS `phpboost_member_extend`;
CREATE TABLE `phpboost_member_extend` (
	`user_id` int(11) NOT NULL auto_increment,
	PRIMARY KEY	(`user_id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_member_extend_cat`;
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
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_modules`;
CREATE TABLE `phpboost_modules` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(150) NOT NULL default '',
	`version` varchar(15) NOT NULL default '',
	`auth` text NOT NULL,
	`activ` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_modules_mini`;
CREATE TABLE `phpboost_modules_mini` (
	`id` int(11) NOT NULL auto_increment,
	`class` int(11) NOT NULL default '0',
	`name` varchar(150) NOT NULL default '',
	`code` text NOT NULL,
	`contents` text NOT NULL,
	`side` tinyint(1) NOT NULL default '0',
	`secure` tinyint(1) NOT NULL default '0',
	`activ` tinyint(1) NOT NULL default '0',
	`added` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_modules_mini` (`class`, `name`, `code`, `contents`, `side`, `secure`, `activ`, `added`) VALUES (2, 'connexion', 'if( SCRIPT != DIR . ''/membre/error.php'')include_once(''../includes/connect.php'');', '', 0, -1, 1, 0);


DROP TABLE IF EXISTS `phpboost_pm_msg`;
CREATE TABLE `phpboost_pm_msg` (
	`id` int(11) NOT NULL auto_increment,
	`idconvers` int(11) NOT NULL default '0',
	`user_id` int(11) NOT NULL default '0',
	`contents` text NOT NULL,
	`timestamp` int(11) NOT NULL default '0',
	`view_status` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `idconvers` (`idconvers`,`user_id`,`timestamp`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_pm_topic`;
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
	`visible` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `user_id` (`user_id`,`user_id_dest`,`user_convers_status`,`last_timestamp`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_ranks`;
CREATE TABLE `phpboost_ranks` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(150) NOT NULL default '',
	`msg` int(11) NOT NULL default '0',
	`icon` varchar(255) NOT NULL default '',
	`special` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_ranks` VALUES (1, 'Administrateur', -2, 'rank_admin.gif', 1);
INSERT INTO `phpboost_ranks` VALUES (2, 'Mod&eacute;rateur', -1, 'rank_modo.gif', 1);


DROP TABLE IF EXISTS `phpboost_sessions`;
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
	PRIMARY KEY	(`session_id`),
	KEY `user_id` (`user_id`,`session_time`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_stats`;
CREATE TABLE `phpboost_stats` (
	`id` int(11) NOT NULL auto_increment,
	`stats_year` smallint(6) NOT NULL default '0',
	`stats_month` tinyint(4) NOT NULL default '0',
	`stats_day` tinyint(4) NOT NULL default '0',
	`nbr` mediumint(9) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	UNIQUE KEY `stats_day` (`stats_day`,`stats_month`,`stats_year`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_themes`;
CREATE TABLE `phpboost_themes` (
	`id` int(11) NOT NULL auto_increment,
	`theme` varchar(50) NOT NULL default '',
	`activ` tinyint(1) NOT NULL default '0',
	`secure` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_upload`;
CREATE TABLE `phpboost_upload` (
	`id` int(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`name` varchar(150) NOT NULL default '',
	`path` varchar(255) NOT NULL default '',
	`user_id` int(11) NOT NULL default '0',
	`size` float NOT NULL default '0',
	`type` varchar(10) NOT NULL default '',
	`timestamp` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_upload_cat`;
CREATE TABLE `phpboost_upload_cat` (
	`id` int(11) NOT NULL auto_increment,
	`id_parent` int(11) NOT NULL default '0',
	`user_id` int(11) NOT NULL default '0',
	`name` varchar(150) NOT NULL default '',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_verif_code`;
CREATE TABLE `phpboost_verif_code` (
	`id` int(11) NOT NULL auto_increment,
	`user_id` varchar(8) NOT NULL default '',
	`code` varchar(20) NOT NULL default '',
	`timestamp` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `phpboost_smileys`;
CREATE TABLE `phpboost_smileys` (
	`idsmiley` int(11) NOT NULL auto_increment,
	`code_smiley` varchar(50) NOT NULL default '',
	`url_smiley` varchar(50) NOT NULL default '',
	PRIMARY KEY	(`idsmiley`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES 
(1, ':|', 'waw.gif'),
(2, ':siffle', 'siffle.gif'),
(3, ':)', 'sourire.gif'),
(4, ':lol', 'rire.gif'),
(5, ':p', 'tirelangue.gif'),
(6, ':(', 'malheureux.gif'),
(7, ';)', 'clindoeil.gif'),
(8, ':heink', 'heink.gif'),
(9, ':D', 'heureux.gif'),
(10, ':d', 'content.gif'),
(11, ':s', 'incertain.gif'),
(12, ':gne', 'pinch.gif'),
(13, ':top', 'top.gif'),
(14, ':clap', 'clap.gif'),
(15, ':hehe', 'hehe.gif'),
(16, ':@', 'angry.gif'),
(17, ':''(', 'snif.gif'),
(18, ':nex', 'nex.gif'),
(19, '8-)', 'star.gif'),
(20, '|-)', 'nuit.gif'),
(21, ':berk', 'berk.gif'),
(22, ':gre', 'colere.gif'),
(23, ':love', 'love.gif'),
(24, ':hum', 'doute.gif'),
(25, ':mat', 'mat.gif'),
(26, ':miam', 'miam.gif'),
(27, ':+1', 'plus1.gif'),
(28, ':lu', 'lu.gif');

-- 
-- Structure de la table `phpboost_search_index`
-- 

DROP TABLE IF EXISTS `phpboost_search_index`;
CREATE TABLE `phpboost_search_results` (
    `id_search`         int(11)         NOT NULL auto_increment,
    `id_module`         int(11)         NOT NULL default '0',
    `id_user`           int(11)         NOT NULL default '0',
    `search`            VARCHAR(255)    NOT NULL default '',
    `options`           VARCHAR(255)    NOT NULL default '',
    `last_search_use`   timestamp       NOT NULL,
    `times_used`        int(11)         NOT NULL default '0',
    PRIMARY KEY (`id_search`, `id_module`),
    INDEX `last_search_use` (`last_search_use`)
) ENGINE=MyISAM;

-- 
-- Contenu de la table `phpboost_search_index`
-- 

-- 
-- Structure de la table `phpboost_search_results`
-- 

DROP TABLE IF EXISTS `phpboost_search_results`;
CREATE TABLE `phpboost_search_results` (
    `id_search`         int(11)         NOT NULL auto_increment,
    `id_module`         int(11)         NOT NULL default '0',
    `id_module_content` int(11)         NOT NULL default '0',
    `relevance`         decimal         NOT NULL default '0',
    `link`              varchar(255)    NOT NULL default '',
    PRIMARY KEY (`id_search`,`id_module`,`id_module_content`),
    FOREIGN KEY (`id_search`,`id_module`),
) ENGINE=MyISAM;

-- 
-- Contenu de la table `phpboost_search_results`
-- 