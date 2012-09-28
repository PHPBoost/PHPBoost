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

$bugtracker_config = BugtrackerConfig::load();

$types = $bugtracker_config->get_types();
$categories = $bugtracker_config->get_categories();
$versions = $bugtracker_config->get_versions();

$id = retrieve(GET, 'id', 0, TINTEGER);
$id_post = retrieve(POST, 'id', 0, TINTEGER);

if (!empty($_POST['valid_add_type']))
{
	if (!empty($_POST['type']))
	{
		$types[] = retrieve(POST, 'type', '');
		$bugtracker_config->set_types($types);
		
		BugtrackerConfig::save();
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=incomplete#message_helper');
}
else if (!empty($_POST['valid_edit_type']) && is_numeric($id_post))
{
	$type = retrieve(POST, 'type', '');
	
	//On met à jour
	if (!empty($type))
	{
		//Modification du type en question dans la liste des bugs
		$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET type = '" . addslashes($type) . "' WHERE type = '" . addslashes($types[$id_post]) . "'", __LINE__, __FILE__);
		
		$types[$id_post] = $type;
		$bugtracker_config->set_types($types);
		
		BugtrackerConfig::save();

		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=edit_type_success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?edit_type=true&id= ' . $id_post . '&error=incomplete#message_helper');
}
else if (isset($_GET['edit_type']) && is_numeric($id)) // edition d'un type
{
	$Template = new FileTemplate('bugtracker/admin_bugtracker.tpl');
	
	$Template->assign_block_vars('edit_type', array(
		'ID'	=> $id,
		'TYPE'	=> stripslashes($types[$id])
	));	
	
	$Template->assign_vars(array(
		'L_BUGS_MANAGEMENT'	=> $LANG['bugs.titles.admin.management'],
		'L_BUGS_CONFIG' 	=> $LANG['bugs.titles.admin.config'],
		'L_EDIT_TYPE'		=> $LANG['bugs.titles.edit_type'],
		'L_REQUIRE' 		=> $LANG['require'],
		'L_REQUIRE_TYPE' 	=> $LANG['bugs.notice.require_type'],
		'L_TYPE'			=> $LANG['bugs.labels.fields.type'],
		'L_UPDATE' 			=> $LANG['update'],
		'L_RESET' 			=> $LANG['reset'],
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	switch ($get_error)
	{
		case 'incomplete':
			$errstr = $LANG['e_incomplete'];
			break;
		default:
			$errstr = '';
	}
	if (!empty($errstr))
		$Template->put('message_helper', MessageHelper::display($errstr, E_USER_NOTICE));
	
	$Template->display(); // traitement du modele
}
else if (isset($_GET['delete_type']) && is_numeric($id)) //Suppression d'un type
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//Suppression du type en question dans la liste des bugs
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET type = '' WHERE type = '" . addslashes($types[$id]) . "'", __LINE__, __FILE__);
	
	//On supprime la catégorie de la liste
	unset($types[$id]);
	$bugtracker_config->set_types($types);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (!empty($_POST['valid_add_category']))
{
	if (!empty($_POST['category']))
	{
		$categories[] = retrieve(POST, 'category', '');
		$bugtracker_config->set_categories($categories);
		
		BugtrackerConfig::save();
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=incomplete#message_helper');
}
else if (!empty($_POST['valid_edit_category']) && is_numeric($id_post))
{
	$category = retrieve(POST, 'category', '');
	
	//On met à jour
	if (!empty($category))
	{
		//Modification de la categorie en question dans la liste des bugs
		$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET category = '" . addslashes($category) . "' WHERE category = '" . addslashes($categories[$id_post]) . "'", __LINE__, __FILE__);
		
		$categories[$id_post] = $category;
		$bugtracker_config->set_categories($categories);
		
		BugtrackerConfig::save();

		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=edit_category_success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?edit_category=true&id= ' . $id_post . '&error=incomplete#message_helper');
}
else if (isset($_GET['edit_category']) && is_numeric($id)) // edition d'une catégorie
{
	$Template = new FileTemplate('bugtracker/admin_bugtracker.tpl');
	
	$Template->assign_block_vars('edit_category', array(
		'ID'		=> $id,
		'CATEGORY'	=> stripslashes($categories[$id])
	));	
	
	$Template->assign_vars(array(
		'L_BUGS_MANAGEMENT'		=> $LANG['bugs.titles.admin.management'],
		'L_BUGS_CONFIG' 		=> $LANG['bugs.titles.admin.config'],
		'L_EDIT_CATEGORY'		=> $LANG['bugs.titles.edit_category'],
		'L_REQUIRE' 			=> $LANG['require'],
		'L_REQUIRE_CATEGORY' 	=> $LANG['bugs.notice.require_category'],
		'L_CATEGORY'			=> $LANG['bugs.labels.fields.category'],
		'L_UPDATE' 				=> $LANG['update'],
		'L_RESET' 				=> $LANG['reset']
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	switch ($get_error)
	{
		case 'incomplete':
			$errstr = $LANG['e_incomplete'];
			break;
		default:
			$errstr = '';
	}
	if (!empty($errstr))
		$Template->put('message_helper', MessageHelper::display($errstr, E_USER_NOTICE));
	
	$Template->display(); // traitement du modele
}
else if (isset($_GET['delete_category']) && is_numeric($id)) //Suppression d'une catégorie
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//Suppression de la catégorie en question dans la liste des bugs
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET category = '' WHERE category = '" . addslashes($categories[$id]) . "'", __LINE__, __FILE__);
	
	//On supprime la catégorie de la liste
	unset($categories[$id]);
	$bugtracker_config->set_categories($categories);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (!empty($_POST['valid_add_version']))
{
	if (!empty($_POST['version']))
	{
		$versions[] = array(
			'name'			=> retrieve(POST, 'version', ''),
			'detected_in' 	=> retrieve(POST, 'detected_in', false),
			'fixed_in' 		=> retrieve(POST, 'fixed_in', false)
		);
		$bugtracker_config->set_versions($versions);
		
		BugtrackerConfig::save();
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=incomplete#message_helper');
}
else if (!empty($_POST['valid_edit_version']) && is_numeric($id_post))
{
	$version = retrieve(POST, 'version', '');
	
	//On met à jour
	if (!empty($version))
	{
		//Modification de la version en question dans la liste des bugs
		$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET detected_in = '" . addslashes($version) . "' WHERE detected_in = '" . addslashes($versions[$id_post]['name']) . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET fixed_in = '" . addslashes($version) . "' WHERE fixed_in = '" . addslashes($versions[$id_post]['name']) . "'", __LINE__, __FILE__);
		
		$versions[$id_post] = array(
			'name' 			=> $version,
			'detected_in' 	=> retrieve(POST, 'detected_in', false),
			'fixed_in' 		=> retrieve(POST, 'fixed_in', false)
		);
		$bugtracker_config->set_versions($versions);
		
		BugtrackerConfig::save();

		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=edit_version_success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?edit_version=true&id= ' . $id_post . '&error=incomplete#message_helper');
}
else if (isset($_GET['edit_version']) && is_numeric($id)) // edition d'une version
{
	$Template = new FileTemplate('bugtracker/admin_bugtracker.tpl');
	
	$Template->assign_block_vars('edit_version', array(
		'ID'			=> $id,
		'VERSION'		=> stripslashes($versions[$id]['name']),
		'DETECTED_IN'	=> ($versions[$id]['detected_in'] == true) ? 'checked=checked' : '',
		'FIXED_IN'		=> ($versions[$id]['fixed_in'] == true) ? 'checked=checked' : '',
	));	
	
	$Template->assign_vars(array(
		'L_BUGS_MANAGEMENT'		=> $LANG['bugs.titles.admin.management'],
		'L_BUGS_CONFIG' 		=> $LANG['bugs.titles.admin.config'],
		'L_EDIT_VERSION'		=> $LANG['bugs.titles.edit_version'],
		'L_REQUIRE' 			=> $LANG['require'],
		'L_REQUIRE_VERSION' 	=> $LANG['bugs.notice.require_version'],
		'L_VERSION'				=> $LANG['bugs.labels.fields.version'],
		'L_VERSION_DETECTED_IN'	=> $LANG['bugs.labels.fields.version_detected_in'],
		'L_VERSION_FIXED_IN'	=> $LANG['bugs.labels.fields.version_fixed_in'],
		'L_UPDATE' 				=> $LANG['update'],
		'L_RESET' 				=> $LANG['reset']
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	switch ($get_error)
	{
		case 'incomplete':
			$errstr = $LANG['e_incomplete'];
			break;
		default:
			$errstr = '';
	}
	if (!empty($errstr))
		$Template->put('message_helper', MessageHelper::display($errstr, E_USER_NOTICE));
	
	$Template->display(); // traitement du modele	
}
else if (isset($_GET['delete_version']) && is_numeric($id)) //Suppression d'une version
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//Suppression de la version en question dans la liste des bugs
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET detected_in = '' WHERE detected_in = '" . addslashes($versions[$id]['name']) . "'", __LINE__, __FILE__);
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET fixed_in = '' WHERE fixed_in = '" . addslashes($versions[$id]['name']) . "'", __LINE__, __FILE__);
	
	//On supprime la version de la liste
	unset($versions[$id]);
	$bugtracker_config->set_versions($versions);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (!empty($_POST['valid']))
{
	if (!empty($_POST['items_per_page'])) {
		$bugtracker_config = BugtrackerConfig::load();
	
		$bugtracker_config->set_items_per_page(retrieve(POST, 'items_per_page', 0));
		$bugtracker_config->set_severity_minor_color(retrieve(POST, 'severity_minor_color', ''));
		$bugtracker_config->set_severity_major_color(retrieve(POST, 'severity_major_color', ''));
		$bugtracker_config->set_severity_critical_color(retrieve(POST, 'severity_critical_color', ''));
		$bugtracker_config->set_rejected_bug_color(retrieve(POST, 'rejected_bug_color', ''));
		$bugtracker_config->set_closed_bug_color(retrieve(POST, 'closed_bug_color', ''));
		$bugtracker_config->set_comments_activated(retrieve(POST, 'comments_activated', false));
		$bugtracker_config->set_versions(retrieve(POST, 'versions', $bugtracker_config->get_versions()));
		$bugtracker_config->set_types(retrieve(POST, 'types', $bugtracker_config->get_types()));
		$bugtracker_config->set_categories(retrieve(POST, 'categories', $bugtracker_config->get_categories()));
		$bugtracker_config->set_contents_value(retrieve(POST, 'contents_value', '', TSTRING_AS_RECEIVED));
		$bugtracker_config->set_authorizations(Authorizations::build_auth_array_from_form(BugtrackerConfig::BUG_READ_AUTH_BIT, BugtrackerConfig::BUG_CREATE_AUTH_BIT, BugtrackerConfig::BUG_CREATE_ADVANCED_AUTH_BIT, BugtrackerConfig::BUG_MODERATE_AUTH_BIT));

		BugtrackerConfig::save();
		
		AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT . '?error=success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=require_items_per_page#message_helper');
}
//Sinon on rempli le formulaire
else	
{	
	$Template = new FileTemplate('bugtracker/admin_bugtracker.tpl');
	
	$authorizations = $bugtracker_config->get_authorizations();
	
	$contents_editor = AppContext::get_content_formatting_service()->get_default_editor();
	$contents_editor->set_identifier('contents_value');
	
	$Template->put_all(array(
		'C_NO_VERSION' 					=> empty($versions) ? true : false,
		'C_NO_TYPE' 					=> empty($types) ? true : false,
		'C_NO_CATEGORY' 				=> empty($categories) ? true : false,
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
		'L_REJECTED_BUG_COLOR' 			=> $LANG['bugs.config.rejected_bug_color_label'],
		'L_CLOSED_BUG_COLOR' 			=> $LANG['bugs.config.closed_bug_color_label'],
		'L_ACTIV_COM'					=> $LANG['bugs.config.activ_com'],
		'L_BUGS_DISPONIBLE_VERSIONS'	=> $LANG['bugs.titles.disponible_versions'],
		'L_BUGS_DISPONIBLE_TYPES'		=> $LANG['bugs.titles.disponible_types'],
		'L_BUGS_DISPONIBLE_CATEGORIES'	=> $LANG['bugs.titles.disponible_categories'],
		'L_CONTENT_VALUE_TITLE'			=> $LANG['bugs.titles.contents_value_title'],
		'L_CONTENT_VALUE'				=> $LANG['bugs.titles.contents_value'],
		'L_BUGS_TYPE_EXPLAIN'			=> $LANG['bugs.explain.type'],
		'L_BUGS_CATEGORY_EXPLAIN'		=> $LANG['bugs.explain.category'],
		'L_BUGS_VERSION_EXPLAIN'		=> $LANG['bugs.explain.version'],
		'L_CONTENT_VALUE_EXPLAIN'		=> $LANG['bugs.explain.contents_value'],
		'L_VERSION'						=> $LANG['bugs.labels.fields.version'],
		'L_VERSION_DETECTED_IN'			=> $LANG['bugs.labels.fields.version_detected_in'],
		'L_VERSION_FIXED_IN'			=> $LANG['bugs.labels.fields.version_fixed_in'],
		'L_VERSION_DETECTED'			=> $LANG['bugs.labels.fields.version_detected'],
		'L_VERSION_FIXED'				=> $LANG['bugs.labels.fields.version_fixed'],
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
		'L_MODERATE_AUTH'				=> $LANG['bugs.config.auth.moderate'],
		'L_ADD' 						=> $LANG['add'],
		'L_UPDATE' 						=> $LANG['update'],
		'L_DELETE' 						=> $LANG['delete'],
		'L_PREVIEW' 					=> $LANG['preview'],
		'ITEMS_PER_PAGE'				=> $bugtracker_config->get_items_per_page(),
		'SEVERITY_MINOR_COLOR'			=> $bugtracker_config->get_severity_minor_color(),
		'SEVERITY_MAJOR_COLOR'			=> $bugtracker_config->get_severity_major_color(),
		'SEVERITY_CRITICAL_COLOR'		=> $bugtracker_config->get_severity_critical_color(),
		'REJECTED_BUG_COLOR'			=> $bugtracker_config->get_rejected_bug_color(),
		'CLOSED_BUG_COLOR'				=> $bugtracker_config->get_closed_bug_color(),
		'COM_CHECKED'					=> ($bugtracker_config->get_comments_activated() == true) ? 'checked=checked' : '',
		'CONTENTS_VALUE'				=> FormatingHelper::unparse($bugtracker_config->get_contents_value()),
		'CONTENTS_KERNEL_EDITOR'		=> $contents_editor->display(),
		'BUG_READ_AUTH'					=> Authorizations::generate_select(BugtrackerConfig::BUG_READ_AUTH_BIT, $authorizations),
		'BUG_CREATE_AUTH'				=> Authorizations::generate_select(BugtrackerConfig::BUG_CREATE_AUTH_BIT, $authorizations),
		'BUG_CREATE_ADVANCED_AUTH'		=> Authorizations::generate_select(BugtrackerConfig::BUG_CREATE_ADVANCED_AUTH_BIT, $authorizations),
		'BUG_MODERATE_AUTH'				=> Authorizations::generate_select(BugtrackerConfig::BUG_MODERATE_AUTH_BIT, $authorizations)
	));
	
	$Template->assign_block_vars('config', array());

	
	foreach ($types as $key => $type)
	{
		$Template->assign_block_vars('types', array(
			'ID'	=> $key,
			'TYPE'	=> stripslashes($type)
		));	
	}	
	
	foreach ($categories as $key => $category)
	{
		$Template->assign_block_vars('categories', array(
			'ID'		=> $key,
			'CATEGORY'	=> stripslashes($category)
		));	
	}
	
	foreach ($versions as $key => $version)
	{
		$Template->assign_block_vars('versions', array(
			'ID'			=> $key,
			'VERSION'		=> stripslashes($version['name']),
			'DETECTED_IN'	=> ($version['detected_in'] == true) ? 'checked=checked' : '',
			'FIXED_IN'		=> ($version['fixed_in'] == true) ? 'checked=checked' : '',
		));	
	}
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	switch ($get_error)
	{
		case 'require_items_per_page':
			$errstr = $LANG['bugs.error.require_items_per_page'];
			$errtyp = E_USER_NOTICE;
			break;
		case 'incomplete':
			$errstr = $LANG['e_incomplete'];
			$errtyp = E_USER_NOTICE;
			break;
		case 'success':
			$errstr = $LANG['bugs.error.e_config_success'];
			$errtyp = E_USER_SUCCESS;
			break;
		case 'edit_type_success':
			$errstr = $LANG['bugs.error.e_edit_type_success'];
			$errtyp = E_USER_SUCCESS;
			break;
		case 'edit_category_success':
			$errstr = $LANG['bugs.error.e_edit_category_success'];
			$errtyp = E_USER_SUCCESS;
			break;
		case 'edit_version_success':
			$errstr = $LANG['bugs.error.e_edit_version_success'];
			$errtyp = E_USER_SUCCESS;
			break;
		default:
			$errstr = '';
			$errtyp = E_USER_NOTICE;
	}
	if (!empty($errstr))
		$Template->put('message_helper', MessageHelper::display($errstr, $errtyp));
		
	$Template->display(); // traitement du modele	
}

require_once('../admin/admin_footer.php');
?>