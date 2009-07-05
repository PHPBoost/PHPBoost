ALTER TABLE `phpboost_news` CHANGE `activ_com` `lock_com` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `phpboost_news` DROP INDEX `id` , ADD INDEX `idcat` ( `idcat` ); 
ALTER TABLE `phpboost_news` ADD FULLTEXT (`title`);
ALTER TABLE `phpboost_news` ADD FULLTEXT (`contents`);
ALTER TABLE `phpboost_news` ADD FULLTEXT (`extend_contents`);