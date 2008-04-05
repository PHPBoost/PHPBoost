-- DROP TABLE IF EXISTS "phpboost_com";
CREATE TABLE "phpboost_com" (
	"idcom" INT AUTOINCREMENT(11) NOT NULL,
	"idprov" INT(11) NOT NULL default '0',
	"login" VARCHAR(255) NOT NULL default '',
	"user_id" INT(11) NOT NULL default '0',
	"contents" TEXT NOT NULL,
	"timestamp" INT(11) NOT NULL default '0',
	"script" VARCHAR(20) NOT NULL default '',
	PRIMARY KEY	("idcom")
);


-- DROP TABLE IF EXISTS "phpboost_compteur";
CREATE TABLE "phpboost_compteur" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"ip" VARCHAR(50) NOT NULL default '',
	"time" DATE NOT NULL default '0000-00-00',
	"total" INT(11) NOT NULL default '0',
	PRIMARY KEY	("id")
);

INSERT INTO "phpboost_compteur" ("id", "ip", "time", "total") VALUES (1, '', datetime('now', 'localtime'), 1);

-- DROP TABLE IF EXISTS "phpboost_configs";
CREATE TABLE "phpboost_configs" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"name" VARCHAR(150) NOT NULL default '',
	"value" TEXT NOT NULL,
	PRIMARY KEY	("id")
);

INSERT INTO "phpboost_configs" ("id", "name", "value") VALUES
(1, 'config', 'a:28:{s:11:"server_name";s:0:"";s:11:"server_path";s:0:"";s:9:"site_name";s:0:"";s:9:"site_desc";s:0:"";s:12:"site_keyword";s:0:"";s:5:"start";s:10:"0";s:7:"version";s:3:"2.0";s:4:"lang";s:6:"french";s:5:"theme";s:4:"main";s:10:"start_page";s:14:"/news/news.php";s:8:"maINTain";s:1:"0";s:14:"maINTain_delay";s:1:"1";s:13:"maINTain_TEXT";s:0:"";s:7:"rewrite";i:0;s:9:"com_popup";s:1:"0";s:8:"compteur";s:1:"0";s:5:"bench";s:1:"1";s:12:"ob_gzhandler";i:0;s:11:"site_cookie";s:7:"session";s:12:"site_session";i:3600;s:18:"site_session_invit";i:300;s:4:"mail";s:0:"";s:10:"activ_mail";s:1:"0";s:4:"sign";s:0:"";s:10:"anti_flood";s:1:"1";s:11:"delay_flood";s:1:"7";s:12:"unlock_admin";s:0:"";s:6:"pm_max";s:2:"50";}'),
(2, 'member', 'a:13:{s:14:"activ_register";i:1;s:7:"msg_mbr";s:0:"";s:12:"msg_register";s:0:"";s:9:"activ_mbr";i:0;s:10:"verif_code";i:1;s:17:"delay_unactiv_max";i:30;s:11:"force_theme";i:0;s:15:"activ_up_avatar";i:1;s:9:"width_max";i:120;s:10:"height_max";i:120;s:10:"weight_max";i:20;s:12:"activ_avatar";i:1;s:10:"avatar_url";s:13:"no_avatar.jpg";}'),
(3, 'files', 'a:3:{s:10:"size_limit";d:512;s:17:"bandwidth_protect";s:1:"1";s:10:"auth_files";s:45:"a:3:{s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}";}'),
(4, 'com', 'a:4:{s:8:"com_auth";i:-1;s:7:"com_max";i:10;s:14:"forbidden_tags";s:99:"a:6:{i:0;s:3:"swf";i:1;s:5:"movie";i:2;s:5:"sound";i:3;s:4:"code";i:4;s:4:"math";i:5;s:6:"anchor";}";s:8:"max_link";i:2;}');


-- DROP TABLE IF EXISTS "phpboost_group";
CREATE TABLE "phpboost_group" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"name" VARCHAR(100) NOT NULL default '',
	"img" VARCHAR(255) NOT NULL default '',
	"auth" SMALLINT(6) NOT NULL default '0',
	"members" TEXT NOT NULL,
	PRIMARY KEY	("id")
);


-- DROP TABLE IF EXISTS "phpboost_lang";
CREATE TABLE "phpboost_lang" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"lang" VARCHAR(150) NOT NULL default '',
	"activ" TINYINT(1) NOT NULL default '0',
	"secure" TINYINT(1) NOT NULL default '0',
	PRIMARY KEY	("id")
);


-- DROP TABLE IF EXISTS "phpboost_member";
CREATE TABLE "phpboost_member" (
	"user_id" INT AUTOINCREMENT(11) NOT NULL,
	"login" VARCHAR(255) NOT NULL default '',
	"password" VARCHAR(50) NOT NULL default '',
	"level" TINYINT(1) NOT NULL default '0',
	"user_groups" TEXT NOT NULL,
	"user_lang" VARCHAR(25) NOT NULL default '',
	"user_theme" VARCHAR(50) NOT NULL default '',
	"user_mail" VARCHAR(50) NOT NULL default '',
	"user_show_mail" TINYINT(1) NOT NULL default '1',
	"timestamp" INT(11) NOT NULL default '0',
	"user_avatar" VARCHAR(255) NOT NULL default '',
	"user_msg" MEDIUMINT(9) NOT NULL default '0',
	"user_local" VARCHAR(50) NOT NULL default '',
	"user_msn" VARCHAR(50) NOT NULL default '',
	"user_yahoo" VARCHAR(50) NOT NULL default '',
	"user_web" VARCHAR(70) NOT NULL default '',
	"user_occupation" VARCHAR(50) NOT NULL default '',
	"user_hobbies" VARCHAR(50) NOT NULL default '',
	"user_desc" TEXT NOT NULL,
	"user_sex" TINYINT(1) NOT NULL default '0',
	"user_born" DATE NOT NULL default '0000-00-00',
	"user_sign" TEXT NOT NULL,
	"user_pm" SMALLINT(6) unsigned NOT NULL default '0',
	"user_warning" SMALLINT(6) NOT NULL default '0',
	"user_readonly" INT(11) NOT NULL default '0',
	"last_connect" INT(11) NOT NULL default '0',
	"test_connect" TINYINT(4) NOT NULL default '0',
	"activ_pass" VARCHAR(30) NOT NULL default '0',
	"new_pass" VARCHAR(50) NOT NULL default '',
	"user_ban" INT(11) NOT NULL default '0',
	"user_aprob" TINYINT(1) NOT NULL default '0',
	PRIMARY KEY	("user_id")
);

INSERT INTO "phpboost_member" (login, level, user_aprob) VALUES ('login', 2, 1);


-- DROP TABLE IF EXISTS "phpboost_member_extend";
CREATE TABLE "phpboost_member_extend" (
	"user_id" INT AUTOINCREMENT(11) NOT NULL,
	PRIMARY KEY ("user_id")
);


-- DROP TABLE IF EXISTS "phpboost_member_extend_cat";
CREATE TABLE "phpboost_member_extend_cat" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"class" INT(11) NOT NULL default '0',
	"name" VARCHAR(255) NOT NULL default '',
	"field_name" VARCHAR(255) NOT NULL default '',
	"contents" TEXT NOT NULL,
	"field" TINYINT(1) NOT NULL default '0',
	"possible_values" TEXT NOT NULL,
	"default_values" TEXT NOT NULL,
	"require" TINYINT(1) NOT NULL default '0',
	"display" TINYINT(1) NOT NULL default '0',
	"regex" VARCHAR(255) NOT NULL default '',
	UNIQUE KEY "id" ("id")
);


-- DROP TABLE IF EXISTS "phpboost_modules";
CREATE TABLE "phpboost_modules" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"name" VARCHAR(150) NOT NULL default '',
	"version" VARCHAR(15) NOT NULL default '',
	"auth" TEXT NOT NULL,
	"activ" TINYINT(1) NOT NULL default '0',
	PRIMARY KEY	("id")
);


-- DROP TABLE IF EXISTS "phpboost_modules_mini";
CREATE TABLE "phpboost_modules_mini" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"class" INT(11) NOT NULL default '0',
	"name" VARCHAR(150) NOT NULL default '',
	"code" TEXT NOT NULL,
	"contents" TEXT NOT NULL,
	"side" TINYINT(1) NOT NULL default '0',
	"secure" TINYINT(1) NOT NULL default '0',
	"activ" TINYINT(1) NOT NULL default '0',
	"added" TINYINT(1) NOT NULL default '0',
	PRIMARY KEY	("id")
);

INSERT INTO "phpboost_modules_mini" ("class", "name", "code", "contents", "side", "secure", "activ", "added") VALUES (2, 'connexion', 'if( SCRIPT != DIR . ''/membre/error.php'')include_once(''../includes/connect.php'');', '', 0, -1, 1, 0);
INSERT INTO "phpboost_modules_mini" ("name", "code", "contents", "side", "secure", "activ", "added") VALUES ('search', 'include_once(''../search/search_mini.php'');', '', 1, -1, 1, 0);


-- DROP TABLE IF EXISTS "phpboost_pm_msg";
CREATE TABLE "phpboost_pm_msg" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"idconvers" INT(11) NOT NULL default '0',
	"user_id" INT(11) NOT NULL default '0',
	"contents" TEXT NOT NULL,
	"timestamp" INT(11) NOT NULL default '0',
	"view_status" TINYINT(1) NOT NULL default '0',
	PRIMARY KEY	("id")
);


-- DROP TABLE IF EXISTS "phpboost_pm_topic";
CREATE TABLE "phpboost_pm_topic" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"title" VARCHAR(150) NOT NULL default '',
	"user_id" INT(11) NOT NULL default '0',
	"user_id_dest" INT(11) NOT NULL default '0',
	"user_convers_status" TINYINT(1) NOT NULL default '0',
	"user_view_pm" INT(11) NOT NULL default '0',
	"nbr_msg" INT(11) unsigned NOT NULL default '0',
	"last_user_id" INT(11) NOT NULL default '0',
	"last_msg_id" INT(11) NOT NULL default '0',
	"last_timestamp" INT(11) NOT NULL default '0',
	"visible" TINYINT(1) NOT NULL default '0',
	PRIMARY KEY	("id")
);


-- DROP TABLE IF EXISTS "phpboost_ranks";
CREATE TABLE "phpboost_ranks" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"name" VARCHAR(150) NOT NULL default '',
	"msg" INT(11) NOT NULL default '0',
	"icon" VARCHAR(255) NOT NULL default '',
	"special" TINYINT(1) NOT NULL default '0',
	PRIMARY KEY	("id")
);

INSERT INTO "phpboost_ranks" VALUES (1, 'Administrateur', -2, 'rank_admin.gif', 1);
INSERT INTO "phpboost_ranks" VALUES (2, 'Mod&eacute;rateur', -1, 'rank_modo.gif', 1);


-- DROP TABLE IF EXISTS "phpboost_sessions";
CREATE TABLE "phpboost_sessions" (
	"session_id" VARCHAR(50) NOT NULL default '',
	"user_id" INT(11) NOT NULL default '0',
	"level" TINYINT(1) NOT NULL default '0',
	"session_ip" VARCHAR(50) NOT NULL default '',
	"session_time" INT(11) NOT NULL default '0',
	"session_script" VARCHAR(100) NOT NULL default '0',
	"session_script_get" VARCHAR(100) NOT NULL default '0',
	"session_script_title" VARCHAR(255) NOT NULL default '',
	"session_flag" TINYINT(1) NOT NULL default '0',
	PRIMARY KEY	("session_id")
);


-- DROP TABLE IF EXISTS "phpboost_stats";
CREATE TABLE "phpboost_stats" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"stats_year" SMALLINT(6) NOT NULL default '0',
	"stats_month" TINYINT(4) NOT NULL default '0',
	"stats_day" TINYINT(4) NOT NULL default '0',
	"nbr" MEDIUMINT(9) NOT NULL default '0',
	PRIMARY KEY	("id")
);


-- DROP TABLE IF EXISTS "phpboost_themes";
CREATE TABLE "phpboost_themes" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"theme" VARCHAR(50) NOT NULL default '',
	"activ" TINYINT(1) NOT NULL default '0',
	"secure" TINYINT(1) NOT NULL default '0',
	PRIMARY KEY	("id")
);


-- DROP TABLE IF EXISTS "phpboost_upload";
CREATE TABLE "phpboost_upload" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"idcat" INT(11) NOT NULL default '0',
	"name" VARCHAR(150) NOT NULL default '',
	"path" VARCHAR(255) NOT NULL default '',
	"user_id" INT(11) NOT NULL default '0',
	"size" float NOT NULL default '0',
	"type" VARCHAR(10) NOT NULL default '',
	"timestamp" INT(11) NOT NULL default '0',
	PRIMARY KEY	("id")
);


-- DROP TABLE IF EXISTS "phpboost_upload_cat";
CREATE TABLE "phpboost_upload_cat" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"id_parent" INT(11) NOT NULL default '0',
	"user_id" INT(11) NOT NULL default '0',
	"name" VARCHAR(150) NOT NULL default '',
	PRIMARY KEY	("id")
);


-- DROP TABLE IF EXISTS "phpboost_verif_code";
CREATE TABLE "phpboost_verif_code" (
	"id" INT AUTOINCREMENT(11) NOT NULL,
	"user_id" VARCHAR(8) NOT NULL default '',
	"code" VARCHAR(20) NOT NULL default '',
	"timestamp" INT(11) NOT NULL default '0',
	PRIMARY KEY	("id")
);


-- DROP TABLE IF EXISTS "phpboost_smileys";
CREATE TABLE "phpboost_smileys" (
	"idsmiley" INT AUTOINCREMENT(11) NOT NULL,
	"code_smiley" VARCHAR(50) NOT NULL default '',
	"url_smiley" VARCHAR(50) NOT NULL default '',
	PRIMARY KEY	("idsmiley")
);

INSERT INTO "phpboost_smileys" ("idsmiley", "code_smiley", "url_smiley") VALUES
(1, ':|', 'waw.gif'),
(2, ':siffle', 'siffle.gif'),
(3, ':)', 'sourire.gif'),
(4, ':lol', 'rire.gif'),
(5, ':p', 'tirelangue.gif'),
(6, ':(', 'malheureux.gif'),
(7, ';)', 'clindoeil.gif'),
(8, ':heink', 'heink.gif'),
(9, ':D', 'heureux.gif'),
(10, ':d', 'content.gif'),
(11, ':s', 'incertain.gif'),
(12, ':gne', 'pinch.gif'),
(13, ':top', 'top.gif'),
(14, ':clap', 'clap.gif'),
(15, ':hehe', 'hehe.gif'),
(16, ':@', 'angry.gif'),
(17, ':''(', 'snif.gif'),
(18, ':nex', 'nex.gif'),
(19, '8-)', 'star.gif'),
(20, '|-)', 'nuit.gif'),
(21, ':berk', 'berk.gif'),
(22, ':gre', 'colere.gif'),
(23, ':love', 'love.gif'),
(24, ':hum', 'doute.gif'),
(25, ':mat', 'mat.gif'),
(26, ':miam', 'miam.gif'),
(27, ':+1', 'plus1.gif'),
(28, ':lu', 'lu.gif');

-- 
-- Structure de la table "phpboost_search_index"
-- 

-- DROP TABLE IF EXISTS "phpboost_search_index";
CREATE TABLE "phpboost_search_index" (
    "id_search"         INT AUTOINCREMENT(11)         NOT NULL,
    "id_user"           INT(11)         NOT NULL default '0',
    "module"            VARCHAR(128)    NOT NULL default '0',
    "search"            VARCHAR(255)    NOT NULL default '',
    "options"           VARCHAR(255)    NOT NULL default '',
    "last_search_use"   timestamp       NOT NULL,
    "times_used"        INT(3)          NOT NULL default '0',
    UNIQUE ("id_user", "module", "search", "options"),
    PRIMARY KEY ("id_search"),
    INDEX "last_search_use" ("last_search_use")
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table "phpboost_search_index"
-- 

-- 
-- Structure de la table "phpboost_search_results"
-- 

-- DROP TABLE IF EXISTS "phpboost_search_results";
CREATE TABLE "phpboost_search_results" (
    "id_search"         INT AUTOINCREMENT(11)         NOT NULL,
    "id_content"        INT(11)         NOT NULL default '0',
    "title"             VARCHAR(255)    NOT NULL default '',
    "relevance"         decimal(5,2)    NOT NULL,
    "link"              VARCHAR(255)    NOT NULL default '',
    PRIMARY KEY ("id_search","id_content"),
    INDEX ("relevance")
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table "phpboost_search_results"
-- 