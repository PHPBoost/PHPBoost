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

$server_configuration = new ServerConfiguration();

//Si c'est confirmé on exécute
if (!empty($_POST['valid']) && empty($_POST['cache']))
{
	// Page de démarrage
	$start_page = !empty($_POST['start_page2']) ? TextHelper::strprotect($_POST['start_page2'], HTML_UNPROTECT) : (!empty($_POST['start_page']) ? TextHelper::strprotect($_POST['start_page'], HTML_UNPROTECT) : '/member/member.php');
	$config = $CONFIG;	 
	$config['lang'] 		= stripslashes(retrieve(POST, 'lang', ''));
	$config['theme'] 		= stripslashes(retrieve(POST, 'theme', 'base')); //main par defaut. 
	$config['bench'] 		= retrieve(POST, 'bench', 0);
	$config['theme_author'] = retrieve(POST, 'theme_author', 0);

	if (!empty($config['theme']) && !empty($config['lang'])) //Nom de serveur obligatoire
	{
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config)) . "' WHERE name = 'config'", __LINE__, __FILE__);
		$Cache->Generate_file('config');
		
		$general_config = GeneralConfig::load();
		$general_config->set_site_name(stripslashes(retrieve(POST, 'site_name', '')));
		$general_config->set_site_description(stripslashes(retrieve(POST, 'site_desc', '')));
		$general_config->set_site_keywords(stripslashes(retrieve(POST, 'site_keyword', '')));
		$general_config->set_home_page(stripslashes($start_page));
		GeneralConfig::save();
		
		$graphical_environment_config = GraphicalEnvironmentConfig::load();
		$graphical_environment_config->set_visit_counter_enabled((boolean)retrieve(POST, 'compteur', 0));
		GraphicalEnvironmentConfig::save();
		
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
	
	$general_config = GeneralConfig::load();
	$server_environment_config = ServerEnvironmentConfig::load();
	
	//Gestion fuseau horaire par défaut.
	$select_timezone = '';
	$timezone = $general_config->get_site_timezone();
	for ($i = -12; $i <= 14; $i++)
	{
		$selected = ($i == $timezone) ? 'selected="selected"' : '';
		$name = (!empty($i) ? ($i > 0 ? ' + ' . $i : ' - ' . -$i) : '');
		$select_timezone .= '<option value="' . $i . '" ' . $selected . '> [GMT' . $name . ']</option>';
	}
	
	$Template->assign_vars(array(
		'SERVER_NAME' 		=> $general_config->get_site_url(),
		'SERVER_PATH' 		=> $general_config->get_site_path(),
		'SELECT_TIMEZONE' 	=> $select_timezone,
		'CHECKED' 			=> $server_environment_config->is_url_rewriting_enabled() ? 'checked="checked"' : '',
		'UNCHECKED' 		=> !$server_environment_config->is_url_rewriting_enabled() ? 'checked="checked"' : '',
		'CHECK_REWRITE' 	=> $server_configuration->has_url_rewriting() ? '<span class="success_test">' . $LANG['yes'] . '</span>' : '<span class="failure_test">' . $LANG['no'] . '</span>',
		'HTACCESS_MANUAL_CONTENT' => !empty($CONFIG['htaccess_manual_content']) ? $CONFIG['htaccess_manual_content'] : '',
		'GZ_DISABLED' 		=> ((!function_exists('ob_gzhandler') || !@extension_loaded('zlib')) ? 'disabled="disabled"' : ''),
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
	$site_url = trim(TextHelper::strprotect(retrieve(POST, 'server_name', GeneralConfig::get_default_site_url(), TSTRING_AS_RECEIVED), TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE), '/');
	$site_path = trim(TextHelper::strprotect(retrieve(POST, 'server_path', GeneralConfig::get_default_site_path('/admin'), TSTRING_AS_RECEIVED), TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE), '/');
	
	if ($site_path != '')
	{
		$site_path = '/' . $site_path;
	}
	
	$timezone = retrieve(POST, 'timezone', 0);
	  
	$url_rewriting = retrieve(POST, 'rewrite_engine', false);
		  
	$CONFIG['ob_gzhandler'] = (!empty($_POST['ob_gzhandler'])&& function_exists('ob_gzhandler') && @extension_loaded('zlib')) ? 1 : 0;
	$CONFIG['site_cookie'] = TextHelper::strprotect(retrieve(POST, 'site_cookie', 'session', TSTRING_UNCHANGE), TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE); //Session par defaut.
	$CONFIG['site_session'] = retrieve(POST, 'site_session', 3600); //Valeur par defaut à 3600.					
	$CONFIG['site_session_invit'] = retrieve(POST, 'site_session_invit', 300); //Durée compteur 5min par defaut.
	$CONFIG['htaccess_manual_content'] = retrieve(POST, 'htaccess_manual_content', '', TSTRING_UNCHANGE);
	$CONFIG['debug_mode'] = retrieve(POST, 'debug', 0);
	
	if (!empty($site_url) && !empty($CONFIG['site_cookie']) && !empty($CONFIG['site_session']) && !empty($CONFIG['site_session_invit']) ) //Nom de serveur obligatoire
	{
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
		
		$general_config = GeneralConfig::load();
		$general_config->set_site_url($site_url);
		$general_config->set_site_path($site_path);
		$general_config->set_site_timezone($timezone);
		GeneralConfig::save();
		
		$server_environment_config = ServerEnvironmentConfig::load();
		$server_environment_config->set_url_rewriting_enabled($url_rewriting);
		ServerEnvironmentConfig::save();
		
		AppContext::get_response()->redirect($site_url . $site_path . '/admin/admin_config.php?adv=1');
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
	
	$general_config = GeneralConfig::load();
	
	//Pages de démarrage
	$select_page = '';
	$start_page = array();
	$i = 0;
	foreach (ModulesManager::get_installed_modules_ids_list() as $name)
	{
		$module_configuration = ModuleConfigurationManager::get($name);
		
		if ($module_configuration->get_home_page())
		{
			$get_home_page = '/' . $name . '/' . $module_configuration->get_home_page();
			$selected = $get_home_page == $general_config->get_home_page() ? 'selected="selected"' : '';
			$start_page[] = $get_home_page;
			$select_page .= '<option value="' . $get_home_page . '" ' . $selected . '>' . $module_configuration->get_name() . '</option>';
			
			$i++;
		}
		
	}
	if ($i == 0)
	{
		$select_page = '<option value="" selected="selected">' . $LANG['no_module_starteable'] . '</option>';
	}
	
	$graphical_environment_config = GraphicalEnvironmentConfig::load();
	$visit_counter_enabled = $graphical_environment_config->is_visit_counter_enabled();
	
	$Template->assign_vars(array(		
		'THEME' => get_utheme(),
		'THEME_DEFAULT' => $CONFIG['theme'],
		'SITE_NAME' => $general_config->get_site_name(),
		'SITE_DESCRIPTION' => $general_config->get_site_description(),
		'SITE_KEYWORD' => $general_config->get_site_keywords(),		
		'SELECT_PAGE' => $select_page, 
		'START_PAGE' => $general_config->get_home_page(), 
		'NOTE_MAX' => isset($CONFIG['note_max']) ? $CONFIG['note_max'] : '10',
		'COMPTEUR_ENABLED' => $visit_counter_enabled ? 'checked="checked"' : '',
		'COMPTEUR_DISABLED' => !$visit_counter_enabled ? 'checked="checked"' : '',
		'BENCH_ENABLED' => ($CONFIG['bench'] == 1) ? 'checked="checked"' : '',
		'BENCH_DISABLED' => ($CONFIG['bench'] == 0) ? 'checked="checked"' : '',
		'THEME_AUTHOR_ENABLED' => $graphical_environment_config->get_display_theme_author() ? 'checked="checked"' : '',
		'THEME_AUTHOR_DISABLED' => !$graphical_environment_config->get_display_theme_author() ? 'checked="checked"' : '',
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
	$array_identifier = '';
	$langs_cache = LangsCache::load();
	foreach ($langs_cache->get_installed_langs() as $lang => $properties) 
	{
		if ($properties['enabled'] == 1)
    	{
			$info_lang = load_ini_file(PATH_TO_ROOT . '/lang/', $lang);
			$selected = ($lang == $CONFIG['lang']) ? ' selected="selected"' : '';
			
    		$Template->assign_block_vars('select_lang', array(
				'LANG' => '<option value="' . $lang . '" ' . $selected . '>' . $info_lang['name'] . '</option>'
    		));
			
			$array_identifier .= 'array_identifier[\'' . $lang . '\'] = \'' . $info_lang['identifier'] . '\';' . "\n";
			
			if ($lang == $CONFIG['lang'])
				$lang_identifier = $info_lang['identifier'];
    	}
	}	
	
	$Template->assign_vars(array(
		'JS_LANG_IDENTIFIER' => $array_identifier,
		'IMG_LANG_IDENTIFIER' => '../images/stats/countries/' . $lang_identifier. '.png'
	));
	
	// Thème par defaut.
	foreach (ThemesCache::load()->get_installed_themes() as $theme => $properties) 
	{
		if ($theme !== 'default' && $properties['enabled'] == 1)
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
