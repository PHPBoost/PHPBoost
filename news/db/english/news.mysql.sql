DROP TABLE IF EXISTS `phpboost_news`;
CREATE TABLE `phpboost_news` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `contents` mediumtext,
  `extend_contents` mediumtext,
  `archive` tinyint(1) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '0',
  `start` int(11) NOT NULL default '0',
  `end` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `img` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`),
  FULLTEXT KEY `extend_contents` (`extend_contents`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_news_cat`;
CREATE TABLE `phpboost_news_cat` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(150) NOT NULL default '',
	`contents` text,
	`icon` varchar(255) NOT NULL default '',
	PRIMARY KEY	(`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_news_cat` (`id`, `name`, `contents`, `icon`) VALUES (1, 'Test', 'Test category', 'news.png');
INSERT INTO `phpboost_news` (`id`, `idcat`, `title`, `contents`, `extend_contents`, `timestamp`, `visible`, `start`, `end`, `user_id`, `img`, `alt`, `nbr_com`, `lock_com`) VALUES (1, 1, 'PHPBoost 3', 'Your PHPBoost 3 website is now installed and running. To help you build your website, each module home has a message to guide you through its configuration. We strongly recommand to do the followings : <br />\r\n<br />\r\n<br /><h4 class="stitle1">Delete the install folder</h4><br /><br />\r\nFor security reasons, you must delete the entire /install folder located in the PHPBoost root directory. Otherwise, some people may try to re-install the software and in that case you may lose datas.<br />\r\n<br />\r\n<br /><h4 class="stitle1">Manage your website</h4><br /><br />\r\nAccess the <a href="/admin/admin_index.php">Administration Panel</a> to set up your website as you wish. To do so : <br/>\r\n<br />\r\n<ul class="bb_ul">\r\n	<li class="bb_li"><a href="/admin/admin_maintain.php">Put your website under maintenance</a> and you won''t be disturbed while you''re working on it\r\n	</li><li class="bb_li">Now''s the time to setup the <a href="/admin/admin_config.php">main configurations</a> of the website.\r\n</li><li class="bb_li"><a href="/admin/admin_modules.php">Configure the installed modules</a> and give them access rights (If you have not installed the complete package, all modules are available on the <a href="http://www.phpboost.org/download/download.php">PHPBoost website</a> in the resources section.\r\n	</li><li class="bb_li"><a href="/admin/admin_content_config.php">Choose the default content language formatting</a> of the site.\r\n</li><li class="bb_li"><a href="/admin/admin_members_config.php">Configure the members settings</a>.\r\n	</li><li class="bb_li"><a href="/admin/admin_themes.php">Choose the website style</a> to change the look of your site (You can find more styles on the <a href="http://www.phpboost.org/download/download.php">PHPBoost website</a> in the resources section.\r\n</li><li class="bb_li"><a href="/news/admin_news_config.php">	
Change the editorial</a> of your site.\r\n	</li><li class="bb_li">Before giving back access to your members, take time to add content to your website!</li>\r\n</li><li class="bb_li">Finally, <a href="/admin/admin_maintain.php">put your site online</a> in order to restore access to your site to your visitors.<br />\r\n</li></ul><br />\r\n<br />\r\n<br /><h4 class="stitle1">What should I do if I have problems?</h4><br /><br />\r\nDo not hesitate to consult the <a href="http://www.phpboost.org/wiki/wiki.php">PHPBoost documentation</a> or ask your question on the <a href="http://www.phpboost.org/forum/index.php">forum</a> for assistance. As the English community is still young, we strongly recommend that you use the second solution.<br />\r\n<br />\r\n<br />\r\n<p class="float_right">The PHPBoost Team thanks you for using its software to create your Web site!</p>', '', unix_timestamp(current_timestamp), 1, 0, 0, 1, '/templates/base/theme/images/phpboost3.png', 'PHPBoost 3.0', 0, 0);