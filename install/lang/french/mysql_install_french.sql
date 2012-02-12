INSERT INTO `phpboost_visit_counter` (`id`, `ip`, `time`, `total`) VALUES (1, '', NOW(), 0);

INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES 
(1, 'config', ''),
(2, 'member', 'a:14:{s:14:"activ_register";i:1;s:7:"msg_mbr";s:169:"Bienvenue sur le site. Vous êtes membre du site, vous pouvez accéder à tous les espaces nécessitant un compte utilisateur, éditer votre profil et voir vos contributions.";s:12:"msg_register";s:156:"Vous vous apprêtez à vous enregistrer sur le site. Nous vous demandons d''être poli et courtois dans vos interventions.<br />
<br />
Merci, l''équipe du site.";s:9:"activ_mbr";i:0;s:10:"verif_code";i:1;s:21:"verif_code_difficulty";i:2;s:17:"delay_unactiv_max";i:20;s:11:"force_theme";i:0;s:15:"activ_up_avatar";i:1;s:9:"width_max";i:120;s:10:"height_max";i:120;s:10:"weight_max";i:20;s:12:"activ_avatar";i:1;s:10:"avatar_url";s:13:"no_avatar.png";}'),
(3, 'uploads', 'a:4:{s:10:"size_limit";d:512;s:17:"bandwidth_protect";i:1;s:15:"auth_extensions";a:48:{i:0;s:3:"jpg";i:1;s:4:"jpeg";i:2;s:3:"bmp";i:3;s:3:"gif";i:4;s:3:"png";i:5;s:3:"tif";i:6;s:3:"svg";i:7;s:3:"ico";i:8;s:3:"rar";i:9;s:3:"zip";i:10;s:2:"gz";i:11;s:3:"txt";i:12;s:3:"doc";i:13;s:4:"docx";i:14;s:3:"pdf";i:15;s:3:"ppt";i:16;s:3:"xls";i:17;s:3:"odt";i:18;s:3:"odp";i:19;s:3:"ods";i:20;s:3:"odg";i:21;s:3:"odc";i:22;s:3:"odf";i:23;s:3:"odb";i:24;s:3:"xcf";i:25;s:3:"flv";i:26;s:3:"mp3";i:27;s:3:"ogg";i:28;s:3:"mpg";i:29;s:3:"mov";i:30;s:3:"swf";i:31;s:3:"wav";i:32;s:3:"wmv";i:33;s:4:"midi";i:34;s:3:"mng";i:35;s:2:"qt";i:36;s:1:"c";i:37;s:1:"h";i:38;s:3:"cpp";i:39;s:4:"java";i:40;s:2:"py";i:41;s:3:"css";i:42;s:4:"html";i:43;s:3:"xml";i:44;s:3:"ttf";i:45;s:3:"tex";i:46;s:3:"rtf";i:47;s:3:"psd";}s:10:"auth_files";s:32:"a:2:{s:2:"r0";i:1;s:2:"r1";i:1;}";}'),
(4, 'com', 'a:6:{s:8:"com_auth";i:-1;s:7:"com_max";i:10;s:14:"com_verif_code";i:1;s:25:"com_verif_code_difficulty";i:2;s:14:"forbidden_tags";a:0:{}s:8:"max_link";i:2;}'),
(5, 'writingpad', '');

INSERT INTO `phpboost_member` (login, level, user_aprob, user_groups, user_desc, user_sign) VALUES ('login', 2, 1, '', '', '');

INSERT INTO `phpboost_ranks` VALUES (1, 'Administrateur', -2, 'rank_admin.png', 1);
INSERT INTO `phpboost_ranks` VALUES (2, 'Modérateur', -1, 'rank_modo.png', 1);
INSERT INTO `phpboost_ranks` VALUES (3, 'Boosteur Inactif', 0, 'rank_0.png', 0);
INSERT INTO `phpboost_ranks` VALUES (4, 'Booster Fronde', 1, 'rank_0.png', 0);
INSERT INTO `phpboost_ranks` VALUES (5, 'Booster Minigun', 25, 'rank_1.png', 0);
INSERT INTO `phpboost_ranks` VALUES (6, 'Booster Fuzil', 50, 'rank_2.png', 0);
INSERT INTO `phpboost_ranks` VALUES (7, 'Booster Bazooka', 100, 'rank_3.png', 0);
INSERT INTO `phpboost_ranks` VALUES (8, 'Booster Roquette', 250, 'rank_4.png', 0);
INSERT INTO `phpboost_ranks` VALUES (9, 'Booster Mortier', 500, 'rank_5.png', 0);
INSERT INTO `phpboost_ranks` VALUES (10, 'Booster Missile', 1000, 'rank_6.png', 0);
INSERT INTO `phpboost_ranks` VALUES (11, 'Booster Fusée', 1500, 'rank_special.png', 0);

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
