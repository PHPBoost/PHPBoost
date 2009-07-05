ALTER TABLE `phpboost_articles` CHANGE `contents` `contents` MEDIUMTEXT NOT NULL;
ALTER TABLE `phpboost_articles` CHANGE `note` `note` float NOT NULL DEFAULT '0';
ALTER TABLE `phpboost_articles` CHANGE `activ_com` `lock_com` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `phpboost_articles` ADD FULLTEXT (`title`);
ALTER TABLE `phpboost_articles` ADD FULLTEXT (`contents`);