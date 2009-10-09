DROP TABLE IF EXISTS phpboost_articles, phpboost_articles_cats;


CREATE TABLE `phpboost_articles` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `description` text,
  `sources` text,
  `contents` mediumtext NOT NULL,
  `icon` varchar(255) NOT NULL default '',
  `timestamp` int(11) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '0',
  `start` int(11) NOT NULL default '0',
  `end` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `views` mediumint(9) NOT NULL default '0',
  `users_note` text,
  `nbrnote` mediumint(9) NOT NULL default '0',
  `note` float NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `contents` (`contents`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

CREATE TABLE `phpboost_articles_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `c_order` int(11) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `tpl_articles` varchar(100) NOT NULL default 'articles.tpl',
  `tpl_cat` varchar(100) NOT NULL default 'articles_cat.tpl',
  `description` text,
  `nbr_articles_visible` mediumint(9) unsigned NOT NULL default '0',
  `nbr_articles_unvisible` mediumint(9) unsigned NOT NULL default '0',
  `image` varchar(255) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '0',
  `auth` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;



INSERT INTO phpboost_articles (`id`, `idcat`, `title`, `contents`, `icon`, `timestamp`, `visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`) VALUES ('8', '10', 'de', 'de', '', '1253876581', '1', '0', '0', '1', '3', '', '0', '0', '0', '0'), ('9', '0', 'dz', 'dz', '', '1253876941', '0', '0', '0', '1', '14', '', '0', '0', '0', '0'), ('10', '12', 'dz', 'dz', '', '1253876941', '0', '0', '0', '1', '2', '', '0', '0', '0', '0'), ('12', '10', 'zzzzzzzzzzzzzzzzz', 'zz', '', '1253876941', '1', '0', '0', '1', '2', '', '0', '0', '0', '0'), ('13', '10', 'Créer DVD/Blu-ray : 3 logiciels PC en test', 'xz', '02450882.jpg', '1253877241', '1', '0', '0', '1', '1', '', '0', '0', '0', '0'), ('14', '10', 'xz', 'xz', '', '1253877241', '1', '0', '0', '1', '0', '', '0', '0', '0', '0'), ('15', '10', 'Les tuners TNT HD USB au banc d''essai', 'd', '02449506.jpg', '1253877301', '1', '0', '0', '1', '1', '', '0', '0', '0', '0'), ('16', '10', 'Top des logiciels pour Facebook, Twitter et MySpace', 'd', '02458698.jpg', '1253877361', '1', '0', '0', '1', '1', '', '0', '0', '0', '0'), ('29', '0', 'dz', 'gvzsdc<br />[page]  dzdz[/page]cd', '', '1253889481', '0', '0', '0', '1', '191', '', '0', '0', '3', '0');
INSERT INTO phpboost_articles (id, idcat, title, contents, icon, timestamp, visible, start, end, user_id, views, users_note, nbrnote, note, nbr_com, lock_com) VALUES ('30', '0', 'dz', 'dzqssz<br />\r\n<br />\r\nszsaxqsa', '', '1253968321', '1', '0', '0', '1', '10', '', '0', '0', '0', '0'), ('31', '10', 'Comparatif de six encyclopédies en ligne', 'dz', '02462842.jpg', '1254022981', '1', '0', '0', '1', '23', '', '0', '0', '0', '0'), ('32', '10', 'AMD Radeon HD 5850 : DirectX 11 à 250 euros', '[page]pre[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]deux[/page]<br />\r\ndzcs<br />\r\n[page]troisi[/page]<br />\r\nz<br />\r\n&amp;efgf<br />\r\n[page]quatr[/page]<br />\r\ndzcszzzzzzzzz<br />\r\n[page]cinq[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]six[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]sept[/page]<br />\r\ndzcs<br />\r\n[page]huit[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]neuf[/page]<br />\r\ndzcs<br />\r\n[page]dix[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]onze[/page]<br />\r\ndzcs<br />\r\n[page]douze[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]treize[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]quatorze[/page]<br />\r\ndzcs<br />\r\n[page]quinze[/page]<br />\r\n<br />\r\n&amp;efgf<br />\r\n[page]seize[/page]<br />\r\ndzcs<br />\r\n[page]dix[/page]<br />\r\n<br />\r\n&amp;efgf', '02464020.jpg', '1254024241', '1', '0', '0', '1', '574', '', '0', '0', '0', '0'), ('33', '0', 'test 12', 'daq<br />\r\n[page]deczsde[/page]<br />\r\nczscs<br />\r\n[page]deczsfzde[/page]<br />\r\ndzdz<br />\r\n[page] [/page]dz<br />\r\n<img src="/images/smileys/top.gif" alt=":top" class="smiley" /> z<br />\r\n<br />\r\n[page] [/page]s<br />\r\n<img src="/images/smileys/top.gif" alt=":top" class="smiley" /> z<br />\r\n[style=notice]dzdz[/styles]', 'articles.png', '1254022501', '1', '0', '0', '1', '327', '', '0', '0', '1', '0'), ('20', '10', 'zczczcz', 'czcz', '', '1253877961', '0', '0', '0', '-1', '0', '', '0', '0', '0', '0'), ('25', '10', 'vzsvzs', 'czcz', '', '1253878501', '0', '0', '0', '-1', '0', '', '0', '0', '0', '0'), ('22', '10', 'cz', 'cz', '', '1253878141', '0', '0', '0', '-1', '0', '', '0', '0', '0', '0'), ('26', '10', 'Comment partager ses fichiers entre plusieurs PC ?', '[page]test[/page]<br />\r\ndz<br />\r\n[page]teszt[/page]<br />\r\nfzcfzf<br />\r\n<br />\r\nfzfzfz<br />\r\nfzfc<br />\r\nz<br />\r\nc<br />\r\ncz<br />\r\ncz<br />\r\ncz<br />\r\ncz<br />\r\ncz<br />\r\n[page]tedzszt[/page]<br />\r\nfzcfzf<br />\r\n<br />\r\nfzfzfz<br />\r\nfzfc<br />\r\nz<br />\r\nc<br />\r\ncz<br />\r\ncz<br />\r\ncz<br />\r\ncz<br />\r\ncz', '02454836.jpg', '1253878561', '1', '0', '0', '1', '80', '', '0', '0', '0', '0'), ('27', '10', 'wwwwwwwwww', 'wwwwwwwwwwwww', '', '1253878561', '0', '0', '0', '-1', '0', '', '0', '0', '0', '0'), ('34', '0', 'qq', 'q', '', '1254278101', '1', '0', '0', '1', '16', '', '0', '0', '0', '0');
INSERT INTO phpboost_articles_cats (`id`, `id_parent`, `c_order`, `name`, `tpl_articles`, `tpl_cat`, `description`, `nbr_articles_visible`, `nbr_articles_unvisible`, `image`, `visible`, `auth`) VALUES ('10', '0', '1', 'Test logiciel', 'articles.tpl', 'articles_cat2.tpl', 'dz', '9', '4', 'articles.png', '1', ''), ('11', '10', '1', 'trucs et astuces', 'articles.tpl', 'articles_cat.tpl', 'gvsdx', '0', '0', 'http://www.phpboost.com/templates/tornade/images/rss.png', '1', ''), ('12', '0', '2', 'ved', 'articles.tpl', 'articles_cat.tpl', 'z', '0', '0', 'http://www.phpboost.com/templates/tornade/images/rss.png', '1', ''), ('13', '0', '3', 'test', 'articles_catx.tpl', 'articles_cat.tpl', 'erere', '0', '0', '../articles/articles.png', '1', '');
