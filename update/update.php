<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : August 23, 2007
 *   copyright          : (C) 2007 	SAUTEL Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

ob_start();

set_magic_quotes_runtime(0);
$update_version = '2.0';

define('HOST', 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')));
$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
define('FILE', $server_path);
define('DIR', str_replace('/update/update.php', '', $server_path));

//Thème par défaut.
define('DEFAULT_THEME', 'main');

if (!@include_once('../kernel/framework/io/template.class.php'))
	die('Votre dossier de mise à jour n\'est pas placé où il faut');
include_once('../kernel/framework/functions.inc.php');

$step = !empty($_GET['step']) ? numeric($_GET['step']) : 1;
$step = $step > 14 ? 1 : $step;
$go_to_next_step = !empty($_POST['submit']) ? true : false;

$lang = !empty($_GET['lang']) ? strprotect($_GET['lang']) : 'french';
if (!@include_once('lang/' . $lang . '/install_' . $lang . '.php'))
	include_once('lang/french/install.php');
	
$Template = new Template; //!\\Initialisation des templates//!\\

if ($step >= 2)
{
	require_once('../kernel/framework/functions.inc.php'); //Fonctions de base.
	require_once('../kernel/constant.php'); //Constante utiles.
	require_once('../kernel/framework/content/mathpublisher.php'); //Gestion des formules mathématiques.
	require_once('../kernel/framework/core/errors.class.php');
	require_once('../kernel/framework/io/template.class.php');
	require_once('../kernel/framework/db/' . DBTYPE . '.class.php');
	require_once('../kernel/framework/core/cache.class.php');
	require_once('../kernel/framework/members/sessions.class.php');
	require_once('../kernel/framework/members/groups.class.php');

	//Instanciation des objets indispensables au noyau.
	$Errorh = new Errors; //!\\Initialisation  de la class des erreurs//!\\
	$Template = new Template; //!\\Initialisation des templates//!\\
	$Sql = new Sql; //!\\Initialisation  de la class sql//!\\
	$Cache = new Cache; //!\\Initialisation  de la class de gestion du cache//!\\
		
	//Vérifications de version, pour éviter les doubles mise à jours, mise à jours incorrectes..
	$previous_version = '1.6.0';
	$new_version = '2.0.0';
}
else
{
	define('MAGIC_QUOTES', get_magic_quotes_gpc()); //Récupère la valeur du magic quotes
	define('PHPBOOST', true);
	define('ERROR_REPORTING', E_ALL | E_NOTICE);
	@error_reporting(ERROR_REPORTING);
}

$Template->set_filenames(array('update' => '../update/templates/update.tpl'));

//Fonction pour gérer la langue
function add_lang($url, $header_location = false)
{
	global $lang;
	if ($lang != 'french')
	{
		$ampersand = $header_location ? '&' : '&amp;';
		if (strpos($url, '?') !== false)
			return $url . $ampersand . 'lang=' . $lang;
		else
			return $url . '?' . 'lang=' . $lang;		
	}
	else
		return $url;
}

//Préambule
if ($step == 1)
{
	$config_contents = file_get_contents('../kernel/db/config.php');
	//Si le fichier de config est à l'ancien format
	if (strpos($config_contents, 'DBSECURE') === false)
	{
		$INCLUDE_secure = 1;
		include_once('../kernel/db/config.php');
		//Remplacement des fichiers de configuration
		$file = @fopen('../kernel/db/config.php', 'w+'); //On ouvre le fichier, si il n'existe pas on le crée.
		@fputs($file, '<?php
if (!defined(\'DBSECURE\'))
{
	$sql_host = "' . $mysql_host . '"; //Adresse serveur mysql.
	$sql_login = "' . $mysql_login . '"; //Login
	$sql_pass = "' . $mysql_pass . '"; //Mot de passe
	$sql_base = "' . $mysql_base . '"; //Nom de la base de données.
	$host = "' . $host . '"; //Nom du serveur (ex: http://www.google.fr)
	$table_prefix = "' . $table_prefix . '"; //Préfixe des tables
	$dbtype = "mysql"; //Système de gestion de base de données
	define(\'DBSECURE\', true);
	define(\'PHPBOOST_INSTALLED\', true);
}	
else
{
	exit;
}
?>');
		@fclose($file);
	}
	
	$Template->assign_block_vars('intro', array());
	$Template->assign_vars(array(
		'L_NEXT_STEP' => add_lang('update.php?step=2')
	));
}
//Mise à jour du noyau
elseif ($step == 2)
{
	if ($go_to_next_step)
	{		
		###########################Suppression###########################
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "admin_album`", __LINE__, __FILE__);		
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "admin_membre`", __LINE__, __FILE__);
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "admin_module`", __LINE__, __FILE__);
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "admin_theme`", __LINE__, __FILE__);
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "admin_news`", __LINE__, __FILE__);
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "config_lien`", __LINE__, __FILE__);
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "group`", __LINE__, __FILE__);
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "mp_convers`", __LINE__, __FILE__);
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "mp_msg`", __LINE__, __FILE__);
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "sondageconfig`", __LINE__, __FILE__); 	
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "sondageip`", __LINE__, __FILE__); 
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "sondagereponses`", __LINE__, __FILE__);
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "menus'`", __LINE__, __FILE__);
				
		###########################Création de table.###########################
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "configs` (
		`id` int(11) NOT NULL auto_increment,
		`name` varchar(150) NOT NULL default '',
		`value` text NOT NULL,
		PRIMARY KEY	(`id`),
		UNIQUE KEY `name` (`name`)
		) ENGINE=MyISAM", __LINE__, __FILE__); 
			
		$config_info = $Sql->query_array(PREFIX . "config", "*", __LINE__, __FILE__);		
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "config`", __LINE__, __FILE__);
		
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` (`id`, `name`, `value`) VALUES 
(1, 'config', 'a:27:{s:11:\"server_name\";s:" . strlen(HOST) . ":\"" . HOST . "\";s:11:\"server_path\";s:" . strlen(DIR) . ":\"" . DIR . "\";s:9:\"site_name\";s:" . strlen($config_info['site_name']) . ":\"" . str_replace('\'', '\\\'', $config_info['site_name']) . "\";s:9:\"site_desc\";s:" . strlen($config_info['site_desc']) . ":\"" . str_replace('\'', '\\\'', $config_info['site_desc']) . "\";s:12:\"site_keyword\";s:" . strlen($config_info['site_keyword']) . ":\"" . str_replace('\'', '\\\'', $config_info['site_keyword']) . "\";s:5:\"start\";i:" . $config_info['start'] . ";s:7:\"version\";s:3:\"2.0\";s:4:\"lang\";s:6:\"french\";s:5:\"theme\";s:4:\"main\";s:10:\"start_page\";s:14:\"/news/news.php\";s:8:\"maintain\";s:1:\"0\";s:14:\"maintain_delay\";s:1:\"1\";s:13:\"maintain_text\";s:0:\"\";s:7:\"rewrite\";i:" . $config_info['rewrite'] . ";s:9:\"com_popup\";s:1:\"0\";s:8:\"compteur\";s:1:\"0\";s:12:\"ob_gzhandler\";i:0;s:11:\"site_cookie\";s:7:\"session\";s:12:\"site_session\";i:3600;s:18:\"site_session_invit\";i:300;s:4:\"mail\";s:0:\"\";s:10:\"activ_mail\";s:1:\"0\";s:4:\"sign\";s:0:\"\";s:10:\"anti_flood\";s:1:\"1\";s:11:\"delay_flood\";s:1:\"7\";s:12:\"unlock_admin\";s:0:\"\";s:6:\"pm_max\";s:2:\"50\";}'),
(2, 'member', 'a:13:{s:14:\"activ_register\";i:1;s:7:\"msg_mbr\";s:0:\"\";s:12:\"msg_register\";s:0:\"\";s:9:\"activ_mbr\";i:0;s:10:\"verif_code\";i:1;s:17:\"delay_unactiv_max\";i:30;s:11:\"force_theme\";i:0;s:15:\"activ_up_avatar\";i:1;s:9:\"width_max\";i:120;s:10:\"height_max\";i:120;s:10:\"weight_max\";i:20;s:12:\"activ_avatar\";i:1;s:10:\"avatar_url\";s:13:\"no_avatar.jpg\";}'),
(3, 'files', 'a:3:{s:10:\"size_limit\";d:512;s:17:\"bandwidth_protect\";s:1:\"1\";s:10:\"auth_files\";s:45:\"a:3:{s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}\";}'),
(4, 'com', 'a:4:{s:8:\"com_auth\";i:-1;s:7:\"com_max\";i:10;s:14:\"forbidden_tags\";s:99:\"a:6:{i:0;s:3:\"swf\";i:1;s:5:\"movie\";i:2;s:5:\"sound\";i:3;s:4:\"code\";i:4;s:4:\"math\";i:5;s:6:\"anchor\";}\";s:8:\"max_link\";i:2;}')", __LINE__, __FILE__); 
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (5, 'articles', 'a:5:{s:16:\"nbr_articles_max\";i:10;s:11:\"nbr_cat_max\";i:10;s:10:\"nbr_column\";i:2;s:8:\"note_max\";i:10;s:9:\"auth_root\";s:59:\"a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}\";}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (6, 'calendar', 'a:1:{s:13:\"calendar_auth\";i:2;}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (7, 'download', 'a:4:{s:12:\"nbr_file_max\";i:10;s:11:\"nbr_cat_max\";i:10;s:10:\"nbr_column\";i:2;s:8:\"note_max\";i:10;}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (8, 'forum', 'a:13:{s:10:\"forum_name\";s:14:\"PHPBoost forum\";s:16:\"pagination_topic\";i:20;s:14:\"pagination_msg\";i:15;s:9:\"view_time\";i:2592000;s:11:\"topic_track\";i:40;s:9:\"edit_mark\";i:1;s:14:\"no_left_column\";i:0;s:15:\"no_right_column\";i:0;s:17:\"activ_display_msg\";i:1;s:11:\"display_msg\";s:21:\"[R&eacute;gl&eacute;]\";s:19:\"explain_display_msg\";s:26:\"Sujet r&eacute;gl&eacute;?\";s:23:\"explain_display_msg_bis\";s:30:\"Sujet non r&eacute;gl&eacute;?\";s:22:\"icon_activ_display_msg\";i:1;}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (9, 'gallery', 'a:27:{s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"width_max\";i:800;s:10:\"height_max\";i:600;s:10:\"weight_max\";i:1024;s:7:\"quality\";i:80;s:5:\"trans\";i:60;s:4:\"logo\";s:8:\"logo.jpg\";s:10:\"activ_logo\";i:1;s:7:\"d_width\";i:5;s:8:\"d_height\";i:5;s:10:\"nbr_column\";i:4;s:12:\"nbr_pics_max\";i:16;s:8:\"note_max\";i:5;s:11:\"activ_title\";i:1;s:9:\"activ_com\";i:1;s:10:\"activ_note\";i:1;s:15:\"display_nbrnote\";i:1;s:10:\"activ_view\";i:1;s:10:\"activ_user\";i:1;s:12:\"limit_member\";i:10;s:10:\"limit_modo\";i:25;s:12:\"display_pics\";i:3;s:11:\"scroll_type\";i:1;s:13:\"nbr_pics_mini\";i:2;s:15:\"speed_mini_pics\";i:6;s:9:\"auth_root\";s:59:\"a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:3;s:2:\"r1\";i:7;s:2:\"r2\";i:7;}\";}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (10, 'guestbook', 'a:3:{s:14:\"guestbook_auth\";i:-1;s:24:\"guestbook_forbidden_tags\";s:52:\"a:3:{i:0;s:3:\"swf\";i:1;s:5:\"movie\";i:2;s:5:\"sound\";}\";s:18:\"guestbook_max_link\";i:2;}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (11, 'news', 'a:11:{s:4:\"type\";i:1;s:11:\"activ_pagin\";i:1;s:11:\"activ_edito\";i:1;s:15:\"pagination_news\";i:5;s:15:\"pagination_arch\";i:10;s:9:\"activ_com\";i:1;s:10:\"activ_icon\";i:1;s:8:\"nbr_news\";s:1:\"0\";s:10:\"nbr_column\";i:1;s:5:\"edito\";s:22:\"Bienvenue sur le site!\";s:11:\"edito_title\";s:22:\"Bienvenue sur le site!\";}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (12, 'newsletter', 'a:2:{s:11:\"sender_mail\";s:0:\"\";s:15:\"newsletter_name\";s:0:\"\";}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (13, 'online', 'a:2:{s:16:\"online_displayed\";i:4;s:20:\"display_order_online\";s:28:\"s.level, s.session_time DESC\";}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (14, 'pages', 'a:3:{s:10:\"count_hits\";i:1;s:9:\"activ_com\";i:1;s:4:\"auth\";s:59:\"a:4:{s:3:\"r-1\";i:5;s:2:\"r0\";i:7;s:2:\"r1\";i:7;s:2:\"r2\";i:7;}\";}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (15, 'poll', 'a:4:{s:9:\"poll_auth\";i:-1;s:9:\"poll_mini\";i:-1;s:11:\"poll_cookie\";s:4:\"poll\";s:18:\"poll_cookie_lenght\";i:1800000;}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (16, 'shoutbox', 'a:4:{s:16:\"shoutbox_max_msg\";i:100;s:13:\"shoutbox_auth\";i:-1;s:23:\"shoutbox_forbidden_tags\";s:359:\"a:22:{i:0;s:5:\"title\";i:1;s:6:\"stitle\";i:2;s:5:\"style\";i:3;s:3:\"url\";i:4;s:3:\"img\";i:5;s:5:\"quote\";i:6;s:4:\"hide\";i:7;s:4:\"list\";i:8;s:5:\"color\";i:9;s:4:\"size\";i:10;s:5:\"align\";i:11;s:5:\"float\";i:12;s:3:\"sup\";i:13;s:3:\"sub\";i:14;s:6:\"indent\";i:15;s:5:\"table\";i:16;s:3:\"swf\";i:17;s:5:\"movie\";i:18;s:5:\"sound\";i:19;s:4:\"code\";i:20;s:4:\"math\";i:21;s:6:\"anchor\";}\";s:17:\"shoutbox_max_link\";i:2;}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (17, 'web', 'a:4:{s:11:\"nbr_web_max\";i:10;s:11:\"nbr_cat_max\";i:10;s:10:\"nbr_column\";i:2;s:8:\"note_max\";i:10;}')", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "configs` VALUES (18, 'wiki', 'a:6:{s:9:\"wiki_name\";s:4:\"Wiki\";s:13:\"last_articles\";i:10;s:12:\"display_cats\";i:0;s:10:\"index_text\";s:0:\"\";s:10:\"count_hits\";i:1;s:4:\"auth\";s:71:\"a:4:{s:3:\"r-1\";i:1041;s:2:\"r0\";i:1495;s:2:\"r1\";i:4095;s:2:\"r2\";i:4095;}\";}')", __LINE__, __FILE__);
		      	  
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "group` (
			`id` int(11) NOT NULL auto_increment,
			`name` varchar(100) NOT NULL default '',
			`img` varchar(255) NOT NULL default '',
			`auth` smallint(6) NOT NULL default '0',
			`members` text NOT NULL,
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);
		
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "lang` (
			`id` int(11) NOT NULL auto_increment,
			`lang` varchar(150) NOT NULL default '',
			`activ` tinyint(1) NOT NULL default '0',
			`secure` tinyint(1) NOT NULL default '0',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "lang` ( `id` , `lang` , `activ` , `secure` ) VALUES ('', 'french', '1', '-1')", __LINE__, __FILE__);

		$Sql->query_inject("CREATE TABLE `" . PREFIX . "member_extend` (
		  `user_id` int(11) NOT NULL auto_increment,
		  `last_view_forum` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`user_id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);

		$Sql->query_inject("CREATE TABLE `" . PREFIX . "member_extend_cat` (
			`id` int(11) NOT NULL auto_increment,
			`class` int(11) NOT NULL default '0',
			`name` varchar(255) NOT NULL default '',
			`field_name` varchar(255) NOT NULL default '',
			`contents` text NOT NULL,
			`field` tinyint(1) NOT NULL default '0',
			`possible_values` text NOT NULL,
			`default_values` text NOT NULL,
			`require` tinyint(1) NOT NULL default '0',
			`display` tinyint(1) NOT NULL default '0',
			`regex` varchar(255) NOT NULL default '',
			UNIQUE KEY `id` (`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);

		//Modules
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "modules` (
		  `id` int(11) NOT NULL auto_increment,
		  `name` varchar(150) NOT NULL default '',
		  `version` varchar(15) NOT NULL default '',
		  `auth` text NOT NULL,
		  `activ` tinyint(1) NOT NULL default '0',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (1, 'articles', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (2, 'calendar', '1.2', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (3, 'contact', '1.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (4, 'download', '1.4', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (5, 'forum', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (6, 'gallery', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (7, 'guestbook', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (8, 'links', '1.5', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (9, 'news', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (10, 'newsletter', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (11, 'online', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (12, 'pages', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (13, 'poll', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (14, 'shoutbox', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (15, 'stats', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (16, 'web', '1.4', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "modules` VALUES (17, 'wiki', '2.0', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1)", __LINE__, __FILE__);
		
		//Modules mini
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "menus` (
			`id` int(11) NOT NULL auto_increment,
			`class` int(11) NOT NULL default '0',
			`name` varchar(150) NOT NULL default '',
			`code` text NOT NULL,
			`contents` text NOT NULL,
			`side` tinyint(1) NOT NULL default '0',
			`secure` tinyint(1) NOT NULL default '0',
			`activ` tinyint(1) NOT NULL default '0',
			`added` tinyint(1) NOT NULL default '0',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "menus` VALUES (1, 1, 'connexion', 'if (SCRIPT != DIR . ''/membre/error.php'')include_once(''../kernel/connect.php'');', '', 0, -1, 1, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "menus` VALUES (2, 1, 'gallery', 'include_once(''../gallery/gallery_mini.php'');', '', 1, -1, 1, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "menus` VALUES (3, 2, 'links', 'include_once(''../links/links_mini.php'');', '', 0, -1, 1, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "menus` VALUES (4, 3, 'newsletter', 'include_once(''../newsletter/newsletter_mini.php'');', '', 0, -1, 1, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "menus` VALUES (5, 2, 'online', 'include_once(''../online/online_mini.php'');', '', 1, -1, 1, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "menus` VALUES (6, 3, 'poll', 'include_once(''../poll/poll_mini.php'');', '', 1, -1, 1, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "menus` VALUES (7, 4, 'shoutbox', 'include_once(''../shoutbox/shoutbox_mini.php'');', '', 1, -1, 1, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "menus` VALUES (8, 4, 'stats', 'include_once(''../stats/stats_mini.php'');', '', 0, -1, 1, 0)", __LINE__, __FILE__);
	
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "pm_msg` (
			`id` int(11) NOT NULL auto_increment,
			`idconvers` int(11) NOT NULL default '0',
			`user_id` int(11) NOT NULL default '0',
			`contents` text NOT NULL,
			`timestamp` int(11) NOT NULL default '0',
			`view_status` tinyint(1) NOT NULL default '0',
			PRIMARY KEY	(`id`),
			KEY `idconvers` (`idconvers`,`user_id`,`timestamp`)
		) ENGINE=MyISAM", __LINE__, __FILE__);

		$Sql->query_inject("CREATE TABLE `" . PREFIX . "pm_topic` (
			`id` int(11) NOT NULL auto_increment,
			`title` varchar(150) NOT NULL default '',
			`user_id` int(11) NOT NULL default '0',
			`user_id_dest` int(11) NOT NULL default '0',
			`user_convers_status` tinyint(1) NOT NULL default '0',
			`user_view_pm` int(11) NOT NULL default '0',
			`nbr_msg` int(11) unsigned NOT NULL default '0',
			`last_user_id` int(11) NOT NULL default '0',
			`last_msg_id` int(11) NOT NULL default '0',
			`last_timestamp` int(11) NOT NULL default '0',
			`visible` tinyint(1) NOT NULL default '0',
			PRIMARY KEY	(`id`),
			KEY `user_id` (`user_id`,`user_id_dest`,`user_convers_status`,`last_timestamp`)
		) ENGINE=MyISAM", __LINE__, __FILE__);

		$Sql->query_inject("CREATE TABLE `" . PREFIX . "ranks` (
			`id` int(11) NOT NULL auto_increment,
			`name` varchar(150) NOT NULL default '',
			`msg` int(11) NOT NULL default '0',
			`icon` varchar(255) NOT NULL default '',
			`special` tinyint(1) NOT NULL default '0',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);

		$Sql->query_inject("INSERT INTO `" . PREFIX . "ranks` VALUES (1, 'Administrateur', -2, 'rank_admin.gif', 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "ranks` VALUES (2, 'Mod&eacute;rateur', -1, 'rank_modo.gif', 1)", __LINE__, __FILE__);

		$Sql->query_inject("CREATE TABLE `" . PREFIX . "themes` (
			`id` int(11) NOT NULL auto_increment,
			`theme` varchar(50) NOT NULL default '',
			`activ` tinyint(1) NOT NULL default '0',
			`secure` tinyint(1) NOT NULL default '0',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "themes` ( `id` , `theme` , `activ` , `secure` ) VALUES ('', 'main', '1', '-1')", __LINE__, __FILE__);
		
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "upload` (
			`id` int(11) NOT NULL auto_increment,
			`idcat` int(11) NOT NULL default '0',
			`name` varchar(150) NOT NULL default '',
			`path` varchar(255) NOT NULL default '',
			`user_id` int(11) NOT NULL default '0',
			`size` float NOT NULL default '0',
			`type` varchar(10) NOT NULL default '',
			`timestamp` int(11) NOT NULL default '0',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);

		$Sql->query_inject("CREATE TABLE `" . PREFIX . "upload_cat` (
			`id` int(11) NOT NULL auto_increment,
			`id_parent` int(11) NOT NULL default '0',
			`user_id` int(11) NOT NULL default '0',
			`name` varchar(150) NOT NULL default '',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);

		$Sql->query_inject("CREATE TABLE `" . PREFIX . "verif_code` (
			`id` int(11) NOT NULL auto_increment,
			`user_id` varchar(8) NOT NULL default '',
			`code` varchar(20) NOT NULL default '',
			`timestamp` int(11) NOT NULL default '0',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);
	
		
		###########################Mises à jour###########################
		//Com
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "com` CHANGE `contenu` `contents` TEXT NOT NULL", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "com` CHANGE `login` `login` VARCHAR( 255 ) NOT NULL", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "com` CHANGE `idcom` `idcom` INT NOT NULL AUTO_INCREMENT", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "com` CHANGE `idprov` `idprov` INT DEFAULT '0' NOT NULL", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "com` CHANGE `user_id` `user_id` INT DEFAULT '0' NOT NULL", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "com` DROP INDEX `idcom`, ADD INDEX `idprov` ( `idprov` , `script` )", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "com` ADD PRIMARY KEY ( `idcom` )", __LINE__, __FILE__);

		//Compteur
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "compteur` CHANGE `count_ip` `total` INT( 11 ) DEFAULT '0' NOT NULL", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "compteur` CHANGE `time` `time` DATE NOT NULL", __LINE__, __FILE__);
		
		//Membre
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "membre` CHANGE `login` `login` VARCHAR( 255 ) NOT NULL", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "membre` CHANGE `user_group` `user_groups` TEXT NOT NULL", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "membre` CHANGE `user_age` `user_born` DATE NOT NULL", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "membre` CHANGE `user_mp` `user_pm` SMALLINT( 6 ) UNSIGNED DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "membre` ADD `user_readonly` INT NOT NULL AFTER `user_warning`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "membre` DROP `last_view_forum`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "membre` DROP `topic_track`", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE `" . PREFIX . "membre` SET user_lang = 'french', user_theme = 'main'", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "membre` RENAME `" . PREFIX . "member`", __LINE__, __FILE__);
		
		//Sessions
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "sessions` CHANGE `level` `level` TINYINT( 1 ) DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "sessions` CHANGE `session_script_name` `session_script_title` VARCHAR( 255 ) NOT NULL ", __LINE__, __FILE__); 
		
		//Smileys
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_smilies` CHANGE `idsmile` `idsmiley` INT( 11 ) NOT NULL AUTO_INCREMENT", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_smilies` CHANGE `code_smile` `code_smiley` VARCHAR( 50 ) NOT NULL", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_smilies` CHANGE `url_smile` `url_smiley` VARCHAR( 50 ) NOT NULL", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_smilies` RENAME `" . PREFIX . "smileys`", __LINE__, __FILE__);
				
		//Stats		
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "stats` CHANGE `year` `stats_year` SMALLINT( 6 ) DEFAULT '0' NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "stats` CHANGE `month` `stats_month` TINYINT( 4 ) DEFAULT '0' NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "stats` CHANGE `day` `stats_day` TINYINT( 4 ) DEFAULT '0' NOT NULL", __LINE__, __FILE__);  
		$result = $Sql->query_while("SELECT s1.id
		FROM `" . PREFIX . "stats` AS s1
		JOIN " . PREFIX . "stats AS s2
		WHERE s1.stats_day = s2.stats_day AND s1.stats_month = s2.stats_month AND s1.stats_year = s2.stats_year AND s1.id != s2.id
		GROUP BY s2.stats_day", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$Sql->query_inject("DELETE FROM " . PREFIX . "stats WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__); //Suppression des doublons.
		}
		$Sql->query_close($result);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "stats` ADD PRIMARY KEY ( `id` )", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "stats` DROP INDEX `id`", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "stats` ADD UNIQUE `stats_day` ( `stats_year` , `stats_month` , `stats_day` )", __LINE__, __FILE__);  
				
		//Liens
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "links` (
		  `id` int(11) NOT NULL auto_increment,
		  `class` int(11) NOT NULL default '0',
		  `name` varchar(50) NOT NULL default '',
		  `url` varchar(255) NOT NULL default '',
		  `activ` tinyint(1) NOT NULL default '0',
		  `secure` char(2) NOT NULL default '',
		  `added` tinyint(1) NOT NULL default '0',
		  `sep` tinyint(1) NOT NULL default '1',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (1, 1, 'Membres', '', 1, '-1', 0, 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (2, 2, 'Membres', '../member/member.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (3, 3, 'Menu', '', 1, '-1', 0, 1)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (4, 6, 'Articles', '../articles/articles.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (5, 7, 'Calendrier', '../calendar/calendar.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (6, 8, 'Contact', '../contact/contact.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (7, 9, 'Téléchargements', '../download/download.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (8, 10, 'Forum', '../forum/index.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (9, 11, 'Galerie', '../gallery/gallery.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (10, 12, 'Livre d''or', '../guestbook/guestbook.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (11, 14, 'News', '../news/news.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (12, 18, 'Sondages', '../poll/poll.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (13, 21, 'Liens web', '../web/web.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO `" . PREFIX . "links` VALUES (14, 22, 'Wiki', '../wiki/wiki.php', 1, '-1', 0, 0)", __LINE__, __FILE__);
		
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "poll` (
			`id` int(11) NOT NULL auto_increment,
			`question` varchar(255) NOT NULL default '',
			`answers` text NOT NULL,
			`votes` text NOT NULL,
			`type` tinyint(1) NOT NULL default '0',
			`archive` tinyint(1) NOT NULL default '0',
			`timestamp` int(11) NOT NULL default '0',
			`visible` tinyint(1) NOT NULL default '0',
			`start` int(11) NOT NULL default '0',
			`end` int(11) NOT NULL default '0',
			`user_id` int(11) NOT NULL default '0',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__); 

		$Sql->query_inject("CREATE TABLE `" . PREFIX . "poll_ip` (
			`id` int(11) NOT NULL auto_increment,
			`ip` varchar(50) NOT NULL default '',
			`idpoll` int(11) NOT NULL default '0',
			`timestamp` int(11) NOT NULL default '0',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__); 
		
		
		###########################Insertions###########################
		//Wiki
		$Sql->query_inject("CREATE TABLE `phpboost_wiki_articles` (
			`id` int(11) NOT NULL auto_increment,
			`id_contents` int(11) NOT NULL default '0',
			`title` varchar(250) NOT NULL default '',
			`encoded_title` varchar(250) NOT NULL default '',
			`hits` int(11) NOT NULL default '0',
			`id_cat` int(11) NOT NULL default '0',
			`is_cat` tinyint(1) NOT NULL default '0',
			`defined_status` smallint(6) NOT NULL default '0',
			`undefined_status` text NOT NULL,
			`redirect` int(11) NOT NULL default '0',
			`auth` text NOT NULL,
			`nbr_com` int(11) unsigned NOT NULL default '0',
			`lock_com` tinyint(1) NOT NULL default '0',
			PRIMARY KEY	(`encoded_title`),
			KEY `id` (`id`),
			FULLTEXT KEY `title` (`title`)
		) ENGINE=MyISAM", __LINE__, __FILE__); 

		$Sql->query_inject("CREATE TABLE `phpboost_wiki_cats` (
			`id` int(11) NOT NULL auto_increment,
			`id_parent` int(11) NOT NULL default '0',
			`article_id` int(11) NOT NULL default '0',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__); 

		$Sql->query_inject("CREATE TABLE `phpboost_wiki_contents` (
			`id_contents` int(11) NOT NULL auto_increment,
			`id_article` int(11) NOT NULL default '0',
			`menu` text NOT NULL,
			`content` text NOT NULL,
			`activ` tinyint(1) NOT NULL default '0',
			`user_id` int(11) NOT NULL default '0',
			`user_ip` varchar(50) NOT NULL default '',
			`timestamp` int(11) NOT NULL default '0',
			PRIMARY KEY	(`id_contents`),
			FULLTEXT KEY `content` (`content`)
		) ENGINE=MyISAM", __LINE__, __FILE__); 

		$Sql->query_inject("CREATE TABLE `phpboost_wiki_favorites` (
			`id` int(11) NOT NULL auto_increment,
			`user_id` int(11) NOT NULL default '0',
			`id_article` int(11) NOT NULL default '0',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__); 

		//Pages
		$Sql->query_inject("CREATE TABLE `phpboost_pages` (
		  `id` int(11) NOT NULL auto_increment,
		  `title` varchar(255) NOT NULL default '',
		  `encoded_title` varchar(255) NOT NULL default '',
		  `contents` text NOT NULL,
		  `auth` text NOT NULL,
		  `is_cat` tinyint(1) NOT NULL default '0',
		  `id_cat` int(11) NOT NULL default '0',
		  `hits` int(11) NOT NULL default '0',
		  `count_hits` tinyint(1) NOT NULL default '0',
		  `user_id` int(11) NOT NULL default '0',
		  `timestamp` int(11) NOT NULL default '0',
		  `activ_com` tinyint(1) NOT NULL default '0',
		  `nbr_com` int(11) NOT NULL default '0',  
		  `lock_com` tinyint(1) NOT NULL default '0',
		  `redirect` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`encoded_title`),
		  KEY `id` (`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__); 

		$Sql->query_inject("CREATE TABLE `phpboost_pages_cats` (
		  `id` int(11) NOT NULL auto_increment,
		  `id_page` int(11) NOT NULL default '0',
		  `id_parent` int(11) NOT NULL default '0',
		  KEY `id` (`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__); 
		
		redirect(HOST . FILE . add_lang('?step=3', true));
	}
	
	$Template->assign_block_vars('kernel_update', array());
	
	if (!is_dir('../cache/backup'))
	{	
		@mkdir('../cache/backup');
		@chmod('../cache/backup', 0777);
	}
		
	if (!is_dir('../cache/backup'))
	{
		$Template->assign_block_vars('kernel_update.error', array(
			'ERROR' => 'Il manque le dossier backup dans le dossier cache du site (cache/backup). Vous devez le créer manuellement!'
		));
	}
	
	$Template->assign_vars(array(
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=1'),
		'TARGET' => add_lang('update.php?step=2'),
		'U_NEXT_PAGE' => add_lang('update.php?step=3'),
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
}
//Articles
elseif ($step == 3)
{
	if ($go_to_next_step)
	{
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` CHANGE `contenu` `contents` TEXT NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` ADD `lock_com` TINYINT( 1 ) NOT NULL AFTER `nbr_com`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` CHANGE `nbr_com` `nbr_com` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` CHANGE `timestamp` `timestamp` INT DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` ADD `visible` TINYINT( 1 ) NOT NULL AFTER `timestamp`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` ADD `start` INT NOT NULL AFTER `visible`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` ADD `end` INT NOT NULL AFTER `start`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` DROP `aprob`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` ADD `icon` VARCHAR( 255 ) NOT NULL AFTER `contents`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` CHANGE `compt` `views` MEDIUMINT( 9 ) DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` CHANGE `votes` `users_note` TEXT NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "articles` DROP INDEX `id`, ADD INDEX `idcat` ( `idcat` )", __LINE__, __FILE__);
		
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "articles_cats` (
			`id` int(11) NOT NULL auto_increment,
			`id_left` int(11) NOT NULL default '0',
			`id_right` int(11) NOT NULL default '0',
			`level` int(11) NOT NULL default '0',
			`name` varchar(100) NOT NULL default '',
			`contents` text NOT NULL,
			`nbr_articles_visible` mediumint(9) unsigned NOT NULL default '0',
			`nbr_articles_unvisible` mediumint(9) unsigned NOT NULL default '0',
			`icon` varchar(255) NOT NULL default '',
			`aprob` tinyint(1) NOT NULL default '0',
			`auth` text NOT NULL,
			PRIMARY KEY  (`id`),
			KEY `id_left` (`id_left`)
		) ENGINE=MyISAM", __LINE__, __FILE__);

		$i = 1;
		$j = 2;
		$result = $Sql->query_while ("SELECT * FROM " . PREFIX . "admin_articles", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$aprob = ($row['aprob'] == 1) ? 0 : 1;
			
			$Sql->query_inject("INSERT INTO " . PREFIX . "articles_cats (id, id_left, id_right, level, name, contents, nbr_articles_visible, nbr_articles_unvisible, icon, aprob, auth) VALUES ('" . $row['idcat'] . "', '" . $i . "', '" . $j . "', '0', '" . addslashes($row['cat']) . "', '" . addslashes($row['contenu']) . "', '0', '0', '', '" . $aprob . "', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:3;s:2:\"r1\";i:7;s:2:\"r2\";i:7;}')", __LINE__, __FILE__);
			$i += 2;
			$j += 2;
		}
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "admin_articles`", __LINE__, __FILE__);

		redirect(HOST . FILE . add_lang('?step=4', true));
	}
	$Template->assign_block_vars('articles_update', array());
	$Template->assign_vars(array(
		'TARGET' => add_lang('update.php?step=3'),
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=2'),
		'U_NEXT_PAGE' => add_lang('update.php?step=4'),
		'L_IGNORE' => 'Ignorer cette étape',
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
}
//Calendrier
elseif ($step == 4)
{
	if ($go_to_next_step)
	{
		//Calendrier.
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "calendar` CHANGE `contenu` `contents` TEXT NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "calendar` CHANGE `nbr_com` `nbr_com` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "calendar` ADD `lock_com` TINYINT( 1 ) NOT NULL AFTER `nbr_com`", __LINE__, __FILE__); 
		redirect(HOST . FILE . add_lang('?step=5', true));
	}
	$Template->assign_block_vars('calendar_update', array());
	$Template->assign_vars(array(
		'TARGET' => add_lang('update.php?step=4'),
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=3'),
		'U_NEXT_PAGE' => add_lang('update.php?step=5'),
		'L_IGNORE' => 'Ignorer cette étape',
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
}
//Forum
elseif ($step == 5)
{
	if ($go_to_next_step)
	{
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "forum_config`", __LINE__, __FILE__); 
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "forum_alerts` ", __LINE__, __FILE__); 		
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "history` DROP `script`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "history` CHANGE `type` `action` SMALLINT( 6 ) DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "history` DROP INDEX `user_id`, ADD INDEX `user_id` ( `user_id` )", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "history` RENAME `" . PREFIX . "forum_history`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_track` ADD `pm` TINYINT( 1 ) NOT NULL AFTER `user_id`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_track` ADD `mail` TINYINT( 1 ) NOT NULL AFTER `pm`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_track` DROP INDEX `idtopic`, ADD UNIQUE `idtopic` ( `idtopic` , `user_id` )", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_poll` DROP INDEX `idtopic`, ADD UNIQUE `idtopic` ( `idtopic` )", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_topics` CHANGE `nbr_vus` `nbr_views` MEDIUMINT( 9 ) DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_topics` ADD `display_msg` TINYINT( 1 ) UNSIGNED NOT NULL AFTER `aprob`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_msg` CHANGE `contenu` `contents` TEXT NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_msg` ADD `user_ip` VARCHAR( 50 ) NOT NULL AFTER `user_id_edit`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_cats` ADD `id_left` INT NOT NULL AFTER `id`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_cats` ADD `id_right` INT NOT NULL AFTER `id_left`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_cats` ADD `level` INT NOT NULL AFTER `id_right`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_cats` CHANGE `status` `status` TINYINT( 1 ) DEFAULT '1' NOT NULL ", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_cats` CHANGE `secure` `auth` TEXT NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_cats` ADD INDEX `id_left` ( `id_left` )", __LINE__, __FILE__); 
		
		$i = 1;
		$j = 2;
		$start = false;
		$right = array();
		$current_cat = 0;
		$result = $Sql->query_while ("SELECT * FROM " . PREFIX . "forum_cats ORDER BY `class`", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if ($row['type'] == 0 && $start)
			{	
				$i += 1;
				$j += 1;
			}
			$aprob = ($row['aprob'] == 1) ? 0 : 1;
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $i . "', id_right = '" . $j . "', level = '" . $row['type'] . "', aprob = '" . $aprob . "', auth = 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:7;}' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			$i += 2;			
			$j += 2;
				
			if ($row['type'] == 0)
			{
				$i -= 1;
				$j -= 1;
				$start = true;
				$right[$row['id']] = isset($right[$current_cat]) ? $right[$current_cat] : 0;
				$current_cat = $row['id'];
			}
			if (isset($right[$current_cat])) 
				$right[$current_cat] += 2;
		}
		foreach ($right as $idcat => $right_edge)
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = '" . $right_edge . "' WHERE id = '" . $idcat . "'", __LINE__, __FILE__);

		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_cats` DROP `class`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "forum_cats` DROP `type`", __LINE__, __FILE__); 
				
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "forum_alerts` (
		`id` mediumint(11) NOT NULL auto_increment,
		`idcat` int(11) NOT NULL default '0',
		`idtopic` int(11) NOT NULL default '0',
		`title` varchar(255) NOT NULL default '',
		`contents` text NOT NULL,
		`user_id` int(11) NOT NULL default '0',
		`status` tinyint(1) NOT NULL default '0',
		`idmodo` int(11) NOT NULL default '0',
		`timestamp` int(11) NOT NULL default '0',
		PRIMARY KEY	(`id`),
		KEY `idtopic` (`idtopic`,`user_id`,`idmodo`)
		) ENGINE=MyISAM;", __LINE__, __FILE__); 
		
		redirect(HOST . FILE . add_lang('?step=6', true));
	}
	$Template->assign_block_vars('forum_update', array());
	$Template->assign_vars(array(
		'TARGET' => add_lang('update.php?step=5'),
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=4'),
		'U_NEXT_PAGE' => add_lang('update.php?step=6'),
		'L_IGNORE' => 'Ignorer cette étape',
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
}
//Galerie
elseif ($step == 6)
{
	if ($go_to_next_step)
	{
		$Sql->query_inject("CREATE TABLE `phpboost_gallery` (
		`id` int(11) NOT NULL auto_increment,
		`idcat` int(11) NOT NULL default '0',
		`name` varchar(255) NOT NULL default '',
		`path` varchar(255) NOT NULL default '',
		`width` mediumint(9) NOT NULL default '0',
		`height` mediumint(9) NOT NULL default '0',
		`weight` mediumint(9) NOT NULL default '0',
		`user_id` int(11) NOT NULL default '0',
		`aprob` tinyint(1) NOT NULL default '0',
		`views` int(11) NOT NULL default '0',
		`timestamp` int(11) NOT NULL default '0',
		`users_note` text NOT NULL,
		`nbrnote` mediumint(9) NOT NULL default '0',
		`note` float NOT NULL default '0',
		`nbr_com` int(11) unsigned NOT NULL default '0',
		`lock_com` tinyint(1) NOT NULL default '0',
		PRIMARY KEY	(`id`),
		KEY `idcat` (`idcat`)
		) ENGINE=MyISAM", __LINE__, __FILE__);

		$Sql->query_inject("CREATE TABLE `phpboost_gallery_cats` (
		`id` int(11) NOT NULL auto_increment,
		`id_left` int(11) NOT NULL default '0',
		`id_right` int(11) NOT NULL default '0',
		`level` int(11) NOT NULL default '0',
		`name` varchar(150) NOT NULL default '',
		`contents` text NOT NULL,
		`nbr_pics_aprob` mediumint(9) unsigned NOT NULL default '0',
		`nbr_pics_unaprob` mediumint(9) unsigned NOT NULL default '0',
		`status` tinyint(1) NOT NULL default '0',
		`aprob` tinyint(1) NOT NULL default '1',
		`auth` text NOT NULL,
		PRIMARY KEY	(`id`),
		KEY `id_left` (`id_left`)
		) ENGINE=MyISAM", __LINE__, __FILE__);

		$result = $Sql->query_while ("SELECT * FROM " . PREFIX . "album", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$image_name = explode('/', $row['large']);
			$image_infos = getimagesize('../album/' . $image_name[1]);
			
			$aprob = ($row['aprob'] == 1) ? 0 : 1;
			
			$Sql->query_inject("INSERT INTO " . PREFIX . "gallery (id, idcat, name, path, width, height, weight, user_id, aprob, views, timestamp, users_note, nbrnote, note, nbr_com, lock_com) VALUES ('" . $row['idphoto'] . "', '" . $row['cat'] . "', '" . addslashes($row['name']) . "', '" . addslashes($image_name[1]) . "', '" . $image_infos[0] . "', '" . $image_infos[1] . "' , '" . filesize('../album/' . $image_name[1]) . "', '" . $row['user'] . "', '" . $aprob . "', '" . $row['compt'] . "', '" . $row['timestamp'] . "', '" . $row['user_id'] . "', '" . $row['nbrnote'] . "', '" . $row['note'] . "', '" . $row['nbr_com'] . "', '0')", __LINE__, __FILE__);
		}

		$i = 1;
		$j = 2;
		$result = $Sql->query_while ("SELECT * FROM " . PREFIX . "admin_albumcat", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$Sql->query_inject("INSERT INTO " . PREFIX . "gallery_cats (id, id_left, id_right, level, name, contents, nbr_pics_aprob, nbr_pics_unaprob, status, aprob, auth) VALUES ('" . $row['idcat'] . "', '" . $i . "', '" . $j . "', '0', '" . addslashes($row['cat']) . "', '', '0', '0', '1', '1', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:3;s:2:\"r1\";i:7;s:2:\"r2\";i:7;}')", __LINE__, __FILE__);
			$i += 2;
			$j += 2;
		}
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "album`", __LINE__, __FILE__); 
		$Sql->query_inject("DROP TABLE IF EXISTS  `" . PREFIX . "admin_albumcat`", __LINE__, __FILE__); 

		redirect(HOST . FILE . add_lang('?step=7', true));
	}
	$Template->assign_block_vars('gallery_update', array());
	$Template->assign_vars(array(
		'TARGET' => add_lang('update.php?step=6'),
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=5'),
		'U_NEXT_PAGE' => add_lang('update.php?step=7'),
		'L_IGNORE' => 'Ignorer cette étape',
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
	if (!@rename('../album/mini', '../album/thumbnails'))
	{
		$Template->assign_block_vars('gallery_update.error', array(
			'ERROR' => 'Veuillez renommer manuellement le dossier album/mini en album/thumbnails par l\'intermédiaire de votre client ftp'
		));
	}
	if (!@rename('../album', '../gallery/pics'))
	{
		$Template->assign_block_vars('gallery_update.error', array(
			'ERROR' => 'Veuillez déplacer manuellement le dossier album dans le dossier gallery par l\'intermédiaire de votre client ftp'
		));
	}
}
//Livre d'or
elseif ($step == 7)
{
	if ($go_to_next_step)
	{
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "livreor` CHANGE `login` `login` VARCHAR( 255 ) DEFAULT '' NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "livreor` CHANGE `contenu` `contents` TEXT NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "livreor` RENAME `" . PREFIX . "guestbook`", __LINE__, __FILE__); 
		redirect(HOST . FILE . add_lang('?step=8', true));
	}
	$Template->assign_block_vars('guestbook_update', array());
	$Template->assign_vars(array(
		'TARGET' => add_lang('update.php?step=7'),
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=6'),
		'U_NEXT_PAGE' => add_lang('update.php?step=8'),
		'L_IGNORE' => 'Ignorer cette étape',
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
}
//News
elseif ($step == 8)
{
	if ($go_to_next_step)
	{
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` ADD PRIMARY KEY ( `id` ) ", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` DROP INDEX `id`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` ADD `idcat` INT NOT NULL AFTER `id`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` ADD INDEX `idcat` ( `idcat` )", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` CHANGE `contenu` `contents` TEXT NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` ADD `extend_contents` TEXT NOT NULL AFTER `contents`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` ADD `archive` TINYINT( 1 ) NOT NULL AFTER `extend_contents`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` CHANGE `timestamp` `timestamp` INT DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` ADD `visible` TINYINT( 1 ) NOT NULL AFTER `timestamp`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` ADD `start` INT NOT NULL AFTER `visible`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` ADD `end` INT NOT NULL AFTER `start`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` DROP `aprob`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` CHANGE `photos` `img` VARCHAR( 250 ) NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` ADD `lock_com` TINYINT( 1 ) NOT NULL AFTER `nbr_com`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "news` CHANGE `nbr_com` `nbr_com` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("UPDATE `" . PREFIX . "news` SET visible = 1", __LINE__, __FILE__); 
		$Sql->query_inject("CREATE TABLE `" . PREFIX . "news_cat` (
			`id` int(11) NOT NULL auto_increment,
			`name` varchar(150) NOT NULL default '',
			`contents` text NOT NULL,
			`icon` varchar(255) NOT NULL default '',
			PRIMARY KEY	(`id`)
		) ENGINE=MyISAM", __LINE__, __FILE__); 
		$Sql->query_inject("INSERT INTO `" . PREFIX . "news_cat` (`id`, `name`, `contents`, `icon`) VALUES (1, 'Test', 'Cat&eacute;gorie de test', 'news.png')", __LINE__, __FILE__);  

		redirect(HOST . FILE . add_lang('?step=9', true));
	}
	$Template->assign_block_vars('news_update', array());
	$Template->assign_vars(array(
		'TARGET' => add_lang('update.php?step=8'),
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=7'),
		'U_NEXT_PAGE' => add_lang('update.php?step=9'),
		'L_IGNORE' => 'Ignorer cette étape',
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
}
//Pages
elseif ($step == 9)
{
	if ($go_to_next_step)
	{
		$i = 0;
		$get_title = '';
		//On explore le dossier pages/
		if (is_dir('../pages'))
		{
		   if ($dh = opendir($dir))
			{
				while (($file = readdir($dh)) !== false)
				{
					$contents = @readfile('../pages/' . $file);
					if (preg_match('`^[a-z0-9._-]+\.php$`', $file) && preg_match('`\$TITLE = \'\$TITLE = "(.+)";\';`isU', $contents, $get_title))				
					{
						$title = stripslashes($get_title[1]);
						$contents = preg_replace('`<\?php(.+)\?>`isU', '', $contents, 1);
						$contents = preg_replace('`<\?php .* include_once\(\'../kernel/footer.php\'\); \?>`isU', '', $contents);
						$contents = preg_replace('`<!-- START -->(.*)<!-- END -->`is', '$1', $contents);
						$contents = trim($contents);
						$Sql->query_inject("INSERT INTO " . PREFIX . "pages ('title', 'encoded_title', 'contents', 'auth', 'is_cat', 'id_cat', 'hits', 'count_hits', 'user_id', 'timestamp', 'activ_com', 'nbr_com', 'lock_com', 'redirect') VALUES ('" . $title . "', '" . str_replace('.php', '', $file) . "', '" . $contents . "', '', '0', '0', '0', '1', '1', '" . time() . "' . '1', '0', '0', '0')", __LINE__, __FILE__);
					}
				}
			}
		}
		redirect(HOST . FILE . add_lang('?step=10', true));
	}
	$Template->assign_block_vars('pages_update', array());
	$Template->assign_vars(array(
		'TARGET' => add_lang('update.php?step=9'),
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=8'),
		'U_NEXT_PAGE' => add_lang('update.php?step=10'),
		'L_IGNORE' => 'Ignorer cette étape',
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
}
//Shoutbox
elseif ($step == 10)
{
	if ($go_to_next_step)
	{
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "shoutbox` CHANGE `user_id` `user_id` INT DEFAULT '0' NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "shoutbox` CHANGE `contenu` `contents` TEXT NOT NULL", __LINE__, __FILE__);  
		redirect(HOST . FILE . add_lang('?step=11', true));
	}
	$Template->assign_block_vars('shoutbox_update', array());
	$Template->assign_vars(array(
		'TARGET' => add_lang('update.php?step=10'),
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=9'),
		'U_NEXT_PAGE' => add_lang('update.php?step=11'),
		'L_IGNORE' => 'Ignorer cette étape',
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
}
//Liens web
elseif ($step == 11)
{
	if ($go_to_next_step)
	{
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "web` CHANGE `idcat` `idcat_tmp` INT( 11 ) DEFAULT '0' NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "web` ADD `idcat` INT NOT NULL AFTER `id`", __LINE__, __FILE__); 
		$Sql->query_inject("UPDATE `" . PREFIX . "web` SET idcat = idcat_tmp", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "web` DROP `idcat_tmp`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "web` CHANGE `contenu` `contents` TEXT NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "web` CHANGE `user_id` `users_note` TEXT NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "web` ADD `lock_com` TINYINT( 1 ) NOT NULL AFTER `nbr_com`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "web` CHANGE `nbr_com` `nbr_com` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "web` DROP INDEX `idcat`, ADD INDEX `idcat` ( `idcat` )", __LINE__, __FILE__);  
		$Sql->query_inject("UPDATE `" . PREFIX . "admin_web` SET id = idcat", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_web` DROP `idcat`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_web` CHANGE `cat` `name` VARCHAR( 150 ) NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_web` CHANGE `contenu` `contents` TEXT NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_web` ADD `icon` VARCHAR( 255 ) NOT NULL AFTER `contents`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_web` ADD `secure` TINYINT( 1 ) NOT NULL AFTER `aprob`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_web` DROP INDEX `idcat` , ADD INDEX `class` ( `class` ) ", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_web` RENAME `" . PREFIX . "web_cat`", __LINE__, __FILE__); 

		redirect(HOST . FILE . add_lang('?step=12', true));
	}
	$Template->assign_block_vars('web_update', array());
	$Template->assign_vars(array(
		'TARGET' => add_lang('update.php?step=11'),
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=10'),
		'U_NEXT_PAGE' => add_lang('update.php?step=12'),
		'L_IGNORE' => 'Ignorer cette étape',
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
}
//Téléchargements
elseif ($step == 12)
{
	if ($go_to_next_step)
	{
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` CHANGE `idcat` `idcat_tmp` INT( 11 ) DEFAULT '0' NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` ADD `idcat` INT NOT NULL AFTER `id`", __LINE__, __FILE__); 
		$Sql->query_inject("UPDATE `" . PREFIX . "download` SET idcat = idcat_tmp", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` DROP `idcat_tmp`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` CHANGE `user_id` `users_note` TEXT NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` CHANGE `timestamp` `timestamp` INT DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` ADD `visible` TINYINT( 1 ) NOT NULL AFTER `timestamp`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` ADD `start` INT NOT NULL AFTER `visible`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` ADD `end` INT NOT NULL AFTER `start`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` ADD `user_id` INT NOT NULL AFTER `end`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` DROP `aprob`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` CHANGE `contenu` `contents` TEXT NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` CHANGE `nbr_com` `nbr_com` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` ADD `lock_com` TINYINT( 1 ) NOT NULL AFTER `nbr_com`", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "download` DROP INDEX `idcat` , ADD INDEX `idcat` ( `idcat` )", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE `" . PREFIX . "admin_download` SET id = idcat", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_download` DROP `idcat`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_download` CHANGE `cat` `name` VARCHAR( 150 ) NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_download` CHANGE `contenu` `contents` TEXT NOT NULL", __LINE__, __FILE__);  
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_download` ADD `icon` VARCHAR( 255 ) NOT NULL AFTER `contents`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_download` ADD `secure` TINYINT( 1 ) NOT NULL AFTER `aprob`", __LINE__, __FILE__); 
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_download` DROP INDEX `idcat` , ADD INDEX `class` ( `class` ) ", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE `" . PREFIX . "admin_download` RENAME `" . PREFIX . "download_cat`", __LINE__, __FILE__); 
		
		redirect(HOST . FILE . add_lang('?step=13', true));
	}
	$Template->assign_block_vars('download_update', array());
	$Template->assign_vars(array(
		'TARGET' => add_lang('update.php?step=12'),
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=11'),
		'U_NEXT_PAGE' => add_lang('update.php?step=13'),
		'L_IGNORE' => 'Ignorer cette étape',
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
}
//Finalisation
elseif ($step == 13)
{
	if ($go_to_next_step)
	{
		$Cache = new Cache; //!\\Initialisation  de la class de gestion du cache//!\\
		
		//Régénération du cache
		$Cache->Generate_file('htaccess'); //Régénération du htaccess.	
		$Cache->Generate_all_files();
		redirect(HOST . FILE . add_lang('?step=14', true));
	}
	$Template->assign_block_vars('cache', array());
	$Template->assign_vars(array(
		'TARGET' => add_lang('update.php?step=13'),
		'U_PREVIOUS_PAGE' => add_lang('update.php?step=12'),
		'U_NEXT_PAGE' => add_lang('update.php?step=14'),
		'L_IGNORE' => 'Ignorer cette étape',
		'L_PREVIOUS_STEP' => 'Etape précédente',
		'L_NEXT_STEP' => 'Etape suivante'
	));
}
//Fin
elseif ($step == 14)
{
	$Template->assign_block_vars('end', array());
	$Template->assign_vars(array());
}

$steps = array(
	array('Préambule', 0),
	array('Mise à jour du noyau', 25),
	array('Mise à jour du module article', 30),
	array('Mise à jour du module calendrier', 35),
	array('Mise à jour du module forum', 40),
	array('Mise à jour du module galerie', 45),
	array('Mise à jour du module livre d\'or', 50),
	array('Mise à jour du module news', 60),
	array('Mise à jour du module pages', 75),
	array('Mise à jour du module shoutbox', 85),
	array('Mise à jour du module liens web', 90),
	array('Mise à jour du module téléchargements', 95),
	array('Finalisation', 100),
	array('Fin de la mise à jour', 100)
);

$step_name = $steps[$step - 1][0];

$Template->assign_vars(array(
	'LANG' => $lang,
	'NUM_STEP' => $step,
	'PROGRESS_BAR_PICS' => str_repeat('<img src="templates/images/loading.png" alt="" />', floor($steps[$step - 1][1] * 24 / 100)),
	'PROGRESS_LEVEL' => $steps[$step - 1][1],
	'L_TITLE' => 'Mise à jour : PHPBoost 1.6.0 -> PHPBoost 2.0' . ' - ' . $step_name,
	'L_STEP' => $step_name,
	'L_STEPS_LIST' => $LANG['steps_list'],
	'L_LICENSE' => $LANG['license'],
	'L_INSTALL_PROGRESS' => $LANG['install_progress'],
	'L_GENERATED_BY' => sprintf($LANG['generated_by'], '<a href="http://www.phpboost.com" style="color:#799cbb;">PHPBoost ' . $update_version . '</a>'),
	'L_APPENDICES' => $LANG['appendices'],
	'L_DOCUMENTATION' => $LANG['documentation'],
	'U_DOCUMENTATION' => $LANG['documentation_link'],
	'L_RESTART_INSTALL' => $LANG['restart_installation'],
	'L_CONFIRM_RESTART' => $LANG['confirm_restart_installation'],
	'L_LANG' => $LANG['change_lang'],
	'L_CHANGE' => $LANG['change'],
	'U_RESTART' => add_lang('install.php')
));

for ($i = 1; $i <= 14; $i++)
{
	if ($i < $step)
		$row = 'row_success';
	elseif ($i == $step)
		$row = 'row_current';
	else
		$row = 'row_next';
	$Template->assign_block_vars('link_menu', array(
		'ROW' => '<tr>
				<td class="' . $row . '">
					' . $steps[$i - 1][0] . '
				</td>				
			</tr>'
	));
}

$Template->pparse('update');

ob_end_flush();

?>