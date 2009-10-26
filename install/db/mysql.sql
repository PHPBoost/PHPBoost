DROP TABLE IF EXISTS `phpboost_com`;
CREATE TABLE `phpboost_com` (
  `idcom` int(11) NOT NULL auto_increment,
  `idprov` int(11) NOT NULL default '0',
  `login` varchar(255) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `contents` text,
  `timestamp` int(11) NOT NULL default '0',
  `script` varchar(20) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `user_ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`idcom`),
  KEY `idprov` (`idprov`,`script`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_visit_counter`;
CREATE TABLE `phpboost_visit_counter` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(50) NOT NULL default '',
  `time` date NOT NULL default '0000-00-00',
  `total` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_configs`;
CREATE TABLE `phpboost_configs` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `value` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_events`;
CREATE TABLE `phpboost_events` (
  `id` int(11) NOT NULL auto_increment,
  `entitled` varchar(255) NOT NULL,
  `description` text,
  `fixing_url` varchar(255) NOT NULL,
  `module` varchar(100),
  `current_status` tinyint(3) NOT NULL default '0',
  `creation_date` int(11) NOT NULL,
  `fixing_date` int(11) default NULL,
  `auth` text,
  `poster_id` int(11),
  `fixer_id` int(11),
  `id_in_module` int(11) default NULL,
  `identifier` varchar(64) default NULL,
  `contribution_type` tinyint(1) unsigned NOT NULL default '1',
  `type` varchar(64) default NULL,
  `priority` tinyint(3) unsigned default '3',
  `nbr_com` int(10) unsigned default 0,
  `lock_com` tinyint(1) unsigned default 0,
  PRIMARY KEY  (`id`),
  KEY `type_index` (`type`),
  KEY `identifier_index` (`identifier`),
  KEY `module_index` (`module`),
  KEY `id_in_module_index` (`id_in_module`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_group`;
CREATE TABLE `phpboost_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `img` varchar(255) NOT NULL default '',
  `color` varchar(6) NOT NULL default '',
  `auth` varchar(255) NOT NULL default '0',
  `members` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_lang`;
CREATE TABLE `phpboost_lang` (
  `id` int(11) NOT NULL auto_increment,
  `lang` varchar(150) NOT NULL default '',
  `activ` tinyint(1) NOT NULL default '0',
  `secure` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_member`;
CREATE TABLE `phpboost_member` (
  `user_id` int(11) NOT NULL auto_increment,
  `login` varchar(255) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `level` tinyint(1) NOT NULL default '0',
  `user_groups` text,
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
  `user_desc` text,
  `user_sex` tinyint(1) NOT NULL default '0',
  `user_born` date NOT NULL default '0000-00-00',
  `user_sign` text,
  `user_pm` smallint(6) unsigned NOT NULL default '0',
  `user_warning` smallint(6) NOT NULL default '0',
  `user_readonly` int(11) NOT NULL default '0',
  `last_connect` int(11) NOT NULL default '0',
  `test_connect` tinyint(4) NOT NULL default '0',
  `activ_pass` varchar(30) NOT NULL default '0',
  `new_pass` varchar(64) NOT NULL default '',
  `user_ban` int(11) NOT NULL default '0',
  `user_aprob` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `login` (`login`),
  KEY `user_id` (`login`,`password`,`level`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_member_extend`;
CREATE TABLE `phpboost_member_extend` (
  `user_id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_member_extend_cat`;
CREATE TABLE `phpboost_member_extend_cat` (
  `id` int(11) NOT NULL auto_increment,
  `class` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `field_name` varchar(255) NOT NULL default '',
  `contents` text,
  `field` tinyint(1) NOT NULL default '0',
  `possible_values` text,
  `default_values` text,
  `required` tinyint(1) NOT NULL default '0',
  `display` tinyint(1) NOT NULL default '0',
  `regex` varchar(255) NOT NULL default '',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_modules`;
CREATE TABLE `phpboost_modules` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `version` varchar(15) NOT NULL default '',
  `auth` text,
  `activ` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_menus`;
CREATE TABLE `phpboost_menus` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(128) NOT NULL,
  `object` text,
  `class` varchar(64) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `block` tinyint(2) NOT NULL,
  `position` tinyint(2) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `block` (`block`),
  KEY `class` (`class`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

CREATE TABLE `phpboost-trunk`.`phpboost_menu_configuration` (
  `id` INTEGER  NOT NULL,
  `name` VARCHAR(100)  NOT NULL,
  `match_regex` MEDIUMTEXT  NOT NULL,
  `priority` INTEGER  NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `priority`(`priority`)
)
ENGINE = MyISAM;

DROP TABLE IF EXISTS `phpboost_pm_msg`;
CREATE TABLE `phpboost_pm_msg` (
  `id` int(11) NOT NULL auto_increment,
  `idconvers` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `contents` text,
  `timestamp` int(11) NOT NULL default '0',
  `view_status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idconvers` (`idconvers`,`user_id`,`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


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
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`,`user_id_dest`,`user_convers_status`,`last_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
 

DROP TABLE IF EXISTS `phpboost_ranks`;
CREATE TABLE `phpboost_ranks` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `msg` int(11) NOT NULL default '0',
  `icon` varchar(255) NOT NULL default '',
  `special` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_search_index`;
CREATE TABLE `phpboost_search_index` (
  `id_search` int(11) NOT NULL auto_increment,
  `id_user` int(11) NOT NULL default '0',
  `module` varchar(64) NOT NULL default '0',
  `search` varchar(50) NOT NULL default '',
  `options` varchar(50) NOT NULL default '',
  `last_search_use` int(14) NOT NULL default '0',
  `times_used` int(3) NOT NULL default '0',
  PRIMARY KEY  (`id_search`),
  UNIQUE KEY `id_user` (`id_user`,`module`,`search`,`options`),
  KEY `last_search_use` (`last_search_use`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_search_results`;
CREATE TABLE `phpboost_search_results` (
  `id_search` int(11) NOT NULL auto_increment,
  `id_content` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `relevance` decimal(5,2) NOT NULL default '0.00',
  `link` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_search`,`id_content`),
  KEY `relevance` (`relevance`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_sessions`;
CREATE TABLE `phpboost_sessions` (
  `session_id` varchar(64) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `level` tinyint(1) NOT NULL default '0',
  `session_ip` varchar(50) NOT NULL default '',
  `session_time` int(11) NOT NULL default '0',
  `session_script` varchar(100) NOT NULL default '0',
  `session_script_get` varchar(100) NOT NULL default '0',
  `session_script_title` varchar(255) NOT NULL default '',
  `session_flag` tinyint(1) NOT NULL default '0',
  `user_theme` varchar(50) NOT NULL default '',
  `user_lang` varchar(50) NOT NULL default '',
  `modules_parameters` text,
  `token` varchar(64) NOT NULL,
  PRIMARY KEY  (`session_id`),
  KEY `user_id` (`user_id`,`session_time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_smileys`;
CREATE TABLE `phpboost_smileys` (
  `idsmiley` int(11) NOT NULL auto_increment,
  `code_smiley` varchar(50) NOT NULL default '',
  `url_smiley` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`idsmiley`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_stats`;
CREATE TABLE `phpboost_stats` (
  `id` int(11) NOT NULL auto_increment,
  `stats_year` smallint(6) NOT NULL default '0',
  `stats_month` tinyint(4) NOT NULL default '0',
  `stats_day` tinyint(4) NOT NULL default '0',
  `nbr` mediumint(9) NOT NULL default '0',
  `pages` int(11) NOT NULL default '0',
  `pages_detail` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `stats_day` (`stats_day`,`stats_month`,`stats_year`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_stats_referer`;
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_themes`;
CREATE TABLE `phpboost_themes` (
  `id` int(11) NOT NULL auto_increment,
  `theme` varchar(50) NOT NULL default '',
  `activ` tinyint(1) NOT NULL default '0',
  `secure` tinyint(1) NOT NULL default '0',
  `left_column` tinyint(1) NOT NULL default '0',
  `right_column` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_upload_cat`;
CREATE TABLE `phpboost_upload_cat` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `phpboost_verif_code`;
CREATE TABLE `phpboost_verif_code` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` varchar(15) NOT NULL default '',
  `code` varchar(20) NOT NULL default '',
  `difficulty` tinyint(1) NOT NULL,
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
