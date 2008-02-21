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
define('ERROR_REPORTING', E_ALL | E_NOTICE);
@error_reporting(ERROR_REPORTING);
set_magic_quotes_runtime(0);
$update_version = '2.0';

define('MAGIC_QUOTES', get_magic_quotes_gpc()); //Récupère la valeur du magic quotes
define('HOST', 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')));
$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
define('FILE', $server_path);
define('DIR', str_replace('/install/install.php', '', $server_path));
define('PHP_BOOST', true);

//Thème par défaut.
define('DEFAULT_THEME', 'main');

if( !@include_once('../includes/template.class.php') )
	die('Votre dossier d\'installation n\'est pas placé où il faut');
include_once('../includes/function.php');

$step = !empty($_GET['step']) ? numeric($_GET['step']) : 1;
$step = $step > 9 ? 1 : $step;

$lang = !empty($_GET['lang']) ? securit($_GET['lang']) : 'french';
if( !@include_once('lang/' . $lang . '/install_' . $lang . '.php') )
	include_once('lang/french/install_french.php');
	
if( !empty($_GET['restart']) )
	redirect(HOST . add_lang(FILE, true));

$Template = new Templates; //!\\Initialisation des templates//!\\

$Template->Set_filenames(array('install' => '../install/templates/install.tpl'));

//Fonction pour gérer la langue
function add_lang($url, $header_location = false)
{
	global $lang;
	if( $lang != 'french' )
	{
		$ampersand = $header_location ? '&' : '&amp;';
		if( strpos($url, '?') !== false )
			return $url . $ampersand . 'lang=' . $lang;
		else
			return $url . '?' . 'lang=' . $lang;		
	}
	else
		return $url;
}

//Changement de langue
if( !empty($_POST['new_language']) && is_file('lang/' . $_POST['new_language'] . '/install_' . $_POST['new_language'] . '.php') && $_POST['new_language'] != $lang)
{
	$lang = $_POST['new_language'];
	redirect(HOST . FILE . add_lang('?step=' . $step, true));
}

//Préambule
if( $step == 1 )
{
	$Template->Assign_block_vars('intro', array());
	$Template->Assign_vars(array(
		'L_INTRO_EXPLAIN' => $LANG['intro_explain'],
		'L_NEXT_STEP' => add_lang('install.php?step=2')
	));
	$Template->Assign_vars(array(
		'L_START_INSTALL' => $LANG['start_install'],
	));
}
//Licence
elseif( $step == 2 )
{
	$submit = !empty($_POST['submit']) ? true : false;
	$license_agreement = !empty($_POST['license_agreement']) ? true : false;
	//On vérifie l'étape et si elle est validée on passe à la suivante
	if( $submit && $license_agreement )
		redirect(HOST . FILE . add_lang('?step=3', true));
		
	$Template->Assign_block_vars('license', array());
	$Template->Assign_vars(array(
		'TARGET' => add_lang('install.php?step=2'),
		'U_PREVIOUS_PAGE' => add_lang('install.php?step=1'),
		'L_REQUIRE_LICENSE_AGREEMENT' => ($submit && !$license_agreement) ? '<div class="warning">' . $LANG['require_license_agreement'] . '</div>' : $LANG['require_license_agreement'],
		'L_ALERT_PLEASE_AGREE_LICENSE' => $LANG['alert_agree_license'],
		'L_REQUIRE_LICENSE' => $LANG['license_agreement'],
		'L_PLEASE_AGREE' => $LANG['please_agree_license'],
		'L_NEXT_STEP' => $LANG['next_step'],
		'L_PREVIOUS_STEP' => $LANG['previous_step'],
		'L_LICENSE_TERMS' => file_get_contents('license.txt')
	));
}
//Configuration du serveur
elseif( $step == 3 )
{
	//Url rewriting
	if( function_exists('apache_get_modules') )
	{	
		$get_rewrite = apache_get_modules();
		$check_rewrite = (!empty($get_rewrite[5])) ? '<div class="success_block">' . $LANG['yes'] . '</div>' : '<div class="failure_block">' . $LANG['no'] . '</div>';
	}
	else
		$check_rewrite = '<div class="unspecified_block">' . $LANG['unknown'] . '</div>';
	
	$Template->Assign_block_vars('config_server', array(
		'PHP_VERSION' => phpversion() >= '4.1.0' ? '<div class="success_block">' . $LANG['yes'] . '</div>' : '<div class="failure_block">' . $LANG['no'] . '</div>',
		'GD' => ( @extension_loaded('gd') ) ? '<div class="success_block">' . $LANG['yes'] . '</div>' : '<div class="failure_block">' . $LANG['no'] . '</div>',
		'URL_REWRITING' => $check_rewrite
	));
	
	//Mise à jour du cache.
	@clearstatcache();
	
	$chmod_dir = array('../cache', '../cache/backup', '../cache/tpl', '../images/avatars', '../images/group', '../images/maths', '../images/smileys', '../includes/auth', '../lang', '../templates', '../upload');
	
	//Vérifications et le cas échéants changements des autorisations en écriture.
	foreach($chmod_dir as $dir)
	{
		$is_writable = $is_dir = true;
		if( file_exists($dir) && is_dir($dir) )
		{
			if( !is_writable($dir) )
				$is_writable = (@chmod($dir, 0777)) ? true : false;			
		}
		else
			$is_dir = $is_writable = ($fp = @mkdir($dir, 0777)) ? true : false;

		$found = ($is_dir === true) ? '<div class="success_block">' . $LANG['existing'] . '</div>' : '<div class="failure_block">' . $LANG['unexisting'] . '</div>';
		$writable = ($is_writable === true) ? '<div class="success_block">' . $LANG['writable'] . '</div>' : '<div class="failure_block">' . $LANG['unwritable'] . '</div>';
			
		$Template->Assign_block_vars('config_server.chmod', array(
			'TITLE'	=> str_replace('../' , '', $dir),
			'FOUND' => $found,
			'WRITABLE' => $writable			
		));
	}
	
	$Template->Assign_vars(array(
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
		'L_REFRESH' => $LANG['refresh_chmod'],
		'L_RESULT' => $LANG['result'],
		'L_QUERY_LOADING' => $LANG['query_loading'],
		'L_QUERY_SENT' => $LANG['query_sent'],
		'L_QUERY_PROCESSING' => $LANG['query_processing'],
		'L_QUERY_SUCCESS' => $LANG['query_success'],
		'L_QUERY_FAILURE' => $LANG['query_failure'],
		'L_NEXT_STEP' => $LANG['next_step'],
		'L_PREVIOUS_STEP' => $LANG['previous_step'],
		'U_PREVIOUS_STEP' => add_lang('install.php?step=2'),
		'U_CURRENT_STEP' => add_lang('install.php?step=3'),
		'U_NEXT_STEP' => add_lang('install.php?step=4')
	));	
}
//Mise en place de la base de données
elseif( $step == 4 )
{
	if( !empty($_POST['submit']) )
	{
		//Préfixe des tables
		$tableprefix = !empty($_POST['tableprefix']) ? securit($_POST['tableprefix']) : 'phpboost_';
			
		function test_db_config()
		{
			global $LANG, $dbms, $host, $login, $password, $database, $tableprefix;
			//Assignation des variables et erreurs
			$array_fields = array('dbms', 'host', 'login', 'password', 'database');
			foreach( $array_fields as $field_name )
			{
				if( !empty($_POST[$field_name]) || $field_name == 'password' )
					$$field_name = trim($_POST[$field_name]);
				else
					return '<div class="warning">' . sprintf($LANG['empty_field'], $LANG['field_' . $field_name]) . '</div>';
			}
			
			//Tentative de connexion
			if( !@include_once('../includes/db/' . $dbms . '.class.php') )
				return '<div class="error">' . $LANG['db_error_dbms'] . '</div>';
				
			require_once('../includes/errors.class.php');
			$Errorh = new Errors;
			$Sql = new Sql(false);
			
			//Connexion
			$result = @$Sql->Sql_connect($host, $login, $password);
			if( !$result )
				return '<div class="error">' . $LANG['db_error_connexion'] . '</div>';
				
			//Sélection de la base de données
			if( !@$Sql->Sql_select_db($database, $result) )
				return '<div class="warning">' . $LANG['db_error_selection'] . '</div>';
				
			//Déconnexion
			$Sql->Sql_close();
			return '';
		}
		$error = test_db_config();

		if( empty($error) )
		{
			require_once('../includes/errors.class.php');
			$Errorh = new Errors;
			$Sql = new Sql(false);			
			//Connexion
			$result = $Sql->Sql_connect($host, $login, $password);
			//Sélection de la base de données
			$Sql->Sql_select_db($database, $result);
						
			//Création du fichier de configuration.
			$file_path = '../includes/auth/config.php';
			$file = @fopen($file_path, 'w+'); //On ouvre le fichier, si il n'existe pas on le crée.
@fputs($file, '<?php
if( !defined(\'DBSECURE\') )
{
	$sql_host = "' . $host . '"; //Adresse serveur mysql.
	$sql_login = "' . $login . '"; //Login
	$sql_pass = "' . $password . '"; //Mot de passe
	$sql_base = "' . $database . '"; //Nom de la base de données.
	$table_prefix = "' . $tableprefix . '"; //Préfixe des tables
	$dbtype = "' . $dbms .'"; //Système de gestion de base de données
	define(\'DBSECURE\', true);
	define(\'PHPBOOST_INSTALLED\', true);
}	
else
{
	exit;
}
?>');
			@fclose($file);
			//On crée la structure de la base de données et on y insère la configuration de base
			$Sql->Sql_parse('db/' . $dbms . '.sql', $tableprefix);
			$Sql->Close();
			redirect(HOST . FILE . add_lang('?step=5', true));
		}
	}
	
	$Template->Assign_block_vars('db', array(
		'DISPLAY_RESULT' => !empty($error) ? 'block' : 'none',
		'ERROR' => !empty($error) ? $error : '',
		'PROGRESS' => !empty($error) ? '100' : '0',
		'PROGRESS_STATUS' => !empty($error) ? $LANG['query_success'] : '',
		'PROGRESS_BAR' => !empty($error) ? str_repeat('<img src="templates/images/loading.png" alt="">', 56) : ''
	));
	$dbms = array('MySQL', 'SQLite', 'PostgreSQL');
	$default_dbms = 'MySQL';
	$supported_dbms = array('MySQL');
	
	foreach( $dbms as $name )
	{	
		$Template->Assign_block_vars('db.dbms', array(
			'L_DBMS' => $name,
			'DBMS' => strtolower($name),
			'SELECTED' => $name == $default_dbms ? 'selected="selected"' : '',
			'DISABLED' => in_array($name, $supported_dbms) ? '' : 'disabled="disabled"'
		));		
	}
	
	$Template->Assign_vars(array(
		'HOST_VALUE' => !empty($error) ? $host  : 'localhost',
		'LOGIN_VALUE' => !empty($error) ? $login  : '',
		'PASSWORD_VALUE' => !empty($error) ? $password  : '',
		'DB_NAME_VALUE' => !empty($error) ? $database  : '',
		'PREFIX_VALUE' => !empty($error) ? $tableprefix  : 'phpboost_',
		'U_PREVIOUS_STEP' => add_lang('install.php?step=3'),
		'U_CURRENT_STEP' => add_lang('install.php?step=4'),
		'L_DB_EXPLAIN' => $LANG['db_explain'],
		'L_DBMS' => $LANG['dbms'],
		'L_CHOOSE_DBMS' => $LANG['choose_dbms'],
		'L_CHOOSE_DBMS_EXPLAIN' => $LANG['choose_dbms_explain'],
		'L_INFORMATIONS' => $LANG['db_informations'],
		'L_HOST' => $LANG['db_host_name'],
		'L_HOST_EXPLAIN' => $LANG['db_host_name'],
		'L_LOGIN' => $LANG['db_login'],
		'L_LOGIN_EXPLAIN' => $LANG['db_login'],
		'L_PASSWORD' => $LANG['db_password'],
		'L_PASSWORD_EXPLAIN' => $LANG['db_password'],
		'L_DB_NAME' => $LANG['db_name'],
		'L_DB_NAME_EXPLAIN' => $LANG['db_name'],
		'L_DB_PREFIX' => $LANG['db_prefix'],
		'L_DB_PREFIX_EXPLAIN' => $LANG['db_prefix'],
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
		'L_REQUIRE_DATABASE_NAME' => $LANG['require_db_name']
	));
}
// Configuration du site
elseif( $step == 5 )
{
	//Variables serveur.
	$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
	if( !$server_path )
		$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
	$server_path = trim(str_replace('/install', '', dirname($server_path)));
	$server_path = ($server_path == '/') ? '' : $server_path;
	$server_name = 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));
	
	if( !empty($_POST['submit']) )
	{
		$server_url = !empty($_POST['site_url']) ? securit($_POST['site_url']) : $server_name;
		$server_path = !empty($_POST['site_path']) ? securit($_POST['site_path']) : $server_path;
		$site_name = !empty($_POST['site_name']) ? stripslashes(securit($_POST['site_name'])) : '';
		$site_desc = !empty($_POST['site_desc']) ? stripslashes(securit($_POST['site_desc'])) : '';
		$site_keyword = !empty($_POST['site_keyword']) ? stripslashes(securit($_POST['site_keyword'])) : '';
		$site_lang = !empty($_POST['lang']) ? stripslashes(securit($_POST['lang'])) : $lang;
		$site_theme = !empty($_POST['theme']) ? stripslashes(securit($_POST['theme'])) : DEFAULT_THEME;
				
		$CONFIG = array();
		$CONFIG['server_name'] = $server_url;
		$CONFIG['server_path'] = ($server_path == '/') ? '' : $server_path;
		$CONFIG['site_name'] = $site_name;
		$CONFIG['site_desc'] = $site_desc;
		$CONFIG['site_keyword'] = $site_keyword;
		$CONFIG['start'] = time();
		$CONFIG['version'] = $update_version;
		$CONFIG['lang'] = $site_lang;
		$CONFIG['theme'] = $site_theme;
		$CONFIG['rewrite'] = '0';
		$CONFIG['com_popup'] = '0';
		$CONFIG['start_page'] = 'member/member.php';
		$CONFIG['maintain'] = '0';
		$CONFIG['maintain_delay'] = '';
		$CONFIG['maintain_text'] = '';
		$CONFIG['compteur'] = '0';
		$CONFIG['ob_gzhandler'] = '0';
		$CONFIG['site_cookie'] = 'session';
		$CONFIG['site_session'] = '3600';
		$CONFIG['site_session_invit'] = '300';
		$CONFIG['mail'] = '';
		$CONFIG['activ_mail'] = '0';
		$CONFIG['sign'] = '';
		$CONFIG['anti_flood'] = '1';
		$CONFIG['delay_flood'] = '7';
		$CONFIG['unlock_admin'] = '';
		$CONFIG['pm_max'] = '5';
		
		$config_string = serialize($CONFIG);
		require_once('../includes/errors.class.php');
		$Errorh = new Errors;
		include_once('../includes/auth/config.php');
		define('PREFIX', $table_prefix);
		include_once('../includes/db/' . $dbtype . '.class.php');
		$Sql = new Sql;
		//On insère dans la base de données
		$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes($config_string) . "' WHERE name = 'config'", __LINE__, __FILE__);
		
		//On insère la langue dans la bdd.
		$check_lang = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."lang WHERE lang = '" . securit($CONFIG['lang']) . "'", __LINE__, __FILE__);
		if( $check_lang == 0 )	
			$Sql->Query_inject("INSERT INTO ".PREFIX."lang (lang, activ, secure) VALUES ('" . securit($CONFIG['lang']) . "', 1, -1)", __LINE__, __FILE__);
		
		//On insère le thème dans la bdd.
		$check_theme = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."themes WHERE theme = '" . securit($CONFIG['theme']) . "'", __LINE__, __FILE__);
		if( $check_theme == 0 )	
			$Sql->Query_inject("INSERT INTO ".PREFIX."themes (theme, activ, secure) VALUES ('" . securit($CONFIG['theme']) . "', 1, -1)", __LINE__, __FILE__);
		
		//On génère le cache
		include('../includes/cache.class.php');
		$Cache = new Cache;
		$Cache->Generate_all_files();
		redirect(HOST . FILE . add_lang('?step=6', true));
	}
		
	//Interface configuration du site
	$Template->Assign_block_vars('site_config', array(
		'SITE_URL' => $server_name,
		'SITE_PATH' => $server_path
	));
	
	//Gestion langue par défaut.
	$array_identifier = '';
	$lang_identifier = '../images/stats/other.png';
	$rep = '../lang/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$file_array = array();
		$dh = @opendir( $rep);
		while( !is_bool($file = readdir($dh)) )
		{	
			//Si c'est un répertoire un regarde si c'est effectivement un dossier de langues
			if( !preg_match('`\.`', $file) )
			{
				$lang_info = load_ini_file('../lang/', $file);
				$lang_name = !empty($lang_info['name']) ? $lang_info['name'] : $lang;
				
				if( $lang_info )
				{
					$array_identifier .= 'array_identifier[\'' . $file . '\'] = \'' . $lang_info['identifier'] . '\';' . "\n";
					$selected = false;
					if( $file == $lang )
					{
						$selected = true;
						$lang_identifier = '../images/stats/countries/' . $lang_info['identifier'] . '.png';
					}					
					$Template->Assign_block_vars('site_config.lang', array(
						'LANG' => $file,
						'LANG_NAME' => $lang_info['name'],
						'SELECTED' => ($selected) ? 'selected="selected"' : ''
					));
				}
			}
		}	
		closedir($dh); //On ferme le dossier
	}

	//Gestion thème par défaut.
	$rep = '../templates/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$file_array = array();
		$dh = @opendir( $rep);
		while( !is_bool($file = readdir($dh)) )
		{	
			//Si c'est un répertoire un regarde si c'est effectivement un dossier de langues
			if( !preg_match('`\.`', $file) )
			{
				$theme_info = load_ini_file('../templates/' . $file . '/config/', $lang);
				if( $theme_info )
				{
					$Template->Assign_block_vars('site_config.theme', array(
						'THEME' => $file,
						'THEME_NAME' => $theme_info['name'],
						'SELECTED' => ($file == DEFAULT_THEME) ? 'selected="selected"' : ''
					));
				}
			}
		}	
		closedir($dh); //On ferme le dossier
	}
	
	$Template->Assign_vars(array(
		'JS_LANG_IDENTIFIER' => $array_identifier,
		'IMG_LANG_IDENTIFIER' => $lang_identifier,
		'IMG_THEME' => DEFAULT_THEME,
		'U_PREVIOUS_STEP' => add_lang('install.php?step=4'),
		'U_CURRENT_STEP' => add_lang('install.php?step=5'),
		'L_CONFIG_SITE_EXPLAIN' => $LANG['config_site_explain'],
		'L_YOUR_SITE' => $LANG['your_site'],
		'L_SITE_URL' => $LANG['site_url'],
		'L_SITE_URL_EXPLAIN' => $LANG['site_url'],
		'L_SITE_PATH' => $LANG['site_path'],
		'L_SITE_PATH_EXPLAIN' => $LANG['site_path'],
		'L_DEFAULT_LANGUAGE' => $LANG['default_language'],
		'L_DEFAULT_THEME' => $LANG['default_theme'],
		'L_SITE_NAME' => $LANG['site_name'],
		'L_SITE_DESCRIPTION' => $LANG['site_description'],
		'L_SITE_DESCRIPTION_EXPLAIN' => $LANG['site_description'],
		'L_SITE_KEYWORDS' => $LANG['site_keywords'],
		'L_SITE_KEYWORDS_EXPLAIN' => $LANG['site_keywords'],
		'L_PREVIOUS_STEP' => $LANG['previous_step'],
		'L_NEXT_STEP' => $LANG['next_step'],
		'L_REQUIRE_SITE_URL' => $LANG['require_site_url'],
		'L_REQUIRE_SITE_NAME' => $LANG['require_site_name'],
		'L_CONFIRM_SITE_URL' => $LANG['confirm_site_url'],
		'L_CONFIRM_SITE_PATH' => $LANG['confirm_site_path']
	));
}
elseif( $step == 6 )
{
	$Template->Assign_block_vars('admin', array());
	//Validation de l'étape
	if( !empty($_POST['submit']) )
	{
		$login = !empty($_POST['login']) ? trim($_POST['login']) : '';
		$password = !empty($_POST['password']) ? trim($_POST['password']) : '';
		$password_repeat = !empty($_POST['password_repeat']) ? trim($_POST['password_repeat']) : '';
		$user_mail = !empty($_POST['mail']) ? trim($_POST['mail']) : '';
		$user_lang = !empty($_POST['lang']) ? trim($_POST['lang']) : 'french';
		$create_session = !empty($_POST['create_session']) ? true : false;
		$auto_connection = !empty($_POST['auto_connection']) ? 1 : 0;
		function check_admin_account($login, $password, $password_repeat, $user_mail, $lang)
		{
			global $LANG;
			if( empty($login) )
				return $LANG['admin_require_login'];
			elseif( strlen($login) < 3 )
				return $LANG['admin_login_too_short'];
			elseif( empty($password) )
				return $LANG['admin_require_password'];
			elseif( empty($password_repeat) )
				return $LANG['admin_require_password_repeat'];
			elseif( empty($user_mail) )
				return $LANG['admin_require_mail'];
			elseif( $password != $password_repeat )
				return $LANG['admin_passwords_error'];
			elseif( !preg_match('`^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-zA-Z]{2,4}$`', $user_mail)  )
				return $LANG['admin_email_error'];
			else
				return '';
		}
		$error = check_admin_account($login, $password, $password_repeat, $user_mail, $user_lang);
		//Si il n'y a pas d'erreur on enregistre dans la table
		if( empty($error) )
		{
			require_once('../includes/errors.class.php');
			$Errorh = new Errors;
			require_once('../includes/auth/config.php');
			define('PREFIX', $table_prefix);
			include_once('../includes/db/' . $dbtype . '.class.php');
			$Sql = new Sql;
			//On crée le code de déverrouillage
			include_once('../includes/cache.class.php');
			$Cache = new Cache;
			$Cache->Load_file('config');
			
			//On enregistre le membre
			$Sql->Query_inject("UPDATE ".PREFIX."member SET login = '" . securit($login) . "', password = '" . md5($password) . "', level = '2', user_lang = '" . $user_lang . "', user_theme = '" . $CONFIG['theme'] . "', user_mail = '" . $user_mail . "', user_show_mail = '1', timestamp = '" . time() . "', user_aprob = '1' WHERE user_id = '1'",__LINE__, __FILE__);
			
			$unlock_admin = substr(md5(uniqid(mt_rand(), true)), 0, 12); //Génération de la clée d'activation, en cas de verrouillage de l'administration.;
			$CONFIG['unlock_admin'] = md5($unlock_admin);
			$CONFIG['mail'] = $user_mail;
			$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
			$Cache->Generate_file('config');
			//On envoie un mail à l'administrateur
			$LANG['admin'] = '';
			include_once('../includes/mail.class.php');
			$Mail = new Mail();
			$Mail->Send_mail($user_mail, $LANG['admin_mail_object'], sprintf($LANG['admin_mail_unlock_code'], stripslashes($login), stripslashes($login), $password, $unlock_admin, HOST . DIR), $CONFIG['mail']);
			
			//On connecte directement l'administrateur si il l'a demandé
			if( $create_session )
			{
				include('../includes/constant.php');
				include('../includes/sessions.class.php');
				$Session = new Sessions;
				$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "' WHERE user_id = '1'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
				$Session->Session_begin(1, md5($password), 2, '/install/install.php', '', $LANG['page_title'], $auto_connection); //On lance la session.
			}
			
			//On redirige vers l'étape suivante
			redirect(HOST . FILE . add_lang('?step=7', true));
		}
		else
			$Template->Assign_block_vars('admin.error', array(
				'ERROR' => '<div class="warning">' . $error . '</div>'
			));
	}

	//Gestion langue par défaut.
	$array_identifier = '';
	$lang_identifier = '../images/stats/other.png';
	$rep = '../lang/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$file_array = array();
		$dh = @opendir( $rep);
		while( !is_bool($file = readdir($dh)) )
		{	
			//Si c'est un répertoire un regarde si c'est effectivement un dossier de langues
			if( !preg_match('`\.`', $file) )
			{
				$lang_info = load_ini_file('../lang/', $file);
				$lang_name = !empty($lang_info['name']) ? $lang_info['name'] : $lang;
				
				if( $lang_info )
				{
					$array_identifier .= 'array_identifier[\'' . $file . '\'] = \'' . $lang_info['identifier'] . '\';' . "\n";
					$selected = false;
					if( $file == $lang )
					{
						$selected = true;
						$lang_identifier = '../images/stats/countries/' . $lang_info['identifier'] . '.png';
					}					
					$Template->Assign_block_vars('admin.lang', array(
						'LANG' => $file,
						'LANG_NAME' => $lang_info['name'],
						'SELECTED' => ($selected) ? 'selected="selected"' : ''
					));
				}
			}
		}	
		closedir($dh); //On ferme le dossier
	}
	
	$Template->Assign_vars(array(
		'JS_LANG_IDENTIFIER' => $array_identifier,
		'IMG_LANG_IDENTIFIER' => $lang_identifier,
		'U_PREVIOUS_STEP' => add_lang('install.php?step=5'),
		'U_CURRENT_STEP' => add_lang('install.php?step=6'),
		'L_EXPLAIN_ADMIN_ACCOUNT' => $LANG['admin_account_creation_explain'],
		'L_ADMIN_ACCOUNT' => $LANG['admin_account'],
		'L_PSEUDO' => $LANG['admin_pseudo'],
		'L_PSEUDO_EXPLAIN' => $LANG['admin_pseudo_explain'],
		'L_PASSWORD' => $LANG['admin_password'],
		'L_PASSWORD_EXPLAIN' => $LANG['admin_password_explain'],
		'L_PASSWORD_REPEAT' => $LANG['admin_password_repeat'],
		'L_MAIL' => $LANG['admin_mail'],
		'L_MAIL_EXPLAIN' => $LANG['admin_mail_explain'],
		'L_LANG' => $LANG['admin_lang'],
		'L_PREVIOUS_STEP' => $LANG['previous_step'],
		'L_NEXT_STEP' => $LANG['next_step'],
		'L_ERROR' => $LANG['admin_error'],
		'L_REQUIRE_LOGIN' => $LANG['admin_require_login'],
		'L_LOGIN_TOO_SHORT' => $LANG['admin_login_too_short'],
		'L_REQUIRE_PASSWORD' => $LANG['admin_require_password'],
		'L_REQUIRE_PASSWORD_REPEAT' => $LANG['admin_require_password_repeat'],
		'L_REQUIRE_MAIL' => $LANG['admin_require_mail'],
		'L_PASSWORDS_ERROR' => $LANG['admin_passwords_error'],
		'L_CREATE_SESSION' => $LANG['admin_create_session'],
		'L_AUTO_CONNECTION' => $LANG['admin_auto_connection'],
		'L_EMAIL_ERROR' => $LANG['admin_email_error'],
		'LOGIN_VALUE' => !empty($error) ? $login : '',
		'PASSWORD_VALUE' => !empty($error) ? $password : '',
		'MAIL_VALUE' => !empty($error) ? $user_mail : '',
		'CHECKED_AUTO_CONNECTION' => !empty($error) ? ($auto_connection ? 'checked="checked"' : '') : 'checked="checked"',
		'CHECKED_CREATE_SESSION' => !empty($error) ? ($create_session ? 'checked="checked"' : '') : 'checked="checked"'
	));		
}
elseif( $step == 7 )
{
	//Liste des modules supportés par l'installateur
	$supported_modules = array('articles', 'calendar', 'contact', 'download', 'forum', 'gallery', 'guestbook', 'links', 'news', 'newsletter', 'online', 'pages', 'poll', 'shoutbox', 'stats', 'web', 'wiki');
	//Tableau contenant les modules inexistants sur le serveur
	$unexisting_modules = array();
	$modules_list = '';
	$index_modules = '';
	
	//Validation : installation des modules demandés
	if( !empty($_POST['submit']) )
	{
		foreach( $supported_modules as $module_name )
		{
			$lang_info = load_ini_file('../' . $module_name . '/lang/', $lang);
			if( $lang_info == array() )
				$unexisting_modules[] = $module_name;
		}
		
		$preselection = !empty($_POST['preselection_name']) ? securit($_POST['preselection_name']) : 'perso';
		$index_module = !empty($_POST['index_module']) ? securit($_POST['index_module']) : 'default';
		$index_module_url = '';
		$activ_member = !empty($_POST['activ_member']) ? true : false;
		
		require_once('../includes/errors.class.php');
		$Errorh = new Errors;
		include_once('../includes/auth/config.php');
		define('PREFIX', $table_prefix);
		define('DBTYPE', $dbtype);
		include_once('../includes/db/' . $dbtype . '.class.php');
		$Sql = new Sql;
		//On génère le cache
		include('../includes/cache.class.php');
		$Cache = new Cache;
		
		$link_installed = false; //Module de lien installé?
		
		//L'utilisateur a choisi une préselection
		if( in_array($preselection, array('no_one', 'all', 'community', 'publication')) )
		{
			//Configuration des préselections
			$preselections_configs = array(
				'no_one' => array('default', array(), 1),
				'community' => array('news', array('articles', 'gallery', 'news', 'forum', 'contact', 'newsletter', 'online', 'poll', 'calendar', 'shoutbox', 'stats', 'wiki', 'web', 'links', 'download', 'guestbook'), 1),
				'publication' => array('news', array('pages', 'contact', 'articles', 'web', 'stats', 'links', 'news'), 0),
				'all' => array('news', $supported_modules, 1)
			);
			
			//On enregistre le membre
			foreach( $preselections_configs[$preselection][1] as $module_name )
			{
				if( !in_array($module_name, $unexisting_modules) )
				{
					//Récupération des infos de config.
					$info_module = load_ini_file('../' . $module_name . '/lang/', $lang);
					
					//Si le dossier de base de données de la langue n'existe pas on prend le suivant exisant.
					$dir_db_module = $lang;
					$dir = '../' . $module_name . '/db';
					if( !is_dir($dir . '/' . $dir_db_module) )
					{	
						$dh = @opendir($dir);
						while( !is_bool($dir_db = @readdir($dh)) )
						{	
							if( !preg_match('`\.`', $dir_db) )
							{
								$dir_db_module = $dir_db;
								break;
							}
						}	
						@closedir($dh);
					}
						
					//Parsage du fichier sql si le module utilise la bdd
					if( file_exists('../' . $module_name . '/db/' . $dir_db_module . '/' . $module_name . '.' . DBTYPE . '.sql') )
						$Sql->Sql_parse('../' . $module_name . '/db/' . $dir_db_module . '/' . $module_name . '.' . DBTYPE . '.sql', PREFIX);

					//Insertion du modules dans la bdd => module installé.
					$Sql->Query_inject("INSERT INTO ".PREFIX."modules (name, version, auth, activ) VALUES ('" . securit($module_name) . "', '" . securit($info_module['version']) . "', '" . addslashes(serialize(array('r-1' => 1, 'r0' => 1, 'r1' => 1, 'r2' => 1))) . "', '1')", __LINE__, __FILE__);
					
					if( $module_name == 'links' )
						$link_installed = true;
				}
			}
			$Cache->Load_file('config');
			
			//Page de démarrage
			$module_infos = load_ini_file('../' . $preselections_configs[$preselection][0] . '/lang/', $lang);
			if( $preselections_configs[$preselection][0] != 'member' && $module_infos != array() )
			{
				if( !empty($module_infos['starteable_page']) )
					$start_page = $preselections_configs[$preselection][0] . '/' . $module_infos['starteable_page'];
				else
					$start_page = 'member/member.php';
				
			}
			else
				$start_page = 'member/member.php';
			$CONFIG['start_page'] = '/' . $start_page;

			$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
			
			//Ecriture du fichier de redirection
			$file_path = '../index.php';
			delete_file($file_path); //Rippe le fichier
			
			$start_page =  HOST . DIR . $CONFIG['start_page'];
			
			$file = @fopen($file_path, 'w+'); //On crée le fichier avec droit d'écriture et lecture.
			@fwrite($file, '<?php header(\'location: ' . $start_page . '\'); ?>');
			@fclose($file);
			
			$Cache->Load_file('member');
			$CONFIG_MEMBER['activ_mbr'] = $preselections_configs[$preselection][2];
			$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG_MEMBER)) . "' WHERE name = 'member'", __LINE__, __FILE__); //MAJ
		}
		//Sinon combinaison personnalisée
		else
		{
			foreach($supported_modules as $module_name )
			{
				if( !in_array($module_name, $unexisting_modules) )
				{
					//Si le module est demandé
					if( !empty($_POST['install_' . $module_name]) )
					{
						//Récupération des infos de config.
						$info_module = load_ini_file('../' . $module_name . '/lang/', $lang);

						//Si le dossier de base de données de la langue n'existe pas on prend le suivant exisant.
						$dir_db_module = $lang;
						$dir = '../' . $module_name . '/db';
						if( !is_dir($dir . '/' . $dir_db_module) )
						{	
							$dh = @opendir($dir);
							while( !is_bool($dir_db = @readdir($dh)) )
							{	
								if( !preg_match('`\.`', $dir_db) )
								{
									$dir_db_module = $dir_db;
									break;
								}
							}	
							@closedir($dh);
						}
		
						//Parsage du fichier sql.
						if( file_exists('../' . $module_name . '/db/' . $dir_db_module . '/' . $module_name . '.' . DBTYPE . '.sql') )
							$Sql->Sql_parse('../' . $module_name . '/db/' . $dir_db_module . '/' . $module_name . '.' . DBTYPE . '.sql', PREFIX);

						//Insertion du modules dans la bdd => module installé.
						$Sql->Query_inject("INSERT INTO ".PREFIX."modules (name, version, auth, activ) VALUES ('" . securit($module_name) . "', '" . securit($info_module['version']) . "', '" . addslashes(serialize(array('r-1' => 1, 'r0' => 1, 'r1' => 1, 'r2' => 1))) . "', '1')", __LINE__, __FILE__);
						
						if( $module_name == 'links' )
							$link_installed = true;
					}
				}
			}
			//Désactivation de l'espace membre dans le cas où c'est demandé
			if( !$activ_member )
			{
				$Cache->Load_file('member');
				$CONFIG_MEMBER['activ_mbr'] = 0;
				$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG_MEMBER)) . "' WHERE name = 'member'", __LINE__, __FILE__); //MAJ
			}
			//Traitement de la page de démarrage
			if( !empty($_POST['install_' . $index_module]) && in_array($index_module, $supported_modules) )
			{
				$info_module = load_ini_file('../' . $index_module . '/lang/', $lang);
				if( !empty($info_module['starteable_page']) )
					$index_module_url = '/' . $index_module . '/' . $info_module['starteable_page'];
				else
					$index_module_url = '/member/member.php';
			}
			else
				$index_module_url = '/member/member.php';
				
			$Cache->Load_file('config');
			$CONFIG['start_page'] = $index_module_url;
			$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
			
			//Ecriture du fichier de redirection
			$file_path = '../index.php';
			delete_file($file_path); //Rippe le fichier
			
			$start_page =  HOST . DIR . $CONFIG['start_page'];
			
			$file = @fopen($file_path, 'w+'); //On crée le fichier avec droit d'écriture et lecture.
			@fwrite($file, '<?php header(\'location: ' . $start_page . '\'); ?>');
			@fclose($file);
		}
		
		//On réassigne le class des modules minis
		$i = 0;
		$result = $Sql->Query_while("SELECT id
		FROM ".PREFIX."modules_mini 
		ORDER BY side, name", __LINE__, __FILE__);
		while($row = $Sql->Sql_fetch_assoc($result) )
		{
			$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = '" . $i . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			$i++;
		}
		$Sql->Close($result);		
		
		//On insère les liens dans le module de liens, s'il est installé.
		if( $link_installed )
		{
			$i = 6;
			$result = $Sql->Query_while("SELECT name
			FROM ".PREFIX."modules 
			WHERE activ = 1
			ORDER BY name", __LINE__, __FILE__);
			while($row = $Sql->Sql_fetch_assoc($result) )
			{
				$info_module = load_ini_file('../' . $row['name'] . '/lang/', $lang);
				if( !empty($info_module['name']) && !empty($info_module['starteable_page']) )
					$Sql->Query_inject("INSERT INTO ".PREFIX."links (class, name, url, activ, secure, added, sep) VALUES ('" . $i . "', '" . addslashes($info_module['name']) . "', '../" . $row['name'] . "/" . addslashes($info_module['starteable_page']) . "', 1, '-1', 0, 0)", __LINE__, __FILE__);
				$i++;
			}
			$Sql->Close($result);
		}
		
		$i = 1;		
		$side = true;
		$result = $Sql->Query_while("SELECT id, side
		FROM ".PREFIX."modules_mini 
		ORDER BY side, name", __LINE__, __FILE__);
		while($row = $Sql->Sql_fetch_assoc($result) )
		{
			if( $row['side'] == 1 && $side )
			{
				$i = 1;
				$side = false;
			}
			$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = '" . $i . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			$i++;
		}
		$Sql->Close($result);
		
		//Régénération du cache
		$Cache->Generate_htaccess();
		$Cache->Generate_all_files();		
		
		//On redirige vers l'étape suivante
		redirect(HOST . FILE . add_lang('?step=8', true));
	}
	
	$Template->Assign_block_vars('modules', array());
	
	foreach( $supported_modules as $module_name )
	{
		$module_info = load_ini_file('../' . $module_name . '/lang/', $lang);
		if( $module_info != array() )
		{
			$Template->Assign_block_vars('modules.module_list', array(
				'MODULE_NAME' => $module_info['name'],
				'MODULE_DESC' => $module_info['info'],
				'MODULE_FOLDER_NAME' => $module_name,
				'SRC_IMAGE_MODULE' => '../' . $module_name . '/' . $module_name . '.png'
			));
			$Template->Assign_block_vars('modules.module_index_list', array(
				'MODULE_NAME' => $module_info['name'],
				'MODULE' => $module_name
			));
			if( !empty($module_info['starteable_page']) )
				$index_modules .= '\'' . $module_name . '\', ';
			$modules_list .= '\'' . $module_name . '\', ';
		}
		else
			$unexisting_modules[] = $module_name;
	}
	$Template->Assign_vars(array(
		'ARRAY_MODULE_LIST' => trim($modules_list, ', '),
		'ARRAY_MODULE_INDEX_LIST' => trim($index_modules, ', '),
		'L_EXPLAIN_MODULES' => $LANG['modules_explain'],
		'L_MODULE_LIST' => $LANG['modules_list'],
		'L_PRESELECTIONS' => $LANG['modules_preselections'],
		'L_NO_MODULE' => $LANG['modules_no_module'],
		'L_ALL' => $LANG['modules_all'],
		'L_COMMUNITY' => $LANG['modules_community'],
		'L_PUBLICATION' => $LANG['modules_publication'],
		'L_PERSO' => $LANG['modules_perso'],
		'L_OTHER_OPTIONS' => $LANG['modules_other_options'],
		'L_ACTIV_MEMBER_ACCOUNTS' => $LANG['modules_activ_member_accounts'],
		'L_INDEX_MODULE' => $LANG['modules_index_module'],
		'L_REQUIRE_JAVASCRIPT' => $LANG['modules_require_javascript'],
		'L_DEFAULT_INDEX' => $LANG['modules_default_index'],
		'L_PREVIOUS_STEP' => $LANG['previous_step'],
		'L_NEXT_STEP' => $LANG['next_step'],
		'U_PREVIOUS_STEP' => add_lang('install.php?step=6'),
		'U_CURRENT_STEP' => add_lang('install.php?step=7')
	));
}
elseif( $step == 8 )
{
	$Template->Assign_block_vars('register_online', array());
	$Template->Assign_vars(array(
		'U_PREVIOUS_STEP' => add_lang('install.php?step=7'),
		'U_NEXT_STEP' => add_lang('install.php?step=9'),
		'L_PREVIOUS_STEP' => $LANG['previous_step'],
		'L_NEXT_STEP' => $LANG['next_step'],
		'L_REGISTER_EXPLAIN' => $LANG['register_online_explain'],
		'L_REGISTER' => $LANG['register'],
		'L_I_WANT_TO_REGISTER' => $LANG['register_i_want_to']
	));
}
elseif( $step == 9 )
{
	require_once('../includes/errors.class.php');
	$Errorh = new Errors;
	include_once('../includes/auth/config.php');
	define('PREFIX', $table_prefix);
	define('DBTYPE', $dbtype);
	include_once('../includes/db/' . $dbtype . '.class.php');
	$Sql = new Sql;
	//On génère le cache
	include('../includes/cache.class.php');
	$Cache = new Cache;
	$Cache->Load_file('config');
	
	//Enregistrement en ligne
	if( !empty($_POST['register']) )
		$register_file = 'update.php?t=' . $CONFIG['site_name'] . '&amp;s=-1&amp;v=' . $update_version . '&amp;u=' . $CONFIG['server_name'] . $CONFIG['server_path'];
	else
		$register_file = 'update.php?t=' . $CONFIG['site_name'] . '&amp;s=2&amp;v=' . $update_version . '&amp;u=' . $CONFIG['server_name'] . $CONFIG['server_path'];
	
	$Template->Assign_block_vars('end', array(
		'CONTENTS' => sprintf($LANG['end_installation']),
		'REGISTER' => '<img src="http://www.phpboost.com/phpboost/' . str_replace('"', '\"', $register_file) . '" alt="" />',		
		'U_INDEX' => '..' . $CONFIG['start_page']
	));
	
	$Template->Assign_vars(array(
		'L_SITE_INDEX' => $LANG['site_index']
	));
}

$steps = array(
	array($LANG['introduction'], 'intro.png', 0),
	array($LANG['license'], 'license.png', 10),
	array($LANG['config_server'], 'config.png', 20),
	array($LANG['database_config'], 'database.png', 30),
	array($LANG['advanced_config'], 'advanced_config.png', 40),
	array($LANG['administrator_account_creation'], 'admin.png', 60),
	array($LANG['modules_installation'], 'modules.png', 70),
	array($LANG['register_online'], 'register_online.png', 80),
	array($LANG['end'], 'end.png', 100)
);

$step_name = $steps[$step - 1][0];

$rep = '../lang/';
if( is_dir($rep) ) //Si le dossier existe
{
	$file_array = array();
	$dh = @opendir( $rep);
	while( !is_bool($file = readdir($dh)) )
	{	
		//Si c'est un répertoire un regarde si c'est effectivement un dossier de langues
		if( !preg_match('`\.`', $file) )
		{
			$info_lang = load_ini_file('../lang/', $file);
			if( !empty($info_lang['name']) )
			{	
				$Template->Assign_block_vars('lang', array(
					'LANG' => $file,
					'LANG_NAME' => $info_lang['name'],
					'SELECTED' => $file == $lang ? 'selected="selected"' : ''
				));
				
				if(	$file == $lang )
				{
					$Template->Assign_vars(array(
						'LANG_IDENTIFIER' => $info_lang['identifier']
					));
				}
			}
		}
	}	
	closedir($dh); //On ferme le dossier
}

$Template->Assign_vars(array(
	'LANG' => $lang,
	'NUM_STEP' => $step,
	'PROGRESS_BAR_PICS' => str_repeat('<img src="templates/images/loading.png" alt="" />', floor($steps[$step - 1][2] * 24 / 100)),
	'PROGRESS_LEVEL' => $steps[$step - 1][2],
	'L_TITLE' => $LANG['page_title'] . ' - ' . $step_name,
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

for($i = 1; $i <= 9; $i++ )
{
	if( $i < $step )
		$row = 'row_success';
	elseif( $i == $step )
		$row = 'row_current';
	else
		$row = 'row_next';
	$Template->Assign_block_vars('link_menu', array(
		'ROW' => '<tr>
				<td class="' . $row . '">
					<img src="templates/images/' . $steps[$i - 1][1] . '" alt="' . $steps[$i - 1][0] . '" style="vertical-align:middle;" />&nbsp;&nbsp;' . $steps[$i - 1][0] . '
				</td>				
			</tr>'
	));
}

$Template->Pparse('install');

ob_end_flush();

?>