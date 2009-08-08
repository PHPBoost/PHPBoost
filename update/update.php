<?php
/*##################################################
 *                                update.php
 *                            -------------------
 *   begin                : March 20, 2009
 *   copyright            : (C) 2009    Sautel Benoit
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

//A personnaliser
define('UPDATE_VERSION', '3.0');
define('DEFAULT_LANGUAGE_UPDATE', 'french');
define('DEFAULT_THEME_UPDATE', 'base');
define('STEPS_NUMBER', 6);
define('STEP_INTRODUCTION', 1);
define('STEP_SERVER_CONFIG', 2);
define('STEP_DB_CONFIG', 3);
define('STEP_DB_MAJ', 4);

define('DEBUG', false);

ob_start();

define('PATH_TO_ROOT', '..');
define('TPL_PATH_TO_ROOT', PATH_TO_ROOT);

header('Content-type: text/html; charset=iso-8859-1');
header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
header('Pragma: no-cache');

//Inclusion des fichiers
require_once(PATH_TO_ROOT . '/kernel/framework/functions.inc.php'); //Fonctions de base.
require_once(PATH_TO_ROOT . '/kernel/constant.php'); //Constante utiles.

import('core/errors');
import('io/template');
import('db/mysql');
import('core/cache');
import('members/session');
import('members/user');
import('members/groups');
import('members/authorizations');
import('core/breadcrumb');
import('content/parser/content_formatting_factory');

@error_reporting(ERROR_REPORTING);

define('HOST', 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')));
$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
define('FILE', $server_path);
define('DIR', str_replace('/update/update.php', '', $server_path));
define('SID', '');

$step = retrieve(GET, 'step', 1, TUNSIGNED_INT);
$step = $step > STEPS_NUMBER ? 1 : $step;

$lang = retrieve(GET, 'lang', DEFAULT_LANGUAGE_UPDATE);

//Inclusion du fichier langue
include_once('lang/' . DEFAULT_LANGUAGE_UPDATE . '/update_' . DEFAULT_LANGUAGE_UPDATE . '.php');

//Création de l'utilisateur
$user_data = array(
    'm_user_id' => 1,
    'login' => 'login',
    'level' => ADMIN_LEVEL,
    'user_groups' => '',
    'user_lang' => $lang,
    'user_theme' => DEFAULT_THEME_UPDATE,
    'user_mail' => '',
    'user_pm' => 0,
    'user_editor' => 'bbcode',
    'user_timezone' => 1,
    'avatar' => '',
    'user_readonly' => 0,
    'user_id' => 1,
    'session_id' => ''
);
$user_groups = array();
$User = new User($user_data, $user_groups);

//On vérifie que le dossier cache/tpl existe et est inscriptible, sans quoi on ne peut pas mettre en cache les fichiers et donc afficher l'installateur
if (!is_dir('../cache') || !is_writable('../cache') || !is_dir('../cache/tpl') || !is_writable('../cache/tpl'))
{
    die($LANG['cache_tpl_must_exist_and_be_writable']);
}

//Fonction pour gérer la langue
function add_lang($url, $header_location = false)
{
	global $lang;
	if ($lang != DEFAULT_LANGUAGE_UPDATE)
	{
		if (strpos($url, '?') !== false)
		{
			$ampersand = $header_location ? '&' : '&amp;';
			return $url . $ampersand . 'lang=' . $lang;
		}
		else
		{
			return $url . '?' . 'lang=' . $lang;
		}
	}
	else
	{
		return $url;
	}
}

//Template d'installation
$template = new Template('update/update.tpl', DO_NOT_AUTO_LOAD_FREQUENT_VARS);

//Préambule
switch($step)
{
    //Préambule
    case STEP_INTRODUCTION:
        $template->assign_vars(array(
            'C_INTRO' => true,
            'L_INTRO_TITLE' => $LANG['intro_title'],
            'L_INTRO_EXPLAIN' => $LANG['intro_explain'],
            'L_NEXT_STEP' => 'update.php?step=' . (STEP_INTRODUCTION + 1),
            'L_START_INSTALL' => $LANG['start_install']
        ));
       break;
    //Configuration du serveur
    case STEP_SERVER_CONFIG:
        //Url rewriting
        if (function_exists('apache_get_modules'))
        {
            $get_rewrite = apache_get_modules();
            $check_rewrite = (!empty($get_rewrite[5])) ? 1 : 0;
        }
        else
        {
            $check_rewrite = -1;
        }
        
        $template->assign_vars(array(
            'C_SERVER_CONFIG' => true,
            'C_PHP_VERSION_OK' => phpversion() >= '4.1.0',
            'C_GD_LIBRAIRY_ENABLED' => @extension_loaded('gd'),
            'C_URL_REWRITING_KNOWN' => $check_rewrite != -1,
            'C_URL_REWRITING_ENABLED' => $check_rewrite == 1
        ));
        
        //Mise à jour du cache de Apache à propos du système de fichiers
        @clearstatcache();
        
        $chmod_dir = array('../cache', '../cache/backup', '../cache/syndication', '../cache/tpl', '../images/avatars', '../images/group', '../images/maths', '../images/smileys', '../kernel/db', '../lang', '../menus', '../templates', '../upload');
        
        $all_dirs_ok = true;
        
        //Vérifications et le cas échéant tentative de changement des autorisations en écriture.
        foreach ($chmod_dir as $dir)
        {
            $is_writable = $is_dir = true;
            //If the file exists and is a directory
            if (file_exists($dir) && is_dir($dir))
            {
                //Si il n'est pas inscriptible, on demande à Apache de le rendre inscriptible en espérant qu'il soit configurer pour accepter de telles requêtes
                if (!is_writable($dir))
                {
                    $is_writable = (@chmod($dir, 0777)) ? true : false;
                }
            }
            else
            {
                $is_dir = $is_writable = ($fp = @mkdir($dir, 0777)) ? true : false;
            }
                
            $template->assign_block_vars('chmod', array(
                'TITLE' => str_replace('..' , '', $dir),
                'C_EXISTING_DIR' => $is_dir,
                'C_WRITIBLE_DIR' => $is_writable
            ));
            
            if ($all_dirs_ok && (!$is_dir || !$is_writable))
            {
                $all_dirs_ok = false;
            }
        }
        
        //On empêche de passer à l'étape suivant si cette étape n'est pas validée
        if (retrieve(POST, 'submit', false))
        {
            if (!$all_dirs_ok)
            {
                $template->assign_vars(array(
                    'C_ERROR' => true,
                    'L_ERROR' => $LANG['config_server_dirs_not_ok']
                ));
            }
            else
            {
                redirect(HOST . FILE . '?step=' . (STEP_SERVER_CONFIG + 1));
            }
        }
        
        $template->assign_vars(array(
            'L_CONFIG_SERVER_TITLE' => $LANG['config_server_title'],
            'L_CONFIG_SERVER_EXPLAIN' => $LANG['config_server_explain'],
            'L_PHP_VERSION' => $LANG['php_version'],
            'L_CHECK_PHP_VERSION' => $LANG['check_php_version'],
            'L_CHECK_PHP_VERSION_EXPLAIN' => $LANG['check_php_version_explain'],
            'L_EXTENSIONS' => $LANG['extensions'],
            'L_CHECK_EXTENSIONS' => $LANG['check_extensions'],
            'L_GD_LIBRARY' => $LANG['gd_library'],
            'L_GD_LIBRARY_EXPLAIN' => $LANG['gd_library_explain'],
            'L_URL_REWRITING' => $LANG['url_rewriting'],
            'L_URL_REWRITING_EXPLAIN' => $LANG['url_rewriting_explain'],
            'L_AUTH_DIR' => $LANG['auth_dir'],
            'L_CHECK_AUTH_DIR' => $LANG['check_auth_dir'],
            'L_EXISTING' => $LANG['existing'],
            'L_NOT_EXISTING' => $LANG['unexisting'],
            'L_WRITABLE' => $LANG['writable'],
            'L_NOT_WRITABLE' => $LANG['unwritable'],
            'L_REFRESH' => $LANG['refresh_chmod'],
            'L_RESULT' => $LANG['result'],
            'L_QUERY_LOADING' => $LANG['query_loading'],
            'L_QUERY_SENT' => $LANG['query_sent'],
            'L_QUERY_PROCESSING' => $LANG['query_processing'],
            'L_QUERY_SUCCESS' => $LANG['query_success'],
            'L_QUERY_FAILURE' => $LANG['query_failure'],
            'L_NEXT_STEP' => $LANG['next_step'],
            'L_PREVIOUS_STEP' => $LANG['previous_step'],
            'U_PREVIOUS_STEP' => 'update.php?step=' . (STEP_SERVER_CONFIG - 1),
            'U_CURRENT_STEP' => 'update.php?step=' . STEP_SERVER_CONFIG,
            'U_NEXT_STEP' => 'update.php?step=' . (STEP_SERVER_CONFIG + 1)
        ));
        break;
    //Mise en place de la base de données
    case STEP_DB_CONFIG:
        require_once('functions.php');
        
        if (retrieve(POST, 'submit', false))
        {
            //Récupération de la configuration de connexion
            $host = retrieve(POST, 'host', 'localhost');
            $login = retrieve(POST, 'login', '');
            $password = retrieve(POST, 'password', '');
            $database = str_replace('.', '_', retrieve(POST, 'database', ''));
            $tables_prefix = str_replace('.', '_', retrieve(POST, 'tableprefix', 'phpboost_', TSTRING, USE_DEFAULT_IF_EMPTY));
            
            include_once('functions.php');
            if (!empty($host) && !empty($login) && !empty($database))
            {
                $result = check_database_config($host, $login, $password, $database, $tables_prefix);
            }
            else
            {
                $result = DB_UNKNOW_ERROR;
            }
            
            switch ($result)
            {
                case DB_CONFIG_SUCCESS:
                    $Errorh = new Errors;

					$Cache = new Cache(); //!\\Initialisation  de la class de gestion du cache//!\\
					
                    //Création du fichier de configuration
                    import('io/filesystem/file');
                    
                    $file_path = '../kernel/db/config.php';
                    
                    $db_config_content = '<?php' . "\n" . 
                        'if (!defined(\'DBSECURE\'))'  . "\n" .
                        '{' . "\n" .
                        '   $sql_host = "' . $host . '"; //Adresse serveur MySQL - MySQL server address' . "\n" .
                        '   $sql_login = "' . $login . '"; //Login' . "\n" .
                        '   $sql_pass = "' . $password . '"; //Mot de passe - Password' . "\n" .
                        '   $sql_base = "' . $database . '"; //Nom de la base de données - Database name' . "\n" .
                        '   define(\'PREFIX\' , \'' . $tables_prefix . '\'); //Préfixe des tables - Tables prefix' . "\n" .
                        '   define(\'DBSECURE\', true);' . "\n" .
                        '   define(\'PHPBOOST_INSTALLED\', true);' . "\n" .
                        '   ' . "\n" .        
                        '   require_once PATH_TO_ROOT . \'/kernel/db/tables.php\';' . "\n" .
                        '}' . "\n" .
                        'else' . "\n" .
                        '{' . "\n" .
                        '   exit;' . "\n" .
                        '}' . "\n" .
                        '?>';
                    
                    //Ouverture du fichier kernel/db/config.php
                    $db_config_file = new File($file_path);
                    //Ecriture de son contenu (les variables de configuration)
                    $db_config_file->write($db_config_content);
                    //Fermeture du fichier dont on n'a plus besoin
                    $db_config_file->close();
					
                    redirect(HOST . FILE . '?step=4');
                    break;
                case DB_CONFIG_ERROR_CONNECTION_TO_DBMS:
                    $error = '<div class="error">' . $LANG['db_error_connexion'] . '</div>';
                    break;
                case DB_CONFIG_ERROR_DATABASE_NOT_FOUND:
                    $error = '<div class="error">La base de données que vous avez choisie n\'existe pas.</div>';
                    break;
                case DB_CONFIG_TABLES_DONT_EXIST:
                    $error = '<div class="error">La base de données que vous avez choisie ne semble pas contenir d\'installation de PHPBoost 2.0. Choisissez une base de données contenant une installation de PHPBoost 2.0. Modifiez au besoin le préfixe des tables.</div>';
                    break;
                case DB_UNKNOW_ERROR:
                default:
                    $error = '<div class="error">' . $LANG['db_unknown_error'] . '</div>';
            }
        }
        //Default values for the variables
        else
        {
            $host = 'localhost';
            $login = '';
            $password = '';
            $database = '';
            $tables_prefix = 'phpboost_';
        }
        
        $template->assign_vars(array(
            'C_DATABASE_CONFIG' => true,
            'C_DISPLAY_RESULT' => !empty($error),
            'ERROR' => !empty($error) ? $error : '',
            'PROGRESS' => !empty($error) ? '100' : '0',
            'PROGRESS_STATUS' => !empty($error) ? $LANG['query_success'] : '',
            'PROGRESS_BAR' => !empty($error) ? str_repeat('<img src="templates/images/progress.png" alt="">', 56) : '',
            'HOST_VALUE' => $host,
            'LOGIN_VALUE' => $login,
            'PASSWORD_VALUE' => $password,
            'DB_NAME_VALUE' => $database,
            'PREFIX_VALUE' => $tables_prefix,
            'U_PREVIOUS_STEP' => 'update.php?step=2',
            'U_CURRENT_STEP' => 'update.php?step=3',
            'DB_CONFIG_SUCCESS' => DB_CONFIG_SUCCESS,
            'DB_CONFIG_ERROR_CONNECTION_TO_DBMS' => DB_CONFIG_ERROR_CONNECTION_TO_DBMS,
            'DB_CONFIG_ERROR_DATABASE_NOT_FOUND' => DB_CONFIG_ERROR_DATABASE_NOT_FOUND,
            'DB_CONFIG_TABLES_DONT_EXIST' => DB_CONFIG_TABLES_DONT_EXIST,
            'DB_UNKNOW_ERROR' => DB_UNKNOW_ERROR,
            'L_DB_CONFIG_SUCESS' => addslashes($LANG['db_success']),
            'L_DB_CONFIG_ERROR_CONNECTION_TO_DBMS' => addslashes($LANG['db_error_connexion']),
            'L_DB_CONFIG_ERROR_DATABASE_NOT_FOUND' => addslashes($LANG['db_error_selection']),
            'L_DB_CONFIG_TABLES_DONT_EXIST' => 'La base de données que vous avez choisie ne semble pas contenir d\\\'installation de PHPBoost 2.0. Choisissez une base de données contenant une installation de PHPBoost 2.0. Modifiez au besoin le préfixe des tables.',
            'L_UNKNOWN_ERROR' => $LANG['db_unknown_error'],
            'L_DB_EXPLAIN' => $LANG['db_explain'],
            'L_DB_TITLE' => $LANG['db_title'],
            'L_SGBD_PARAMETERS' => $LANG['dbms_paramters'],
            'L_DB_PARAMETERS' => $LANG['db_properties'],
            'L_HOST' => $LANG['db_host_name'],
            'L_HOST_EXPLAIN' => $LANG['db_host_name_explain'],
            'L_LOGIN' => $LANG['db_login'],
            'L_LOGIN_EXPLAIN' => $LANG['db_login_explain'],
            'L_PASSWORD' => $LANG['db_password'],
            'L_PASSWORD_EXPLAIN' => $LANG['db_password_explain'],
            'L_DB_NAME' => $LANG['db_name'],
            'L_DB_NAME_EXPLAIN' => $LANG['db_name_explain'],
            'L_DB_PREFIX' => $LANG['db_prefix'],
            'L_DB_PREFIX_EXPLAIN' => $LANG['db_prefix_explain'],
            'L_TEST_DB_CONFIG' => $LANG['test_db_config'],
            'L_PREVIOUS_STEP' => $LANG['previous_step'],
            'L_NEXT_STEP' => $LANG['next_step'],
            'L_QUERY_LOADING' => $LANG['query_loading'],
            'L_QUERY_SENT' => $LANG['query_sent'],
            'L_QUERY_PROCESSING' => $LANG['query_processing'],
            'L_QUERY_SUCCESS' => $LANG['query_success'],
            'L_QUERY_FAILURE' => $LANG['query_failure'],
            'L_RESULT' => $LANG['db_result'],
            'L_REQUIRE_HOSTNAME' => $LANG['require_hostname'],
            'L_REQUIRE_LOGIN' => $LANG['require_login'],
            'L_REQUIRE_DATABASE_NAME' => $LANG['require_db_name'],
            'L_ALREADY_INSTALLED' => $LANG['already_installed'],
            'L_ALREADY_INSTALLED_EXPLAIN' => $LANG['already_installed_explain'],
            'L_ALREADY_INSTALLED_OVERWRITE' => $LANG['already_installed_overwrite']
        ));
        break;
	case STEP_DB_MAJ :
		if (retrieve(POST, 'submit', false))
        {
			import('io/filesystem/folder');
			$cache_folder_path = new Folder('../cache/');
			foreach($cache_folder_path->get_files('`\.php$`')as $cache)
			{
				$cache->delete();
			}

			$Errorh = new Errors;
			$Sql = new Sql();
			
			include_once '../kernel/db/config.php';
			//Connexion
			$Sql->connect($sql_host, $sql_login, $sql_pass, $sql_base, ERRORS_MANAGEMENT_BY_RETURN);
			
			$Cache = new Cache(); //!\\Initialisation  de la class de gestion du cache//!\\
			
			$MODULES = array();
			$query = "SELECT name
            FROM " . DB_TABLE_MODULES;
			$result = $Sql->query_while ($query, __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$MODULES[$row['name']] = true;
			}
			
			//On parse le fichier de mise à jour de la structure de la base de données
			$Sql->parse('migration_2.0_to_3.0.sql');
			
			$Cache->load('modules'); //Cache des autorisations des modules
			if (isset($MODULES['forum']))
			{	
				$Sql->parse('migration_forum_2.0_to_3.0.sql');
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (11, 'forum', 'a:15:{s:10:\"forum_name\";s:14:\"PHPBoost forum\";s:16:\"pagination_topic\";i:20;s:14:\"pagination_msg\";i:15;s:9:\"view_time\";i:2592000;s:11:\"topic_track\";i:40;s:9:\"edit_mark\";i:1;s:14:\"no_left_column\";i:0;s:15:\"no_right_column\";i:0;s:17:\"activ_display_msg\";i:1;s:11:\"display_msg\";s:21:\"[R&eacute;gl&eacute;]\";s:19:\"explain_display_msg\";s:26:\"Sujet r&eacute;gl&eacute;?\";s:23:\"explain_display_msg_bis\";s:30:\"Sujet non r&eacute;gl&eacute;?\";s:22:\"icon_activ_display_msg\";i:1;s:4:\"auth\";s:19:\"a:1:{s:2:\"r2\";i:7;}\";s:17:\"display_connexion\";i:0;}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['download']))
			{	
				$Sql->parse('migration_download_2.0_to_3.0.sql');
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (9, 'download', 'a:5:{s:12:\"nbr_file_max\";i:10;s:10:\"nbr_column\";i:2;s:8:\"note_max\";i:5;s:13:\"root_contents\";s:50:\"Bienvenue dans l''espace de téléchargement du site!\";s:11:\"global_auth\";a:3:{s:3:\"r-1\";i:1;s:2:\"r0\";i:5;s:2:\"r1\";i:7;}}')", __LINE__, __FILE__);
				
				//Récomptage du nombre de fichier
				$Cache = new Cache;
				$Cache->Generate_module_file('download');
	
				include_once(PATH_TO_ROOT.'/download/download_auth.php');
				$Cache->load('download');
				include_once(PATH_TO_ROOT.'/download/download_cats.class.php');
				
				$download_categories = new DownloadCats();
				$download_categories->Recount_sub_files();
			}
			if (isset($MODULES['poll']))
			{	
				$Sql->query_inject("ALTER TABLE `phpboost_poll_ip` ADD `user_id` int(11) NOT NULL DEFAULT '0' AFTER `ip`", __LINE__, __FILE__);
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (19, 'poll', 'a:4:{s:9:\"poll_auth\";i:-1;s:9:\"poll_mini\";a:1:{i:0;s:1:\"1\";}s:11:\"poll_cookie\";s:4:\"poll\";s:18:\"poll_cookie_lenght\";i:2592000;}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['articles']))
			{
				$Sql->parse('migration_articles_2.0_to_3.0.sql');
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (6, 'articles', 'a:5:{s:16:\"nbr_articles_max\";i:10;s:11:\"nbr_cat_max\";i:10;s:10:\"nbr_column\";i:2;s:8:\"note_max\";i:5;s:9:\"auth_root\";s:59:\"a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}\";}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['news']))
			{
				$Sql->parse('migration_news_2.0_to_3.0.sql');
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (15, 'news', 'a:13:{s:4:\"type\";i:1;s:11:\"activ_pagin\";i:1;s:11:\"activ_edito\";i:1;s:15:\"pagination_news\";i:5;s:15:\"pagination_arch\";i:10;s:9:\"activ_com\";i:1;s:10:\"activ_icon\";i:1;s:14:\"display_author\";i:1;s:12:\"display_date\";i:1;s:8:\"nbr_news\";s:1:\"2\";s:10:\"nbr_column\";i:1;s:5:\"edito\";s:22:\"Bienvenue sur le site!\";s:11:\"edito_title\";s:22:\"Bienvenue sur le site!\";}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['faq']))
			{
				$Sql->parse('migration_faq_2.0_to_3.0.sql');
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (10, 'faq', 'a:5:{s:8:\"faq_name\";s:12:\"FAQ PHPBoost\";s:8:\"num_cols\";i:4;s:13:\"display_block\";b:0;s:11:\"global_auth\";a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:3;s:2:\"r2\";i:3;}s:4:\"root\";a:3:{s:12:\"display_mode\";i:0;s:4:\"auth\";a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:3;s:2:\"r2\";i:3;}s:11:\"description\";s:23:\"Bienvenue dans la FAQ !\";}}')", __LINE__, __FILE__);
				
			}
			if (isset($MODULES['calendar']))
			{
				$Sql->query_inject("ALTER TABLE `phpboost_calendar` CHANGE `contents` `contents` text", __LINE__, __FILE__);
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (7, 'calendar', 'a:1:{s:13:\"calendar_auth\";i:2;}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['contact']))
			{
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (8, 'contact', 'a:2:{s:17:\"contact_verifcode\";i:1;s:28:\"contact_difficulty_verifcode\";i:2;}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['online']))
			{
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (17, 'online', 'a:2:{s:16:\"online_displayed\";i:4;s:20:\"display_order_online\";s:33:\"s.level DESC, s.session_time DESC\";}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['guestbook']))
			{
				$Sql->query_inject("ALTER TABLE `phpboost_guestbook` CHANGE `contents` `contents` text", __LINE__, __FILE__);
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (13, 'guestbook', 'a:5:{s:14:\"guestbook_auth\";i:-1;s:24:\"guestbook_forbidden_tags\";s:52:\"a:3:{i:0;s:3:\"swf\";i:1;s:5:\"movie\";i:2;s:5:\"sound\";}\";s:18:\"guestbook_max_link\";i:2;s:19:\"guestbook_verifcode\";i:0;s:30:\"guestbook_difficulty_verifcode\";i:0;}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['newsletter']))
			{
				$Sql->query_inject("ALTER TABLE `phpboost_newsletter_arch` CHANGE `message` `message` text", __LINE__, __FILE__);
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (16, 'newsletter', 'a:2:{s:11:\"sender_mail\";s:0:\"\";s:15:\"newsletter_name\";s:0:\"\";}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['gallery']))
			{
				$Sql->query_inject("ALTER TABLE `phpboost_gallery` CHANGE `users_note` `users_note` text", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_gallery_cats` CHANGE `contents` `contents` text", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_gallery_cats` CHANGE `auth` `auth` text", __LINE__, __FILE__);
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (12, 'gallery', 'a:27:{s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"width_max\";i:800;s:10:\"height_max\";i:600;s:10:\"weight_max\";i:1024;s:7:\"quality\";i:80;s:5:\"trans\";i:40;s:4:\"logo\";s:8:\"logo.jpg\";s:10:\"activ_logo\";i:1;s:7:\"d_width\";i:5;s:8:\"d_height\";i:5;s:10:\"nbr_column\";i:4;s:12:\"nbr_pics_max\";i:16;s:8:\"note_max\";i:5;s:11:\"activ_title\";i:1;s:9:\"activ_com\";i:1;s:10:\"activ_note\";i:1;s:15:\"display_nbrnote\";i:1;s:10:\"activ_view\";i:1;s:10:\"activ_user\";i:1;s:12:\"limit_member\";i:10;s:10:\"limit_modo\";i:25;s:12:\"display_pics\";i:3;s:11:\"scroll_type\";i:1;s:13:\"nbr_pics_mini\";i:6;s:15:\"speed_mini_pics\";i:6;s:9:\"auth_root\";s:59:\"a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:3;s:2:\"r1\";i:7;s:2:\"r2\";i:7;}\";}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['shoutbox']))
			{
				$Sql->query_inject("ALTER TABLE `phpboost_shoutbox` CHANGE `contents` `contents` text", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_shoutbox` ADD `level` tinyint(1) NOT NULL DEFAULT '0' AFTER `user_id`", __LINE__, __FILE__);
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (21, 'shoutbox', 'a:5:{s:16:\"shoutbox_max_msg\";i:100;s:13:\"shoutbox_auth\";i:-1;s:23:\"shoutbox_forbidden_tags\";s:428:\"a:26:{i:0;s:5:\"title\";i:1;s:6:\"stitle\";i:2;s:5:\"style\";i:3;s:3:\"url\";i:4;s:3:\"img\";i:5;s:5:\"quote\";i:6;s:4:\"hide\";i:7;s:4:\"list\";i:8;s:5:\"color\";i:9;s:7:\"bgcolor\";i:10;s:4:\"font\";i:11;s:4:\"size\";i:12;s:5:\"align\";i:13;s:5:\"float\";i:14;s:3:\"sup\";i:15;s:3:\"sub\";i:16;s:6:\"indent\";i:17;s:3:\"pre\";i:18;s:5:\"table\";i:19;s:3:\"swf\";i:20;s:5:\"movie\";i:21;s:5:\"sound\";i:22;s:4:\"code\";i:23;s:4:\"math\";i:24;s:6:\"anchor\";i:25;s:7:\"acronym\";}\";s:17:\"shoutbox_max_link\";i:2;s:22:\"shoutbox_refresh_delay\";d:60000;}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['wiki']))
			{	
				$Sql->query_inject("ALTER TABLE `phpboost_wiki_articles` CHANGE `undefined_status` `undefined_status` text", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_wiki_articles` CHANGE `auth` `auth` text", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_wiki_contents` CHANGE `menu` `menu` text", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_wiki_contents` CHANGE `content` `content` mediumtext", __LINE__, __FILE__);
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (23, 'wiki', 'a:6:{s:4:\"auth\";s:71:\"a:4:{s:3:\"r-1\";i:1041;s:2:\"r0\";i:1299;s:2:\"r1\";i:4095;s:2:\"r2\";i:4095;}\";s:9:\"wiki_name\";s:13:\"Wiki PHPBoost\";s:13:\"last_articles\";i:0;s:12:\"display_cats\";i:0;s:10:\"index_text\";s:22:\"Bienvenue sur le wiki.\";s:10:\"count_hits\";i:1;}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['web']))
			{
				$Sql->query_inject("ALTER TABLE `phpboost_web_cat` CHANGE `contents` `contents` text", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_web` CHANGE `contents` `contents` text", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_web` CHANGE `users_note` `users_note` text", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_web` CHANGE `url` `url` text", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_web` CHANGE `note` `note` float NOT NULL DEFAULT '0'", __LINE__, __FILE__);
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (22, 'web', 'a:4:{s:11:\"nbr_web_max\";i:10;s:11:\"nbr_cat_max\";i:10;s:10:\"nbr_column\";i:2;s:8:\"note_max\";i:5;}')", __LINE__, __FILE__);
			}
			if (isset($MODULES['pages']))
			{
				$Sql->query_inject("ALTER TABLE `phpboost_pages` CHANGE `auth` `auth` text", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_pages` CHANGE `contents` `contents` mediumtext", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_pages` ADD FULLTEXT (`title`)", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_pages` ADD FULLTEXT (`contents`)", __LINE__, __FILE__);
				$Sql->query_inject("ALTER TABLE `phpboost_pages` ADD FULLTEXT `all` (`title` ,`contents`)", __LINE__, __FILE__);
				$Sql->query_inject("INSERT INTO `phpboost_configs` (`id`, `name`, `value`) VALUES (18, 'pages', 'a:3:{s:10:\"count_hits\";i:1;s:9:\"activ_com\";i:1;s:4:\"auth\";s:59:\"a:4:{s:3:\"r-1\";i:5;s:2:\"r0\";i:7;s:2:\"r1\";i:7;s:2:\"r2\";i:7;}\";}')", __LINE__, __FILE__);
			}

			//Installation de modules modules
			import('modules/packages_manager');
			PackagesManager::install_module('connect', true, DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION);
			PackagesManager::install_module('database', true, DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION);
			PackagesManager::install_module('faq', true, DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION);
			PackagesManager::install_module('media', true, DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION);
			PackagesManager::install_module('search', true, DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION);
			
			//Fermeture de la connexion BDD
			$Sql->close();
			
			redirect(HOST . FILE . add_lang('?step=5', true));
		}
		
		 $template->assign_vars(array(
            'C_DATABASE_MAJ' => true,
            'U_PREVIOUS_STEP' => 'update.php?step=3',
            'U_CURRENT_STEP' => 'update.php?step=4',
            'L_PREVIOUS_STEP' => $LANG['previous_step'],
            'L_NEXT_STEP' => $LANG['next_step'],
            'L_DATABASE_MAJ' => $LANG['db_update'],
            'L_DATABASE_MAJ_EXPLAIN' => $LANG['db_update_explain']
        ));
		
		break;
    // Configuration du site
    case 5:
        //Variables serveur.
        $server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
        if (!$server_path)
        {
            $server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
        }
        $server_path = trim(str_replace('/update', '', dirname($server_path)));
        $server_path = ($server_path == '/') ? '' : $server_path;
        $server_name = 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));
        
        //Enregistrement de la réponse
        if (retrieve(POST, 'submit', false))
        {
            $server_url = strprotect(retrieve(POST, 'site_url', $server_name, TSTRING_AS_RECEIVED), HTML_PROTECT, ADDSLASHES_NONE);
            $server_path = trim(strprotect(retrieve(POST, 'site_path', $server_path, TSTRING_AS_RECEIVED), HTML_PROTECT, ADDSLASHES_NONE), '/');
            $site_name = stripslashes(retrieve(POST, 'site_name', ''));
            $site_desc = stripslashes(retrieve(POST, 'site_desc', ''));
            $site_keyword = stripslashes(retrieve(POST, 'site_keyword', ''));
            $site_timezone = retrieve(POST, 'site_timezone', (int)date('I'));
            
            $CONFIG = array();
            $CONFIG['server_name'] = $server_url;
            //Si le chemin de PHPBoost n'est pas vide, on y ajoute un / devant
            if ($server_path != '')
            {
                $CONFIG['server_path'] = '/' . $server_path;
            }
            else
            {
                $CONFIG['server_path'] = $server_path;
            }
            $CONFIG['site_name'] = $site_name;
            $CONFIG['site_desc'] = $site_desc;
            $CONFIG['site_keyword'] = $site_keyword;
            $CONFIG['start'] = time();
            $CONFIG['version'] = UPDATE_VERSION;
            $CONFIG['lang'] = $lang;
            $CONFIG['theme'] = DEFAULT_THEME_UPDATE;
            $CONFIG['editor'] = 'bbcode';
            $CONFIG['timezone'] = $site_timezone;
            $CONFIG['start_page'] = '/index.php';
            $CONFIG['maintain'] = 0;
            $CONFIG['maintain_delay'] = 1;
            $CONFIG['maintain_display_admin'] = 1;
            $CONFIG['maintain_text'] = $LANG['site_config_maintain_text'];
            $CONFIG['htaccess_manual_content'] = '';
            $CONFIG['rewrite'] = 0;
            $CONFIG['debug'] = 0;
            $CONFIG['com_popup'] = 0;
            $CONFIG['compteur'] = 0;
            $CONFIG['bench'] = 0;
            $CONFIG['theme_author'] = 0;
            $CONFIG['ob_gzhandler'] = 0;
            $CONFIG['site_cookie'] = 'session';
            $CONFIG['site_session'] = 3600;
            $CONFIG['site_session_invit'] = 300;
            $CONFIG['mail_exp'] = '';
            $CONFIG['mail'] = '';
            $CONFIG['sign'] = $LANG['site_config_mail_signature'];
            $CONFIG['anti_flood'] = 0;
            $CONFIG['delay_flood'] = 7;
            $CONFIG['unlock_admin'] = '';
            $CONFIG['pm_max'] = 50;
            $CONFIG['search_cache_time'] = 30;
            $CONFIG['search_max_use'] = 100;
            $CONFIG['html_auth'] = array ('r2' => 1);
            $CONFIG['forbidden_tags'] = array ();
            
            //Connexion à la base de données
            require_once('functions.php');
            load_db_connection();
            
            //On insère dans la base de données
            $Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
            
            //On installe la langue
            $Sql->query_inject("INSERT INTO " . DB_TABLE_LANG . " (lang, activ, secure) VALUES ('" . strprotect($CONFIG['lang']) . "', 1, -1)", __LINE__, __FILE__);
            
            //On installe le thème
            $info_theme = load_ini_file('../templates/' . $CONFIG['theme'] . '/config/', get_ulang());
            $Sql->query_inject("INSERT INTO " . DB_TABLE_THEMES . " (theme, activ, secure, left_column, right_column) VALUES ('" . strprotect($CONFIG['theme']) . "', 1, -1, '" . $info_theme['left_column'] . "', '" . $info_theme['right_column'] . "')", __LINE__, __FILE__);
            
            //On génère le cache
            include '../lang/' . $lang . '/main.php';
            $Cache = new Cache;
            
            $Cache->generate_file('modules');
            $Cache->load('modules', RELOAD_CACHE);
            
            // Ajout du menu de lien par défaut tout en haut à gauche
            import('core/menu_service');
            MenuService::enable_all(true);
			
            $modules_menu = MenuService::website_modules(VERTICAL_MENU);
            MenuService::move($modules_menu, BLOCK_POSITION__LEFT, false);
            MenuService::change_position($modules_menu, -$modules_menu->get_block_position());
            MenuService::save($modules_menu);
            
			// Try to find out new mini-modules and delete old ones
			MenuService::update_mini_modules_list(false);
			// The same with the mini menus
			MenuService::update_mini_menus_list();

			$Sql->query_inject("UPDATE ".DB_TABLE_MENUS." SET enabled = 1", __LINE__, __FILE__);
			
            $Cache->generate_all_files();
            
            $Cache->load('themes', RELOAD_CACHE);
            $Cache->Generate_file('css');
            
            $Sql->close();
            
            redirect(HOST . FILE . add_lang('?step=6', true));
        }
            
        //Interface configuration du site
        $template->assign_vars(array(
            'C_SITE_CONFIG' => true,
            'SITE_URL' => $server_name,
            'SITE_PATH' => $server_path
        ));
    
        //Balayage des fuseaux horaires
        $site_timezone = number_round(date('Z')/3600, 0) - (int)date('I');
        for ($i = -12; $i <= 14; $i++)
        {
            $timezone_name = '';
            if ($i === 0)
            {
                $timezone_name = 'GMT';
            }
            elseif ($i > 0)
            {
                $timezone_name = 'GMT + ' . $i;
            }
            else
            {
                $timezone_name = 'GMT - ' . (-$i);
            }
            
            $template->assign_block_vars('timezone', array(
                'NAME' => $timezone_name,
                'VALUE' => $i,
                'SELECTED' => $i === $site_timezone ? 'selected="selected"' : ''
            ));
        }
            
        $template->assign_vars(array(
            'IMG_THEME' => DEFAULT_THEME_UPDATE,
            'U_PREVIOUS_STEP' => add_lang('update.php?step=4'),
            'U_CURRENT_STEP' => add_lang('update.php?step=5'),
            'L_SITE_CONFIG' => $LANG['site_config_title'],
            'L_SITE_CONFIG_EXPLAIN' => $LANG['site_config_explain'],
            'L_YOUR_SITE' => $LANG['your_site'],
            'L_SITE_URL' => $LANG['site_url'],
            'L_SITE_URL_EXPLAIN' => $LANG['site_url_explain'],
            'L_SITE_PATH' => $LANG['site_path'],
            'L_SITE_PATH_EXPLAIN' => $LANG['site_path_explain'],
            'L_SITE_NAME' => $LANG['site_name'],
            'L_SITE_TIMEZONE' => $LANG['site_timezone'],
            'L_SITE_TIMEZONE_EXPLAIN' => $LANG['site_timezone_explain'],
            'L_SITE_DESCRIPTION' => $LANG['site_description'],
            'L_SITE_DESCRIPTION_EXPLAIN' => $LANG['site_description_explain'],
            'L_SITE_KEYWORDS' => $LANG['site_keywords'],
            'L_SITE_KEYWORDS_EXPLAIN' => $LANG['site_keywords_explain'],
            'L_PREVIOUS_STEP' => $LANG['previous_step'],
            'L_NEXT_STEP' => $LANG['next_step'],
            'L_REQUIRE_SITE_URL' => $LANG['require_site_url'],
            'L_REQUIRE_SITE_NAME' => $LANG['require_site_name'],
            'L_CONFIRM_SITE_URL' => $LANG['confirm_site_url'],
            'L_CONFIRM_SITE_PATH' => $LANG['confirm_site_path']
        ));
		break;
    case 6:
        require_once('functions.php');
        load_db_connection();
        
        $Cache = new Cache;
        $Cache->load('config');
        $Cache->load('modules');
        $Cache->load('themes');
        
        $template->assign_vars(array(
            'C_END' => true,
            'CONTENTS' => sprintf($LANG['end_installation']),
            'L_ADMIN_INDEX' => $LANG['admin_index'],
            'L_SITE_INDEX' => $LANG['site_index'],
            'U_ADMIN_INDEX' => '../admin/admin_index.php',
            'U_INDEX' => '..' . $CONFIG['start_page']
        ));
        
        import('core/updates');
        new Updates();
        $Sql->close();
        break;
}

$steps = array(
    array($LANG['introduction'], 'intro.png', 0),
    array($LANG['config_server'], 'config.png', 30),
    array($LANG['database_config'], 'database.png', 40),
    array($LANG['maj'], 'database.png', 40),
    array($LANG['advanced_config'], 'advanced_config.png', 80),
    array($LANG['end'], 'end.png', 100)
);

$step_name = $steps[$step - 1][0];


import('io/filesystem/folder');

$lang_dir = new Folder('../lang');

foreach ($lang_dir->get_folders('`[a-z_-]`i') as $folder)
{
    $info_lang = load_ini_file('../lang/', $folder->get_name());
    if (!empty($info_lang['name']))
    {
        $template->assign_block_vars('lang', array(
            'LANG' => $folder->get_name(),
            'LANG_NAME' => $info_lang['name'],
            'SELECTED' => $folder->get_name() == $lang ? 'selected="selected"' : ''
        ));
        
        if ($folder->get_name() == $lang)
        {
            $template->assign_vars(array(
                'LANG_IDENTIFIER' => $info_lang['identifier']
            ));
        }
    }
}

$template->assign_vars(array(
    'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
    'LANG' => $lang,
    'NUM_STEP' => $step,
    'PROGRESS_LEVEL' => $steps[$step - 1][2],
    'L_TITLE' => $LANG['page_title'] . ' - ' . $step_name,
    'L_STEP' => $step_name,
    'L_STEPS_LIST' => $LANG['steps_list'],
    'L_INSTALL_PROGRESS' => $LANG['install_progress'],
    'L_APPENDICES' => $LANG['appendices'],
    'L_DOCUMENTATION' => $LANG['documentation'],
    'U_DOCUMENTATION' => $LANG['documentation_link'],
    'L_RESTART_INSTALL' => $LANG['restart_installation'],
    'L_CONFIRM_RESTART' => $LANG['confirm_restart_installation'],
    'L_LANG' => $LANG['change_lang'],
    'L_CHANGE' => $LANG['change'],
    'L_YES' => $LANG['yes'],
    'L_NO' => $LANG['no'],
    'L_UNKNOWN' => $LANG['unknown'],
    'L_POWERED_BY' => $LANG['powered_by'],
    'PHPBOOST_VERSION' => UPDATE_VERSION,
    'L_PHPBOOST_RIGHT' => $LANG['phpboost_right'],
    'U_RESTART' => 'update.php'
));

//Images de la barre de progression
for ($i = 1; $i <= floor($steps[$step - 1][2] * 24 / 100); $i++)
{
    $template->assign_block_vars('progress_bar', array());
}

//Etapes de l'installation
for ($i = 1; $i <= STEPS_NUMBER; $i++)
{
    if ($i < $step)
    {
        $row_class = 'row_success';
    }
    elseif ($i == $step && $i == STEPS_NUMBER)
    {
        $row_class = 'row_current row_final';
    }
    elseif ($i == $step)
    {
        $row_class = 'row_current';
    }
    elseif ($i == STEPS_NUMBER)
    {
        $row_class = 'row_next row_final';
    }
    else
    {
        $row_class = 'row_next';
    }
    
    $template->assign_block_vars('link_menu', array(
        'CLASS' => $row_class,
        'STEP_IMG' => $steps[$i - 1][1],
        'STEP_NAME' => $steps[$i - 1][0]
    ));
}

$template->parse();

ob_end_flush();

?>
