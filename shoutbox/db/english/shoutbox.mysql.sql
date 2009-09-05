DROP TABLE IF EXISTS `phpboost_shoutbox`;
CREATE TABLE `phpboost_shoutbox` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(150) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `level` tinyint(1) NOT NULL default '0',
  `contents` text,
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_shoutbox` VALUES (1, 'PHPBoost Team', -1, -1, 'PHPBoost Team welcomes you on your website!', unix_timestamp(current_timestamp));