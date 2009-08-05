ALTER TABLE `phpboost_com` ADD `path` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `script` ;
ALTER TABLE `phpboost_com` ADD `user_ip` VARCHAR( 50 ) NOT NULL DEFAULT '' AFTER `path`; 
ALTER TABLE `phpboost_com` CHANGE `contents` `contents` text; 

DROP TABLE IF EXISTS `phpboost_compteur`;

TRUNCATE `phpboost_themes`;

TRUNCATE `phpboost_configs`;
ALTER TABLE `phpboost_configs` CHANGE `value` `value` text; 
INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES 
(1, 'config', ''),
(2, 'member', 'a:14:{s:14:"activ_register";i:1;s:7:"msg_mbr";s:169:"Bienvenue sur le site. Vous êtes membre du site, vous pouvez accéder à tous les espaces nécessitant un compte utilisateur, éditer votre profil et voir vos contributions.";s:12:"msg_register";s:156:"Vous vous apprêtez à vous enregistrer sur le site. Nous vous demandons d''être poli et courtois dans vos interventions.<br />
<br />
Merci, l''équipe du site.";s:9:"activ_mbr";i:0;s:10:"verif_code";i:1;s:21:"verif_code_difficulty";i:2;s:17:"delay_unactiv_max";i:20;s:11:"force_theme";i:0;s:15:"activ_up_avatar";i:1;s:9:"width_max";i:120;s:10:"height_max";i:120;s:10:"weight_max";i:20;s:12:"activ_avatar";i:1;s:10:"avatar_url";s:13:"no_avatar.png";}'),
(3, 'uploads', 'a:4:{s:10:"size_limit";d:512;s:17:"bandwidth_protect";i:1;s:15:"auth_extensions";a:48:{i:0;s:3:"jpg";i:1;s:4:"jpeg";i:2;s:3:"bmp";i:3;s:3:"gif";i:4;s:3:"png";i:5;s:3:"tif";i:6;s:3:"svg";i:7;s:3:"ico";i:8;s:3:"rar";i:9;s:3:"zip";i:10;s:2:"gz";i:11;s:3:"txt";i:12;s:3:"doc";i:13;s:4:"docx";i:14;s:3:"pdf";i:15;s:3:"ppt";i:16;s:3:"xls";i:17;s:3:"odt";i:18;s:3:"odp";i:19;s:3:"ods";i:20;s:3:"odg";i:21;s:3:"odc";i:22;s:3:"odf";i:23;s:3:"odb";i:24;s:3:"xcf";i:25;s:3:"flv";i:26;s:3:"mp3";i:27;s:3:"ogg";i:28;s:3:"mpg";i:29;s:3:"mov";i:30;s:3:"swf";i:31;s:3:"wav";i:32;s:3:"wmv";i:33;s:4:"midi";i:34;s:3:"mng";i:35;s:2:"qt";i:36;s:1:"c";i:37;s:1:"h";i:38;s:3:"cpp";i:39;s:4:"java";i:40;s:2:"py";i:41;s:3:"css";i:42;s:4:"html";i:43;s:3:"xml";i:44;s:3:"ttf";i:45;s:3:"tex";i:46;s:3:"rtf";i:47;s:3:"psd";}s:10:"auth_files";s:32:"a:2:{s:2:"r0";i:1;s:2:"r1";i:1;}";}'),
(4, 'com', 'a:6:{s:8:"com_auth";i:-1;s:7:"com_max";i:10;s:14:"com_verif_code";i:1;s:25:"com_verif_code_difficulty";i:2;s:14:"forbidden_tags";a:0:{}s:8:"max_link";i:2;}'),
(5, 'writingpad', '');


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
ALTER TABLE `phpboost_group` ADD `auth` varchar(255) NOT NULL DEFAULT '' AFTER `color` ;
ALTER TABLE `phpboost_group` CHANGE `members` `members` text; 

DROP TABLE IF EXISTS `phpboost_links`;

ALTER TABLE `phpboost_member` CHANGE `password` `password`  varchar(64) NOT NULL DEFAULT '';
ALTER TABLE `phpboost_member` ADD `user_editor` varchar(15) NOT NULL DEFAULT '' AFTER `user_show_mail` ;
ALTER TABLE `phpboost_member` ADD `user_timezone` tinyint(2) NOT NULL DEFAULT '0' AFTER `user_editor` ;
ALTER TABLE `phpboost_member` CHANGE `new_pass` `new_pass`  varchar(64) NOT NULL DEFAULT '';
ALTER TABLE `phpboost_member` CHANGE `user_groups` `user_groups` text; 
ALTER TABLE `phpboost_member` CHANGE `user_desc` `user_desc` text; 
ALTER TABLE `phpboost_member` CHANGE `user_sign` `user_sign` text; 
ALTER TABLE `phpboost_member` ADD UNIQUE `login` ( `login` ) ;

UPDATE `phpboost_member` SET user_theme = 'base';

CREATE TABLE IF NOT EXISTS `phpboost_member_extend` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `last_view_forum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `phpboost_member_extend_cat` CHANGE `require` `required` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `phpboost_member_extend_cat` CHANGE `contents` `contents` text; 
ALTER TABLE `phpboost_member_extend_cat` CHANGE `field` `field` tinyint(1) NOT NULL DEFAULT '0'; 
ALTER TABLE `phpboost_member_extend_cat` CHANGE `possible_values` `possible_values` text; 
ALTER TABLE `phpboost_member_extend_cat` CHANGE `default_values` `default_values` text; 

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

DROP TABLE IF EXISTS `phpboost_modules_mini`;

ALTER TABLE `phpboost_modules` CHANGE `auth` `auth` text; 

ALTER TABLE `phpboost_pm_topic` DROP `visible`;
ALTER TABLE `phpboost_pm_msg` CHANGE `contents` `contents` text; 

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
ALTER TABLE `phpboost_sessions` ADD `modules_parameters` text AFTER `user_lang` ;
ALTER TABLE `phpboost_sessions` ADD `token` varchar(64) NOT NULL AFTER `modules_parameters` ;

ALTER TABLE `phpboost_stats` ADD `pages` int(11) NOT NULL DEFAULT '0' AFTER `nbr` ;
ALTER TABLE `phpboost_stats` ADD `pages_detail` text NOT NULL AFTER `pages` ;
ALTER TABLE `phpboost_stats` DROP INDEX `stats_day`, ADD UNIQUE `stats_day` ( `stats_day` , `stats_month` , `stats_year` ) ;
ALTER TABLE `phpboost_stats` CHANGE `pages_detail` `pages_detail` text; 

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

INSERT INTO `phpboost_visit_counter` (`id`, `ip`, `time`, `total`) VALUES (1, '1', NOW(), 1);




