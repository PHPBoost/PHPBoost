<?php
/*##################################################
 *                               admin_themes.php
 *                            -------------------
 *   begin                : June 29, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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

require_once '../admin/admin_begin.php';
define('TITLE', $LANG['administration']);
require_once '../admin/admin_header.php';
	
$uninstall = retrieve(GET, 'uninstall', false, TBOOL);
$edit = retrieve(GET, 'edit', false, TBOOL);
$id = retrieve(GET, 'id', TSTRING);
$name = retrieve(GET, 'name', '');

$template = new FileTemplate('admin/admin_themes_management.tpl');

if (isset($_GET['activ']) && !empty($id)) //Aprobation du thème.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	ThemeManager::change_visibility($id, NumberHelper::numeric($_GET['activ']));
	
	AppContext::get_response()->redirect(HOST . SCRIPT . '#t' . $id);	
}
elseif (isset($_POST['valid'])) //Modification de tous les thèmes.	
{	

	foreach (ThemeManager::get_installed_themes_map() as $id => $theme)
	{
		$activ = retrieve(POST, $id . 'activ', 0);
		
		if ($theme->is_activated() !== $activ)
		{
			ThemeManager::change_informations($activ);
		}
	}
		
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);	
}
elseif ($edit && (!empty($id) || !empty($name))) //Edition
{
	if (!empty($name))
	{
        $id = (int) $Sql->query("SELECT id FROM " . DB_TABLE_THEMES . " WHERE theme='" . $name . "'", __LINE__, __FILE__);
	}
	if (isset($_POST['valid_edit'])) //Modication de la configuration du thème.
	{
		// TODO update columns
		
		$secure = $theme['theme'] == UserAccountsConfig::load()->get_default_theme() ? array('r-1' => 1, 'r0' => 1, 'r1' => 1) : Authorizations::build_auth_array_from_form(AUTH_THEME);
		ThemeManager::change_informations($id, true, $secure);
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '#t' . $id);	
	}
	else
	{		
		$theme = ThemeManager::get_theme($id);

		$template->put_all(array(
			'C_EDIT_THEME' => true,
			'IDTHEME' => $id,
			'THEME_NAME' => $theme->get_configuration()->get_name(),
			'LEFT_COLUMN_ENABLED' => !$theme->get_configuration()->get_columns_disabled()->left_columns_is_disabled() ? 'checked="checked"' : '',
			'RIGHT_COLUMN_ENABLED' => !$theme->get_configuration()->get_columns_disabled()->right_columns_is_disabled() ? 'checked="checked"' : '',
			'AUTH_THEME' => $id !== UserAccountsConfig::load()->get_default_theme() ? Authorizations::generate_select(AUTH_THEME, $theme->get_authorizations()) : $LANG['guest'],
			'L_THEME_ADD' => $LANG['theme_add'],	
			'L_THEME_MANAGEMENT' => $LANG['theme_management'],
			'L_THEME' => $LANG['theme'],
			'L_AUTH' => $LANG['auth_access'],
			'L_LEFT_COLUMN' => $LANG['activ_left_column'],
			'L_RIGHT_COLUMN' => $LANG['activ_right_column'],
			'L_RESET' => $LANG['reset'],
			'L_UPDATE' => $LANG['update']
		));
	}
}
elseif ($uninstall) //Désinstallation.
{
	if (!empty($_POST['valid_del']))
	{
		ThemeManager::uninstall(retrieve(POST, 'idtheme', TSTRING), retrieve(POST, 'drop_files', false, TBOOL));

		AppContext::get_response()->redirect(HOST . SCRIPT);
	}
	else
	{
		//Récupération de l'identifiant du thème.
		$idtheme = '';
		foreach ($_POST as $key => $value)
		{
			if ($value == $LANG['uninstall'])
			{
				$idtheme = $key;
			}
		}

		$template->put_all(array(
			'C_DEL_THEME' => true,
			'IDTHEME' => $idtheme,
			'THEME' => get_utheme(),
			'L_THEME_ADD' => $LANG['theme_add'],	
			'L_THEME_MANAGEMENT' => $LANG['theme_management'],
			'L_DEL_THEME' => $LANG['del_theme'],
			'L_DEL_FILE' => $LANG['del_theme_files'],
			'L_NAME' => $LANG['name'],
			'L_YES' => $LANG['yes'],
			'L_NO' => $LANG['no'],
			'L_DELETE' => $LANG['delete']
		));
	}
}		
else
{			
	$template->put_all(array(
		'C_THEME_MAIN' => true,
		'THEME' => get_utheme(),	
		'LANG' => get_ulang(),	
		'L_THEME_ADD' => $LANG['theme_add'],	
		'L_THEME_MANAGEMENT' => $LANG['theme_management'],
		'L_THEME_ON_SERV' => $LANG['theme_on_serv'],
		'L_THEME' => $LANG['theme'],
		'L_PREVIEW' => $LANG['preview'],
		'L_EXPLAIN_DEFAULT_THEME' => $LANG['explain_default_theme'],
		'L_NO_THEME_ON_SERV' => $LANG['no_theme_on_serv'],
		'L_AUTH' => $LANG['rank'],
		'L_AUTHOR' => $LANG['author'],
		'L_COMPAT' => $LANG['compat'],
		'L_DESC' => $LANG['description'],
		'L_ACTIV' => $LANG['activ'],
		'L_XHTML' => $LANG['xhtml_version'],
		'L_CSS' => $LANG['css_version'],
		'L_MAIN_COLOR' => $LANG['main_colors'],
		'L_VARIABLE_WIDTH' => $LANG['exensible'],
		'L_WIDTH' => $LANG['width'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_GUEST' => $LANG['guest'],
		'L_EDIT' => $LANG['edit'],
		'L_UNINSTALL' => $LANG['uninstall']		
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
	{
		$template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], E_USER_NOTICE));
	}
	elseif (!empty($get_error) && isset($LANG[$get_error]))
	{
		$template->put('message_helper', MessageHelper::display($LANG[$get_error], E_USER_WARNING));
	}
	
	$z = 0;
	//On listes les thèmes.
	foreach (ThemeManager::get_installed_themes_map() as $id => $value)
	{
		$default_theme = ($id == UserAccountsConfig::load()->get_default_theme());
		
		$configuration = $value->get_configuration();
		
		$author_mail = $configuration->get_author_mail();
		$author_link = $configuration->get_author_link();
		$template->assign_block_vars('list', array(
			'C_THEME_DEFAULT' => $default_theme ? true : false,
			'C_THEME_NOT_DEFAULT' => !$default_theme ? true : false,
			'IDTHEME' =>  $id,		
			'THEME' => $configuration->get_name(),				
			'ICON' => $id,
			'VERSION' => $configuration->get_version(),
			'AUTHOR' => (!empty($author_mail) ? '<a href="mailto:' . $author_mail . '">' . $configuration->get_author_mail() . '</a>' : $configuration->get_author_name()),
			'AUTHOR_WEBSITE' => (!empty($author_link) ? '<a href="' . $author_link . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="" /></a>' : ''),
			'DESC' => $configuration->get_description(),
			'COMPAT' => $configuration->get_compatibility(),
			'HTML_VERSION' => $configuration->get_html_version(),
			'CSS_VERSION' => $configuration->get_css_version(),
			'MAIN_COLOR' => $configuration->get_main_color(),
			'VARIABLE_WIDTH' => $configuration->get_variable_width() ? $LANG['yes'] : $LANG['no'],
			'WIDTH' => $configuration->get_width(),
			'THEME_ACTIV' => $value->is_activated() ? 'checked="checked"' : '',
			'THEME_UNACTIV' => !$value->is_activated() ? 'checked="checked"' : ''
		));
		$z++;
	}
	
	if ($z != 0)
	{
		$template->put_all(array(
			'C_THEME_PRESENT' => true
		));
	}
	else
	{
		$template->put_all(array(		
			'C_NO_THEME_PRESENT' => true
		));
	}
}
$template->display(); 

require_once '../admin/admin_footer.php';

?>
