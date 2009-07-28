ALTER TABLE `phpboost_download` ADD `short_contents` text NOT NULL AFTER `title` ;
ALTER TABLE `phpboost_download` ADD `image` varchar(255) NOT NULL DEFAULT '' AFTER `url` ;
ALTER TABLE `phpboost_download` ADD `release_timestamp` int(11) NOT NULL AFTER `timestamp` ;
ALTER TABLE `phpboost_download` ADD `approved` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `visible` ;
ALTER TABLE `phpboost_download` CHANGE `compt` `count`  int(11) NOT NULL DEFAULT '0';
ALTER TABLE `phpboost_download` CHANGE `note` `note` float NOT NULL DEFAULT '0';
ALTER TABLE `phpboost_download` CHANGE `contents` `contents` text;
ALTER TABLE `phpboost_download` CHANGE `short_contents` `short_contents` text;
ALTER TABLE `phpboost_download` CHANGE `url` `url` text;
ALTER TABLE `phpboost_download` CHANGE `users_note` `users_note` text;
ALTER TABLE `phpboost_download` ADD `force_download` tinyint(1) NOT NULL AFTER `lock_com` ;
ALTER TABLE `phpboost_download` ADD FULLTEXT (`title`);
ALTER TABLE `phpboost_download` ADD FULLTEXT (`contents`);
ALTER TABLE `phpboost_download` ADD FULLTEXT (`short_contents`);


ALTER TABLE `phpboost_download_cat` CHANGE `class` `c_order` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `phpboost_download_cat` CHANGE `contents` `contents` text;
ALTER TABLE `phpboost_download_cat` ADD `id_parent` int(11) NOT NULL DEFAULT '0' AFTER `c_order` ;
ALTER TABLE `phpboost_download_cat` CHANGE `aprob` `visible` tinyint(1) NOT NULL DEFAULT '1';
ALTER TABLE `phpboost_download_cat` DROP `secure`;
ALTER TABLE `phpboost_download_cat` ADD `auth` text AFTER `visible` ;
ALTER TABLE `phpboost_download_cat` ADD `num_files` int(11) NOT NULL DEFAULT '0' AFTER `auth` ;
