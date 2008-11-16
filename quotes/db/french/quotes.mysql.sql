DROP TABLE IF EXISTS `phpboost_quotes`;

-- 
-- Structure de la table `phpboost_quotes`
-- 

CREATE TABLE `phpboost_quotes` (
  `id` int(11) NOT NULL auto_increment,
  `contents` varchar(255) NOT NULL,
  `author` varchar(63) NOT NULL,
  `user_id` int(11) NOT NULL default '0',
  `status` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `phpboost_quotes`
-- 

INSERT INTO `phpboost_quotes` (`id`, `contents`, `author`, `user_id`, `status`, `timestamp`) VALUES 
(1, 'Rien de sert de courir, il faut partir à point.<br />\r\nLe lièvre et la tortue en sont un témoignage.<br />\r\nGageons dit celle-ci que vous n''atteindrez pas sitôt que moi ce but.', 'Jean de la Fontaine', 1, 0, 1225988965);
