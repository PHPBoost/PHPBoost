DROP TABLE IF EXISTS `phpboost_newsletter`;
CREATE TABLE `phpboost_newsletter` (
	`id` int(11) NOT NULL auto_increment,
	`mail` varchar(50) NOT NULL default '',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_newsletter_arch`;
CREATE TABLE `phpboost_newsletter_arch` (
	`id` int(11) NOT NULL auto_increment,
	`title` varchar(200) NOT NULL default '',
	`message` text,
	`timestamp` bigint(20) NOT NULL default '0',
	`type` varchar(10) NOT NULL default '',
	`nbr` mediumint(9) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;
