DROP TABLE IF EXISTS `phpboost_faq`;
CREATE TABLE `phpboost_faq` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL,
  `q_order` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_faq_cats`;
CREATE TABLE IF NOT EXISTS `phpboost_faq_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL,
  `c_order` int(11) unsigned NOT NULL,
  `auth` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `visible` tinyint(1) NOT NULL default '0',
  `display_mode` tinyint(2) NOT NULL default '0',
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `num_questions` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_configs` (`name`, `value`) VALUES ('faq', 'a:5:{s:8:"faq_name";s:12:"FAQ PHPBoost";s:8:"num_cols";i:3;s:13:"display_block";b:1;s:11:"global_auth";a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:3;s:2:"r2";i:3;}s:4:"root";a:3:{s:12:"display_mode";i:0;s:4:"auth";N;s:11:"description";s:30:"Bienvenue sur la FAQ PHPBoost.";}}');