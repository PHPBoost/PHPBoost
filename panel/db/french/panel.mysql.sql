--
-- Structure de la table `phpboost_panel`
-- 
DROP TABLE IF EXISTS `phpboost_panel` ;

CREATE TABLE `phpboost_panel` (
  `id` int(11) NOT NULL auto_increment,
  `module_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `cat_id` int(11) NOT NULL default '0',
  `location` int(11) NOT NULL default '0',
  `limit_max` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
