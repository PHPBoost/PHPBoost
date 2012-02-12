ALTER TABLE `phpboost_news` CHANGE `contents` `contents` mediumtext;
ALTER TABLE `phpboost_news` CHANGE `extend_contents` `extend_contents` mediumtext;
ALTER TABLE `phpboost_news` CHANGE `img` `img` varchar(255) NOT NULL default '';

ALTER TABLE `phpboost_news` ADD FULLTEXT (`title`);
ALTER TABLE `phpboost_news` ADD FULLTEXT (`contents`);
ALTER TABLE `phpboost_news` ADD FULLTEXT (`extend_contents`);

ALTER TABLE `phpboost_news_cat` CHANGE `contents` `contents` text;