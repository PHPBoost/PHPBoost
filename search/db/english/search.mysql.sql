DROP TABLE IF EXISTS `phpboost_search_results`;
CREATE TABLE `phpboost_search_results` (
	`id` int(11) NOT NULL auto_increment,
    `id_module` int(11) NOT NULL default '0',
    `id_module_content` int(11) NOT NULL default '0',
    `pertinence` decimal NOT NULL default '0',
    `last_search_use` timestamp NOT NULL,
    `link` varchar(255) NOT NULL default '',
	PRIMARY KEY	(`id`,`id_module`,`id_module_content`),
	INDEX `last_search_use` (`last_search_use`),
) ENGINE=MyISAM;
