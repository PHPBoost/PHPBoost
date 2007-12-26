DROP TABLE IF EXISTS `phpboost_links`;
CREATE TABLE `phpboost_links` (
	`id` int(11) NOT NULL auto_increment,
	`class` int(11) NOT NULL default '0',
	`name` varchar(50) NOT NULL default '',
	`url` varchar(255) NOT NULL default '',
	`activ` tinyint(1) NOT NULL default '0',
	`secure` char(2) NOT NULL default '',
	`added` tinyint(1) NOT NULL default '0',
	`sep` tinyint(1) NOT NULL default '1',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_links` (`id`, `class`, `name`, `url`, `activ`, `secure`, `added`, `sep`) VALUES (1, 1, 'Membres', '', 1, '-1', 0, 1);
INSERT INTO `phpboost_links` (`id`, `class`, `name`, `url`, `activ`, `secure`, `added`, `sep`) VALUES (2, 2, 'Membres', '../member/member.php', 1, '-1', 0, 0);
INSERT INTO `phpboost_links` (`id`, `class`, `name`, `url`, `activ`, `secure`, `added`, `sep`) VALUES (3, 3, 'Menu', '', 1, '-1', 0, 1);
                                                                                             
INSERT INTO `phpboost_modules_mini` (`name`, `code`, `contents`, `side`, `secure`, `activ`, `added`) VALUES ('links', 'include_once(''../links/links_mini.php'');', '', 0, -1, 1, 0);