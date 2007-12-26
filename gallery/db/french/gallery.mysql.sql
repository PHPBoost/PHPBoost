DROP TABLE IF EXISTS `phpboost_gallery`;
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
	PRIMARY KEY	(`id`),
	KEY `idcat` (`idcat`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_gallery_cats`;
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
	PRIMARY KEY	(`id`),
	KEY `id_left` (`id_left`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_configs` (`name`, `value`) VALUES ('gallery', 'a:27:{s:5:"width";i:150;s:6:"height";i:150;s:9:"width_max";i:800;s:10:"height_max";i:600;s:10:"weight_max";i:1024;s:7:"quality";i:80;s:5:"trans";i:40;s:4:"logo";s:8:"logo.jpg";s:10:"activ_logo";i:1;s:7:"d_width";i:5;s:8:"d_height";i:5;s:10:"nbr_column";i:4;s:12:"nbr_pics_max";i:16;s:8:"note_max";i:5;s:11:"activ_title";i:1;s:9:"activ_com";i:1;s:10:"activ_note";i:1;s:15:"display_nbrnote";i:1;s:10:"activ_view";i:1;s:10:"activ_user";i:1;s:12:"limit_member";i:10;s:10:"limit_modo";i:25;s:12:"display_pics";i:3;s:11:"scroll_type";i:1;s:13:"nbr_pics_mini";i:2;s:15:"speed_mini_pics";i:6;s:9:"auth_root";s:59:"a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}";}');
INSERT INTO `phpboost_modules_mini` (`name`, `code`, `contents`, `side`, `secure`, `activ`, `added`) VALUES ('gallery', 'include_once(''../gallery/gallery_mini.php'');', '', 1, -1, 1, 0);
