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

$uninstall = retrieve(GET, 'uninstall', false);
$id = retrieve(GET, 'id', 0);
$error = retrieve(GET, 'error', '');

//Modification des propriétés des modules (activés et autorisations globales d'accès)
if (isset($_POST['valid']))
{
	//Listage des modules
	$result = $Sql->query_while("SELECT id, name, auth, activ
	FROM " . PREFIX . "modules", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//Récupération des propriétés du module courant
		$activ = retrieve(POST, 'activ' . $row['id'], 0);
		$array_auth_all = Authorizations::auth_array_simple(ACCESS_MODULE, $row['id']);
		
		//Enregistrement en base de données
		$Sql->query_inject("UPDATE " . DB_TABLE_MODULES . " SET activ = '" . $activ . "', auth = '" . addslashes(serialize($array_auth_all)) . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	//Génération du cache des modules
	$Cache->Generate_file('modules');
	$Cache->Load('modules', RELOAD_CACHE);
	
	MenuService::generate_cache();
	
	redirect(HOST . SCRIPT);
}
elseif ($uninstall) //Désinstallation du module
{
	if (!empty($_POST['valid_del']))
	{
		$idmodule = retrieve(POST, 'idmodule', 0);
		$drop_files = retrieve(POST, 'drop_files', false);
		
		
		
		switch (ModulesManager::uninstall_module($idmodule, $drop_files))
		{
			case NOT_INSTALLED_MODULE:
				redirect('/admin/admin_modules.php?error=incomplete#errorh');
				break;
			case MODULE_FILES_COULD_NOT_BE_DROPPED:
				redirect('/admin/admin_modules.php?error=files_del_failed#errorh');
				break;
			case MODULE_UNINSTALLED:
			default:
				redirect(HOST . SCRIPT . $error);
		}
	}
	else
	{
		//Récupération de l'identifiant du module
		$idmodule = '';
		foreach ($_POST as $key => $value)
			if ($value == $LANG['uninstall'])
				$idmodule = $key;
				
		$Template->set_filenames(array(
			'admin_modules_management'=> 'admin/admin_modules_management.tpl'
		));
		
		$Template->assign_vars(array(
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
	
	$Template->assign_vars(array(
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
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif (!empty($get_error) && isset($LANG[$get_error]))
		$Errorh->handler($LANG[$get_error], E_USER_WARNING);
		
	//Modules installé
	$i = 0;
	$array_modules = array();
	$array_info_module = array();
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$result = $Sql->query_while("SELECT id, name, auth, activ
	FROM " . PREFIX . "modules
	ORDER BY name", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//Récupération des infos de config.
		$array_info_module[$row['name']] = load_ini_file('../' . $row['name'] . '/lang/', get_ulang());
		$array_modules[$array_info_module[$row['name']]['name']] = array('id' => $row['id'], 'name' => $row['name'], 'auth' => $row['auth'], 'activ' => $row['activ']);
	}
	$Sql->query_close($result);
	
	ksort($array_modules);
	foreach ($array_modules as $name => $array_config)
	{
		$row = $array_modules[$name];
		$info_module = $array_info_module[$array_config['name']];
		
		//Récupération des tableaux des autorisations et des groupes.
		$array_auth = !empty($row['auth']) ? unserialize($row['auth']) : array();
		
		$l_tables = ($info_module['sql_table'] > 1) ? $LANG['tables'] : $LANG['table'];
		$Template->assign_block_vars('installed', array(
			'ID' => $row['id'],
			'NAME' => ucfirst($info_module['name']),
			'ICON' => $row['name'],
			'VERSION' => $info_module['version'],
			'AUTHOR' => (!empty($info_module['author_mail']) ? '<a href="mailto:' . $info_module['author_mail'] . '">' . $info_module['author'] . '</a>' : $info_module['author']),
			'AUTHOR_WEBSITE' => (!empty($info_module['author_link']) ? '<a href="' . $info_module['author_link'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="" /></a>' : ''),
			'DESC' => $info_module['info'],
			'COMPAT' => $info_module['compatibility'],
			'ADMIN' => ($info_module['admin'] ? $LANG['yes'] : $LANG['no']),
			'USE_SQL' => (($info_module['sql_table'] > 0) ? $LANG['yes'] : $LANG['no']),
			'SQL_TABLE' => (($info_module['sql_table'] > 0) ? '(' . $info_module['sql_table'] . ' ' . $l_tables . ')' : ''),
			'USE_CACHE' => ($info_module['cache'] ? $LANG['yes'] : $LANG['no']),
			'ALTERNATIVE_CSS' => ($info_module['css'] ? $LANG['yes'] : $LANG['no']),
			'STARTEABLE_PAGE' => ($info_module['starteable_page'] ? $LANG['yes'] : $LANG['no']),
			'ACTIV_ENABLED' => ($row['activ'] == 1 ? 'checked="checked"' : ''),
			'ACTIV_DISABLED' => ($row['activ'] == 0 ? 'checked="checked"' : ''),
			'AUTH_MODULES' => Authorizations::generate_select(ACCESS_MODULE, $array_auth, array(2 => true), $row['id']),
		));
		$i++;
	}

	if ($i == 0)
		$Template->assign_vars(array(
			'C_NO_MODULE_INSTALLED' => true
		));
	else
		$Template->assign_vars(array(
			'C_MODULES_INSTALLED' => true
		));
	
	$Template->pparse('admin_modules_management');
}

require_once('../admin/admin_footer.php');

?>