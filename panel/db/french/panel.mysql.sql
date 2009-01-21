-- @package     Panel
-- @author        alain91
-- @copyright   (c) 2008-2009 Alain Gandon
-- @license        GPL
--

DELETE FROM `phpboost_configs` WHERE `name` = 'panel';
INSERT INTO `phpboost_configs` (`name`, `value`) VALUES
('panel', '');
