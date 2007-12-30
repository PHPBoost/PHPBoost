DROP TABLE IF EXISTS `phpboost_guestbook`;
CREATE TABLE `phpboost_guestbook` (
	`id` int(11) NOT NULL auto_increment,
	`contents` text NOT NULL,
	`login` varchar(255) NOT NULL default '',
	`user_id` int(11) NOT NULL default '0',
	`timestamp` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_configs` (`name`, `value`) VALUES ('guestbook', 'a:3:{s:14:"guestbook_auth";i:-1;s:24:"guestbook_forbidden_tags";s:52:"a:3:{i:0;s:3:"swf";i:1;s:5:"movie";i:2;s:5:"sound";}";s:18:"guestbook_max_link";i:2;}');
