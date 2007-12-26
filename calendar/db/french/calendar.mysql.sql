DROP TABLE IF EXISTS `phpboost_calendar`;
CREATE TABLE `phpboost_calendar` (
	`id` int(11) NOT NULL auto_increment,
	`timestamp` int(11) NOT NULL default '0',
	`title` varchar(150) NOT NULL default '',
	`contents` text NOT NULL,
	`user_id` int(11) NOT NULL default '0',
	`nbr_com` int(11) unsigned NOT NULL default '0',
	`lock_com` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_configs` (`name`, `value`) VALUES ('calendar', 'a:1:{s:13:"calendar_auth";i:2;}');
