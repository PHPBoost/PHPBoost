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

$versions_detected_in = array();
foreach ($versions as $key => $version)
{	
	if ($version['detected_in'] == true)
		$versions_detected_in[$key] = $version;
}

$display_types = sizeof($types) > 1 ? true : false;
$display_categories = sizeof($categories) > 1 ? true : false;
$display_priorities = sizeof($priorities) > 1 ? true : false;
$display_severities = sizeof($severities) > 1 ? true : false;
$display_versions_detected_in = sizeof($versions_detected_in) > 1 ? true : false;

$default_type = $bugtracker_config->get_default_type();
$default_category = $bugtracker_config->get_default_category();
$default_priority = $bugtracker_config->get_default_priority();
$default_severity = $bugtracker_config->get_default_severity();
$default_version = $bugtracker_config->get_default_version();

$type_mandatory = $bugtracker_config->get_type_mandatory();
$category_mandatory = $bugtracker_config->get_category_mandatory();
$priority_mandatory = $bugtracker_config->get_priority_mandatory();
$severity_mandatory = $bugtracker_config->get_severity_mandatory();
$detected_in_mandatory = $bugtracker_config->get_detected_in_mandatory();

$id = retrieve(GET, 'id', 0, TINTEGER);
$id_post = retrieve(POST, 'id', 0, TINTEGER);

if (isset($_POST['valid_add_type']))
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
else if (isset($_POST['valid_add_category']))
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
else if (isset($_POST['valid_add_priority']))
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
else if (isset($_POST['valid_add_version']))
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
else if (isset($_POST['valid_add_severity']))
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
else if (isset($_POST['delete_default_type'])) //Suppression du type par défaut
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$bugtracker_config->set_default_type(0);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (isset($_POST['delete_default_category'])) //Suppression de la catégorie par défaut
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$bugtracker_config->set_default_category(0);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (isset($_POST['delete_default_severity'])) //Suppression du niveau par défaut
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$bugtracker_config->set_default_severity(0);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (isset($_POST['delete_default_priority'])) //Suppression de la priorité par défaut
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$bugtracker_config->set_default_priority(0);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (isset($_POST['delete_default_version'])) //Suppression de la version par défaut
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$bugtracker_config->set_default_version(0);
	
	BugtrackerConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else if (isset($_POST['valid']))
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
		$bugtracker_config->set_date_format(retrieve(POST, 'date_format', 'date_format'));
		$bugtracker_config->set_comments_activated(retrieve(POST, 'comments_activated', false));
		$bugtracker_config->set_roadmap_activated(retrieve(POST, 'roadmap_activated', false));
		$bugtracker_config->set_cat_in_title_activated(retrieve(POST, 'cat_in_title_activated', false));
		$bugtracker_config->set_pm_activated(retrieve(POST, 'pm_activated', false));
		$bugtracker_config->set_versions($versions);
		$bugtracker_config->set_types($types);
		$bugtracker_config->set_categories($categories);
		$bugtracker_config->set_priorities($priorities);
		$bugtracker_config->set_severities($severities);
		$bugtracker_config->set_default_type(retrieve(POST, 'default_type', 0));
		$bugtracker_config->set_default_category(retrieve(POST, 'default_category', 0));
		$bugtracker_config->set_default_priority(retrieve(POST, 'default_priority', 0));
		$bugtracker_config->set_default_severity(retrieve(POST, 'default_severity', 0));
		$bugtracker_config->set_default_version(retrieve(POST, 'default_version', 0));
		$bugtracker_config->set_type_mandatory(retrieve(POST, 'type_mandatory', false));
		$bugtracker_config->set_category_mandatory(retrieve(POST, 'category_mandatory', false));
		$bugtracker_config->set_priority_mandatory(retrieve(POST, 'priority_mandatory', false));
		$bugtracker_config->set_severity_mandatory(retrieve(POST, 'severity_mandatory', false));
		$bugtracker_config->set_detected_in_mandatory(retrieve(POST, 'detected_in_mandatory', 0));
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
		'C_NO_VERSION' 								=> empty($versions) ? true : false,
		'C_NO_TYPE' 								=> empty($types) ? true : false,
		'C_NO_CATEGORY' 							=> empty($categories) ? true : false,
		'C_NO_PRIORITY' 							=> empty($priorities) ? true : false,
		'C_NO_SEVERITY' 							=> empty($severities) ? true : false,
		'C_DISPLAY_TYPES' 							=> $display_types,
		'C_DISPLAY_CATEGORIES' 						=> $display_categories,
		'C_DISPLAY_SEVERITIES' 						=> $display_severities,
		'C_DISPLAY_PRIORITIES' 						=> $display_priorities,
		'C_DISPLAY_VERSIONS' 						=> $display_versions_detected_in,
		'C_DISPLAY_DEFAULT_TYPE_DELETE_BUTTON' 		=> !empty($default_type) ? true : false,
		'C_DISPLAY_DEFAULT_CATEGORY_DELETE_BUTTON' 	=> !empty($default_category) ? true : false,
		'C_DISPLAY_DEFAULT_SEVERITY_DELETE_BUTTON' 	=> !empty($default_severity) ? true : false,
		'C_DISPLAY_DEFAULT_PRIORITY_DELETE_BUTTON' 	=> !empty($default_priority) ? true : false,
		'C_DISPLAY_DEFAULT_VERSION_DELETE_BUTTON' 	=> !empty($default_version) ? true : false,
		'C_TYPE_MANDATORY'							=> $type_mandatory ? true : false,
		'C_CATEGORY_MANDATORY'						=> $category_mandatory ? true : false,
		'C_SEVERITY_MANDATORY'						=> $severity_mandatory ? true : false,
		'C_PRIORITY_MANDATORY'						=> $priority_mandatory ? true : false,
		'C_DETECTED_IN_MANDATORY'					=> $detected_in_mandatory ? true : false,
		'C_DATE_FORMAT'								=> ($bugtracker_config->get_date_format() == 'date_format') ? true : false,
		'L_DELETE_DEFAULT_VALUE' 					=> $LANG['bugs.labels.del_default_value'],
		'L_CONFIRM_DEL_VERSION' 					=> $LANG['bugs.actions.confirm.del_version'],
		'L_CONFIRM_DEL_TYPE' 						=> $LANG['bugs.actions.confirm.del_type'],
		'L_CONFIRM_DEL_CATEGORY' 					=> $LANG['bugs.actions.confirm.del_category'],
		'L_CONFIRM_DEL_PRIORITY' 					=> $LANG['bugs.actions.confirm.del_priority'],
		'L_CONFIRM_DEL_SEVERITY' 					=> $LANG['bugs.actions.confirm.del_severity'],
		'L_BUGS_MANAGEMENT'							=> $LANG['bugs.titles.admin.management'],
		'L_BUGS_CONFIG' 							=> $LANG['bugs.titles.admin.config'],
		'L_ITEMS_PER_PAGE'							=> $LANG['bugs.config.items_per_page'],
		'L_REQUIRE'									=> $LANG['require'],
		'L_REQUIRE_ITEMS_PER_PAGE'					=> $LANG['bugs.error.require_items_per_page'],
		'L_REQUIRE_TYPE'	 						=> $LANG['bugs.notice.require_type'],
		'L_REQUIRE_CATEGORY' 						=> $LANG['bugs.notice.require_category'],
		'L_REQUIRE_PRIORITY' 						=> $LANG['bugs.notice.require_priority'],
		'L_REQUIRE_SEVERITY' 						=> $LANG['bugs.notice.require_severity'],
		'L_REQUIRE_VERSION' 						=> $LANG['bugs.notice.require_version'],
		'L_REJECTED_BUG_COLOR' 						=> $LANG['bugs.config.rejected_bug_color_label'],
		'L_FIXED_BUG_COLOR' 						=> $LANG['bugs.config.fixed_bug_color_label'],
		'L_ACTIV_COM'								=> $LANG['bugs.config.activ_com'],
		'L_ACTIV_ROADMAP'							=> $LANG['bugs.config.activ_roadmap'],
		'L_ACTIV_CAT_IN_TITLE'						=> $LANG['bugs.config.activ_cat_in_title'],
		'L_ACTIV_PM'								=> $LANG['bugs.config.activ_pm'],
		'L_DISPONIBLE_VERSIONS'						=> $LANG['bugs.titles.disponible_versions'],
		'L_DISPONIBLE_TYPES'						=> $LANG['bugs.titles.disponible_types'],
		'L_DISPONIBLE_CATEGORIES'					=> $LANG['bugs.titles.disponible_categories'],
		'L_DISPONIBLE_PRIORITIES'					=> $LANG['bugs.titles.disponible_priorities'],
		'L_DISPONIBLE_SEVERITIES'					=> $LANG['bugs.titles.disponible_severities'],
		'L_CONTENT_VALUE_TITLE'						=> $LANG['bugs.titles.contents_value_title'],
		'L_CONTENT_VALUE'							=> $LANG['bugs.titles.contents_value'],
		'L_ROADMAP_EXPLAIN'							=> $LANG['bugs.explain.roadmap'],
		'L_PM_EXPLAIN'								=> $LANG['bugs.explain.pm'],
		'L_TYPE_EXPLAIN'							=> $LANG['bugs.explain.type'] . '<br /><br />' . $LANG['bugs.explain.remarks'],
		'L_CATEGORY_EXPLAIN'						=> $LANG['bugs.explain.category'] . '<br /><br />' . $LANG['bugs.explain.remarks'],
		'L_PRIORITY_EXPLAIN'						=> $LANG['bugs.explain.priority'] . '<br /><br />' . $LANG['bugs.explain.remarks'],
		'L_SEVERITY_EXPLAIN'						=> $LANG['bugs.explain.severity'] . '<br /><br />' . $LANG['bugs.explain.remarks'],
		'L_VERSION_EXPLAIN'							=> $LANG['bugs.explain.version'] . '<br /><br />' . $LANG['bugs.explain.remarks'],
		'L_TYPE_MANDATORY'							=> $LANG['bugs.labels.type_mandatory'],
		'L_CATEGORY_MANDATORY'						=> $LANG['bugs.labels.category_mandatory'],
		'L_PRIORITY_MANDATORY'						=> $LANG['bugs.labels.severity_mandatory'],
		'L_SEVERITY_MANDATORY'						=> $LANG['bugs.labels.priority_mandatory'],
		'L_DETECTED_IN_MANDATORY'					=> $LANG['bugs.labels.detected_in_mandatory'],
		'L_CONTENT_VALUE_EXPLAIN'					=> $LANG['bugs.explain.contents_value'],
		'L_VERSION_DETECTED_IN'						=> $LANG['bugs.labels.fields.version_detected_in'],
		'L_VERSION_DETECTED'						=> $LANG['bugs.labels.fields.version_detected'],
		'L_ADD_VERSION'								=> $LANG['bugs.titles.add_version'],
		'L_ADD_TYPE'								=> $LANG['bugs.titles.add_type'],
		'L_ADD_CATEGORY'							=> $LANG['bugs.titles.add_category'],
		'L_ADD_PRIORITY'							=> $LANG['bugs.titles.add_priority'],
		'L_ADD_SEVERITY'							=> $LANG['bugs.titles.add_severity'],
		'L_NO_VERSION'								=> $LANG['bugs.notice.no_version'],
		'L_NO_TYPE'									=> $LANG['bugs.notice.no_type'],
		'L_NO_CATEGORY'								=> $LANG['bugs.notice.no_category'],
		'L_NO_PRIORITY'								=> $LANG['bugs.notice.no_priority'],
		'L_NO_SEVERITY'								=> $LANG['bugs.notice.no_severity'],
		'L_AUTH'		 							=> $LANG['bugs.config.auth'],
		'L_COLOR' 									=> $LANG['bugs.labels.color'],
		'L_DEFAULT'									=> $LANG['bugs.labels.default'],
		'L_DATE_FORMAT'								=> $LANG['bugs.labels.date_format'],
		'L_DATE_TIME'								=> $LANG['bugs.labels.date_time'],
		'L_DATE'									=> $LANG['date'],
		'L_NAME' 									=> $LANG['name'],
		'L_ADD' 									=> $LANG['add'],
		'L_UPDATE' 									=> $LANG['update'],
		'L_DELETE' 									=> $LANG['delete'],
		'L_PREVIEW' 								=> $LANG['preview'],
		'L_YES'		 								=> $LANG['yes'],
		'L_NO' 										=> $LANG['no'],
		'ITEMS_PER_PAGE'							=> $bugtracker_config->get_items_per_page(),
		'REJECTED_BUG_COLOR'						=> $bugtracker_config->get_rejected_bug_color(),
		'FIXED_BUG_COLOR'							=> $bugtracker_config->get_fixed_bug_color(),
		'COM_CHECKED'								=> ($bugtracker_config->get_comments_activated() == true) ? 'checked=checked' : '',
		'ROADMAP_CHECKED'							=> ($bugtracker_config->get_roadmap_activated() == true) ? 'checked=checked' : '',
		'CAT_IN_TITLE_CHECKED'						=> ($bugtracker_config->get_cat_in_title_activated() == true) ? 'checked=checked' : '',
		'PM_CHECKED'								=> ($bugtracker_config->get_pm_activated() == true) ? 'checked=checked' : '',
		'CONTENTS_VALUE'							=> FormatingHelper::unparse($bugtracker_config->get_contents_value()),
		'CONTENTS_KERNEL_EDITOR'					=> $contents_editor->display()
	));
	
	foreach ($types as $key => $type)
	{
		$Template->assign_block_vars('types', array(
			'ID'			=> $key,
			'NAME'			=> stripslashes($type),
			'IS_DEFAULT'	=> ($default_type == $key) ? 'checked=checked' : ''
		));	
	}
	
	foreach ($categories as $key => $category)
	{
		$Template->assign_block_vars('categories', array(
			'ID'			=> $key,
			'NAME'			=> stripslashes($category),
			'IS_DEFAULT'	=> ($default_category == $key) ? 'checked=checked' : ''
		));	
	}
	
	foreach ($priorities as $key => $priority)
	{
		$Template->assign_block_vars('priorities', array(
			'ID'			=> $key,
			'NAME'			=> stripslashes($priority),
			'IS_DEFAULT'	=> ($default_priority == $key) ? 'checked=checked' : ''
		));	
	}
	
	foreach ($severities as $key => $severity)
	{
		$Template->assign_block_vars('severities', array(
			'ID'				=> $key,
			'ID_BBCODE_COLOR'	=> ($key + 4),
			'NAME'				=> stripslashes($severity['name']),
			'COLOR'				=> $severity['color'],
			'IS_DEFAULT'		=> ($default_severity == $key) ? 'checked=checked' : ''
		));	
	}
	
	foreach ($versions as $key => $version)
	{
		$Template->assign_block_vars('versions', array(
			'ID'				=> $key,
			'NAME'				=> stripslashes($version['name']),
			'DETECTED_IN'		=> ($version['detected_in'] == true) ? 'checked=checked' : '',
			'DISPLAY_DEFAULT'	=> ($version['detected_in'] != true) ? 'style="display:none"' : '',
			'IS_DEFAULT'		=> ($default_version == $key) ? 'checked=checked' : ''
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