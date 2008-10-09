INSERT INTO `phpboost_compteur` (`id`, `ip`, `time`, `total`) VALUES (1, '', NOW(), 1);

INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES 
(1, 'config', 'a:27:{s:11:"server_name";s:0:"";s:11:"server_path";s:0:"";s:9:"site_name";s:0:"";s:9:"site_desc";s:0:"";s:12:"site_keyword";s:0:"";s:5:"start";s:10:"0";s:7:"version";s:3:"2.1";s:4:"lang";s:6:"french";s:5:"theme";s:4:"main";s:10:"start_page";s:20:"/members/members.php";s:8:"maintain";s:1:"0";s:14:"maintain_delay";s:1:"1";s:13:"maintain_text";s:0:"";s:7:"rewrite";i:0;s:9:"com_popup";s:1:"0";s:8:"compteur";s:1:"0";s:12:"ob_gzhandler";i:0;s:11:"site_cookie";s:7:"session";s:12:"site_session";i:3600;s:18:"site_session_invit";i:300;s:4:"mail";s:0:"";s:10:"activ_mail";s:1:"0";s:4:"sign";s:0:"";s:10:"anti_flood";s:1:"1";s:11:"delay_flood";s:1:"7";s:12:"unlock_admin";s:0:"";s:6:"pm_max";s:2:"50";}'),
(2, 'member', 'a:13:{s:14:"activ_register";i:1;s:7:"msg_mbr";s:0:"";s:12:"msg_register";s:0:"";s:9:"activ_mbr";i:0;s:10:"verif_code";i:1;s:17:"delay_unactiv_max";i:30;s:11:"force_theme";i:0;s:15:"activ_up_avatar";i:1;s:9:"width_max";i:120;s:10:"height_max";i:120;s:10:"weight_max";i:20;s:12:"activ_avatar";i:1;s:10:"avatar_url";s:13:"no_avatar.jpg";}'),
(3, 'files', 'a:3:{s:10:"size_limit";d:512;s:17:"bandwidth_protect";s:1:"1";s:10:"auth_files";s:45:"a:3:{s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}";}'),
(4, 'com', 'a:4:{s:8:"com_auth";i:-1;s:7:"com_max";i:10;s:14:"forbidden_tags";s:99:"a:6:{i:0;s:3:"swf";i:1;s:5:"movie";i:2;s:5:"sound";i:3;s:4:"code";i:4;s:4:"math";i:5;s:6:"anchor";}";s:8:"max_link";i:2;}');

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
