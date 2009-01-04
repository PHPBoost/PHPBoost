-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Lundi 24 Novembre 2008 à 22:32
-- Version du serveur: 4.1.9
-- Version de PHP: 4.3.10
-- 
-- Base de données: `phpboost`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `phpboost_quotes`
-- 
DROP TABLE `phpboost_quotes` 

CREATE TABLE `phpboost_quotes` (
  `id` int(11) NOT NULL auto_increment,
  `contents` varchar(255) NOT NULL default '',
  `author` varchar(63) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `status` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `timestamp` (`timestamp`),
  FULLTEXT KEY `contents` (`contents`),
  FULLTEXT KEY `author` (`author`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- 
-- Contenu de la table `phpboost_quotes`
-- 

INSERT INTO `phpboost_quotes` VALUES (1, 'Rien de sert de courir, il faut partir à point.<br />\r\nLe lièvre et la tortue en sont un témoignage.<br />\r\nGageons dit celle-ci que vous n''atteindrez pas sitôt que moi ce but.', 'Jean de la Fontaine', 1, 0, 1225988965);
INSERT INTO `phpboost_quotes` VALUES (2, 'La cigale ayant chanté tout l''été<br />\r\nse trouva fort dépourvue<br />\r\nquand la bise fut venue.', 'Jean de la Fontaine', 1, 0, 1226870661);
INSERT INTO `phpboost_quotes` VALUES (3, 'Un agneau se désaltérait dans le courant d''une onde pure,<br />\r\nun loup survint à jeun qui cherchait aventure.', 'Jean de la Fontaine', 1, 0, 1227376702);
INSERT INTO `phpboost_quotes` VALUES (4, 'Je suis venu, j''ai vu et j''ai vaincu', 'Jules César', 1, 0, 1227376747);
INSERT INTO `phpboost_quotes` VALUES (5, 'Je vous promets du sang et des larmes', 'Winston Churchill', 1, 0, 1227376771);
INSERT INTO `phpboost_quotes` VALUES (6, 'Une citation de membre', 'Moi même', 2, 0, 1227560628);