<?php
/*##################################################
 *                             admin_config.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');

require_once(PATH_TO_ROOT.'/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT.'/admin/admin_header.php');

$check_advanced = !empty($_GET['adv']);

//Variables serveur.
$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
if (!$server_path)
{
	$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
}
$server_path = trim(str_replace('/admin', '', dirname($server_path)));
$server_name = 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));

//Si c'est confirmé on exécute
if (!empty($_POST['valid']) && empty($_POST['cache']))
{
	//Gestion de la page de démarrage.
	if (!empty($_POST['start_page2']) )
	{
		$start_page = TextHelper::strprotect($_POST['start_page2'], HTML_UNPROTECT);
	}
	elseif (!empty($_POST['start_page']))
	{
		$start_page = TextHelper::strprotect($_POST['start_page'], HTML_UNPROTECT);
	}
	else
	{
		$start_page = '';
	}
		
	$config = $CONFIG;	 
	$config['site_name'] 	= stripslashes(retrieve(POST, 'site_name', ''));	
	$config['site_desc'] 	= stripslashes(retrieve(POST, 'site_desc', ''));
	$config['site_keyword'] = stripslashes(retrieve(POST, 'site_keyword', ''));
	$config['lang'] 		= stripslashes(retrieve(POST, 'lang', ''));
	$config['theme'] 		= stripslashes(retrieve(POST, 'theme', 'base')); //main par defaut. 
	$config['start_page'] 	= !empty($start_page) ? stripslashes($start_page) : '/member/member.php';
	$config['compteur'] 	= retrieve(POST, 'compteur', 0);
	$config['bench'] 		= retrieve(POST, 'bench', 0);
	$config['theme_author'] = retrieve(POST, 'theme_author', 0);

	if (!empty($config['theme']) && !empty($config['lang'])) //Nom de serveur obligatoire
	{
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config)) . "' WHERE name = 'config'", __LINE__, __FILE__);
		$Cache->Generate_file('config');
		
		AppContext::get_response()->redirect(HOST . SCRIPT);
	}
	else
	{
		AppContext::get_response()->redirect('/admin/admin_config.php?error=incomplete#errorh');
	}
}
elseif ($check_advanced && empty($_POST['advanced']))
{
	$Template->set_filenames(array(
		'admin_config2'=> 'admin/admin_config2.tpl'
	));	
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
	{
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	}
	elseif (isset($_GET['mail']))
	{
		$Errorh->handler($LANG['unlock_admin_confirm'], E_USER_NOTICE);
	}
	
	//Gestion fuseau horaire par défaut.
	$select_timezone = '';
	for ($i = -12; $i <= 14; $i++)
	{
		$selected = ($i == $CONFIG['timezone']) ? 'selected="selected"' : '';
		$name = (!empty($i) ? ($i > 0 ? ' + ' . $i : ' - ' . -$i) : '');
		$select_timezone .= '<option value="' . $i . '" ' . $selected . '> [GMT' . $name . ']</option>';
	}
	
	$Template->assign_vars(array(
		'SERVER_NAME' 		=> !empty($CONFIG['server_name']) ? $CONFIG['server_name'] : $server_name,
		'SERVER_PATH' 		=> isset($CONFIG['server_path']) ? $CONFIG['server_path'] : $server_path,
		'SELECT_TIMEZONE' 	=> $select_timezone,
		'CHECKED' 			=> ($CONFIG['rewrite'] == '1') ? 'checked="checked"' : '',
		'UNCHECKED' 		=> ($CONFIG['rewrite'] == '0') ? 'checked="checked"' : '',
		'CHECK_REWRITE' 	=> new ServerConfiguration()->has_url_rewriting()has_url_rewriting() ? '<span class="success_test">' . $LANG['yes'] . '</span>' : '<span class="failure_test">' . $LANG['no'] . '</span>',
		'HTACCESS_MANUAL_CONTENT' => !empty($CONFIG['htaccess_manual_content']) ? $CONFIG['htaccess_manual_content'] : '',
		'GZ_DISABLED' 		=> (!function_exists('ob_gzhandler') || !@extension_loaded('zlib')) ? 'disabled="disabled"' : '',
		'GZHANDLER_ENABLED' => ($CONFIG['ob_gzhandler'] == 1 && (function_exists('ob_gzhandler') && @extension_loaded('zlib'))) ? 'checked="checked"' : '',
		'GZHANDLER_DISABLED' => ($CONFIG['ob_gzhandler'] == 0) ? 'checked="checked"' : '',
		'SITE_COOKIE' 		=> !empty($CONFIG['site_cookie']) ? $CONFIG['site_cookie'] : 'session',
		'SITE_SESSION' 		=> !empty($CONFIG['site_session']) ? $CONFIG['site_session'] : '3600',
		'SITE_SESSION_VISIT'=> !empty($CONFIG['site_session_invit']) ? $CONFIG['site_session_invit'] : '300',
	    'DEBUG_ENABLED'     => Debug::is_debug_mode_enabled() ? 'checked="checked"' : '',
        'DEBUG_DISABLED'    => !Debug::is_debug_mode_enabled() ? 'checked="checked"' : '',
		'L_SECONDS' 		=> $LANG['unit_seconds'],
		'L_REQUIRE_SERV' 	=> $LANG['require_serv'],
		'L_REQUIRE_NAME' 	=> $LANG['require_name'],
		'L_REQUIRE_COOKIE_NAME' 	=> $LANG['require_cookie_name'],
		'L_REQUIRE_SESSION_TIME' 	=> $LANG['require_session_time'],
		'L_REQUIRE_SESSION_INVIT' 	=> $LANG['require_session_invit'],
		'L_REQUIRE' 		=> $LANG['require'],
		'L_SERV_NAME' 		=> $LANG['serv_name'],
		'L_SERV_NAME_EXPLAIN' 	=> $LANG['serv_name_explain'],
		'L_SERV_PATH' 			=> $LANG['serv_path'],
		'L_SERV_PATH_EXPLAIN' 	=> $LANG['serv_path_explain'],
		'L_CONFIG' 			=> $LANG['configuration'],
		'L_CONFIG_MAIN' 	=> $LANG['config_main'],
		'L_CONFIG_ADVANCED' => $LANG['config_advanced'],
		'L_MAIL_CONFIG'		=> $LANG['config_mail'],
		'L_REWRITE' 		=> $LANG['rewrite'],
		'L_EXPLAIN_REWRITE' => $LANG['explain_rewrite'], 
		'L_REWRITE_SERVER' 	=> $LANG['server_rewrite'],
		'L_HTACCESS_MANUAL_CONTENT' 		=> $LANG['htaccess_manual_content'],
		'L_HTACCESS_MANUAL_CONTENT_EXPLAIN' => $LANG['htaccess_manual_content_explain'],
		'L_TIMEZONE_CHOOSE' => $LANG['timezone_choose'],
		'L_TIMEZONE_CHOOSE_EXPLAIN' => $LANG['timezone_choose_explain'],
	    'L_DEBUG' 			=> $LANG['debug_mode'],
        'L_DEBUG_EXPLAIN' => $LANG['debug_mode_explain'],
		'L_ACTIV' 			=> $LANG['activ'],
		'L_UNACTIVE' 		=> $LANG['unactiv'],
		'L_USER_CONNEXION' 	=> $LANG['user_connexion'],
		'L_COOKIE_NAME' 	=> $LANG['cookie_name'],
		'L_SESSION_TIME' 	=> $LANG['session_time'],
		'L_SESSION_TIME_EXPLAIN' => $LANG['session_time_explain'],
		'L_SESSION_INVIT' 	=> $LANG['session invit'],
		'L_SESSION_INVIT_EXPLAIN' => $LANG['session invit_explain'],
		'L_MISC' 			=> $LANG['miscellaneous'],	
		'L_ACTIV_GZHANDLER' => $LANG['activ_gzhandler'],
		'L_ACTIV_GZHANDLER_EXPLAIN' => $LANG['activ_gzhandler_explain'],
		'L_CONFIRM_UNLOCK_ADMIN' 	=> $LANG['confirm_unlock_admin'],
		'L_UNLOCK_ADMIN' 	=> $LANG['unlock_admin'],
		'L_UNLOCK_ADMIN_EXPLAIN' 	=> $LANG['unlock_admin_explain'],
		'L_UNLOCK_LINK' 	=> $LANG['send_unlock_admin'],
		'L_UPDATE' 			=> $LANG['update'],
		'L_RESET' 			=> $LANG['reset']	
	));
	
	$Template->pparse('admin_config2');
}
elseif (!empty($_POST['advanced']))
{
	$CONFIG['rewrite'] = 1;
	$CONFIG['server_name'] = trim(TextHelper::strprotect(retrieve(POST, 'server_name', $server_name, TSTRING_AS_RECEIVED), TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE), '/');
	 
	$CONFIG['server_path'] = trim(TextHelper::strprotect(retrieve(POST, 'server_path', $server_path, TSTRING_AS_RECEIVED), TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE), '/');
	//Si le chemin de PHPBoost n'est pas vide, on y ajoute un / devant
	if ($CONFIG['server_path'] != '')
	{
		$CONFIG['server_path'] = '/' . $CONFIG['server_path'];
	}
		  
	$CONFIG['timezone'] = retrieve(POST, 'timezone', 0);  
	$CONFIG['ob_gzhandler'] = (!empty($_POST['ob_gzhandler'])&& function_exists('ob_gzhandler') && @extension_loaded('zlib')) ? 1 : 0;
	$CONFIG['site_cookie'] = TextHelper::strprotect(retrieve(POST, 'site_cookie', 'session', TSTRING_UNCHANGE), TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE); //Session par defaut.
	$CONFIG['site_session'] = retrieve(POST, 'site_session', 3600); //Valeur par defaut à 3600.					
	$CONFIG['site_session_invit'] = retrieve(POST, 'site_session_invit', 300); //Durée compteur 5min par defaut.
	$CONFIG['htaccess_manual_content'] = retrieve(POST, 'htaccess_manual_content', '', TSTRING_UNCHANGE);
	$CONFIG['debug_mode'] = retrieve(POST, 'debug', 0);
	
	if (!empty($CONFIG['server_name']) && !empty($CONFIG['site_cookie']) && !empty($CONFIG['site_session']) && !empty($CONFIG['site_session_invit']) ) //Nom de serveur obligatoire
	{
		list($host, $dir) = array($CONFIG['server_name'], $CONFIG['server_path']); //Réassignation pour la redirection.
		if (empty($_POST['rewrite_engine']) || strpos($_SERVER['SERVER_NAME'], 'free.fr')) //Désactivation de l'url rewriting.
		{
			$CONFIG['rewrite'] = 0;
		}
			
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
		###### Régénération du cache $CONFIG #######
		$Cache->generate_file('config');
		
		// TODO remove it when the $CONFIG variable will be managed by the new config manager
		if ($CONFIG['debug_mode'])
		{
			Debug::enabled_debug_mode();
		}
		else
		{
			Debug::disable_debug_mode();
		}
		
		//Régénération du htaccess.
		
		HtaccessFileCache::regenerate();
			
		AppContext::get_response()->redirect($host . $dir . '/admin/admin_config.php?adv=1');
	}
	else
	{
		AppContext::get_response()->redirect('/admin/admin_config.php?adv=1&error=incomplete#errorh');
	}
}
else //Sinon on rempli le formulaire	 
{		
	$Template->set_filenames(array(
		'admin_config'=> 'admin/admin_config.tpl'
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
	{
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	}
	
	$select_page = '';
	$start_page = '';
	//Pages de démarrage
	$i = 0;
	$modules_names = ModulesManager::get_installed_modules_ids_list();

	foreach ($modules_names as $name)
	{
		$config_ini = PATH_TO_ROOT . '/' .$name. '/config.ini';
		$desc_ini = PATH_TO_ROOT . '/' .$name. '/lang/'. get_ulang() . '/desc.ini';
		if(file_exists($desc_ini))
		{
		
			$GetModuleConfiguration = new ModuleConfiguration($config_ini, $desc_ini);
			
			$selected = '/' . $name . '/' . $GetModuleConfiguration->get_home_page() == $CONFIG['start_page'] ? 'selected="selected"' : '';
			$select_page .= '<option value="' . '/' . $name . '/' . $GetModuleConfiguration->get_home_page() . '" ' . $selected . '>' . $GetModuleConfiguration->get_name() . '</option>';
		
			$i++;
		}
	}
	if ($i == 0)
	{
		$select_page = '<option value="" selected="selected">' . $LANG['no_module_starteable'] . '</option>';
	}

	$Template->assign_vars(array(		
		'THEME' => get_utheme(),
		'THEME_DEFAULT' => $CONFIG['theme'],
		'SITE_NAME' => !empty($CONFIG['site_name']) ? $CONFIG['site_name'] : '',
		'SITE_DESCRIPTION' => !empty($CONFIG['site_desc']) ? $CONFIG['site_desc'] : '',
		'SITE_KEYWORD' => !empty($CONFIG['site_keyword']) ? $CONFIG['site_keyword'] : '',		
		'SELECT_PAGE' => $select_page, 
		'START_PAGE' => empty($start_page) ? $CONFIG['start_page'] : '', 
		'NOTE_MAX' => isset($CONFIG['note_max']) ? $CONFIG['note_max'] : '10',
		'COMPTEUR_ENABLED' => ($CONFIG['compteur'] == 1) ? 'checked="checked"' : '',
		'COMPTEUR_DISABLED' => ($CONFIG['compteur'] == 0) ? 'checked="checked"' : '',
		'BENCH_ENABLED' => ($CONFIG['bench'] == 1) ? 'checked="checked"' : '',
		'BENCH_DISABLED' => ($CONFIG['bench'] == 0) ? 'checked="checked"' : '',
		'THEME_AUTHOR_ENABLED' => ($CONFIG['theme_author'] == 1) ? 'checked="checked"' : '',
		'THEME_AUTHOR_DISABLED' => ($CONFIG['theme_author'] == 0) ? 'checked="checked"' : '',

		'L_REQUIRE' => $LANG['require'],
		'L_CONFIG' => $LANG['configuration'],
		'L_CONFIG_MAIN' => $LANG['config_main'],
		'L_CONFIG_ADVANCED' => $LANG['config_advanced'],
		'L_MAIL_CONFIG'		=> $LANG['config_mail'],
		'L_SITE_NAME' => $LANG['site_name'],
		'L_SITE_DESC' => $LANG['site_desc'],
		'L_SITE_DESC_EXPLAIN' => $LANG['site_desc_explain'],
		'L_SITE_KEYWORDS' => $LANG['site_keywords'],
		'L_SITE_KEYWORDS_EXPLAIN' => $LANG['site_keywords_explain'],
		'L_DEFAULT_LANGUAGES' => $LANG['default_language'],
		'L_DEFAULT_THEME' => $LANG['default_theme'],
		'L_START_PAGE' => $LANG['start_page'],
		'L_OTHER' => $LANG['other_start_page'],
		'L_COMPT' => $LANG['compt'],
		'L_BENCH' => $LANG['bench'],
		'L_BENCH_EXPLAIN' => $LANG['bench_explain'],
		'L_THEME_AUTHOR' => $LANG['theme_author'],
		'L_THEME_AUTHOR_EXPLAIN' => $LANG['theme_author_explain'],
		'L_REWRITE' => $LANG['rewrite'],
		'L_ACTIV' 			=> $LANG['activ'],
		'L_UNACTIVE' 		=> $LANG['unactiv'],

		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']		
	));

	//Gestion langue par défaut.
	
	$lang_array = array();
	$lang_folder_path = new Folder('../lang/');
	foreach ($lang_folder_path->get_folders('`^[a-z0-9_ -]+$`i') as $lang)
	{
		$lang_array[] = $lang->get_name();
	}
	
	$lang_array_bdd = array();
	$result = $Sql->query_while("SELECT lang 
	FROM " . PREFIX . "lang", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On recherche les clées correspondante à celles trouvée dans la bdd.
		if (array_search($row['lang'], $lang_array) !== false)
		{
			$lang_array_bdd[] = $row['lang']; //On insère ces clées dans le tableau.
		}
	}
	$Sql->query_close($result);
	
	$array_identifier = '';
	$lang_identifier = '../images/stats/other.png';
	foreach ($lang_array_bdd as $lang_key => $lang_value) //On effectue la recherche dans le tableau.
	{
		$lang_info = load_ini_file('../lang/', $lang_value);
		if ($lang_info)
		{
			$lang_name = !empty($lang_info['name']) ? $lang_info['name'] : $lang_value;
			
			$array_identifier .= 'array_identifier[\'' . $lang_value . '\'] = \'' . $lang_info['identifier'] . '\';' . "\n";
			$selected = '';
			if ($lang_value == $CONFIG['lang'])
			{
				$selected = 'selected="selected"';
				$lang_identifier = '../images/stats/countries/' . $lang_info['identifier'] . '.png';
			}
			$Template->assign_block_vars('select_lang', array(
				'LANG' => '<option value="' . $lang_value . '" ' . $selected . '>' . $lang_name . '</option>'
			));
		}
	}
	$Template->assign_vars(array(
		'JS_LANG_IDENTIFIER' => $array_identifier,
		'IMG_LANG_IDENTIFIER' => $lang_identifier
	));
	
	foreach ($THEME_CONFIG as $theme => $array_info) 
	{

		if ($theme != 'default' && $array_info['activ'] == 1)
    	{
			$info_theme = @parse_ini_file(PATH_TO_ROOT . '/templates/' . $theme . '/config/' . get_ulang() . '/config.ini');
			$selected = ($theme == $CONFIG['theme']) ? ' selected="selected"' : '';
    		$Template->assign_block_vars('select', array(
				'THEME' => '<option value="' . $theme . '" ' . $selected . '>' . $info_theme['name'] . '</option>'
    		));
    	}
	}	
	
	$Template->pparse('admin_config');
}

//Renvoi du code de déblocage.
if (!empty($_GET['unlock']))
{
	
	$unlock_admin_clean = substr(strhash(uniqid(mt_rand(), true)), 0, 18); //Génération de la clée d'activation, en cas de verrouillage de l'administration.;
	$unlock_admin = strhash($unlock_admin_clean);
	
	$CONFIG['unlock_admin'] = $unlock_admin;
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	
	###### Régénération du cache $CONFIG #######
	$Cache->Generate_file('config');
	
	AppContext::get_mail_service()->send_from_properties($User->get_attribute('user_mail'), $LANG['unlock_title_mail'], sprintf($LANG['unlock_mail'], $unlock_admin_clean), $CONFIG['mail_exp']);	

	AppContext::get_response()->redirect('/admin/admin_config.php?adv=1&mail=1');
}

require_once(PATH_TO_ROOT.'/admin/admin_footer.php');

?>
