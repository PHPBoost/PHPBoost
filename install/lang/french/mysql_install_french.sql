INSERT INTO `phpboost_visit_counter` (`id`, `ip`, `time`, `total`) VALUES (1, '', NOW(), 1);

INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES 
(1, 'config', ''),
(2, 'member', 'a:14:{s:14:"activ_register";i:1;s:7:"msg_mbr";s:0:"";s:12:"msg_register";s:0:"";s:9:"activ_mbr";i:0;s:10:"verif_code";i:0;s:21:"verif_code_difficulty";i:2;s:17:"delay_unactiv_max";i:20;s:11:"force_theme";i:1;s:15:"activ_up_avatar";i:0;s:9:"width_max";i:120;s:10:"height_max";i:120;s:10:"weight_max";i:20;s:12:"activ_avatar";i:0;s:10:"avatar_url";s:0:"";}'),
(3, 'uploads', 'a:3:{s:10:"size_limit";d:512;s:17:"bandwidth_protect";s:1:"1";s:10:"auth_files";s:45:"a:3:{s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:1;}";}'),
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
(42, ':lu', 'lu.gif');
