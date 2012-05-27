<?php
/*##################################################
 *                              bugtracker.php
 *                            -------------------
 *   begin                : February 05, 2012
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

require_once('../kernel/begin.php');
include_once('bugtracker_begin.php');
include_once('bugtracker_functions.php');
require_once('../kernel/header.php');

$auth_read = $User->check_auth($BUGS_CONFIG['auth'], BUG_READ_AUTH_BIT);
$auth_create = $User->check_auth($BUGS_CONFIG['auth'], BUG_CREATE_AUTH_BIT);
$auth_create_advanced = $User->check_auth($BUGS_CONFIG['auth'], BUG_CREATE_ADVANCED_AUTH_BIT);
$auth_moderate = $User->check_auth($BUGS_CONFIG['auth'], BUG_MODERATE_AUTH_BIT);
$display_types = sizeof($BUGS_CONFIG['types']) > 1 ? true : false;
$display_categories = sizeof($BUGS_CONFIG['categories']) > 1 ? true : false;
$display_versions = sizeof($BUGS_CONFIG['versions']) > 1 ? true : false;

$now = new Date(DATE_NOW, TIMEZONE_AUTO);

$id = retrieve(GET, 'id', 0, TINTEGER);
$id_post = retrieve(POST, 'id', 0, TINTEGER);

if (!empty($id) || !empty($id_post))
{
	$id_tmp = !empty($id) ? $id : $id_post;
	
	$bug_exist = $Sql->query_array(PREFIX . 'bugtracker', '*', "WHERE id = '" . $id_tmp . "'", __LINE__, __FILE__);
	if (empty($bug_exist))
		AppContext::get_response()->redirect(HOST . DIR . '/bugtracker/bugtracker.php?error=unexist_bug#message_helper');
}

if ( !empty($_POST['valid_add']) )
{
	//checking authorization
	if (!$auth_create)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	$title = retrieve(POST, 'title', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
	$type = $display_types ? retrieve(POST, 'type', '') : $BUGS_CONFIG['types'][0];
	$category = $display_categories ? retrieve(POST, 'category', '') : $BUGS_CONFIG['categories'][0];
	$severity = $auth_create_advanced ? retrieve(POST, 'severity', '') : 'minor';
	$priority = $auth_create_advanced ? retrieve(POST, 'priority', '') : 'normal';
	$detected_in = $display_versions ? retrieve(POST, 'detected_in', '') : $BUGS_CONFIG['versions'][0];
	$reproductible = retrieve(POST, 'reproductible', '', TBOOL);
	$reproduction_method = retrieve(POST, 'reproduction_method', '', TSTRING_PARSE);
	
	$category_needed = false;
	if ($display_categories && empty($category))
		$category_needed = true;
	
	if (!empty($title) && !empty($contents) && !$category_needed)
	{
		// Creation du bug
		$Sql->query_inject("INSERT INTO " . PREFIX . "bugtracker (title, contents, author_id, submit_date, status, type, category, severity, priority, detected_in, reproductible, reproduction_method)
		VALUES('" . $title . "', '" . $contents . "', '" . $User->get_id() . "', '" . $now->get_timestamp() . "', 'new', '" . $type . "', '" . $category . "', '" . $severity . "', '" . $priority . "', '" . $detected_in . "', '" . $reproductible . "', '" . $reproduction_method . "')", __LINE__, __FILE__);
		
		$Cache->Generate_module_file('bugtracker');
		
		AppContext::get_response()->redirect(HOST . DIR . '/bugtracker/bugtracker.php');
	}
	else
	AppContext::get_response()->redirect(HOST . DIR . '/bugtracker/bugtracker.php?error=incomplete#message_helper');
}
else if (isset($_GET['add'])) // ajout d'un bug
{	
	//checking authorization
	if (!$auth_create)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	$Template->set_filenames(array(
		'bugtracker' => 'bugtracker/bugtracker.tpl'
	));
	
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');
	
	$Template->assign_vars(array(
		'C_DISPLAY_TYPES' 		=> $display_types,
		'C_DISPLAY_CATEGORIES' 	=> $display_categories,
		'C_DISPLAY_VERSIONS' 	=> $display_versions,
		'C_DISPLAY_ADVANCED' 	=> $auth_create_advanced ? true : false,
		'L_ADD_BUG'				=> $LANG['bugs.titles.add_bug'],
		'L_TITLE'				=> $LANG['bugs.labels.fields.title'],
		'L_CONTENT'				=> $LANG['bugs.labels.fields.contents'],
		'L_REQUIRE' 			=> $LANG['require'],
		'L_REQUIRE_TITLE' 		=> $LANG['require_title'],
		'L_REQUIRE_CONTENT'		=> $LANG['require_text'],
		'L_REQUIRE_CATEGORY'	=> $LANG['bugs.notice.require_category'],
		'L_TYPE'				=> $LANG['bugs.labels.fields.type'],
		'L_CATEGORY'			=> $LANG['bugs.labels.fields.category'],
		'L_PRIORITY'			=> $LANG['bugs.labels.fields.priority'],
		'L_SEVERITY'			=> $LANG['bugs.labels.fields.severity'],
		'L_DETECTED_IN'			=> $LANG['bugs.labels.fields.detected_in'],
		'L_REPRODUCTIBLE'		=> $LANG['bugs.labels.fields.reproductible'],
		'L_REPRODUCTION_METHOD'	=> $LANG['bugs.labels.fields.reproduction_method'],
		'L_SUBMIT' 				=> $LANG['submit'],
		'L_PREVIEW' 			=> $LANG['preview'],
		'L_RESET' 				=> $LANG['reset'],
		'L_YES' 				=> $LANG['yes'],
		'L_NO'	 				=> $LANG['no'],
		'CONTENTS'	 			=> $LANG['bugs.explain.default_content'],
		'REPRODUCTIBLE_ENABLED' => 'checked="checked"',
		'REPRODUCTIBLE_DISABLED'=> '',
		'REPRODUCTION_METHOD' 	=> '',
		'KERNEL_EDITOR'			=> $editor->display(),
		'TOKEN'					=> $Session->get_token()
	));
	
	$Template->assign_block_vars('add', array());
	
	// Gravités
	foreach ($severity as $s)
	{
		$Template->assign_block_vars('select_severity', array(
			'SEVERITY' => '<option value="' . $s . '">' . $LANG['bugs.severity.' . $s] . '</option>'
		));
	}
	
	// Priorités
	foreach ($priority as $p)
	{
		$selected = ($p == 'normal') ? 'selected="selected"' : '';
		$Template->assign_block_vars('select_priority', array(
			'PRIORITY' => '<option value="' . $p . '" ' . $selected . '>' . $LANG['bugs.priority.' . $p] . '</option>'
		));
	}
	
	//Types
	foreach ($BUGS_CONFIG['types'] as $type)
	{
		$Template->assign_block_vars('select_type', array(
			'TYPE' => '<option value="' . $type . '">' . stripslashes($type) . '</option>'
		));
	}
	
	//Categories
	$Template->assign_block_vars('select_category', array(
		'CATEGORY' => '<option value=""></option>'
	));
	foreach ($BUGS_CONFIG['categories'] as $category)
	{
		$Template->assign_block_vars('select_category', array(
			'CATEGORY' => '<option value="' . $category . '">' . stripslashes($category) . '</option>'
		));
	}
	
	//Detected in
	$Template->assign_block_vars('select_detected_in', array(
		'VERSION' => '<option value=""></option>'
	));
	foreach ($BUGS_CONFIG['versions'] as $version)
	{
		if ($version['detected_in'] == true)
		{
			$Template->assign_block_vars('select_detected_in', array(
				'VERSION' => '<option value="' . $version['name'] . '">' . stripslashes($version['name']) . '</option>'
			));
		}

	}
	
	// Gestion erreur.
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
}
else if (isset($_GET['delete']) && is_numeric($id)) //Suppression du bug.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//checking authorization
	if (!$User->is_admin())
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	//On supprime dans la table bugtracker_history.
	$Sql->query_inject("DELETE FROM " . PREFIX . "bugtracker_history WHERE bug_id = '" . $id . "'", __LINE__, __FILE__);

	//On supprime dans la bdd.
	$Sql->query_inject("DELETE FROM " . PREFIX . "bugtracker WHERE id = '" . $id . "'", __LINE__, __FILE__);

	//On supprimes les éventuels commentaires associés.
	CommentsService::delete_comments_topic_module('bugtracker', $id);

	//Mise à jour de la liste des bugs dans le cache de la configuration.
	$Cache->Generate_module_file('bugtracker');

	AppContext::get_response()->redirect(HOST . SCRIPT);
}
else if (!empty($_POST['valid_edit']) && is_numeric($id_post))
{
	//checking authorization
	if (!$auth_create)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	$title = retrieve(POST, 'title', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
	$category = retrieve(POST, 'category', '');
	$status = retrieve(POST, 'status', '');
	$assigned_to = retrieve(POST, 'assigned_to', '');
	$reproductible = retrieve(POST, 'reproductible', '', TBOOL);
	$reproduction_method = retrieve(POST, 'reproduction_method', '', TSTRING_PARSE);
	
	$category_needed = false;
	if ($display_categories && empty($category))
		$category_needed = true;
	
	//On met à jour
	if (!empty($title) && !empty($contents) && !$category_needed)
	{
		$old_values = $Sql->query_array(PREFIX . 'bugtracker b', '*', "
		JOIN " . DB_TABLE_MEMBER . " a ON (a.user_id = b.author_id)
		WHERE b.id = '" . $id_post . "'", __LINE__, __FILE__);
		
		$type = $display_types ? retrieve(POST, 'type', '') : $old_values['type'];
		$category = $display_categories ? retrieve(POST, 'category', '') : $old_values['category'];
		$detected_in = $display_versions ? retrieve(POST, 'detected_in', '') : $old_values['detected_in'];
		$fixed_in = $display_versions ? retrieve(POST, 'fixed_in', '') : $old_values['fixed_in'];
		
		$severity = $auth_create_advanced ? retrieve(POST, 'severity', '') : $old_values['severity'];
		$priority = $auth_create_advanced ? retrieve(POST, 'priority', '') : $old_values['priority'];
		
		//Champs supplémentaires pour l'administrateur
		if ($User->is_admin() || $auth_moderate || (!empty($old_values['assigned_to_id']) && $User->get_attribute('user_id') == $old_values['assigned_to_id']))
		{
			if ($status == 'assigned' && empty($assigned_to)) // Erreur si le statut est "Assigné" et aucun utilisateur n'est sélectionné
				AppContext::get_response()->redirect(PATH_TO_ROOT . '/bugtracker/bugtracker.php?edit=true&id=' . $id_post . '&error=no_user_assigned#message_helper');
			
			// if ($status == 'closed' && empty($fixed_in)) // Erreur si le statut est "Fermé" et aucune version de correction n'est sélectionnée
				// AppContext::get_response()->redirect(PATH_TO_ROOT . '/bugtracker/bugtracker.php?edit=true&id=' . $id_post . '&error=no_closed_version#message_helper');
			
			$assigned_to_id = (!empty($assigned_to)) ? $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $assigned_to . "'", __LINE__, __FILE__) : 0;
			
			if (!empty($assigned_to) && empty($assigned_to_id)) // Erreur si l'utilisateur sélectionné n'existe pas
				AppContext::get_response()->redirect(PATH_TO_ROOT . '/bugtracker/bugtracker.php?edit=true&id=' . $id_post . '&error=unexist_user#message_helper');
			
			$new_values = array(
				'title'					=> $title,
				'contents' 				=> $contents,
				'type' 					=> $type,
				'category' 				=> $category,
				'severity' 				=> $severity,
				'priority' 				=> $priority,		
				'detected_in' 			=> $detected_in,
				'reproductible' 		=> $reproductible,
				'reproduction_method' 	=> $reproduction_method,
				'status' 				=> $status,
				'assigned_to_id'		=> $assigned_to_id,		
				'fixed_in' 				=> $fixed_in
			);
			
			$fields = array('title', 'contents', 'type', 'category', 'severity', 'priority', 'detected_in', 'reproductible', 'reproduction_method', 'status', 'fixed_in', 'assigned_to_id');
			
			$modification = false;
			foreach ($fields as $field)
			{
				if ($old_values[$field] != $new_values[$field])
					$modification = true;
			}
			if ($modification == false)
				AppContext::get_response()->redirect(PATH_TO_ROOT . '/bugtracker/bugtracker.php?edit=true&id=' . $id_post);
			
			$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET title = '" . $title . "', contents = '" . $contents . "', status = '" . $status . "', type = '" . $type . "', category = '" . $category . "', severity = '" . $severity . "', priority = '" . $priority . "', assigned_to_id = '" . $assigned_to_id . "', detected_in = '" . $detected_in . "', fixed_in = '" . $fixed_in . "', reproductible = '" . $reproductible . "', reproduction_method = '" . $reproduction_method . "'
			WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
			
			// Envoi d'un MP à l'utilisateur auquel a été affecté le bug
			if (!empty($assigned_to_id) && ($old_values['assigned_to_id'] != $assigned_to_id) && ($User->get_attribute('user_id') != $assigned_to_id)) {
				$Privatemsg = new PrivateMsg();
				$Privatemsg->start_conversation(
					$assigned_to_id, 
					sprintf($LANG['bugs.pm.assigned.title'], $LANG['bugs.module_title'], $id_post), 
					sprintf($LANG['bugs.pm.assigned.contents'], '[url]' . HOST . DIR . '/bugtracker/bugtracker.php?view=true&id=' . $id_post . '[/url]'), 
					'-1', 
					SYSTEM_PM
				);
			}
		}
		else
		{
			$new_values = array(
				'title'					=> $title,
				'contents' 				=> $contents,
				'type' 					=> $type,
				'category' 				=> $category,
				'severity' 				=> $severity,
				'priority' 				=> $priority,
				'detected_in' 			=> $detected_in,	
				'reproductible' 		=> $reproductible,			
				'reproduction_method' 	=> $reproduction_method
			);
			
			$fields = array('title', 'contents', 'type', 'category', 'severity', 'priority', 'detected_in', 'reproductible', 'reproduction_method');
			
			$modification = false;
			foreach ($fields as $field)
			{
				if ($old_values[$field] != $new_values[$field])
					$modification = true;
			}
			if ($modification == false)
				AppContext::get_response()->redirect(PATH_TO_ROOT . '/bugtracker/bugtracker.php?edit=true&id=' . $id_post);
			
			$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET title = '" . $title . "', contents = '" . $contents . "', type = '" . $type . "', category = '" . $category . "', severity = '" . $severity . "', priority = '" . $priority . "', detected_in = '" . $detected_in . "', reproductible = '" . $reproductible . "'
			WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
		}
		
		foreach ($fields as $field)
		{
			if ($field == 'assigned_to_id')
			{
				$result_assigned = $Sql->query_array(DB_TABLE_MEMBER, "login, level", "WHERE user_id = " . $$field, __LINE__, __FILE__);
				$comment = $LANG['bugs.labels.fields.assigned_to_id'] . ' <a href="' . PATH_TO_ROOT . '/member/member.php?id=' . $$field . '" class="' . level_to_status($result_assigned['level']) . '">' . $result_assigned['login'] . '</a>';
			}
			else if ($field == 'title' || $field == 'severity' || $field == 'priority' || $field == 'type' || $field == 'category' || $field == 'detected_in' || $field == 'fixed_in')
			{
				$old_values[$field] = addslashes($old_values[$field]);
			}
			else if ($field == 'contents')
			{
				$old_values[$field] = '';
				$new_values[$field] = '';
				$comment = $LANG['bugs.notice.contents_update'];
			}
			else if ($field == 'reproduction_method')
			{
				$old_values[$field] = '';
				$new_values[$field] = '';
				$comment = $LANG['bugs.notice.reproduction_method_update'];
			}
			else $comment = '';
			
			if ($old_values[$field] != $new_values[$field])
			{
				$Sql->query_inject("INSERT INTO " . PREFIX . "bugtracker_history (bug_id, updater_id, update_date, updated_field, old_value, new_value, change_comment)
				VALUES('" . $id_post . "', '" . $User->get_id() . "', '" . $now->get_timestamp() . "', '" . $field . "', '" . $old_values[$field] . "', '" . $new_values[$field] . "', '" . $comment . "')", __LINE__, __FILE__);
			}
		}
		
		###### Régénération du cache #######
		$Cache->Generate_module_file('bugtracker');

		AppContext::get_response()->redirect(HOST . DIR . '/bugtracker/bugtracker.php?error=edit_success#message_helper');
	}
	else
	AppContext::get_response()->redirect(HOST . DIR . '/bugtracker/bugtracker.php?edit=true&id= ' . $id_post . '&error=incomplete#message_helper');
}
else if (isset($_GET['edit']) && is_numeric($id)) // edition d'un bug
{
	//checking authorization
	if (!$auth_create)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	if (empty($id))
		AppContext::get_response()->redirect(HOST . DIR . '/bugtracker/bugtracker.php?error=unexist_bug#message_helper');
	
	$Template->set_filenames(array(
		'bugtracker' => 'bugtracker/bugtracker.tpl'
	));
	
	//Récupération de l'id de l'auteur du bug
	$author_id = $Sql->query("SELECT author_id FROM " . PREFIX . "bugtracker WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	if ($author_id == '-1')
	{
		$result = $Sql->query_array(PREFIX . 'bugtracker', '*', "
		WHERE id = '" . $id . "'", __LINE__, __FILE__);
	}
	else
	{
		$result = $Sql->query_array(PREFIX . 'bugtracker b', '*', "
		JOIN " . DB_TABLE_MEMBER . " a ON (a.user_id = b.author_id)
		WHERE b.id = '" . $id . "'", __LINE__, __FILE__);
	}
	
	//Champs supplémentaires pour l'administrateur
	if ($User->is_admin() || $auth_moderate || (!empty($result['assigned_to_id']) && $User->get_attribute('user_id') == $result['assigned_to_id']))
	{
		$assigned_to = !empty($result['assigned_to_id']) ? $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $result['assigned_to_id'] . "'", __LINE__, __FILE__) : '';
		$Template->assign_block_vars('edit', array(
			'C_IS_ASSIGNED'				=> true,
			'ID' 						=> $id,
			'TITLE' 					=> $result['title'],
			'CONTENTS' 					=> FormatingHelper::unparse($result['contents']),
			'REPRODUCTIBLE_ENABLED' 	=> ($result['reproductible']) ? 'checked="checked"' : '',
			'REPRODUCTIBLE_DISABLED' 	=> (!$result['reproductible']) ? 'checked="checked"' : '',
			'REPRODUCTION_METHOD' 		=> FormatingHelper::unparse($result['reproduction_method']),
			'AUTHOR' 					=> !empty($result['login']) ? '<a href="' . PATH_TO_ROOT . '/member/member.php?id=' . $result['user_id'] . '" class="' . level_to_status($result['level']) . '">' . $result['login'] . '</a>': $LANG['guest'],
			'ASSIGNED_TO'				=> $assigned_to,
			'DATE' 						=> gmdate_format('date_format', $result['submit_date'])
		));
	}
	else
	{
		$Template->assign_block_vars('edit', array(
			'ID' 						=> $id,
			'TITLE' 					=> $result['title'],
			'CONTENTS' 					=> FormatingHelper::unparse($result['contents']),
			'REPRODUCTIBLE_ENABLED' 	=> ($result['reproductible']) ? 'checked="checked"' : '',
			'REPRODUCTIBLE_DISABLED' 	=> (!$result['reproductible']) ? 'checked="checked"' : ''
		));
	}
	
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');
	
	$Template->assign_vars(array(
		'C_DISPLAY_TYPES' 		=> $display_types,
		'C_DISPLAY_CATEGORIES' 	=> $display_categories,
		'C_DISPLAY_VERSIONS' 	=> $display_versions,
		'C_DISPLAY_ADVANCED' 	=> $auth_create_advanced ? true : false,
		'C_REPRODUCTIBLE' 		=> ($result['reproductible'] == true) ? true : false,
		'L_EDIT_BUG'			=> $LANG['bugs.titles.edit_bug'],
		'L_TITLE'				=> $LANG['bugs.labels.fields.title'],
		'L_CONTENT'				=> $LANG['bugs.labels.fields.contents'],
		'L_REQUIRE' 			=> $LANG['require'],
		'L_REQUIRE_LOGIN' 		=> $LANG['bugs.notice.require_login'],
		'L_REQUIRE_TITLE' 		=> $LANG['require_title'],
		'L_REQUIRE_CONTENT'		=> $LANG['require_text'],
		'L_REQUIRE_CATEGORY'	=> $LANG['bugs.notice.require_category'],
		'L_STATUS'				=> $LANG['bugs.labels.fields.status'],
		'L_TYPE'				=> $LANG['bugs.labels.fields.type'],
		'L_CATEGORY'			=> $LANG['bugs.labels.fields.category'],
		'L_AUTHOR'				=> $LANG['bugs.labels.fields.author_id'],
		'L_ASSIGNED_TO'			=> $LANG['bugs.labels.fields.assigned_to_id'],
		'L_DATE'				=> $LANG['bugs.labels.fields.submit_date'],
		'L_PRIORITY'			=> $LANG['bugs.labels.fields.priority'],
		'L_SEVERITY'			=> $LANG['bugs.labels.fields.severity'],
		'L_DETECTED_IN'			=> $LANG['bugs.labels.fields.detected_in'],
		'L_FIXED_IN'			=> $LANG['bugs.labels.fields.fixed_in'],
		'L_REPRODUCTIBLE'		=> $LANG['bugs.labels.fields.reproductible'],
		'L_REPRODUCTION_METHOD'	=> $LANG['bugs.labels.fields.reproduction_method'],
		'L_SEARCH' 				=> $LANG['search'],
		'L_UPDATE' 				=> $LANG['update'],
		'L_PREVIEW' 			=> $LANG['preview'],
		'L_RESET' 				=> $LANG['reset'],
		'L_YES' 				=> $LANG['yes'],
		'L_NO'	 				=> $LANG['no'],
		'L_JOKER' 				=> $LANG['bugs.notice.joker'],
		'KERNEL_EDITOR' 		=> $editor->display(),
		'TOKEN'					=> $Session->get_token()
	));
	
	//Types
	$selected = (empty($result['type'])) ? 'selected="selected"' : '';
	$Template->assign_block_vars('edit.select_type', array(
		'TYPE' => '<option value="" ' . $selected . '></option>'
	));
	foreach ($BUGS_CONFIG['types'] as $type)
	{
		$selected = ($result['type'] == $type) ? 'selected="selected"' : '';
		$Template->assign_block_vars('edit.select_type', array(
			'TYPE' => '<option value="' . $type . '" ' . $selected . '>' . stripslashes($type) . '</option>'
		));
	}
	
	//Categories
	$selected = (empty($result['category'])) ? 'selected="selected"' : '';
	$Template->assign_block_vars('edit.select_category', array(
		'CATEGORY' => '<option value="" ' . $selected . '></option>'
	));
	foreach ($BUGS_CONFIG['categories'] as $category)
	{
		$selected = ($result['category'] == $category) ? 'selected="selected"' : '';
		$Template->assign_block_vars('edit.select_category', array(
			'CATEGORY' => '<option value="' . $category . '" ' . $selected . '>' . stripslashes($category) . '</option>'
		));
	}
	
	//Statut
	foreach ($status_list as $s)
	{
		$selected = ($result['status'] == $s) ? 'selected="selected"' : '';
		$Template->assign_block_vars('edit.select_status', array(
			'STATUS' => '<option value="' . $s . '" ' . $selected . '>' . $LANG['bugs.status.' . $s] . '</option>'
		));
	}
	
	//Gravités
	foreach ($severity as $s)
	{
		$selected = ($result['severity'] == $s) ? 'selected="selected"' : '';
		$Template->assign_block_vars('edit.select_severity', array(
			'SEVERITY' => '<option value="' . $s . '" ' . $selected . '>' . $LANG['bugs.severity.' . $s] . '</option>'
		));
	}
	
	//Priorités
	foreach ($priority as $p)
	{
		$selected = ($result['priority'] == $p) ? 'selected="selected"' : '';
		$Template->assign_block_vars('edit.select_priority', array(
			'PRIORITY' => '<option value="' . $p . '" ' . $selected . '>' . $LANG['bugs.priority.' . $p] . '</option>'
		));
	}
	
	//Detected in
	$selected = ($result['detected_in'] == '') ? 'selected="selected"' : '';
	$Template->assign_block_vars('edit.select_detected_in', array(
		'VERSION' => '<option value="" ' . $selected . '></option>'
	));
	foreach ($BUGS_CONFIG['versions'] as $version)
	{
		if ($version['detected_in'] == true)
		{
			$selected = ($result['detected_in'] == $version['name']) ? 'selected="selected"' : '';
			$Template->assign_block_vars('edit.select_detected_in', array(
				'VERSION' => '<option value="' . $version['name'] . '" ' . $selected . '>' . stripslashes($version['name']) . '</option>'
			));
		}

	}
	
	//Fixed in
	$selected = ($result['fixed_in'] == '') ? 'selected="selected"' : '';
	$Template->assign_block_vars('edit.select_fixed_in', array(
		'VERSION' => '<option value="" ' . $selected . '></option>'
	));
	foreach ($BUGS_CONFIG['versions'] as $version)
	{
		if ($version['fixed_in'] == true)
		{
			$selected = ($result['fixed_in'] == $version['name']) ? 'selected="selected"' : '';
			$Template->assign_block_vars('edit.select_fixed_in', array(
				'VERSION' => '<option value="' . $version['name'] . '" ' . $selected . '>' . stripslashes($version['name']) . '</option>'
			));
		}
	}
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	switch ($get_error)
	{
		case 'incomplete':
		$errstr = $LANG['e_incomplete'];
		$errtyp = E_USER_NOTICE;
		break;
		case 'unexist_user':
		$errstr = $LANG['e_unexist_user'];
		$errtyp = E_USER_WARNING;
		break;
		case 'no_user_assigned':
		$errstr = $LANG['bugs.error.e_no_user_assigned'];
		$errtyp = E_USER_WARNING;
		break;
		case 'no_closed_version':
		$errstr = $LANG['bugs.error.e_no_closed_version'];
		$errtyp = E_USER_WARNING;
		break;
		default:
		$errstr = '';
		$errtyp = E_USER_NOTICE;
	}
	if (!empty($errstr))
		$Template->put('message_helper', MessageHelper::display($errstr, $errtyp));
}
else if (isset($_GET['history']) && is_numeric($id)) // Affichage de l'historique du bug
{
	//checking authorization
	if (!$auth_read)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	if (empty($id))
		AppContext::get_response()->redirect(HOST . DIR . '/bugtracker/bugtracker.php?error=unexist_bug#message_helper');
	
	$Template->set_filenames(array(
		'bugtracker' => 'bugtracker/bugtracker.tpl'
	));
	
	$Template->assign_block_vars('history', array(
		'ID'			=> $id
	));
	
	//Nombre de lignes concernant ce bug dans l'historique..
	$nbr_history = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "bugtracker_history WHERE bug_id='" . $id . "'", __LINE__, __FILE__);
	
	$result = $Sql->query_while("SELECT *
	FROM " . PREFIX . "bugtracker b
	JOIN " . PREFIX . "bugtracker_history bh ON (bh.bug_id = b.id)
	JOIN " . DB_TABLE_MEMBER . " a ON (a.user_id = bh.updater_id)
	WHERE b.id = '" . $id . "'
	ORDER BY update_date DESC", __LINE__, __FILE__);
	
	while ($row = $Sql->fetch_assoc($result))
	{
		switch ($row['updated_field']) //Coloration du membre suivant son level d'autorisation. 
		{ 		
			case 'title':
			$old_value = $row['old_value'];
			$new_value = $row['new_value'];
			break;

			case 'priority':
			$old_value = $LANG['bugs.priority.' . $row['old_value']];
			$new_value = $LANG['bugs.priority.' . $row['new_value']];
			break;
			
			case 'severity':
			$old_value = $LANG['bugs.severity.' . $row['old_value']];
			$new_value = $LANG['bugs.severity.' . $row['new_value']];
			break;
			
			case 'status': 
			$old_value = $LANG['bugs.status.' . $row['old_value']];
			$new_value = $LANG['bugs.status.' . $row['new_value']];
			break;
			
			case 'reproductible': 
			$old_value = ($row['old_value'] == true) ? $LANG['yes'] : $LANG['no'];
			$new_value = ($row['new_value'] == true) ? $LANG['yes'] : $LANG['no'];
			break;
			
			case 'assigned_to_id': 
			$old_user = !empty($row['old_value']) ? $Sql->query_array(DB_TABLE_MEMBER, 'login, level', "WHERE user_id = '" . $row['old_value'] . "'", __LINE__, __FILE__) : '';
			$new_user = !empty($row['new_value']) ? $Sql->query_array(DB_TABLE_MEMBER, 'login, level', "WHERE user_id = '" . $row['new_value'] . "'", __LINE__, __FILE__) : '';
			$old_value = !empty($old_user) ? '<a href="' . PATH_TO_ROOT . '/member/member.php?id=' . $row['old_value'] . '" class="' . level_to_status($old_user['level']) . '">' . $old_user['login'] . '</a>' : $LANG['bugs.notice.no_one'];
			$new_value = !empty($new_user) ? '<a href="' . PATH_TO_ROOT . '/member/member.php?id=' . $row['new_value'] . '" class="' . level_to_status($new_user['level']) . '">' . $new_user['login'] . '</a>' : $LANG['bugs.notice.no_one'];
			break;
			
			default:
			$old_value = $row['old_value'];
			$new_value = $row['new_value'];
		}
		
		$Template->assign_block_vars('history.bug', array(
			'TOKEN' 		=> $Session->get_token(),
			'UPDATED_FIELD'	=> $LANG['bugs.labels.fields.' . $row['updated_field']],
			'OLD_VALUE'		=> stripslashes($old_value),
			'NEW_VALUE'		=> stripslashes($new_value),
			'COMMENT'		=> $row['change_comment'],
			'UPDATER' 		=> !empty($row['login']) ? '<a href="' . PATH_TO_ROOT . '/member/member.php?id=' . $row['user_id'] . '" class="' . level_to_status($row['level']) . '">' . $row['login'] . '</a>': $LANG['guest'],
			'DATE' 			=> gmdate_format('date_format', $row['update_date'])
		));
	}
	$Sql->query_close($result);
	
	$Template->assign_vars(array(
		'C_NO_HISTORY' 			=> empty($nbr_history) ? true : false,
		'L_HISTORY_BUG'			=> $LANG['bugs.titles.history_bug'],
		'L_NO_HISTORY'			=> $LANG['bugs.notice.no_history'],
		'L_ID' 					=> $LANG['bugs.labels.fields.id'],
		'L_UPDATED_FIELD' 		=> $LANG['bugs.labels.fields.updated_field'],
		'L_OLD_VALUE'			=> $LANG['bugs.labels.fields.old_value'],
		'L_NEW_VALUE'			=> $LANG['bugs.labels.fields.new_value'],
		'L_COMMENT'				=> $LANG['bugs.labels.fields.change_comment'],
		'L_UPDATER'				=> $LANG['bugs.labels.fields.updater_id'],
		'L_DATE'				=> $LANG['bugs.labels.fields.update_date'],
		'ID' 					=> $id,
	));
}
else if (isset($_GET['view']) && is_numeric($id)) // Visualisation d'une fiche Bug
{
	//checking authorization
	if (!$auth_read)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	if (empty($id))
		AppContext::get_response()->redirect(HOST . DIR . '/bugtracker/bugtracker.php?error=unexist_bug#message_helper');
	
  	$Template->set_filenames(array(
		'bugtracker' => 'bugtracker/bugtracker.tpl'
	));
	
	//Récupération de l'id de l'auteur du bug
	$author_id = $Sql->query("SELECT author_id FROM " . PREFIX . "bugtracker WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	if ($author_id == '-1')
	{
		$result = $Sql->query_array(PREFIX . 'bugtracker', '*', "
		WHERE id = '" . $id . "'", __LINE__, __FILE__);
	}
	else
	{
		$result = $Sql->query_array(PREFIX . 'bugtracker b', '*', "
		JOIN " . DB_TABLE_MEMBER . " a ON (a.user_id = b.author_id)
		WHERE b.id = '" . $id . "'", __LINE__, __FILE__);
	}
	
	if (($User->get_id() == $result['author_id'] && $result['author_id'] != '-1') || $User->is_admin() || (!empty($result['assigned_to_id']) && $User->get_attribute('user_id') == $result['assigned_to_id']))
	{
		$Template->assign_vars(array(
			'C_EDIT_BUG'	 		=> true,
			'L_UPDATE'	 			=> $LANG['update']
		));
	}
	
	if ($User->is_admin() || $auth_moderate)
	{
		$Template->assign_vars(array(
			'C_DELETE_BUG'		=> true,
			'L_CONFIRM_DEL_BUG' => $LANG['bugs.actions.confirm.del_bug'],
			'L_DELETE' 			=> $LANG['delete']
		));
	}
	
	$Template->assign_vars(array(
		'C_EMPTY_TYPES' 		=> empty($BUGS_CONFIG['types']) ? true : false,
		'C_EMPTY_CATEGORIES' 	=> empty($BUGS_CONFIG['categories']) ? true : false,
		'C_EMPTY_VERSIONS' 		=> empty($BUGS_CONFIG['versions']) ? true : false,
		'C_REPRODUCTIBLE' 		=> ($result['reproductible'] == true && !empty($result['reproduction_method'])) ? true : false,
		'L_VIEW_BUG' 			=> $LANG['bugs.titles.view_bug'],
		'L_BUG_INFOS' 			=> $LANG['bugs.titles.bugs_infos'],
		'L_BUG_TREATMENT_STATE'	=> $LANG['bugs.titles.bugs_treatment_state'],
		'L_ID' 					=> $LANG['bugs.labels.fields.id'],
		'L_DETECTED_IN'			=> $LANG['bugs.labels.fields.detected_in'],
		'L_FIXED_IN'			=> $LANG['bugs.labels.fields.fixed_in'],
		'L_STATUS'				=> $LANG['bugs.labels.fields.status'],
		'L_TYPE'				=> $LANG['bugs.labels.fields.type'],
		'L_CATEGORY'			=> $LANG['bugs.labels.fields.category'],
		'L_SEVERITY'			=> $LANG['bugs.labels.fields.severity'],
		'L_PRIORITY'			=> $LANG['bugs.labels.fields.priority'],
		'L_REPRODUCTIBLE'		=> $LANG['bugs.labels.fields.reproductible'],
		'L_REPRODUCTION_METHOD'	=> $LANG['bugs.labels.fields.reproduction_method'],
		'L_ASSIGNED_TO'			=> $LANG['bugs.labels.fields.assigned_to_id'],
		'L_DETECTED_BY' 		=> $LANG['bugs.labels.fields.author_id'],
		'C_HISTORY_BUG'			=> true,
		'L_HISTORY'	 			=> $LANG['bugs.actions.history'],
		'L_ON' 					=> $LANG['on'],
		'C_COM' 				=> ($BUGS_CONFIG['activ_com'] == true) ? true : false,
		'U_COM' 				=> '<a href="' . PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?view=true&amp;id=' . $id . '&amp;com=0', '-' . $id . '.php?com=0') .'">'. CommentsService::get_number_and_lang_comments('bugtracker', $id) . '</a>'
	));
	
	if (!empty($result['assigned_to_id'])) {
		$result_assigned = $Sql->query_array(DB_TABLE_MEMBER, "login, level", "WHERE user_id = " . $result['assigned_to_id'], __LINE__, __FILE__);
		$user_assigned = '<a href="' . PATH_TO_ROOT . '/member/member.php?id=' . $result['assigned_to_id'] . '" class="' . level_to_status($result_assigned['level']) . '">' . $result_assigned['login'] . '</a>';
	}
	else
		$user_assigned = $LANG['bugs.notice.no_one'];
		
	$Template->assign_block_vars('view', array(
		'TOKEN' 				=> $Session->get_token(),
		'ID' 					=> $id,
		'TITLE' 				=> $result['title'],
		'CONTENTS' 				=> FormatingHelper::second_parse($result['contents']),
		'STATUS' 				=> $LANG['bugs.status.' . $result['status']],
		'TYPE'					=> !empty($result['type']) ? stripslashes($result['type']) : $LANG['bugs.notice.none'],
		'CATEGORY'				=> !empty($result['category']) ? stripslashes($result['category']) : $LANG['bugs.notice.none_e'],
		'PRIORITY' 				=> $LANG['bugs.priority.' . $result['priority']],
		'SEVERITY' 				=> $LANG['bugs.severity.' . $result['severity']],
		'REPRODUCTIBLE'			=> ($result['reproductible'] == true) ? $LANG['yes'] : $LANG['no'],
		'REPRODUCTION_METHOD'	=> FormatingHelper::second_parse($result['reproduction_method']),
		'DETECTED_IN' 			=> !empty($result['detected_in']) ? $result['detected_in'] : $LANG['bugs.notice.not_defined'],
		'FIXED_IN' 				=> !empty($result['fixed_in']) ? $result['fixed_in'] : $LANG['bugs.notice.not_defined'],
		'USER_ASSIGNED'			=> $user_assigned,
		'AUTHOR' 				=> !empty($result['login']) ? '<a href="' . PATH_TO_ROOT . '/member/member.php?id=' . $result['user_id'] . '" class="' . level_to_status($result['level']) . '">' . $result['login'] . '</a>': $LANG['guest'],
		'SUBMIT_DATE'			=> gmdate_format('date_format', $result['submit_date'])
	));
	
	//Affichage des commentaires
	if (isset($_GET['com']) && is_numeric($id))
	{
		$comments_topic = new CommentsTopic();
		$comments_topic->set_module_id('bugtracker');
		$comments_topic->set_id_in_module($id);
		$comments_topic->set_url(new Url('/bugtracker/bugtracker?view=true&amp;id=' . $id . '&com=0'));
		$Template->put_all(array(
			'COMMENTS' => CommentsService::display($comments_topic)->render()
		));
	}      
}
else // Affichage de la liste
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module = $modulesLoader->get_provider('bugtracker');
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
}

$Template->pparse('bugtracker');

include_once('../kernel/footer.php');
?>