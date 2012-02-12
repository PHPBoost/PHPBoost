DROP TABLE IF EXISTS `phpboost_guestbook`;
CREATE TABLE `phpboost_guestbook` (
  `id` int(11) NOT NULL auto_increment,
  `contents` text,
  `login` varchar(255) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM;