DROP TABLE IF EXISTS `phpboost_Blog`;
CREATE TABLE `phpboost_Blog` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT INDEX `title`(`title`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_BlogPost`;
CREATE TABLE `phpboost_BlogPost` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(128) NOT NULL,
  `content` MEDIUMTEXT NOT NULL,
  `creation_date` INTEGER UNSIGNED NOT NULL,
  `blog_id` INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_id`(`blog_id`),
  FULLTEXT INDEX `title`(`title`),
  FULLTEXT INDEX `content`(`content`),
  FULLTEXT INDEX `title_content`(`content`, `title`),
  KEY `creation_date`(`creation_date`)
) ENGINE=MyISAM;