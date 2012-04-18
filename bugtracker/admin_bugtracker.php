<?php
/*##################################################
 *                              admin_bugtracker_config.php
 *                            -------------------
 *   begin                : February 03, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('bugtracker'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
include_once('bugtracker_constants.php');

//Chargement du cache
$Cache->load('bugtracker');

$id = retrieve(GET, 'id', 0, TINTEGER);
$id_post = retrieve(POST, 'id', 0, TINTEGER);

if (!empty($_POST['valid_add_type']))
{
	$type = retrieve(POST, 'type', '');
	
	$config_bugs = $BUGS_CONFIG;
	
	if (!empty($type))
	{
		$config_bugs['types'][] = $type;

		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_bugs)) . "' WHERE name = 'bugtracker'", __LINE__, __FILE__);
		
		###### Régénération du cache #######
		$Cache->Generate_module_file('bugtracker');
		
		redirect(HOST . SCRIPT . '?error=success#errorh');
	}
	else
	redirect(HOST . SCRIPT . '?error=incomplete#errorh');
}
else if (!empty($_POST['valid_edit_type']) && is_numeric($id_post))
{
	$type = retrieve(POST, 'type', '');
	
	//On met à jour
	if (!empty($type))
	{
		//Modification du type en question dans la liste des bugs
		$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET type = '" . addslashes($type) . "' WHERE type = '" . addslashes($BUGS_CONFIG['types'][$id_post]) . "'", __LINE__, __FILE__);
		
		$BUGS_CONFIG['types'][$id_post] = $type;
		
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($BUGS_CONFIG)) . "' WHERE name = 'bugtracker'", __LINE__, __FILE__);
		
		###### Régénération du cache #######
		$Cache->Generate_module_file('bugtracker');

		redirect(HOST . SCRIPT . '?error=edit_type_success#errorh');
	}
	else
	redirect(HOST . SCRIPT . '?edit_type=true&id= ' . $id_post . '&error=incomplete#errorh');
}
else if (isset($_GET['edit_type']) && is_numeric($id)) // edition d'un type
{
	$Template->set_filenames(array(
		'admin_bugtracker'=> 'bugtracker/admin_bugtracker.tpl'
	));
	
	$Template->assign_block_vars('edit_type', array(
		'ID'	=> $id,
		'TYPE'	=> stripslashes($BUGS_CONFIG['types'][$id])
	));	
	
	$Template->assign_vars(array(
		'L_BUGS_MANAGEMENT'		=> $LANG['bugs.titles.admin.management'],
		'L_BUGS_CONFIG' 		=> $LANG['bugs.titles.admin.config'],
		'L_EDIT_TYPE'			=> $LANG['bugs.titles.edit_type'],
		'L_REQUIRE' 			=> $LANG['require'],
		'L_REQUIRE_TYPE' 		=> $LANG['bugs.notice.require_type'],
		'L_TYPE'				=> $LANG['bugs.labels.fields.type'],
		'L_UPDATE' 				=> $LANG['update'],
		'L_RESET' 				=> $LANG['reset'],
		'TOKEN'					=> $Session->get_token()
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$Template->pparse('admin_bugtracker');
}
else if (isset($_GET['delete_type']) && is_numeric($id)) //Suppression d'un type
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//Suppression du type en question dans la liste des bugs
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET type = '' WHERE type = '" . addslashes($BUGS_CONFIG['types'][$id]) . "'", __LINE__, __FILE__);
	
	//On supprime le type de la liste
	unset($BUGS_CONFIG['types'][$id]);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($BUGS_CONFIG)) . "' WHERE name = 'bugtracker'", __LINE__, __FILE__);
	
	//Mise à jour de la liste des bugs dans le cache de la configuration.
	$Cache->Generate_module_file('bugtracker');

	redirect(HOST . SCRIPT . '?error=success#errorh');
}
else if (!empty($_POST['valid_add_category']))
{
	$category = retrieve(POST, 'category', '');
	
	$config_bugs = $BUGS_CONFIG;
	
	if (!empty($category))
	{
		$config_bugs['categories'][] = $category;

		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_bugs)) . "' WHERE name = 'bugtracker'", __LINE__, __FILE__);
		
		###### Régénération du cache #######
		$Cache->Generate_module_file('bugtracker');
		
		redirect(HOST . SCRIPT . '?error=success#errorh');
	}
	else
	redirect(HOST . SCRIPT . '?error=incomplete#errorh');
}
else if (!empty($_POST['valid_edit_category']) && is_numeric($id_post))
{
	$category = retrieve(POST, 'category', '');
	
	//On met à jour
	if (!empty($category))
	{
		//Modification de la categorie en question dans la liste des bugs
		$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET category = '" . addslashes($category) . "' WHERE category = '" . addslashes($BUGS_CONFIG['categories'][$id_post]) . "'", __LINE__, __FILE__);
		
		$BUGS_CONFIG['categories'][$id_post] = $category;
		
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($BUGS_CONFIG)) . "' WHERE name = 'bugtracker'", __LINE__, __FILE__);
		
		###### Régénération du cache #######
		$Cache->Generate_module_file('bugtracker');

		redirect(HOST . SCRIPT . '?error=edit_category_success#errorh');
	}
	else
	redirect(HOST . SCRIPT . '?edit_category=true&id= ' . $id_post . '&error=incomplete#errorh');
}
else if (isset($_GET['edit_category']) && is_numeric($id)) // edition d'une catégorie
{
	$Template->set_filenames(array(
		'admin_bugtracker'=> 'bugtracker/admin_bugtracker.tpl'
	));
	
	$Template->assign_block_vars('edit_category', array(
		'ID'		=> $id,
		'CATEGORY'	=> stripslashes($BUGS_CONFIG['categories'][$id])
	));	
	
	$Template->assign_vars(array(
		'L_BUGS_MANAGEMENT'		=> $LANG['bugs.titles.admin.management'],
		'L_BUGS_CONFIG' 		=> $LANG['bugs.titles.admin.config'],
		'L_EDIT_CATEGORY'		=> $LANG['bugs.titles.edit_category'],
		'L_REQUIRE' 			=> $LANG['require'],
		'L_REQUIRE_CATEGORY' 	=> $LANG['bugs.notice.require_category'],
		'L_CATEGORY'			=> $LANG['bugs.labels.fields.category'],
		'L_UPDATE' 				=> $LANG['update'],
		'L_RESET' 				=> $LANG['reset'],
		'TOKEN'					=> $Session->get_token()
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$Template->pparse('admin_bugtracker');
}
else if (isset($_GET['delete_category']) && is_numeric($id)) //Suppression d'une catégorie
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//Suppression de la catégorie en question dans la liste des bugs
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET category = '' WHERE category = '" . addslashes($BUGS_CONFIG['categories'][$id]) . "'", __LINE__, __FILE__);
	
	//On supprime la catégorie de la liste
	unset($BUGS_CONFIG['categories'][$id]);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($BUGS_CONFIG)) . "' WHERE name = 'bugtracker'", __LINE__, __FILE__);
	
	//Mise à jour de la liste des bugs dans le cache de la configuration.
	$Cache->Generate_module_file('bugtracker');

	redirect(HOST . SCRIPT . '?error=success#errorh');
}
else if (!empty($_POST['valid_add_version']))
{
	$version = retrieve(POST, 'version', '');
	
	$config_bugs = $BUGS_CONFIG;
	
	if (!empty($version))
	{
		$config_bugs['versions'][] = $version;

		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_bugs)) . "' WHERE name = 'bugtracker'", __LINE__, __FILE__);
		
		###### Régénération du cache #######
		$Cache->Generate_module_file('bugtracker');
		
		redirect(HOST . SCRIPT . '?error=success#errorh');
	}
	else
	redirect(HOST . SCRIPT . '?error=incomplete#errorh');
}
else if (!empty($_POST['valid_edit_version']) && is_numeric($id_post))
{
	$version = retrieve(POST, 'version', '');
	
	//On met à jour
	if (!empty($version))
	{
		//Modification de la version en question dans la liste des bugs
		$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET detected_in = '" . addslashes($version) . "' WHERE detected_in = '" . addslashes($BUGS_CONFIG['versions'][$id_post]) . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET fixed_in = '" . addslashes($version) . "' WHERE fixed_in = '" . addslashes($BUGS_CONFIG['versions'][$id_post]) . "'", __LINE__, __FILE__);
		
		$BUGS_CONFIG['versions'][$id_post] = $version;
		
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($BUGS_CONFIG)) . "' WHERE name = 'bugtracker'", __LINE__, __FILE__);
		
		###### Régénération du cache #######
		$Cache->Generate_module_file('bugtracker');

		redirect(HOST . SCRIPT . '?error=edit_version_success#errorh');
	}
	else
	redirect(HOST . SCRIPT . '?edit_version=true&id= ' . $id_post . '&error=incomplete#errorh');
}
else if (isset($_GET['edit_version']) && is_numeric($id)) // edition d'une version
{
	$Template->set_filenames(array(
		'admin_bugtracker'=> 'bugtracker/admin_bugtracker.tpl'
	));
	
	$Template->assign_block_vars('edit_version', array(
		'ID'		=> $id,
		'VERSION'	=> stripslashes($BUGS_CONFIG['versions'][$id])
	));	
	
	$Template->assign_vars(array(
		'L_BUGS_MANAGEMENT'		=> $LANG['bugs.titles.admin.management'],
		'L_BUGS_CONFIG' 		=> $LANG['bugs.titles.admin.config'],
		'L_EDIT_VERSION'		=> $LANG['bugs.titles.edit_version'],
		'L_REQUIRE' 			=> $LANG['require'],
		'L_REQUIRE_VERSION' 	=> $LANG['bugs.notice.require_version'],
		'L_VERSION'				=> $LANG['bugs.labels.fields.version'],
		'L_UPDATE' 				=> $LANG['update'],
		'L_RESET' 				=> $LANG['reset'],
		'TOKEN'					=> $Session->get_token()
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$Template->pparse('admin_bugtracker');
}
else if (isset($_GET['delete_version']) && is_numeric($id)) //Suppression d'une version
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//Suppression de la version en question dans la liste des bugs
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET detected_in = '' WHERE detected_in = '" . addslashes($BUGS_CONFIG['versions'][$id]) . "'", __LINE__, __FILE__);
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET fixed_in = '' WHERE fixed_in = '" . addslashes($BUGS_CONFIG['versions'][$id]) . "'", __LINE__, __FILE__);
	
	//On supprime la version de la liste
	unset($BUGS_CONFIG['versions'][$id]);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($BUGS_CONFIG)) . "' WHERE name = 'bugtracker'", __LINE__, __FILE__);
	
	//Mise à jour de la liste des bugs dans le cache de la configuration.
	$Cache->Generate_module_file('bugtracker');

	redirect(HOST . SCRIPT . '?error=success#errorh');
}
else if (!empty($_POST['valid']))
{
	$config_bugs = array();
	
	$fields = array('items_per_page', 'severity_minor_color', 'severity_major_color', 'severity_critical_color', 'closed_bug_color', 'activ_com');
	
	foreach ($fields as $field)
	{
		$config_bugs[$field] = retrieve(POST, $field, '');
		if ($field == 'activ_com' && $config_bugs[$field] == 'on')
			$config_bugs[$field] = 1;
		else if ($field == 'activ_com' && $config_bugs[$field] == 'off')
			$config_bugs[$field] = 0;
	}
	
	$config_bugs['types'] = $BUGS_CONFIG['types'];
	$config_bugs['versions'] = $BUGS_CONFIG['versions'];
	$config_bugs['categories'] = $BUGS_CONFIG['categories'];
	$config_bugs['auth'] = Authorizations::build_auth_array_from_form(BUG_READ_AUTH_BIT, BUG_CREATE_AUTH_BIT, BUG_CREATE_ADVANCED_AUTH_BIT);
	
	if ($config_bugs == $BUGS_CONFIG)
		redirect(HOST . SCRIPT);
		
	if (!empty($config_bugs['items_per_page']))
	{
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_bugs)) . "' WHERE name = 'bugtracker'", __LINE__, __FILE__);
		
		###### Régénération du cache #######
		$Cache->Generate_module_file('bugtracker');
		
		redirect(HOST . SCRIPT . '?error=success#errorh');
	}
	else
	redirect(HOST . SCRIPT . '?error=require_items_per_page#errorh');
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_bugtracker'=> 'bugtracker/admin_bugtracker.tpl'
	));
	
	$Template->assign_block_vars('list', array());
	
	$Cache->load('bugtracker');
	
	$BUGS_CONFIG['auth'] = isset($BUGS_CONFIG['auth']) && is_array($BUGS_CONFIG['auth']) ? $BUGS_CONFIG['auth'] : array();
	
	$Template->assign_vars(array(
		'C_NO_VERSION' 					=> empty($BUGS_CONFIG['versions']) ? true : false,
		'C_NO_TYPE' 					=> empty($BUGS_CONFIG['types']) ? true : false,
		'C_NO_CATEGORY' 				=> empty($BUGS_CONFIG['categories']) ? true : false,
		'L_CONFIRM_DEL_VERSION' 		=> $LANG['bugs.actions.confirm.del_version'],
		'L_CONFIRM_DEL_TYPE' 			=> $LANG['bugs.actions.confirm.del_type'],
		'L_CONFIRM_DEL_CATEGORY' 		=> $LANG['bugs.actions.confirm.del_category'],
		'L_BUGS_MANAGEMENT'				=> $LANG['bugs.titles.admin.management'],
		'L_BUGS_CONFIG' 				=> $LANG['bugs.titles.admin.config'],
		'L_ITEMS_PER_PAGE'				=> $LANG['bugs.config.items_per_page'],
		'L_REQUIRE'						=> $LANG['require'],
		'L_REQUIRE_ITEMS_PER_PAGE'		=> $LANG['bugs.error.require_items_per_page'],
		'L_REQUIRE_TYPE'	 			=> $LANG['bugs.notice.require_type'],
		'L_REQUIRE_CATEGORY' 			=> $LANG['bugs.notice.require_category'],
		'L_REQUIRE_VERSION' 			=> $LANG['bugs.notice.require_version'],
		'L_SEVERITY_MINOR_COLOR' 		=> $LANG['bugs.config.severity_color_label'] . ' "' . $LANG['bugs.severity.minor'] . '"',
		'L_SEVERITY_MAJOR_COLOR' 		=> $LANG['bugs.config.severity_color_label'] . ' "' . $LANG['bugs.severity.major'] . '"',
		'L_SEVERITY_CRITICAL_COLOR' 	=> $LANG['bugs.config.severity_color_label'] . ' "' . $LANG['bugs.severity.critical'] . '"',
		'L_CLOSED_BUG_COLOR' 			=> $LANG['bugs.config.closed_bug_color_label'],
		'L_ACTIV_COM'					=> $LANG['bugs.config.activ_com'],
		'L_BUGS_DISPONIBLE_VERSIONS'	=> $LANG['bugs.titles.disponible_versions'],
		'L_BUGS_DISPONIBLE_TYPES'		=> $LANG['bugs.titles.disponible_types'],
		'L_BUGS_DISPONIBLE_CATEGORIES'	=> $LANG['bugs.titles.disponible_categories'],
		'L_BUGS_TYPE_EXPLAIN'			=> $LANG['bugs.explain.type'],
		'L_BUGS_CATEGORY_EXPLAIN'		=> $LANG['bugs.explain.category'],
		'L_BUGS_VERSION_EXPLAIN'		=> $LANG['bugs.explain.version'],
		'L_VERSION'						=> $LANG['bugs.labels.fields.version'],
		'L_TYPE'						=> $LANG['bugs.labels.fields.type'],
		'L_CATEGORY'					=> $LANG['bugs.labels.fields.category'],
		'L_ADD_VERSION'					=> $LANG['bugs.titles.add_version'],
		'L_ADD_TYPE'					=> $LANG['bugs.titles.add_type'],
		'L_ADD_CATEGORY'				=> $LANG['bugs.titles.add_category'],
		'L_NO_VERSION'					=> $LANG['bugs.notice.no_version'],
		'L_NO_TYPE'						=> $LANG['bugs.notice.no_type'],
		'L_NO_CATEGORY'					=> $LANG['bugs.notice.no_category'],
		'L_ACTIONS' 					=> $LANG['bugs.actions'],
		'L_AUTH'		 				=> $LANG['bugs.config.auth'],
		'L_READ_AUTH' 					=> $LANG['bugs.config.auth.read'],
		'L_CREATE_AUTH' 				=> $LANG['bugs.config.auth.create'],
		'L_CREATE_ADVANCED_AUTH'		=> $LANG['bugs.config.auth.create_advanced'],
		'L_CREATE_ADVANCED_AUTH_EXPLAIN'=> $LANG['bugs.config.auth.create_advanced_explain'],
		'L_ADD' 						=> $LANG['add'],
		'L_UPDATE' 						=> $LANG['update'],
		'L_DELETE' 						=> $LANG['delete'],
		'ITEMS_PER_PAGE'				=> $BUGS_CONFIG['items_per_page'],
		'SEVERITY_MINOR_COLOR'			=> $BUGS_CONFIG['severity_minor_color'],
		'SEVERITY_MAJOR_COLOR'			=> $BUGS_CONFIG['severity_major_color'],
		'SEVERITY_CRITICAL_COLOR'		=> $BUGS_CONFIG['severity_critical_color'],
		'CLOSED_BUG_COLOR'				=> $BUGS_CONFIG['closed_bug_color'],
		'COM_CHECKED'					=> ($BUGS_CONFIG['activ_com'] == true) ? 'checked=checked' : '',
		'BUG_READ_AUTH'					=> Authorizations::generate_select(BUG_READ_AUTH_BIT, $BUGS_CONFIG['auth']),
		'BUG_CREATE_AUTH'				=> Authorizations::generate_select(BUG_CREATE_AUTH_BIT, $BUGS_CONFIG['auth']),
		'BUG_CREATE_ADVANCED_AUTH'		=> Authorizations::generate_select(BUG_CREATE_ADVANCED_AUTH_BIT, $BUGS_CONFIG['auth'])
	));
	
	foreach ($BUGS_CONFIG['versions'] as $key => $version)
	{
		$Template->assign_block_vars('versions', array(
			'ID'		=> $key,
			'VERSION'	=> stripslashes($version)
		));	
	}
	
	foreach ($BUGS_CONFIG['types'] as $key => $type)
	{
		$Template->assign_block_vars('types', array(
			'ID'				=> $key,
			'TYPE'				=> stripslashes($type)
		));	
	}	
	
	foreach ($BUGS_CONFIG['categories'] as $key => $category)
	{
		$Template->assign_block_vars('categories', array(
			'ID'				=> $key,
			'CATEGORY'			=> stripslashes($category)
		));	
	}

	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'require_items_per_page')
		$Errorh->handler($LANG['bugs.error.require_items_per_page'], E_USER_NOTICE);
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	if ($get_error == 'success')
		$Errorh->handler($LANG['bugs.error.e_config_success'], E_USER_SUCCESS);
	if ($get_error == 'edit_type_success')
		$Errorh->handler($LANG['bugs.error.e_edit_type_success'], E_USER_SUCCESS);
	if ($get_error == 'edit_category_success')
		$Errorh->handler($LANG['bugs.error.e_edit_category_success'], E_USER_SUCCESS);
	if ($get_error == 'edit_version_success')
		$Errorh->handler($LANG['bugs.error.e_edit_version_success'], E_USER_SUCCESS);
	
	$Template->pparse('admin_bugtracker');	
}

require_once('../admin/admin_footer.php');
?>