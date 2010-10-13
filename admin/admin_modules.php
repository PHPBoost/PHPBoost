<?php
/*##################################################
 *                       admin_modules_management.php
 *                            -------------------
 *   begin                : January 31, 2007
 *   copyright            : (C) 2007 Viarre Régis
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$uninstall = retrieve(GET, 'uninstall', false);
$id = retrieve(GET, 'id', 0);
$error = retrieve(GET, 'error', '');

//Modification des propriétés des modules (activés et autorisations globales d'accès)
if (isset($_POST['valid']))
{
	foreach (ModulesManager::get_installed_modules_map() as $module)
	{
		$request = AppContext::get_request();
		$module_id = $module->get_id();
		$activated = $request->get_bool('activ' . $module_id, false);
		$authorizations = Authorizations::auth_array_simple(ACCESS_MODULE, $module_id);
		ModulesManager::update_module_authorizations($module_id, $activated, $authorizations);
	}
	MenuService::generate_cache();
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
elseif ($uninstall) //Désinstallation du module
{
	if (!empty($_POST['valid_del']))
	{
		$idmodule = AppContext::get_request()->get_string('idmodule', '');
		$drop_files = retrieve(POST, 'drop_files', false);

		switch (ModulesManager::uninstall_module($idmodule, $drop_files))
		{
			case NOT_INSTALLED_MODULE:
				die('module not installed');
				AppContext::get_response()->redirect('/admin/admin_modules.php?error=incomplete#errorh');
				break;
			case MODULE_FILES_COULD_NOT_BE_DROPPED:
				AppContext::get_response()->redirect('/admin/admin_modules.php?error=files_del_failed#errorh');
				break;
			case MODULE_UNINSTALLED:
			default:
				AppContext::get_response()->redirect(HOST . SCRIPT . $error);
		}
	}
	else
	{
		//Récupération de l'identifiant du module
		$idmodule = '';
		foreach ($_POST as $key => $value)
		{
			if ($value == $LANG['uninstall'])
			{
				$idmodule = $key;
			}
		}

		$Template->set_filenames(array(
			'admin_modules_management'=> 'admin/admin_modules_management.tpl'
		));

		$Template->put_all(array(
			'C_MODULES_DEL' => true,
			'THEME' => get_utheme(),
			'LANG' => get_ulang(),
			'IDMODULE' => $idmodule,
			'L_MODULES_MANAGEMENT' => $LANG['modules_management'],
			'L_ADD_MODULES' => $LANG['add_modules'],
			'L_UPDATE_MODULES' => $LANG['update_modules'],
			'L_DEL_MODULE' => $LANG['del_module'],
			'L_DEL_DATA' => $LANG['del_module_data'],
			'L_DEL_FILE' => $LANG['del_module_files'],
			'L_NAME' => $LANG['name'],
			'L_YES' => $LANG['yes'],
			'L_NO' => $LANG['no'],
			'L_SUBMIT' => $LANG['submit']
		));

		$Template->pparse('admin_modules_management');
	}
}
else
{
	$Template->set_filenames(array(
		'admin_modules_management'=> 'admin/admin_modules_management.tpl'
	));

	$Template->put_all(array(
		'C_MODULES_LIST' => true,
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'L_MODULES_MANAGEMENT' => $LANG['modules_management'],
		'L_ADD_MODULES' => $LANG['add_modules'],
		'L_UPDATE_MODULES' => $LANG['update_modules'],
		'L_MODULES_INSTALLED' => $LANG['modules_installed'],
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_ACTIV' => $LANG['activ'],
		'L_AUTHOR' => $LANG['author'],
		'L_COMPAT' => $LANG['compat'],
		'L_USE_SQL' => $LANG['use_sql'],
		'L_ADMIN' => $LANG['administration'],
		'L_USE_CACHE' => $LANG['use_cache'],
		'L_ALTERNATIVE_CSS' => $LANG['alternative_css'],
		'L_STARTEABLE_PAGE' => $LANG['starteable_page'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_AUTH_ACCESS' => $LANG['auth_access'],
		'L_UPDATE' => $LANG['update'],
		'L_MODULES_AVAILABLE' => $LANG['modules_available'],
		'L_NO_MODULES_INSTALLED' => $LANG['no_modules_installed'],
		'L_UNINSTALL' => $LANG['uninstall'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));

	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
	{
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	}
	elseif (!empty($get_error) && isset($LANG[$get_error]))
	{
		$Errorh->handler($LANG[$get_error], E_USER_WARNING);
	}

	// Installed modules
	$i = 0;
	$array_modules = array();
	$array_info_module = array();
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	foreach (ModulesManager::get_installed_modules_map_sorted_by_localized_name() as $module)
	{
		$configuration = $module->get_configuration();
		$array_auth = $module->get_authorizations();

		$Template->assign_block_vars('installed', array(
			'ID' => $module->get_id(),
			'NAME' => ucfirst($configuration->get_name()),
			'ICON' => $module->get_id(),
			'VERSION' => $configuration->get_version(),
			'AUTHOR' => ($configuration->get_author_email() ? '<a href="mailto:' . $configuration->get_author_email() . '">' . $configuration->get_author() . '</a>' : $configuration->get_author()),
			'AUTHOR_WEBSITE' => ($configuration->get_author_website() ? '<a href="' . $configuration->get_author_website() . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="" /></a>' : ''),
			'DESC' => $configuration->get_description(),
			'COMPAT' => $configuration->get_compatibility(),
			'ADMIN' => ($configuration->get_admin_main_page() ? $LANG['yes'] : $LANG['no']),
			'HOME_PAGE' => ($configuration->get_home_page() ? $LANG['yes'] : $LANG['no']),
			'ACTIV_ENABLED' => ($module->is_activated() ? 'checked="checked"' : ''),
			'ACTIV_DISABLED' => (!$module->is_activated() ? 'checked="checked"' : ''),
			'AUTH_MODULES' => Authorizations::generate_select(ACCESS_MODULE, $array_auth, array(2 => true), $module->get_id()),
		));
		$i++;
	}

	if ($i == 0)
	{
		$Template->put_all(array(
			'C_NO_MODULE_INSTALLED' => true
		));
	}
	else
	{
		$Template->put_all(array(
			'C_MODULES_INSTALLED' => true
		));
	}

	$Template->pparse('admin_modules_management');
}

require_once('../admin/admin_footer.php');

?>