CREATE TABLE IF NOT EXISTS `phpboost_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcat` int(11) NOT NULL DEFAULT '0',
  `q_order` int(11) NOT NULL DEFAULT '0',
  `question` varchar(255) NOT NULL DEFAULT '',
  `answer` text,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `question` (`question`),
  FULLTEXT KEY `answer` (`answer`),
  FULLTEXT KEY `question_2` (`question`,`answer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `phpboost_faq_cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL DEFAULT '0',
  `c_order` int(11) unsigned NOT NULL DEFAULT '0',
  `auth` text,
  `name` varchar(255) NOT NULL DEFAULT '',
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `display_mode` tinyint(2) NOT NULL DEFAULT '0',
  `description` text,
  `image` varchar(255) NOT NULL DEFAULT '',
  `num_questions` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

