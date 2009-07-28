ALTER TABLE `phpboost_articles` CHANGE `contents` `contents` MEDIUMTEXT NOT NULL;
ALTER TABLE `phpboost_articles` CHANGE `note` `note` float NOT NULL DEFAULT '0';
ALTER TABLE `phpboost_articles` ADD FULLTEXT (`title`);
ALTER TABLE `phpboost_articles` ADD FULLTEXT (`contents`);
ALTER TABLE `phpboost_articles` CHANGE `users_note` `users_note` text;

ALTER TABLE `phpboost_articles_cats` CHANGE `contents` `contents` text;
ALTER TABLE `phpboost_articles_cats` CHANGE `auth` `auth` text;
