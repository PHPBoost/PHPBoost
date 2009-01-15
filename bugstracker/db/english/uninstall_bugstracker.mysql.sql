-- @package     Bugstracker
-- @author        alain91
-- @copyright   (c) 2008-2009 Alain Gandon
-- @license        GPL
--
DROP TABLE IF EXISTS `phpboost_bugstracker`;
DROP TABLE IF EXISTS `phpboost_bugstracker_parameters`;
DROP TABLE IF EXISTS `phpboost_bugstracker_history`;

DELETE FROM `phpboost_configs` WHERE name = 'bugstracker';