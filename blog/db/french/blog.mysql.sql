DROP TABLE IF EXISTS `phpboost_blog`;
CREATE TABLE `phpboost_blog` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(128) NOT NULL,
  `description` TEXT NOT NULL,
  `user_id` INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT INDEX `title`(`title`),
  FULLTEXT INDEX `description`(`description`),
  FULLTEXT INDEX `title_description`(`description`, `title`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_blog_post`;
CREATE TABLE `phpboost_blog_post` (
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