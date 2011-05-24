<?php
/*##################################################
 *                           admin_modules_add.php
 *                            -------------------
 *   begin                : January 31, 2007
 *   copyright            : (C) 2007 Régis Viarre, Loic Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
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

$install = !empty($_GET['install']) ? true : false;

if ($install) //Installation du module
{
	//Récupération de l'identifiant du module
	$module_id = '';
	foreach ($_POST as $key => $value)
	{
		if ($value == $LANG['install'])
		{
			$module_id = str_replace('module_', '', $key);
		}
	}

	$enable_module = AppContext::get_request()->get_bool($module_id . 'activ', false);
	switch (ModulesManager::install_module($module_id, $enable_module, ModulesManager::GENERATE_CACHE_AFTER_THE_OPERATION))
	{
		case CONFIG_CONFLICT:
			AppContext::get_response()->redirect('/admin/admin_modules_add.php?error=e_config_conflict#message_helper');
			break;
		case UNEXISTING_MODULE:
		case MODULE_ALREADY_INSTALLED:
			AppContext::get_response()->redirect('/admin/admin_modules_add.php?error=incomplete#message_helper');
			break;
		case PHP_VERSION_CONFLICT:
			AppContext::get_response()->redirect('/admin/admin_modules_add.php?error=e_php_version_conflict#message_helper');
			break;
		case MODULE_INSTALLED:
		default:
			AppContext::get_response()->redirect('/admin/admin_modules.php');
	}
}
elseif (!empty($_FILES['upload_module']['name'])) //Upload et décompression de l'archive Zip/Tar
{
	$ext_name = strrchr($_FILES['upload_module']['name'], '.');
	$module_id = str_replace($ext_name, '', $_FILES['upload_module']['name']);

	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = '../';
	if (!is_writable($dir))
	@chmod($dir, 0755);
	if (!is_writable($dir . $module_name))
	@chmod($dir . $module_name, 0755);

	@clearstatcache();
	$error = '';
	if (is_writable($dir)) //Dossier en écriture, upload possible
	{
		$ckeck_module = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MODULES . " WHERE name = '" . addslashes($module_name) . "'", __LINE__, __FILE__);
		if (empty($ckeck_module) && !is_dir(PATH_TO_ROOT .'/' . $module_id))
		{

			$Upload = new Upload($dir);
			$Upload->disableContentCheck();
			if ($Upload->file('upload_module', '`([a-z0-9()_-])+\.(gzip|zip)+$`i'))
			{
				$archive_path = PATH_TO_ROOT .'/' . $Upload->get_filename();
				//Place à la décompression.
				if ($Upload->get_extension() == 'gzip')
				{
					import('/kernel/lib/php/pcl/pcltar', LIB_IMPORT);
					if (!$zip_files = PclTarExtract($Upload->get_filename(), '../'))
					$error = $Upload->get_error();
				}
				elseif ($Upload->get_extension() == 'zip')
				{
					import('/kernel/lib/php/pcl/pclzip', LIB_IMPORT);
					$Zip = new PclZip($archive_path);
					if (!$zip_files = $Zip->extract(PCLZIP_OPT_PATH, '../', PCLZIP_OPT_SET_CHMOD, 0666))
					$error = $Upload->get_error();
				}
				else
				$error = 'e_upload_invalid_format';

				//Suppression de l'archive désormais inutile.
				if (!@unlink($archive_path))
				$error = 'unlink_disabled';
			}
			else
			$error = 'e_upload_error';
		}
		else
		$error = 'e_upload_already_exist';
	}
	else
	$error = 'e_upload_failed_unwritable';

	$error = !empty($error) ? '?error=' . $error : '';
	AppContext::get_response()->redirect(HOST . SCRIPT . $error);
}
else
{
	$template = new FileTemplate('admin/admin_modules_add.tpl');

	$template->put_all(array(
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'L_MODULES_MANAGEMENT' => $LANG['modules_management'],
		'L_ADD_MODULES' => $LANG['add_modules'],
		'L_UPDATE_MODULES' => $LANG['update_modules'],
		'L_UPLOAD_MODULE' => $LANG['upload_module'],
		'L_EXPLAIN_ARCHIVE_UPLOAD' => $LANG['explain_archive_upload'],
		'L_UPLOAD' => $LANG['upload'],
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
		'L_UPDATE' => $LANG['update'],
		'L_MODULES_AVAILABLE' => $LANG['modules_available'],
		'L_NO_MODULES_AVAILABLE' => $LANG['no_modules_available'],
		'L_INSTALL' => $LANG['install']
	));

	//Gestion erreur.
	$get_error = TextHelper::strprotect(AppContext::get_request()->get_getstring('error', ''));
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_php_code', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_unlink_disabled', 'e_config_conflict', 'e_php_version_conflict');
	if (in_array($get_error, $array_error))
	{
		$template->put('message_helper', MessageHelper::display($LANG[$get_error], E_USER_WARNING));
	}
	if ($get_error == 'incomplete')
	{
		$template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], E_USER_NOTICE));
	}

	//Modules disponibles
	$root = PATH_TO_ROOT . '/';
	$uninstalled_modules = array();
	if (is_dir($root)) //Si le dossier existe
	{
		$dir_array = array();
		$lang_folder_path = new Folder($root);
		foreach ($lang_folder_path->get_folders() as $odir)
		{
			$dir = $odir->get_name();
			if (!in_array($dir, ModulesManager::get_installed_modules_ids_list()) && $dir != 'lang')
			{
				try
				{
					$module = new Module($dir);
					$module_configuration = $module->get_configuration();
					$uninstalled_modules[$module_configuration->get_name()] = $module;
				}
				catch (Exception $ex)
				{
					continue;
				}
			}
		}
	}
	//	ksort($uninstalled_modules);

	if (empty($uninstalled_modules))
	{
		$template->put_all( array(
			'C_NO_MODULE' => true,
		));
	}
	else
	{
		$template->put_all( array(
			'C_MODULES_AVAILABLE' => true,
		));
		foreach ($uninstalled_modules as $name => $module)
		{
			$configuration = $module->get_configuration();

			$author_name = $configuration->get_author();
			$author_email = $configuration->get_author_email();
			$author_website = $configuration->get_author_website();

			$template->assign_block_vars('available', array(
				'ID' => $module->get_id(),
				'NAME' => ucfirst($configuration->get_name()),
				'ICON' => $module->get_id(),
				'VERSION' => $configuration->get_version(),
				'AUTHOR' => (!empty($author_email) ? '<a href="mailto:' . $author_email . '">' . $author_name . '</a>' : $author_name),
				'AUTHOR_WEBSITE' => (!empty($author_website) ? '<a href="' . $author_website . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="" /></a>' : ''),
				'DESC' => $configuration->get_description(),
				'COMPAT' => $configuration->get_compatibility(),
				'ACTIV_ENABLED' => (!empty($row) && $row['activ'] == 1 ? 'checked="checked"' : ''),
				'ACTIV_DISABLED' => (!empty($row) && $row['activ'] == 0 ? 'checked="checked"' : '')
			));
		}
	}

	$template->display();
}

require_once('../admin/admin_footer.php');

?>