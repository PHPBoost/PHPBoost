<?php
/*##################################################
 *                               admin_config.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

require_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$check_advanced = !empty($_GET['adv']) ? true : false;

//Variables serveur.
$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
if( !$server_path )
	$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
$server_path = trim(str_replace('/admin', '', dirname($server_path)));
$server_name = 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));

//Si c'est confirmé on execute
if( !empty($_POST['valid']) && empty($_POST['cache']) )
{
	//Gestion de la page de démarrage.
	if( !empty($_POST['start_page2'])  )
		$start_page = securit($_POST['start_page2']);
	elseif( !empty($_POST['start_page']) )
		$start_page = securit($_POST['start_page']);
		
	$config = array();	 
	$config['server_name'] = $CONFIG['server_name'];
	$config['server_path'] = $CONFIG['server_path'];	
	$config['site_name'] = !empty($_POST['site_name']) ? stripslashes(securit($_POST['site_name'])) : '';	
	$config['site_desc'] = !empty($_POST['site_desc']) ? stripslashes(securit($_POST['site_desc'])) : '';    
	$config['site_keyword'] = !empty($_POST['site_keyword']) ? stripslashes(securit($_POST['site_keyword'])) : '';	
	$config['start'] = $CONFIG['start'];
	$config['version'] = $CONFIG['version'];
	$config['lang'] = !empty($_POST['lang']) ? stripslashes(securit($_POST['lang'])) : ''; 
	$config['theme'] = !empty($_POST['theme']) ? stripslashes(securit($_POST['theme'])) : 'main'; //main par defaut. 
	$config['editor'] = !empty($_POST['editor']) ? stripslashes(securit($_POST['editor'])) : 'bbcode'; //bbcode par defaut. 
	$config['timezone'] = $CONFIG['timezone'];
	$config['start_page'] = !empty($start_page) ? stripslashes($start_page) : '/member/member.php';
	$config['maintain'] = $CONFIG['maintain'];
	$config['maintain_delay'] = $CONFIG['maintain_delay'];
	$config['maintain_display_admin'] = $CONFIG['maintain_display_admin'];
	$config['maintain_text'] = $CONFIG['maintain_text'];
	$config['rewrite'] = $CONFIG['rewrite'];
	$config['com_popup'] = $CONFIG['com_popup'];
	$config['compteur'] = isset($_POST['compteur']) ? numeric($_POST['compteur']) : 0;
	$config['bench'] = isset($_POST['bench']) ? numeric($_POST['bench']) : 0;
	$config['theme_author'] = isset($_POST['theme_author']) ? numeric($_POST['theme_author']) : 0;
	$config['ob_gzhandler'] = $CONFIG['ob_gzhandler'];
	$config['site_cookie'] = $CONFIG['site_cookie'];
	$config['site_session'] = $CONFIG['site_session'];				
	$config['site_session_invit'] = $CONFIG['site_session_invit'];	
	$config['mail'] = !empty($_POST['mail']) ? stripslashes(securit($_POST['mail'])) : '';  
	$config['activ_mail'] = isset($_POST['activ_mail']) ? numeric($_POST['activ_mail']) : '1'; //activé par defaut. 
	$config['sign'] = !empty($_POST['sign']) ? stripslashes(securit($_POST['sign'])) : '';   
	$config['anti_flood'] = isset($_POST['anti_flood']) ? numeric($_POST['anti_flood']) : 0;
	$config['delay_flood'] = !empty($_POST['delay_flood']) ? numeric($_POST['delay_flood']) : 0;
	$config['unlock_admin'] = $CONFIG['unlock_admin'];
	$config['pm_max'] = isset($_POST['pm_max']) ? numeric($_POST['pm_max']) : 25;

	if( !empty($config['theme']) && !empty($CONFIG['lang']) ) //Nom de serveur obligatoire
	{
		$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config)) . "' WHERE name = 'config'", __LINE__, __FILE__);
		$Cache->Generate_file('config');
		
		redirect(HOST . SCRIPT);
	}
	else
		redirect(HOST . DIR . '/admin/admin_config.php?error=incomplete#errorh');
}
elseif( !empty($check_advanced) && empty($_POST['advanced']) )
{
	$Template->Set_filenames(array(
		'admin_config2'=> 'admin/admin_config2.tpl'
	));	
	
	//Vérification serveur de l'activation du mod_rewrite.
	if( function_exists('apache_get_modules') )
	{	
		$get_rewrite = apache_get_modules();
		$check_rewrite = (!empty($get_rewrite[5])) ? '<span class="success">' . $LANG['yes'] . '</span>' : '<span class="failure">' . $LANG['no'] . '</span>';
	}
	else
		$check_rewrite = '<span class="unspecified">' . $LANG['undefined'] . '</span>';
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif( isset($_GET['mail']) )
		$Errorh->Error_handler($LANG['unlock_admin_confirm'], E_USER_NOTICE);
	
	//Gestion fuseau horaire par défaut.
	$select_timezone = '';
	for($i = -12; $i <= 14; $i++)
	{
		$selected = ($i == $CONFIG['timezone']) ? 'selected="selected"' : '';
		$name = (!empty($i) ? ($i > 0 ? ' + ' . $i : ' - ' . -$i) : '');
		$select_timezone .= '<option value="' . $i . '" ' . $selected . '> [GMT' . $name . ']</option>';
	}
	
	$Template->Assign_vars(array(
		'SERVER_NAME' => !empty($CONFIG['server_name']) ? $CONFIG['server_name'] : $server_name,
		'SERVER_PATH' => isset($CONFIG['server_path']) ? $CONFIG['server_path'] : $server_path,
		'SELECT_TIMEZONE' => $select_timezone,
		'CHECKED' => ($CONFIG['rewrite'] == '1') ? 'checked="checked"' : '',
		'UNCHECKED' => ($CONFIG['rewrite'] == '0') ? 'checked="checked"' : '',
		'CHECK_REWRITE' => $check_rewrite,
		'GZ_DISABLED' => ((!function_exists('ob_gzhandler') || !@extension_loaded('zlib')) ? 'disabled="disabled"' : ''),
		'GZHANDLER_ENABLED' => ($CONFIG['ob_gzhandler'] == 1 && (function_exists('ob_gzhandler') && @extension_loaded('zlib'))) ? 'checked="checked"' : '',
		'GZHANDLER_DISABLED' => ($CONFIG['ob_gzhandler'] == 0) ? 'checked="checked"' : '',
		'SITE_COOKIE' => !empty($CONFIG['site_cookie']) ? $CONFIG['site_cookie'] : 'session',
		'SITE_SESSION' => !empty($CONFIG['site_session']) ? $CONFIG['site_session'] : '3600',
		'SITE_SESSION_VISIT' => !empty($CONFIG['site_session_invit']) ? $CONFIG['site_session_invit'] : '300',		
		'L_SECONDS' => $LANG['unit_seconds'],
		'L_REQUIRE_SERV' => $LANG['require_serv'],
		'L_REQUIRE_NAME' => $LANG['require_name'],
		'L_REQUIRE_COOKIE_NAME' => $LANG['require_cookie_name'],
		'L_REQUIRE_SESSION_TIME' => $LANG['require_session_time'],
		'L_REQUIRE_SESSION_INVIT' => $LANG['require_session_invit'],
		'L_REQUIRE' => $LANG['require'],
		'L_SERV_NAME' => $LANG['serv_name'],
		'L_SERV_NAME_EXPLAIN' => $LANG['serv_name_explain'],
		'L_SERV_PATH' => $LANG['serv_path'],
		'L_SERV_PATH_EXPLAIN' => $LANG['serv_path_explain'],
		'L_CONFIG' => $LANG['configuration'],
		'L_CONFIG_MAIN' => $LANG['config_main'],
		'L_CONFIG_ADVANCED' => $LANG['config_advanced'],
		'L_REWRITE' => $LANG['rewrite'],
		'L_EXPLAIN_REWRITE' => $LANG['explain_rewrite'], 
		'L_REWRITE_SERVER' => $LANG['server_rewrite'],
		'L_TIMEZONE_CHOOSE' => $LANG['timezone_choose'],
		'L_TIMEZONE_CHOOSE_EXPLAIN' => $LANG['timezone_choose_explain'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIVE' => $LANG['unactiv'],
		'L_USER_CONNEXION' => $LANG['user_connexion'],
		'L_COOKIE_NAME' => $LANG['cookie_name'],
		'L_SESSION_TIME' => $LANG['session_time'],
		'L_SESSION_TIME_EXPLAIN' => $LANG['session_time_explain'],
		'L_SESSION_INVIT' => $LANG['session invit'],
		'L_SESSION_INVIT_EXPLAIN' => $LANG['session invit_explain'],
		'L_MISC' => $LANG['miscellaneous'],	
		'L_ACTIV_GZHANDLER' => $LANG['activ_gzhandler'],
		'L_ACTIV_GZHANDLER_EXPLAIN' => $LANG['activ_gzhandler_explain'],
		'L_CONFIRM_UNLOCK_ADMIN' => $LANG['confirm_unlock_admin'],
		'L_UNLOCK_ADMIN' => $LANG['unlock_admin'],
		'L_UNLOCK_ADMIN_EXPLAIN' => $LANG['unlock_admin_explain'],
		'L_UNLOCK_LINK' => $LANG['send_unlock_admin'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']	
	));
	
	$Template->Pparse('admin_config2');
}
elseif( !empty($_POST['advanced']) )
{
	$CONFIG['rewrite'] = 1;
	$CONFIG['server_name'] = !empty($_POST['server_name']) ? stripslashes(securit($_POST['server_name'])) : stripslashes(securit($server_name)); 
	$CONFIG['server_path'] = !empty($_POST['server_path']) ? stripslashes(securit($_POST['server_path'])) : '';  
	$CONFIG['timezone'] = !empty($_POST['timezone']) ? numeric($_POST['timezone']) : 0;  
	$CONFIG['ob_gzhandler'] = (!empty($_POST['ob_gzhandler'])&& function_exists('ob_gzhandler') && @extension_loaded('zlib')) ? 1 : 0;
	$CONFIG['site_cookie'] = !empty($_POST['site_cookie']) ? stripslashes(securit($_POST['site_cookie'])) : 'session'; //Session par defaut.
	$CONFIG['site_session'] = !empty($_POST['site_session']) ? numeric($_POST['site_session']) : 3600; //Valeur par defaut à 3600.					
	$CONFIG['site_session_invit'] = !empty($_POST['site_session_invit']) ? numeric($_POST['site_session_invit']) : 300; //Durée compteur 5min par defaut.	
	
	if( !empty($CONFIG['server_name']) && !empty($CONFIG['site_cookie']) && !empty($CONFIG['site_session']) && !empty($CONFIG['site_session_invit'])  ) //Nom de serveur obligatoire
	{
		list($host, $dir) = array($CONFIG['server_name'], $CONFIG['server_path']); //Réassignation pour la redirection.
		if( empty($_POST['rewrite_engine']) || strpos($_SERVER['SERVER_NAME'], 'free.fr') ) //Désctivation de l'url rewriting.
			$CONFIG['rewrite'] = 0;
			
		$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
		###### Régénération du cache $CONFIG #######
		$Cache->Generate_file('config');
		
		//Régénération du htaccess.
		$Cache->Generate_htaccess();
			
		redirect($host . $dir . '/admin/admin_config.php?adv=1');
	}
	else
		redirect(HOST . DIR . '/admin/admin_config.php?adv=1&error=incomplete#errorh');
}
else //Sinon on rempli le formulaire	 
{		
	$Template->Set_filenames(array(
		'admin_config'=> 'admin/admin_config.tpl'
	));
	
	$theme_tmp = $CONFIG['theme'];
	//On recupère toute les informations supplementaires.
	$Cache->Load_file('config', RELOAD_CACHE);

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$select_page = '';
	$start_page = '';
	//Pages de démarrage
	$i = 0;
	$root = '../';
	if( is_dir($root) ) //Si le dossier existe
	{
		$dh = @opendir($root);
		while( !is_bool($dir = readdir($dh)) )
		{	
			//Si c'est un repertoire, on affiche.
			if( !preg_match('`\.`', $dir) )
			{
				//Désormais on vérifie que le fichier de configuration est présent.
				if( is_file($root . $dir . '/lang/' . $CONFIG['lang'] . '/config.ini') )
				{
					$config = load_ini_file($root . $dir . '/lang/', $CONFIG['lang']);
					if( !empty($config['starteable_page']) ) //Module possible comme page de démarrage.
					{	
						$selected = '';
						if( '/' . $dir . '/' . $config['starteable_page'] == $CONFIG['start_page'] )
						{	
							$selected = 'selected="selected"';
							$start_page = $CONFIG['start_page'];
						}						
						$select_page .= '<option value="' . '/' . $dir . '/' . $config['starteable_page'] . '" ' . $selected . '>' . $config['name'] . '</option>';
						$i++;					
					}
				}
			}
		}	
		closedir($dh); //On ferme le dossier
	}
	if( $i == 0 )
		$select_page = '<option value="" selected="selected">' . $LANG['no_module_starteable'] . '</option>';

	$Template->Assign_vars(array(		
		'THEME' => $CONFIG['theme'],
		'SITE_NAME' => !empty($CONFIG['site_name']) ? $CONFIG['site_name'] : '',
		'SITE_DESCRIPTION' => !empty($CONFIG['site_desc']) ? $CONFIG['site_desc'] : '',
		'SITE_KEYWORD' => !empty($CONFIG['site_keyword']) ? $CONFIG['site_keyword'] : '',		
		'SELECT_PAGE' => empty($start_page) ? '<option value="" selected="selected">--</option>' . $select_page : $select_page, 
		'START_PAGE' => empty($start_page) ? $CONFIG['start_page'] : '', 
		'MAIL' => !empty($CONFIG['mail']) ? $CONFIG['mail'] : '',   
		'SIGN' => !empty($CONFIG['sign']) ? $CONFIG['sign'] : '',
		'DELAY_FLOOD' => !empty($CONFIG['delay_flood']) ? $CONFIG['delay_flood'] : '7',
		'NOTE_MAX' => isset($CONFIG['note_max']) ? $CONFIG['note_max'] : '10',
		'PM_MAX' => isset($CONFIG['pm_max']) ? $CONFIG['pm_max'] : '50',
		'COMPTEUR_ENABLED' => ($CONFIG['compteur'] == 1) ? 'checked="checked"' : '',
		'COMPTEUR_DISABLED' => ($CONFIG['compteur'] == 0) ? 'checked="checked"' : '',
		'BENCH_ENABLED' => ($CONFIG['bench'] == 1) ? 'checked="checked"' : '',
		'BENCH_DISABLED' => ($CONFIG['bench'] == 0) ? 'checked="checked"' : '',
		'THEME_AUTHOR_ENABLED' => ($CONFIG['theme_author'] == 1) ? 'checked="checked"' : '',
		'THEME_AUTHOR_DISABLED' => ($CONFIG['theme_author'] == 0) ? 'checked="checked"' : '',
		'FLOOD_ENABLED' => ($CONFIG['anti_flood'] == 1) ? 'checked="checked"' : '',
		'FLOOD_DISABLED' => ($CONFIG['anti_flood'] == 0) ? 'checked="checked"' : '',
		'MAIL_ENABLED' => ($CONFIG['activ_mail'] == 1) ? 'checked="checked"' : '','MAIL_ENABLED' => ($CONFIG['activ_mail'] == 1) ? 'checked="checked"' : '',
		'MAIL_DISABLED' => ($CONFIG['activ_mail'] == 0) ? 'checked="checked"' : '',
		'L_REQUIRE_VALID_MAIL' => $LANG['require_mail'],
		'L_REQUIRE' => $LANG['require'],
		'L_CONFIG' => $LANG['configuration'],
		'L_CONFIG_MAIN' => $LANG['config_main'],
		'L_CONFIG_ADVANCED' => $LANG['config_advanced'],
		'L_SITE_NAME' => $LANG['site_name'],
		'L_SITE_DESC' => $LANG['site_desc'],
		'L_SITE_KEYWORDS' => $LANG['site_keyword'],
		'L_DEFAULT_LANGUAGES' => $LANG['default_language'],
		'L_DEFAULT_THEME' => $LANG['default_theme'],
		'L_DEFAULT_EDITOR' => $LANG['default_editor'],
		'L_START_PAGE' => $LANG['start_page'],
		'L_OTHER' => $LANG['other_start_page'],
		'L_COMPT' => $LANG['compt'],
		'L_BENCH' => $LANG['bench'],
		'L_BENCH_EXPLAIN' => $LANG['bench_explain'],
		'L_THEME_AUTHOR' => $LANG['theme_author'],
		'L_THEME_AUTHOR_EXPLAIN' => $LANG['theme_author_explain'],
		'L_REWRITE' => $LANG['rewrite'],
		'L_POST_MANAGEMENT' => $LANG['post_management'],
		'L_PM_MAX' => $LANG['pm_max'],
		'L_SECONDS' => $LANG['unit_seconds'],
		'L_ANTI_FLOOD' => $LANG['anti_flood'],
		'L_INT_FLOOD' => $LANG['int_flood'],
		'L_PM_MAX_EXPLAIN' => $LANG['pm_max_explain'],
		'L_ANTI_FLOOD_EXPLAIN' => $LANG['anti_flood_explain'],
		'L_INT_FLOOD_EXPLAIN' => $LANG['int_flood_explain'],
		'L_EMAIL_MANAGEMENT' => $LANG['email_management'],
		'L_EMAIL_ADMIN' => $LANG['email_admin'],
		'L_EMAIL_ADMIN_STATUS' => $LANG['admin_admin_status'],
		'L_EMAIL_ADMIN_SIGN' => $LANG['admin_sign'],			
		'L_EMAIL_ADMIN_EXPLAIN' => $LANG['email_admin_explain'],
		'L_EMAIL_ADMIN_STATUS_EXPLAIN' => $LANG['admin_admin_status_explain'],
		'L_EMAIL_ADMIN_SIGN_EXPLAIN' => $LANG['admin_sign_explain'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIVE' => $LANG['unactiv'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']		
	));

	//Gestion langue par défaut.
	$rep = '../lang/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$lang_array = array();
		$dh = @opendir( $rep);
		while( ! is_bool($lang = readdir($dh)) )
		{	
			if( !preg_match('`\.`', $lang) )
				$lang_array[] = $lang; //On crée un tableau, avec les different fichiers.				
		}	
		closedir($dh); //On ferme le dossier
		
		$lang_array_bdd = array();
		$result = $Sql->Query_while("SELECT lang 
		FROM ".PREFIX."lang", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			//On recherche les clées correspondante à celles trouvée dans la bdd.
			if( array_search($row['lang'], $lang_array) !== false)
				$lang_array_bdd[] = $row['lang']; //On insère ces clées dans le tableau.
		}
		$Sql->Close($result);
		
		$array_identifier = '';
		$lang_identifier = '../images/stats/other.png';
		foreach($lang_array_bdd as $lang_key => $lang_value) //On effectue la recherche dans le tableau.
		{
			$lang_info = load_ini_file('../lang/', $lang_value);
			if( $lang_info )
			{
				$lang_name = !empty($lang_info['name']) ? $lang_info['name'] : $lang_value;
				
				$array_identifier .= 'array_identifier[\'' . $lang_value . '\'] = \'' . $lang_info['identifier'] . '\';' . "\n";
				$selected = '';
				if( $lang_value == $CONFIG['lang'] )
				{
					$selected = 'selected="selected"';
					$lang_identifier = '../images/stats/countries/' . $lang_info['identifier'] . '.png';
				}
				$Template->Assign_block_vars('select_lang', array(
					'LANG' => '<option value="' . $lang_value . '" ' . $selected . '>' . $lang_name . '</option>'
				));
			}
		}
		$Template->Assign_vars(array(
			'JS_LANG_IDENTIFIER' => $array_identifier,
			'IMG_LANG_IDENTIFIER' => $lang_identifier
		));
	}
	
	//On recupère les dossier des thèmes contents dans le dossier templates.
	$rep = '../templates/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$fichier_array = array();
		$dh = @opendir( $rep);
		while( !is_bool($theme = readdir($dh)) )
		{	
			//Si c'est un repertoire, on affiche.
			if( !preg_match('`\.`', $theme) )
				$fichier_array[] = $theme; //On crée un array, avec les different dossiers.
		}	
		closedir($dh); //On ferme le dossier
		
		$theme_array_bdd = array();
		$result = $Sql->Query_while("SELECT theme 
		FROM ".PREFIX."themes", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			//On recherche les clées correspondante à celles trouvée dans la bdd.
			if( array_search($row['theme'], $fichier_array) !== false)
				$theme_array_bdd[] = $row['theme']; //On insère ces clées dans le tableau.
		}
		$Sql->Close($result);
		
		foreach($theme_array_bdd as $theme_array => $theme_value) //On effectue la recherche dans le tableau.
		{
			$theme_info = load_ini_file('../templates/' . $theme_value . '/config/', $CONFIG['lang']);
			if( $theme_info )
			{
				$theme_name = !empty($theme_info['name']) ? $theme_info['name'] : $theme_value;
				$selected = $theme_value == $CONFIG['theme'] ? 'selected="selected"' : '';
				$Template->Assign_block_vars('select', array(
					'THEME' => '<option value="' . $theme_value . '" ' . $selected . '>' . $theme_name . '</option>'
				));
			}
		}
	}
	
	//Gestion éditeur par défaut.
	$editors = array('bbcode' => 'BBCode', 'tinymce' => 'Tinymce');
	$select_editors = '';
	foreach($editors as $code => $name)
	{
		$selected = ($code == $CONFIG['editor']) ? 'selected="selected"' : '';
		$select_editors .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
	}
	$Template->Assign_block_vars('select_editor', array(
		'EDITOR' => $select_editors
	));
	
	$CONFIG['theme'] = $theme_tmp;
	
	$Template->Pparse('admin_config');
}

//Renvoi du code de déblocage.
if( !empty($_GET['unlock']) )
{
	include_once('../includes/mail.class.php');
	$Mail = new Mail();
	
	$unlock_admin_clean = substr(md5(uniqid(mt_rand(), true)), 0, 18); //Génération de la clée d'activation, en cas de verrouillage de l'administration.;
	$unlock_admin = md5($unlock_admin_clean);
	
	$CONFIG['unlock_admin'] = $unlock_admin;
	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	
	###### Régénération du cache $CONFIG #######
	$Cache->Generate_file('config');
	
	$Mail->Send_mail($Member->Get_attribute('user_mail'), $LANG['unlock_title_mail'], sprintf($LANG['unlock_mail'], $unlock_admin_clean), $CONFIG['mail']);	

	redirect(HOST . DIR . '/admin/admin_config.php?adv=1&mail=1');
}

require_once('../includes/admin_footer.php');

?>