-- @package     Bugstracker
-- @author        alain91
-- @copyright   (c) 2008-2009 Alain Gandon
-- @license        GPL
--

-- 
-- Structure de la table `phpboost_bugstracker`
-- 

DROP TABLE IF EXISTS `phpboost_bugstracker`;
CREATE TABLE `phpboost_bugstracker` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `contents` text NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `submitted_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` int(11) NOT NULL default '0',
  `severity` int(11) NOT NULL default '0',
  `component_orig` int(11) NOT NULL default '0',
  `component` int(11) NOT NULL default '0',
  `assigned_to_id` int(11) NOT NULL default '0',
  `updated_by_id` int(11) NOT NULL default '0',
  `updated_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `target` int(11) NOT NULL default '0',
  `fixed_in` int(11) NOT NULL default '0',
  `last_histo_id` int(11) NOT NULL default '0',
  `nbr_com` int(11) NOT NULL default '0',
  `lock_com` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `phpboost_bugstracker`
-- 

INSERT INTO `phpboost_bugstracker` (`id`, `title`, `contents`, `submitted_date`) VALUES 
(1, 'Premier bug pour test', 'Contenu premier bug pour test', NOW());

-- 
-- Structure de la table `phpboost_bugstracker_history`
-- 

DROP TABLE IF EXISTS `phpboost_bugstracker_history`;
CREATE TABLE `phpboost_bugstracker_history` (
  `id` int(11) NOT NULL auto_increment,
  `bug_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `updated_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_field` varchar(64) default '',
  `old_value` varchar(255) default '',
  `new_value`varchar(255) default '',
  PRIMARY KEY  (`id`),
  KEY `bug_id` (`bug_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `phpboost_bugstracker_history`
-- 


-- 
-- Structure de la table `phpboost_bugstracker_parameters`
-- 

DROP TABLE IF EXISTS `phpboost_bugstracker_parameters`;
CREATE TABLE `phpboost_bugstracker_parameters` (
  `id` int(11) NOT NULL auto_increment,
  `weight` int(11) NOT NULL default '0',
  `nature` int(11) NOT NULL default '0',
  `label` varchar(128) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `phpboost_bugstracker_parameters`
-- 

INSERT INTO `phpboost_bugstracker_parameters` (`id`, `nature`, `weight`, `label`) VALUES 
( 1, 10, 10, 'Mineure'),
( 2, 10, 20, 'Majeure'),
( 3, 10, 30, 'Bloquant'),

( 4, 20, 10, 'Re-ouvert'),
( 5, 20, 20, 'Vérification'),
( 6, 20, 30, 'Traitement'),
( 7, 20, 40, 'Mis en attente'),
( 8, 20, 50, 'Corrigé interne'),
( 9, 20, 60, 'Corrigé'),
(10, 20, 70, 'Abandonné'),

(11, 30, 10, 'PHPBoost-core - 3a8'),
(12, 30, 20, 'articles'),
(13, 30, 30, 'calendar'),
(14, 30, 30, 'contact'),
(15, 30, 30, 'download'),
(16, 30, 30, 'forum'),
(17, 30, 30, 'gallery'),
(18, 30, 30, 'guestbook'),
(19, 30, 30, 'links'),
(20, 30, 30, 'news'),
(21, 30, 30, 'newsletter'),
(22, 30, 30, 'online'),
(23, 30, 30, 'pages'),
(24, 30, 30, 'poll'),
(25, 30, 30, 'shoutbox'),
(26, 30, 30, 'stats'),
(27, 30, 30, 'web'),
(28, 30, 30, 'wiki'),
(29, 30, 40, 'bugstracker'),

(30, 40, 10, 'PHPBoost-core - 3a9'),
(31, 40, 20, 'articles x'),
(32, 40, 30, 'calendar x'),
(33, 40, 30, 'contact x'),
(34, 40, 30, 'download x'),
(35, 40, 30, 'forum x'),
(36, 40, 30, 'gallery x'),
(37, 40, 30, 'guestbook x'),
(38, 40, 30, 'links x'),
(39, 40, 30, 'news x'),
(40, 40, 30, 'newsletter x'),
(41, 40, 30, 'online x'),
(42, 40, 30, 'pages x'),
(43, 40, 30, 'poll x'),
(44, 40, 30, 'shoutbox x'),
(45, 40, 30, 'stats x'),
(46, 40, 30, 'web x'),
(47, 40, 30, 'wiki x'),
(48, 40, 40, 'bugstracker x');

DELETE FROM `phpboost_configs` WHERE `name` = 'bugstracker';
INSERT INTO `phpboost_configs` (`name`, `value`) VALUES
('bugstracker', '');
