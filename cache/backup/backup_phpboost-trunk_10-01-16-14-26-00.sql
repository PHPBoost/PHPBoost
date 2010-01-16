DROP TABLE IF EXISTS phpboost_faq;


CREATE TABLE `phpboost_faq` (
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;



