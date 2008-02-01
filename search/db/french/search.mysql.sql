DROP TABLE IF EXISTS `phpboost_search_index`;
CREATE TABLE `phpboost_search_results` (
    `id_search` int(11) NOT NULL auto_increment,
    `id_module` int(11) NOT NULL default '0',
    `last_search_use` timestamp NOT NULL,
    PRIMARY KEY (`id`,`id_module`),
    INDEX `last_search_use` (`last_search_use`),
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_search_results`;
CREATE TABLE `phpboost_search_results` (
    `id_search` int(11) NOT NULL auto_increment,
    `id_module` int(11) NOT NULL default '0',
    `id_module_content` int(11) NOT NULL default '0',
    `pertinence` decimal NOT NULL default '0',
    `link` varchar(255) NOT NULL default '',
    PRIMARY KEY (`id_search`,`id_module`,`id_module_content`),
    FOREIGN KEY (`id_search`,`id_module`),
) ENGINE=MyISAM;
