<?php
/*##################################################
 *                           admin_modules_add.php
 *                            -------------------
 *   begin                : January 31, 2007
 *   copyright            : (C) 2007 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, horn@phpboost.com
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
###################################################*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$install = !empty($_GET['install']) ? true : false;

if ($install) //Installation du module
{	
	//Récupération de l'identifiant du module
	$module_name = '';
	foreach ($_POST as $key => $value)
		if ($value == $LANG['install'])
			$module_name = str_replace('module_', '', $key);

	$enable_module = retrieve(POST, $module_name . 'activ', false);
	
	import('modules/PackageManager');
	
	switch (PackagesManager::install_module($module_name, $enable_module, GENERATE_CACHE_AFTER_THE_OPERATION))
	{
		case CONFIG_CONFLICT:
			redirect('/admin/admin_modules_add.php?error=e_config_conflict#errorh');
			break;
		case UNEXISTING_MODULE:
		case MODULE_ALREADY_INSTALLED:
			redirect('/admin/admin_modules_add.php?error=incomplete#errorh');
			break;
		case PHP_VERSION_CONFLICT:
			redirect('/admin/admin_modules_add.php?error=e_php_version_conflict#errorh');
			break;
		case MODULE_INSTALLED:
		default:
			redirect('/admin/admin_modules.php');
	}
}			
elseif (!empty($_FILES['upload_module']['name'])) //Upload et décompression de l'archive Zip/Tar
{
	$ext_name = strrchr($_FILES['upload_module']['name'], '.');
	$module_name = str_replace($ext_name, '', $_FILES['upload_module']['name']);
	
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
		if (empty($ckeck_module) && !is_dir('../' . $module_name))
		{
			import('io/Upload');
			$Upload = new Upload($dir);
			if ($Upload->file('upload_module', '`([a-z0-9()_-])+\.(gzip|zip)+$`i'))
			{					
				$archive_path = '../' . $Upload->get_filename();
				//Place à la décompression.
				if ($Upload->get_extension() == 'gzip')
				{
					import('lib/pcl/pcltar', LIB_IMPORT);
					if (!$zip_files = PclTarExtract($Upload->get_filename(), '../'))
						$error = $Upload->get_error();
				}
				elseif ($Upload->get_extension() == 'zip')
				{
					import('lib/pcl/pclzip', LIB_IMPORT);
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
	redirect(HOST . SCRIPT . $error);	
}
else
{			
	$Template->set_filenames(array(
		'admin_modules_add'=> 'admin/admin_modules_add.tpl'
	));

	$Template->assign_vars(array(
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
	$get_error = retrieve(GET, 'error', '');
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_unlink_disabled', 'e_config_conflict', 'e_php_version_conflict');
	if (in_array($get_error, $array_error))
		$Errorh->handler($LANG[$get_error], E_USER_WARNING);
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
		
	//Modules installé
	$i = 0;
	$installed_modules = array();
	$result = $Sql->query_while("SELECT id, name
	FROM " . PREFIX . "modules", __LINE__, __FILE__);
	
	while ($row = $Sql->fetch_assoc($result))
		$installed_modules[] = $row['name'];
	
	$Sql->query_close($result);
	
	//Modules disponibles
	$root = PATH_TO_ROOT . '/';
	$uninstalled_modules = array();
	if (is_dir($root)) //Si le dossier existe
	{
		import('io/filesystem/Folder');
		$dir_array = array();
		$lang_folder_path = new Folder($root);
		foreach ($lang_folder_path->get_folders() as $odir)
		{	
			$dir = $odir->get_name();
			if (!in_array($dir, $installed_modules) && $dir != 'lang')
			{
				//Récupération des infos de config.
				$info_module = load_ini_file($root . $dir . '/lang/', get_ulang());
				if (!empty($info_module) && is_array($info_module))
				{
					$info_module['module_name'] = $dir;
					$uninstalled_modules[$info_module['name']] = $info_module;
				}
			}
		}	
	}
	
	//Tri du tableau
	ksort($uninstalled_modules);
	
	$i = 0;
	foreach ($uninstalled_modules as $name => $info_module)
	{
		$l_tables = ($info_module['sql_table'] > 1) ? $LANG['tables'] : $LANG['table'];
		$Template->assign_block_vars('available', array(
			'ID' => $info_module['module_name'],
			'NAME' => ucfirst($info_module['name']),
			'ICON' => $info_module['module_name'],
			'VERSION' => $info_module['version'],
			'AUTHOR' => (!empty($info_module['author_mail']) ? '<a href="mailto:' . $info_module['author_mail'] . '">' . $info_module['author'] . '</a>' : $info_module['author']),
			'AUTHOR_WEBSITE' => (!empty($info_module['author_link']) ? '<a href="' . $info_module['author_link'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="" /></a>' : ''),
			'DESC' => $info_module['info'],
			'COMPAT' => $info_module['compatibility'],
			'USE_SQL' => (($info_module['sql_table'] > 0) ? $LANG['yes'] : $LANG['no']),
			'SQL_TABLE' => (($info_module['sql_table'] > 0) ? '(' . $info_module['sql_table'] . ' ' . $l_tables . ')' : ''),
			'USE_CACHE' => ($info_module['cache'] ? $LANG['yes'] : $LANG['no']),
			'ALTERNATIVE_CSS' => ($info_module['css'] ? $LANG['yes'] : $LANG['no']),	
			'STARTEABLE_PAGE' => ($info_module['starteable_page'] ? $LANG['yes'] : $LANG['no']),
			'ACTIV_ENABLED' => ($row['activ'] == 1 ? 'checked="checked"' : ''),
			'ACTIV_DISABLED' => ($row['activ'] == 0 ? 'checked="checked"' : '')
		));
		$i++;
	}
	
	if ($i == 0)
		$Template->assign_vars( array(
			'C_NO_MODULE' => true,
		));
	else
		$Template->assign_vars( array(
			'C_MODULES_AVAILABLE' => true,
		));
	
	$Template->pparse('admin_modules_add'); 
}

require_once('../admin/admin_footer.php');

?>