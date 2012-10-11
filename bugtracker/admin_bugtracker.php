<?php
/*##################################################
 *                              admin_bugtracker.php
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
$priorities = $bugtracker_config->get_priorities();
$severities = $bugtracker_config->get_severities();
$versions = $bugtracker_config->get_versions();

$id = retrieve(GET, 'id', 0, TINTEGER);
$id_post = retrieve(POST, 'id', 0, TINTEGER);

if (!empty($_POST['valid_add_type']))
{
	if (!empty($_POST['type']))
	{
		$nb_types = sizeof($types);
		$array_id = empty($nb_types) ? 1 : ($nb_types + 1);
		$types[$array_id] = retrieve(POST, 'type', '');
		$bugtracker_config->set_types($types);
		
		BugtrackerConfig::save();
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=incomplete#message_helper');
}
else if (isset($_GET['delete_type']) && is_numeric($id)) //Suppression d'un type
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//Suppression du type en question dans la liste des bugs
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET type = '' WHERE type = '" . $id . "'", __LINE__, __FILE__);
	
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
		$nb_categories = sizeof($categories);
		$array_id = empty($nb_categories) ? 1 : ($nb_categories + 1);
		$categories[$array_id] = retrieve(POST, 'category', '');
		$bugtracker_config->set_categories($categories);
		
		BugtrackerConfig::save();
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=incomplete#message_helper');
}
else if (isset($_GET['delete_category']) && is_numeric($id)) //Suppression d'une catégorie
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//Suppression de la catégorie en question dans la liste des bugs
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET category = '' WHERE category = '" . $id . "'", __LINE__, __FILE__);
	
	//On supprime la catégorie de la liste
	unset($categories[$id]);
	$bugtracker_config->set_categories($categories);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (!empty($_POST['valid_add_priority']))
{
	if (!empty($_POST['priority']))
	{
		$nb_priorities = sizeof($priorities);
		$array_id = empty($nb_priorities) ? 1 : ($nb_priorities + 1);
		$priorities[$array_id] = retrieve(POST, 'priority', '');
		$bugtracker_config->set_priorities($priorities);
		
		BugtrackerConfig::save();
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=incomplete#message_helper');
}
else if (isset($_GET['delete_priority']) && is_numeric($id)) //Suppression d'une priorité
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//Suppression de la priorité en question dans la liste des bugs
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET priority = '' WHERE priority = '" . $id . "'", __LINE__, __FILE__);
	
	//On supprime la priorité de la liste
	unset($priorities[$id]);
	$bugtracker_config->set_priorities($priorities);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (!empty($_POST['valid_add_version']))
{
	if (!empty($_POST['version']))
	{
		$nb_versions = sizeof($versions);
		$array_id = empty($nb_versions) ? 1 : ($nb_versions + 1);
		$versions[$array_id] = array(
			'name'			=> retrieve(POST, 'version', ''),
			'detected_in' 	=> retrieve(POST, 'detected_in', false)
		);
		$bugtracker_config->set_versions($versions);
		
		BugtrackerConfig::save();
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=incomplete#message_helper');
}
else if (isset($_GET['delete_version']) && is_numeric($id)) //Suppression d'une version
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//Suppression de la version en question dans la liste des bugs
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET detected_in = '' WHERE detected_in = '" . $id . "'", __LINE__, __FILE__);
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET fixed_in = '' WHERE fixed_in = '" . $id . "'", __LINE__, __FILE__);
	
	//On supprime la version de la liste
	unset($versions[$id]);
	$bugtracker_config->set_versions($versions);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (!empty($_POST['valid_add_severity']))
{
	if (!empty($_POST['severity']))
	{
		$nb_severities = sizeof($severities);
		$array_id = empty($nb_severities) ? 1 : ($nb_severities + 1);
		$severities[$array_id] = array(
			'name'		=> retrieve(POST, 'severity', ''),
			'color' 	=> retrieve(POST, 's_color', '')
		);
		$bugtracker_config->set_severities($severities);
		
		BugtrackerConfig::save();
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=incomplete#message_helper');
}
else if (isset($_GET['delete_severity']) && is_numeric($id)) //Suppression d'un niveau
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//Suppression du niveau en question dans la liste des bugs
	$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET severity = '' WHERE severity = '" . $id . "'", __LINE__, __FILE__);
	
	//On supprime le niveau de la liste
	unset($severities[$id]);
	$bugtracker_config->set_severities($severities);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (!empty($_POST['valid']))
{
	if (!empty($_POST['items_per_page'])) {
		foreach ($types as $key => $type)
		{
			$new_type = retrieve(POST, 'type' . $key, '');
			$types[$key] = (!empty($new_type) && $new_type != $type) ? $new_type : $type;
		}
		
		foreach ($categories as $key => $category)
		{
			$new_cat = retrieve(POST, 'category' . $key, '');
			$categories[$key] = (!empty($new_cat) && $new_cat != $category) ? $new_cat : $category;
		}
		
		foreach ($priorities as $key => $priority)
		{
			$new_priority = retrieve(POST, 'priority' . $key, '');
			$priorities[$key] = (!empty($new_priority) && $new_priority != $priority) ? $new_priority : $priority;
		}
		
		foreach ($severities as $key => $severity)
		{
			$new_severity = retrieve(POST, 'severity' . $key, '');
			$new_color = retrieve(POST, 's_color' . $key, '');
			$severities[$key]['name'] = (!empty($new_severity) && $new_severity != $severity['name']) ? $new_severity : $severity['name'];
			$severities[$key]['color'] = ($new_color != $severity['color']) ? $new_color : $severity['color'];
		}
		
		foreach ($versions as $key => $version)
		{
			$new_version = retrieve(POST, 'version' . $key, '');
			$new_detected_in = retrieve(POST, 'detected_in' . $key, '');
			$versions[$key]['name'] = (!empty($new_version) && $new_version != $version['name']) ? $new_version : $version['name'];
			$versions[$key]['detected_in'] = ($new_detected_in != $version['detected_in']) ? $new_detected_in : $version['detected_in'];
		}
		
		$bugtracker_config->set_items_per_page(retrieve(POST, 'items_per_page', 0));
		$bugtracker_config->set_rejected_bug_color(retrieve(POST, 'rejected_bug_color', ''));
		$bugtracker_config->set_fixed_bug_color(retrieve(POST, 'fixed_bug_color', ''));
		$bugtracker_config->set_comments_activated(retrieve(POST, 'comments_activated', false));
		$bugtracker_config->set_roadmap_activated(retrieve(POST, 'roadmap_activated', false));
		$bugtracker_config->set_cat_in_title_activated(retrieve(POST, 'cat_in_title_activated', false));
		$bugtracker_config->set_versions($versions);
		$bugtracker_config->set_types($types);
		$bugtracker_config->set_categories($categories);
		$bugtracker_config->set_priorities($priorities);
		$bugtracker_config->set_severities($severities);
		$bugtracker_config->set_contents_value(retrieve(POST, 'contents_value', '', TSTRING_AS_RECEIVED));

		BugtrackerConfig::save();
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . '?error=require_items_per_page#message_helper');
}
//Sinon on rempli le formulaire
else	
{	
	$Template = new FileTemplate('bugtracker/admin_bugtracker.tpl');
	
	$contents_editor = AppContext::get_content_formatting_service()->get_default_editor();
	$contents_editor->set_identifier('contents_value');
	
	$Template->put_all(array(
		'C_NO_VERSION' 					=> empty($versions) ? true : false,
		'C_NO_TYPE' 					=> empty($types) ? true : false,
		'C_NO_CATEGORY' 				=> empty($categories) ? true : false,
		'C_NO_PRIORITY' 				=> empty($priorities) ? true : false,
		'C_NO_SEVERITY' 				=> empty($severities) ? true : false,
		'L_CONFIRM_DEL_VERSION' 		=> $LANG['bugs.actions.confirm.del_version'],
		'L_CONFIRM_DEL_TYPE' 			=> $LANG['bugs.actions.confirm.del_type'],
		'L_CONFIRM_DEL_CATEGORY' 		=> $LANG['bugs.actions.confirm.del_category'],
		'L_CONFIRM_DEL_PRIORITY' 		=> $LANG['bugs.actions.confirm.del_priority'],
		'L_CONFIRM_DEL_SEVERITY' 		=> $LANG['bugs.actions.confirm.del_severity'],
		'L_BUGS_MANAGEMENT'				=> $LANG['bugs.titles.admin.management'],
		'L_BUGS_CONFIG' 				=> $LANG['bugs.titles.admin.config'],
		'L_ITEMS_PER_PAGE'				=> $LANG['bugs.config.items_per_page'],
		'L_REQUIRE'						=> $LANG['require'],
		'L_REQUIRE_ITEMS_PER_PAGE'		=> $LANG['bugs.error.require_items_per_page'],
		'L_REQUIRE_TYPE'	 			=> $LANG['bugs.notice.require_type'],
		'L_REQUIRE_CATEGORY' 			=> $LANG['bugs.notice.require_category'],
		'L_REQUIRE_PRIORITY' 			=> $LANG['bugs.notice.require_priority'],
		'L_REQUIRE_SEVERITY' 			=> $LANG['bugs.notice.require_severity'],
		'L_REQUIRE_VERSION' 			=> $LANG['bugs.notice.require_version'],
		'L_REJECTED_BUG_COLOR' 			=> $LANG['bugs.config.rejected_bug_color_label'],
		'L_FIXED_BUG_COLOR' 			=> $LANG['bugs.config.fixed_bug_color_label'],
		'L_ACTIV_COM'					=> $LANG['bugs.config.activ_com'],
		'L_ACTIV_ROADMAP'				=> $LANG['bugs.config.activ_roadmap'],
		'L_ACTIV_CAT_IN_TITLE'			=> $LANG['bugs.config.activ_cat_in_title'],
		'L_DISPONIBLE_VERSIONS'			=> $LANG['bugs.titles.disponible_versions'],
		'L_DISPONIBLE_TYPES'			=> $LANG['bugs.titles.disponible_types'],
		'L_DISPONIBLE_CATEGORIES'		=> $LANG['bugs.titles.disponible_categories'],
		'L_DISPONIBLE_PRIORITIES'		=> $LANG['bugs.titles.disponible_priorities'],
		'L_DISPONIBLE_SEVERITIES'		=> $LANG['bugs.titles.disponible_severities'],
		'L_CONTENT_VALUE_TITLE'			=> $LANG['bugs.titles.contents_value_title'],
		'L_CONTENT_VALUE'				=> $LANG['bugs.titles.contents_value'],
		'L_TYPE_EXPLAIN'				=> $LANG['bugs.explain.type'],
		'L_ROADMAP_EXPLAIN'				=> $LANG['bugs.explain.roadmap'],
		'L_CATEGORY_EXPLAIN'			=> $LANG['bugs.explain.category'],
		'L_PRIORITY_EXPLAIN'			=> $LANG['bugs.explain.priority'],
		'L_SEVERITY_EXPLAIN'			=> $LANG['bugs.explain.severity'],
		'L_VERSION_EXPLAIN'				=> $LANG['bugs.explain.version'],
		'L_CONTENT_VALUE_EXPLAIN'		=> $LANG['bugs.explain.contents_value'],
		'L_VERSION'						=> $LANG['bugs.labels.fields.version'],
		'L_VERSION_DETECTED_IN'			=> $LANG['bugs.labels.fields.version_detected_in'],
		'L_VERSION_DETECTED'			=> $LANG['bugs.labels.fields.version_detected'],
		'L_TYPE'						=> $LANG['bugs.labels.fields.type'],
		'L_CATEGORY'					=> $LANG['bugs.labels.fields.category'],
		'L_PRIORITY'					=> $LANG['bugs.labels.fields.priority'],
		'L_SEVERITY'					=> $LANG['bugs.labels.fields.severity'],
		'L_ADD_VERSION'					=> $LANG['bugs.titles.add_version'],
		'L_ADD_TYPE'					=> $LANG['bugs.titles.add_type'],
		'L_ADD_CATEGORY'				=> $LANG['bugs.titles.add_category'],
		'L_ADD_PRIORITY'				=> $LANG['bugs.titles.add_priority'],
		'L_ADD_SEVERITY'				=> $LANG['bugs.titles.add_severity'],
		'L_NO_VERSION'					=> $LANG['bugs.notice.no_version'],
		'L_NO_TYPE'						=> $LANG['bugs.notice.no_type'],
		'L_NO_CATEGORY'					=> $LANG['bugs.notice.no_category'],
		'L_NO_PRIORITY'					=> $LANG['bugs.notice.no_priority'],
		'L_NO_SEVERITY'					=> $LANG['bugs.notice.no_severity'],
		'L_AUTH'		 				=> $LANG['bugs.config.auth'],
		'L_COLOR' 						=> $LANG['bugs.labels.color'],
		'L_ADD' 						=> $LANG['add'],
		'L_UPDATE' 						=> $LANG['update'],
		'L_DELETE' 						=> $LANG['delete'],
		'L_PREVIEW' 					=> $LANG['preview'],
		'ITEMS_PER_PAGE'				=> $bugtracker_config->get_items_per_page(),
		'REJECTED_BUG_COLOR'			=> $bugtracker_config->get_rejected_bug_color(),
		'FIXED_BUG_COLOR'				=> $bugtracker_config->get_fixed_bug_color(),
		'COM_CHECKED'					=> ($bugtracker_config->get_comments_activated() == true) ? 'checked=checked' : '',
		'ROADMAP_CHECKED'				=> ($bugtracker_config->get_roadmap_activated() == true) ? 'checked=checked' : '',
		'CAT_IN_TITLE_CHECKED'			=> ($bugtracker_config->get_cat_in_title_activated() == true) ? 'checked=checked' : '',
		'CONTENTS_VALUE'				=> FormatingHelper::unparse($bugtracker_config->get_contents_value()),
		'CONTENTS_KERNEL_EDITOR'		=> $contents_editor->display()
	));
	
	foreach ($types as $key => $type)
	{
		$Template->assign_block_vars('types', array(
			'ID'	=> $key,
			'NAME'	=> stripslashes($type)
		));	
	}
	
	foreach ($categories as $key => $category)
	{
		$Template->assign_block_vars('categories', array(
			'ID'		=> $key,
			'NAME'		=> stripslashes($category)
		));	
	}
	
	foreach ($priorities as $key => $priority)
	{
		$Template->assign_block_vars('priorities', array(
			'ID'		=> $key,
			'NAME'		=> stripslashes($priority)
		));	
	}
	
	foreach ($severities as $key => $severity)
	{
		$Template->assign_block_vars('severities', array(
			'ID'				=> $key,
			'ID_BBCODE_COLOR'	=> ($key + 4),
			'NAME'				=> stripslashes($severity['name']),
			'COLOR'				=> $severity['color']
		));	
	}
	
	foreach ($versions as $key => $version)
	{
		$Template->assign_block_vars('versions', array(
			'ID'			=> $key,
			'NAME'			=> stripslashes($version['name']),
			'DETECTED_IN'	=> ($version['detected_in'] == true) ? 'checked=checked' : ''
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