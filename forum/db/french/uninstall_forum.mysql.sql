DROP TABLE IF EXISTS `phpboost_forum_alerts`;
DROP TABLE IF EXISTS `phpboost_forum_cats`;
DROP TABLE IF EXISTS `phpboost_forum_history`;
DROP TABLE IF EXISTS `phpboost_forum_msg`;
DROP TABLE IF EXISTS `phpboost_forum_poll`;
DROP TABLE IF EXISTS `phpboost_forum_topics`;
DROP TABLE IF EXISTS `phpboost_forum_track`;
DROP TABLE IF EXISTS `phpboost_forum_view`;

DELETE FROM `phpboost_member_extend_cat` WHERE name = 'last_view_forum';
ALTER TABLE `phpboost_member_extend` DROP `last_view_forum`;
