DROP TABLE IF EXISTS `phpboost_shoutbox`;
CREATE TABLE `phpboost_shoutbox` (
	`id` int(11) NOT NULL auto_increment,
	`login` varchar(150) NOT NULL default '',
	`user_id` int(11) NOT NULL default '0',
	`contents` text NOT NULL,
	`timestamp` int(11) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_configs` (`name`, `value`) VALUES ('shoutbox', 'a:4:{s:16:"shoutbox_max_msg";i:100;s:13:"shoutbox_auth";i:-1;s:23:"shoutbox_forbidden_tags";s:359:"a:22:{i:0;s:5:"title";i:1;s:6:"stitle";i:2;s:5:"style";i:3;s:3:"url";i:4;s:3:"img";i:5;s:5:"quote";i:6;s:4:"hide";i:7;s:4:"list";i:8;s:5:"color";i:9;s:4:"size";i:10;s:5:"align";i:11;s:5:"float";i:12;s:3:"sup";i:13;s:3:"sub";i:14;s:6:"indent";i:15;s:5:"table";i:16;s:3:"swf";i:17;s:5:"movie";i:18;s:5:"sound";i:19;s:4:"code";i:20;s:4:"math";i:21;s:6:"anchor";}";s:17:"shoutbox_max_link";i:2;}');
INSERT INTO `phpboost_modules_mini` (`name`, `code`, `contents`, `side`, `secure`, `activ`, `added`) VALUES ( 'shoutbox', 'include_once(''../shoutbox/shoutbox_mini.php'');', '', 1, -1, 1, 0);
