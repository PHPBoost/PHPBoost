INSERT INTO `phpboost_compteur` (`id`, `ip`, `time`, `total`) VALUES (1, '', NOW(), 1);

INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES 
(1, 'config', 'a:37:{s:11:"server_name";s:19:"http://www.site.com";s:11:"server_path";s:0:"";s:9:"site_name";s:11:"Nom du site";s:9:"site_desc";s:19:"Description du site";s:12:"site_keyword";s:20:"site, cms, mots clés";s:5:"start";i:1118967619;s:7:"version";s:3:"2.1";s:4:"lang";s:6:"french";s:5:"theme";s:8:"phpboost";s:6:"editor";s:6:"bbcode";s:8:"timezone";i:1;s:10:"start_page";s:10:"/index.php";s:8:"maintain";s:1:"0";s:14:"maintain_delay";i:1;s:22:"maintain_display_admin";i:1;s:13:"maintain_text";s:40:"Le site est actuellement en maintenance.";s:7:"rewrite";i:0;s:23:"htaccess_manual_content";s:0:"";s:9:"com_popup";i:0;s:8:"compteur";i:0;s:5:"bench";i:1;s:12:"theme_author";i:0;s:12:"ob_gzhandler";i:0;s:11:"site_cookie";s:7:"session";s:12:"site_session";i:3600;s:18:"site_session_invit";i:300;s:4:"mail";s:23:"administrateur@site.com";s:10:"activ_mail";i:0;s:4:"sign";s:31:"Cordialement, l''équipe du site.";s:10:"anti_flood";i:0;s:11:"delay_flood";i:7;s:12:"unlock_admin";s:0:"";s:6:"pm_max";i:50;s:17:"search_cache_time";i:30;s:14:"search_max_use";i:100;s:9:"html_auth";a:1:{s:2:"r2";i:1;}s:14:"forbidden_tags";a:0:{}}'),
(2, 'member', 'a:14:{s:14:"activ_register";i:1;s:7:"msg_mbr";s:169:"Bienvenue sur le site. Vous êtes membre du site, vous pouvez accéder à tous les espaces nécessitant un compte utilisateur, éditer votre profil et voir vos contributions.";s:12:"msg_register";s:158:"Vous vous apprêtez à vous enregistrer sur le site. Nous vous demandons d''être poli et courtois dans vos interventions.<br />
<br />
Merci, l''équipe du site.";s:9:"activ_mbr";i:0;s:10:"verif_code";i:0;s:21:"verif_code_difficulty";i:1;s:17:"delay_unactiv_max";i:20;s:11:"force_theme";i:1;s:15:"activ_up_avatar";i:0;s:9:"width_max";i:120;s:10:"height_max";i:120;s:10:"weight_max";i:20;s:12:"activ_avatar";i:0;s:10:"avatar_url";s:0:"";}'),
(3, 'files', 'a:3:{s:10:"size_limit";d:512;s:17:"bandwidth_protect";s:1:"1";s:10:"auth_files";s:45:"a:3:{s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}";}'),
(4, 'com', 'a:4:{s:8:"com_auth";i:-1;s:7:"com_max";i:10;s:14:"forbidden_tags";a:0:{}s:8:"max_link";i:2;}');

INSERT INTO `phpboost_member` (login, level, user_aprob) VALUES ('login', 2, 1);

INSERT INTO `phpboost_ranks` VALUES (1, 'Administrateur', -2, 'rank_admin.gif', 1);
INSERT INTO `phpboost_ranks` VALUES (2, 'Modérateur', -1, 'rank_modo.gif', 1);
INSERT INTO `phpboost_ranks` VALUES (3, 'Boosteur Inactif', 0, 'rank_0.gif', 0);
INSERT INTO `phpboost_ranks` VALUES (4, 'Booster Fronde', 1, 'rank_1.gif', 0);
INSERT INTO `phpboost_ranks` VALUES (5, 'Booster Minigun', 25, 'rank_1.gif', 0);
INSERT INTO `phpboost_ranks` VALUES (6, 'Booster Fuzil', 50, 'rank_2.gif', 0);
INSERT INTO `phpboost_ranks` VALUES (7, 'Booster Bazooka', 100, 'rank_2.gif', 0);
INSERT INTO `phpboost_ranks` VALUES (8, 'Booster Roquette', 250, 'rank_3.gif', 0);
INSERT INTO `phpboost_ranks` VALUES (9, 'Booster Mortier', 500, 'rank_3.gif', 0);
INSERT INTO `phpboost_ranks` VALUES (10, 'Booster Missile', 1000, 'rank_4.gif', 0);
INSERT INTO `phpboost_ranks` VALUES (11, 'Booster Fusée', 1500, 'rank_5.gif', 0);

INSERT INTO `phpboost_smileys` (`idsmiley`, `code_smiley`, `url_smiley`) VALUES 
(9, ':|', 'waw.gif'),
(5, ':siffle', 'siffle.gif'),
(18, ':)', 'sourire.gif'),
(43, ':lol', 'rire.gif'),
(10, ':p', 'tirelangue.gif'),
(11, ':(', 'malheureux.gif'),
(44, ';)', 'clindoeil.gif'),
(22, ':heink', 'heink.gif'),
(14, ':D', 'heureux.gif'),
(15, ':d', 'content.gif'),
(16, ':s', 'incertain.gif'),
(19, ':gne', 'pinch.gif'),
(21, ':top', 'top.gif'),
(24, ':clap', 'clap.gif'),
(26, ':hehe', 'hehe.gif'),
(27, ':@', 'angry.gif'),
(29, ':''(', 'snif.gif'),
(30, ':nex', 'nex.gif'),
(34, '8-)', 'star.gif'),
(32, '|-)', 'nuit.gif'),
(35, ':berk', 'berk.gif'),
(36, ':gre', 'colere.gif'),
(37, ':love', 'love.gif'),
(38, ':hum', 'doute.gif'),
(39, ':mat', 'mat.gif'),
(40, ':miam', 'miam.gif'),
(41, ':+1', 'plus1.gif'),
(42, ':lu', 'lu.gif'),
(45, ':boulet', 'boulet-repere.gif');
