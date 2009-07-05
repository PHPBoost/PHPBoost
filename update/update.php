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
define('UPDATE_VERSION', '3.0b');
define('DEFAULT_LANGUAGE', 'french');
define('DEFAULT_THEME', 'base');
define('STEPS_NUMBER', 6);
define('STEP_INTRODUCTION', 1);
define('STEP_SERVER_CONFIG', 2);
define('STEP_DB_CONFIG', 3);

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
import('io/template');

@error_reporting(ERROR_REPORTING);

define('HOST', 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')));
$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
define('FILE', $server_path);
define('DIR', str_replace('/update/update.php', '', $server_path));
define('SID', '');

$step = retrieve(GET, 'step', 1, TUNSIGNED_INT);
$step = $step > STEPS_NUMBER ? 1 : $step;

$lang = retrieve(GET, 'lang', DEFAULT_LANGUAGE);

//Inclusion du fichier langue
include_once('lang/' . DEFAULT_LANGUAGE . '/update_' . DEFAULT_LANGUAGE . '.php');

//Création de l'utilisateur
import('members/user');
$user_data = array(
    'm_user_id' => 1,
    'login' => 'login',
    'level' => ADMIN_LEVEL,
    'user_groups' => '',
    'user_lang' => $lang,
    'user_theme' => DEFAULT_THEME,
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
                    die('correct :)');
                    import('core/errors');
                    import('core/cache');
                    $Errorh = new Errors;
                    $Sql = new Sql();
                    //Connexion
                    $Sql->connect($host, $login, $password, $database, ERRORS_MANAGEMENT_BY_RETURN);

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
                    
                    //On parse le fichier de mise à jour de la structure de la base de données
                    $Sql->parse('migration_2.0_to_3.0.sql');
                    
					$Cache->load('modules'); //Cache des autorisations des modules
					if (isset($MODULES['forum']))
						$Sql->parse('migration_forum_2.0_to_3.0.sql');
					if (isset($MODULES['download']))
						$Sql->parse('migration_download_2.0_to_3.0.sql');
					if (isset($MODULES['poll']))
					{	
						$Sql->query_inject("ALTER TABLE `phpboost_poll_ip` ADD `user_id` int(11) NOT NULL DEFAULT '0' AFTER `ip`", __LINE__, __FILE__);
						$Sql->query_inject("ALTER TABLE `phpboost_poll_ip` ADD `user_id` int(11) NOT NULL DEFAULT '0' AFTER `ip`", __LINE__, __FILE__);
					}
					if (isset($MODULES['articles']))
						$Sql->parse('migration_articles_2.0_to_3.0.sql');
					if (isset($MODULES['news']))
						$Sql->parse('migration_news_2.0_to_3.0.sql');
					if (isset($MODULES['faq']))
						$Sql->parse('migration_faq_2.0_to_3.0.sql');
					if (isset($MODULES['calendar']))
						$Sql->query_inject("ALTER TABLE `phpboost_calendar` DROP `timestamp_end`", __LINE__, __FILE__);
					if (isset($MODULES['gallery']))
						$Sql->query_inject("ALTER TABLE `phpboost_gallery` CHANGE `activ_com` `lock_com` tinyint(1) NOT NULL DEFAULT '0'", __LINE__, __FILE__);
					if (isset($MODULES['shoutbox']))
						$Sql->query_inject("ALTER TABLE `phpboost_shoutbox` ADD `level` tinyint(1) NOT NULL DEFAULT '0' AFTER `user_id`", __LINE__, __FILE__);
					if (isset($MODULES['wiki']))
						$Sql->query_inject("ALTER TABLE `phpboost_wiki_articles` CHANGE `activ_com` `lock_com` tinyint(1) NOT NULL DEFAULT '0'", __LINE__, __FILE__);
					if (isset($MODULES['web']))
					{
						$Sql->query_inject("ALTER TABLE `phpboost_web` CHANGE `note` `note` float NOT NULL DEFAULT '0'", __LINE__, __FILE__);
						$Sql->query_inject("ALTER TABLE `phpboost_web` CHANGE `activ_com` `lock_com` tinyint(1) NOT NULL DEFAULT '0'", __LINE__, __FILE__);
						$Sql->query_inject("ALTER TABLE `phpboost_web_cat` CHANGE `secure` `secure` tinyint(1) NOT NULL DEFAULT '0'", __LINE__, __FILE__);
					}
					if (isset($MODULES['pages']))
					{
						$Sql->query_inject("ALTER TABLE `phpboost_pages` CHANGE `activ_com` `lock_com` tinyint(1) NOT NULL DEFAULT '0'", __LINE__, __FILE__);
						$Sql->query_inject("ALTER TABLE `phpboost_pages` ADD FULLTEXT (`title`)", __LINE__, __FILE__);
						$Sql->query_inject("ALTER TABLE `phpboost_pages` ADD FULLTEXT (`contents`)", __LINE__, __FILE__);
						$Sql->query_inject("ALTER TABLE `phpboost_pages` ADD FULLTEXT `all` (`title` ,`contents`)", __LINE__, __FILE__);
					}
					
                    //Fermeture de la connexion BDD
                    $Sql->close();
                    
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
    // Configuration du site
    case 5:
        //Variables serveur.
        $server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
        if (!$server_path)
        {
            $server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
        }
        $server_path = trim(str_replace('/install', '', dirname($server_path)));
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
            $CONFIG['theme'] = DEFAULT_THEME;
            $CONFIG['editor'] = 'bbcode';
            $CONFIG['timezone'] = $site_timezone;
            $CONFIG['start_page'] = DISTRIBUTION_START_PAGE;
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
            include '../kernel/framework/core/cache.class.php';
            include '../lang/' . $lang . '/main.php';
            $Cache = new Cache;
            
            //Installation des modules de la distribution
            import('modules/packages_manager');
            foreach ($DISTRIBUTION_MODULES as $module_name)
            {
                $Cache->load('modules', RELOAD_CACHE);
                PackagesManager::install_module($module_name, true, DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION);
            }
            
            $Cache->generate_file('modules');
            $Cache->load('modules', RELOAD_CACHE);
            
            // Ajout du menu de lien par défaut tout en haut à gauche
            import('core/menu_service');
            $modules_menu = MenuService::website_modules(VERTICAL_MENU);
            MenuService::move($modules_menu, BLOCK_POSITION__LEFT, false);
            MenuService::change_position($modules_menu, -$modules_menu->get_block_position());
            MenuService::save($modules_menu);
            
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
            'IMG_THEME' => DEFAULT_THEME,
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
    //Compte administrateur
    case 6:
        $template->assign_block_vars('admin', array());
        //Validation de l'étape
        if (retrieve(POST, 'submit', false))
        {
        	import('io/mail');
            $login = retrieve(POST, 'login', '', TSTRING_AS_RECEIVED);
            $password = retrieve(POST, 'password', '', TSTRING_AS_RECEIVED);
            $password_repeat = retrieve(POST, 'password_repeat', '', TSTRING_AS_RECEIVED);
            $user_mail = retrieve(POST, 'mail', '', TSTRING_AS_RECEIVED);
            $create_session = retrieve(POST, 'create_session', false);
            $auto_connection = retrieve(POST, 'auto_connection', false);
            
            function check_admin_account($login, $password, $password_repeat, $user_mail)
            {
                global $LANG;
                if (empty($login))
                {
                    return $LANG['admin_require_login'];
                }
                elseif (strlen($login) < 3)
                {
                    return $LANG['admin_login_too_short'];
                }
                elseif (empty($password))
                {
                    return $LANG['admin_require_password'];
                }
                elseif (empty($password_repeat))
                {
                    return $LANG['admin_require_password_repeat'];
                }
                elseif (strlen($password) < 6)
                {
                    return $LANG['admin_password_too_short'];
                }
                elseif (empty($user_mail))
                {
                    return $LANG['admin_require_mail'];
                }
                elseif ($password != $password_repeat)
                {
                    return $LANG['admin_passwords_error'];
                }
                elseif (!Mail::check_validity($user_mail))
                {
                    return $LANG['admin_email_error'];
                }
                else
                {
                    return '';
                }
            }
            $error = check_admin_account($login, $password, $password_repeat, $user_mail);
    
            //Si il n'y a pas d'erreur on enregistre dans la table
            if (empty($error))
            {
                require_once('functions.php');
                load_db_connection();
                
                //On crée le code de déverrouillage
                import('core/cache');
                $Cache = new Cache;
                $Cache->load('config');
                
                //On enregistre le membre (l'entrée était au préalable créée)
                $Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET login = '" . strprotect($login) . "', password = '" . strhash($password) . "', level = '2', user_lang = '" . $CONFIG['lang'] . "', user_theme = '" . $CONFIG['theme'] . "', user_mail = '" . $user_mail . "', user_show_mail = '1', timestamp = '" . time() . "', user_aprob = '1', user_timezone = '" . $CONFIG['timezone'] . "' WHERE user_id = '1'",__LINE__, __FILE__);
                
                //Génération de la clé d'activation, en cas de verrouillage de l'administration
                $unlock_admin = substr(strhash(uniqid(mt_rand(), true)), 0, 12);
                $CONFIG['unlock_admin'] = strhash($unlock_admin);
                $CONFIG['mail_exp'] = $user_mail;
                $CONFIG['mail'] = $user_mail;
                
                $Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
                
                $Cache->Generate_file('config');
                
                //Configuration des membres
                $Cache->load('member');
                
                $CONFIG_USER['activ_register'] = (int)DISTRIBUTION_ENABLE_USER;
                $CONFIG_USER['msg_mbr'] = $LANG['site_config_msg_mbr'];
                $CONFIG_USER['msg_register'] = $LANG['site_config_msg_register'];
                
                $Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_USER)) . "' WHERE name = 'member'", __LINE__, __FILE__);
                
                $Cache->generate_file('member');
                
                //On envoie un mail à l'administrateur
                $LANG['admin'] = '';
                import('io/mail');
                $mail = new Mail();
                
                //Paramètres du mail
                $mail->set_sender($CONFIG['mail_exp']);
                $mail->set_recipients($user_mail);
                $mail->set_object($LANG['admin_mail_object']);
                $mail->set_content(sprintf($LANG['admin_mail_unlock_code'], stripslashes($login), stripslashes($login), $password, $unlock_admin, HOST . DIR));
                
                //On envoie le mail
                $mail->send();
                
                //On connecte directement l'administrateur si il l'a demandé
                if ($create_session)
                {
                    import('members/session');
                    $Session = new Session;
                    
                    //Remise à zéro du compteur d'essais.
                    $Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect='" . time() . "' WHERE user_id = '1'", __LINE__, __FILE__);
                    //Lancement de la session (avec ou sans autoconnexion selon la demande de l'utilisateur)
                    $Session->start(1, $password, 2, '/install/update.php', '', $LANG['page_title'], $auto_connection);
                }
                
                $Cache->generate_file('stats');
                
                //On redirige vers l'étape suivante
                redirect(HOST . FILE . add_lang('?step=7', true));
            }
            else
            {
                $template->assign_block_vars('error', array(
                    'ERROR' => '<div class="warning">' . $error . '</div>'
                ));
            }
        }
        
        $template->assign_vars(array(
            'C_ADMIN_ACCOUNT' => true,
            'U_PREVIOUS_STEP' => add_lang('update.php?step=5'),
            'U_CURRENT_STEP' => add_lang('update.php?step=6'),
            'L_ADMIN_ACCOUNT_CREATION' => $LANG['admin_account_creation'],
            'L_EXPLAIN_ADMIN_ACCOUNT_CREATION' => $LANG['admin_account_creation_explain'],
            'L_ADMIN_ACCOUNT' => $LANG['admin_account'],
            'L_PSEUDO' => $LANG['admin_pseudo'],
            'L_PSEUDO_EXPLAIN' => $LANG['admin_pseudo_explain'],
            'L_PASSWORD' => $LANG['admin_password'],
            'L_PASSWORD_EXPLAIN' => $LANG['admin_password_explain'],
            'L_PASSWORD_REPEAT' => $LANG['admin_password_repeat'],
            'L_MAIL' => $LANG['admin_mail'],
            'L_MAIL_EXPLAIN' => $LANG['admin_mail_explain'],
            'L_PREVIOUS_STEP' => $LANG['previous_step'],
            'L_NEXT_STEP' => $LANG['next_step'],
            'L_ERROR' => $LANG['admin_error'],
            'L_REQUIRE_LOGIN' => $LANG['admin_require_login'],
            'L_LOGIN_TOO_SHORT' => $LANG['admin_login_too_short'],
            'L_PASSWORD_TOO_SHORT' => $LANG['admin_password_too_short'],
            'L_REQUIRE_PASSWORD' => $LANG['admin_require_password'],
            'L_REQUIRE_PASSWORD_REPEAT' => $LANG['admin_require_password_repeat'],
            'L_REQUIRE_MAIL' => $LANG['admin_require_mail'],
            'L_PASSWORDS_ERROR' => $LANG['admin_passwords_error'],
            'L_CREATE_SESSION' => $LANG['admin_create_session'],
            'L_AUTO_CONNECTION' => $LANG['admin_auto_connection'],
            'L_EMAIL_ERROR' => $LANG['admin_email_error'],
            'L_MAIL_INVALID' => $LANG['admin_invalid_email_error'],
            'LOGIN_VALUE' => !empty($error) ? $login : '',
            'PASSWORD_VALUE' => !empty($error) ? $password : '',
            'MAIL_VALUE' => !empty($error) ? $user_mail : '',
            'CHECKED_AUTO_CONNECTION' => !empty($error) ? ($auto_connection ? 'checked="checked"' : '') : 'checked="checked"',
            'CHECKED_CREATE_SESSION' => !empty($error) ? ($create_session ? 'checked="checked"' : '') : 'checked="checked"'
        ));
    case 7:
        require_once('functions.php');
        load_db_connection();
        
        import('core/cache');
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
    array($LANG['advanced_config'], 'advanced_config.png', 80),
    array($LANG['administrator_account_creation'], 'admin.png', 90),
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
