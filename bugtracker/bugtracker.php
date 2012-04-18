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

$types = $bugtracker_config->get_types();
$categories = $bugtracker_config->get_categories();
$versions = $bugtracker_config->get_versions();
$authorizations = $bugtracker_config->get_authorizations();

$auth_read = $User->check_auth($authorizations, BUG_READ_AUTH_BIT);
$auth_create = $User->check_auth($authorizations, BUG_CREATE_AUTH_BIT);
$auth_create_advanced = $User->check_auth($authorizations, BUG_CREATE_ADVANCED_AUTH_BIT);
$display_categories = sizeof($categories) > 1 ? true : false;

$id = retrieve(GET, 'id', 0, TINTEGER);
$id_post = retrieve(POST, 'id', 0, TINTEGER);

if ( !empty($_POST['valid_add']) )
{
	//checking authorization
	if (!$auth_create)
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}
	
	import('util/date');
	$now = new Date(DATE_NOW, TIMEZONE_AUTO);
	
	$title = retrieve(POST, 'title', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
	$type = (sizeof($types) == 1) ? $types[0] : retrieve(POST, 'type', '');
	$category = (sizeof($categories) == 1) ? $categories[0] : retrieve(POST, 'category', '');
	$severity = $auth_create_advanced ? retrieve(POST, 'severity', '') : 'minor';
	$priority = $auth_create_advanced ? retrieve(POST, 'priority', '') : 'normal';
	$detected_in = (sizeof($versions) == 1) ? $versions[0] : retrieve(POST, 'detected_in', '');
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
		
		redirect(HOST . DIR . '/bugtracker/bugtracker.php');
	}
	else
	redirect(HOST . DIR . '/bugtracker/bugtracker.php?error=incomplete#errorh');
}
else if (isset($_GET['add'])) // ajout d'un bug
{	
	//checking authorization
	if (!$auth_create)
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}
	
	$Template->set_filenames(array(
		'bugtracker' => 'bugtracker/bugtracker.tpl'
	));
	
	$Template->assign_vars(array(
		'C_DISPLAY_TYPES' 		=> sizeof($types) > 1 ? true : false,
		'C_DISPLAY_CATEGORIES' 	=> $display_categories,
		'C_DISPLAY_VERSIONS' 	=> sizeof($versions) > 1 ? true : false,
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
		'REPRODUCTIBLE_ENABLED' => 'checked="checked"',
		'REPRODUCTIBLE_DISABLED'=> '',
		'REPRODUCTION_METHOD' 	=> '',
		'KERNEL_EDITOR' 		=> display_editor(),
		'KERNEL_EDITOR_EXTEND' 	=> display_editor('extend_contents'),
		'TOKEN'					=> $Session->get_token()
	));
	
	$Template->assign_block_vars('add', array());
	
	// Gravit�s
	foreach ($severity as $s)
	{
		$Template->assign_block_vars('select_severity', array(
			'SEVERITY' => '<option value="' . $s . '">' . $LANG['bugs.severity.' . $s] . '</option>'
		));
	}
	
	// Priorit�s
	foreach ($priority as $p)
	{
		$selected = ($p == 'normal') ? 'selected="selected"' : '';
		$Template->assign_block_vars('select_priority', array(
			'PRIORITY' => '<option value="' . $p . '" ' . $selected . '>' . $LANG['bugs.priority.' . $p] . '</option>'
		));
	}
	
	//Types
	foreach ($types as $type)
	{
		$Template->assign_block_vars('select_type', array(
			'TYPE' => '<option value="' . $type . '">' . stripslashes($type) . '</option>'
		));
	}
	
	//Categories
	$Template->assign_block_vars('select_category', array(
		'CATEGORY' => '<option value=""></option>'
	));
	foreach ($categories as $category)
	{
		$Template->assign_block_vars('select_category', array(
			'CATEGORY' => '<option value="' . $category . '">' . stripslashes($category) . '</option>'
		));
	}
	
	//Detected in
	$Template->assign_block_vars('select_detected_in', array(
		'VERSION' => '<option value=""></option>'
	));
	foreach ($versions as $version)
	{
		$selected = ($result['detected_in'] == $version) ? 'selected="selected"' : '';
		$Template->assign_block_vars('select_detected_in', array(
			'VERSION' => '<option value="' . $version . '" ' . $selected . '>' . stripslashes($version) . '</option>'
		));
	}
	
	// Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
}
else if (isset($_GET['delete']) && is_numeric($id)) //Suppression du bug.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//checking authorization
	if ($Session->data['level'] != 2)
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}
	
	//On supprime dans la table bugtracker_history.
	$Sql->query_inject("DELETE FROM " . PREFIX . "bugtracker_history WHERE bug_id = '" . $id . "'", __LINE__, __FILE__);

	//On supprime dans la bdd.
	$Sql->query_inject("DELETE FROM " . PREFIX . "bugtracker WHERE id = '" . $id . "'", __LINE__, __FILE__);

	//On supprimes les �ventuels commentaires associ�s.
	$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE idprov = '" . $id . "' AND script = 'bugtracker'", __LINE__, __FILE__);

	//Mise � jour de la liste des bugs dans le cache de la configuration.
	$Cache->Generate_module_file('bugtracker');

	redirect(HOST . SCRIPT);
}
else if (!empty($_POST['valid_edit']) && is_numeric($id_post))
{
	//checking authorization
	if (!$auth_create)
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}
	
	import('util/date');
	$now = new Date(DATE_NOW, TIMEZONE_AUTO);
	
	$title = retrieve(POST, 'title', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
	$status = retrieve(POST, 'status', '');
	$type = retrieve(POST, 'type', '');
	$category = retrieve(POST, 'category', '');
	$assigned_to = retrieve(POST, 'assigned_to', '');
	$detected_in = retrieve(POST, 'detected_in', '');
	$fixed_in = retrieve(POST, 'fixed_in', '');
	$reproductible = retrieve(POST, 'reproductible', '', TBOOL);
	$reproduction_method = retrieve(POST, 'reproduction_method', '', TSTRING_PARSE);
	
	$category_needed = false;
	if ($display_categories && empty($category))
		$category_needed = true;
	
	//On met � jour
	if (!empty($title) && !empty($contents) && !$category_needed)
	{
		$old_values = $Sql->query_array(PREFIX . 'bugtracker b', '*', "
		JOIN " . DB_TABLE_MEMBER . " a ON (a.user_id = b.author_id)
		WHERE b.id = '" . $id_post . "'", __LINE__, __FILE__);
		
		$severity = $auth_create_advanced ? retrieve(POST, 'severity', '') : $old_values['severity'];
		$priority = $auth_create_advanced ? retrieve(POST, 'priority', '') : $old_values['priority'];
		
		//Champs suppl�mentaires pour l'administrateur
		if ($Session->data['level'] == 2)
		{
			if ($old_values['status'] != $status && !change_bug_status_possible($old_values['status'], $status))
				redirect(PATH_TO_ROOT . '/bugtracker/bugtracker.php?edit=true&id=' . $id_post . '&error=bad_status#errorh');
			
			if ($status == 'assigned' && empty($assigned_to)) // Erreur si le statut est "Assign�" et aucun utilisateur n'est s�lectionn�
				redirect(PATH_TO_ROOT . '/bugtracker/bugtracker.php?edit=true&id=' . $id_post . '&error=no_user_assigned#errorh');
			
			$assigned_to_id = (!empty($assigned_to)) ? $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $assigned_to . "'", __LINE__, __FILE__) : 0;
			
			if (!empty($assigned_to) && empty($assigned_to_id)) // Erreur si l'utilisateur s�lectionn� n'existe pas
				redirect(PATH_TO_ROOT . '/bugtracker/bugtracker.php?edit=true&id=' . $id_post . '&error=unexist_user#errorh');
			
			$new_values = array(
				'title'					=> $title,
				'contents' 				=> $contents,
				'type' 					=> stripslashes($type),
				'category' 				=> stripslashes($category),
				'severity' 				=> stripslashes($severity),
				'priority' 				=> stripslashes($priority),		
				'detected_in' 			=> stripslashes($detected_in),			
				'reproductible' 		=> $reproductible,
				'reproduction_method' 	=> $reproduction_method,
				'status' 				=> stripslashes($status),
				'assigned_to_id'		=> $assigned_to_id,		
				'fixed_in' 				=> stripslashes($fixed_in)
			);
			
			$fields = array('title', 'contents', 'type', 'category', 'severity', 'priority', 'detected_in', 'reproductible', 'reproduction_method', 'status', 'fixed_in', 'assigned_to_id');
			
			$modification = false;
			foreach ($fields as $field)
			{
				if ($old_values[$field] != $new_values[$field])
					$modification = true;
			}
			if ($modification == false)
				redirect(PATH_TO_ROOT . '/bugtracker/bugtracker.php?edit=true&id=' . $id_post);
			
			$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET title = '" . $title . "', contents = '" . $contents . "', status = '" . $status . "', type = '" . $type . "', category = '" . $category . "', severity = '" . $severity . "', priority = '" . $priority . "', assigned_to_id = '" . $assigned_to_id . "', detected_in = '" . $detected_in . "', fixed_in = '" . $fixed_in . "', reproductible = '" . $reproductible . "', reproduction_method = '" . $reproduction_method . "'
			WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
		}
		else
		{
			$new_values = array(
				'title'					=> $title,
				'contents' 				=> $contents,
				'type' 					=> stripslashes($type),
				'category' 				=> stripslashes($category),
				'severity' 				=> stripslashes($severity),
				'priority' 				=> stripslashes($priority),
				'detected_in' 			=> stripslashes($detected_in),			
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
				redirect(PATH_TO_ROOT . '/bugtracker/bugtracker.php?edit=true&id=' . $id_post);
			
			$Sql->query_inject("UPDATE " . PREFIX . "bugtracker SET title = '" . $title . "', contents = '" . $contents . "', type = '" . $type . "', category = '" . $category . "', severity = '" . $severity . "', priority = '" . $priority . "', detected_in = '" . $detected_in . "', reproductible = '" . $reproductible . "'
			WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
		}
		
		foreach ($fields as $field)
		{
			if ($field == 'assigned_to_id')
			{
				$result_assigned = $Sql->query_array(DB_TABLE_MEMBER, "login, level", "WHERE user_id = " . $$field, __LINE__, __FILE__);
				$comment = $LANG['bugs.labels.fields.assigned_to_id'] . ' <a href="' . PATH_TO_ROOT . '/member/member.php?id=' . $result['assigned_to_id'] . '" class="' . level_to_status($result_assigned['level']) . '">' . $result_assigned['login'] . '</a>';
			}
			else if ($field == 'title')
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
		$Sql->query_close($old_values);
		
		###### R�g�n�ration du cache #######
		$Cache->Generate_module_file('bugtracker');

		redirect(HOST . DIR . '/bugtracker/bugtracker.php?edit=true&id= ' . $id_post . '&error=success#errorh');
	}
	else
	redirect(HOST . DIR . '/bugtracker/bugtracker.php?edit=true&id= ' . $id_post . '&error=incomplete#errorh');
}
else if (isset($_GET['edit']) && is_numeric($id)) // edition d'un bug
{
	//checking authorization
	if (!$auth_create)
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}
	
	$Template->set_filenames(array(
		'bugtracker' => 'bugtracker/bugtracker.tpl'
	));
	
	//R�cup�ration de l'id de l'auteur du bug
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
	
	//Champs suppl�mentaires pour l'administrateur
	if ($Session->data['level'] == 2)
	{
		$assigned_to = !empty($result['assigned_to_id']) ? $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $result['assigned_to_id'] . "'", __LINE__, __FILE__) : '';
		$Template->assign_block_vars('edit', array(
			'C_IS_ADMIN'				=> true,
			'ID' 						=> $id,
			'TITLE' 					=> $result['title'],
			'CONTENTS' 					=> unparse($result['contents']),
			'REPRODUCTIBLE_ENABLED' 	=> ($result['reproductible']) ? 'checked="checked"' : '',
			'REPRODUCTIBLE_DISABLED' 	=> (!$result['reproductible']) ? 'checked="checked"' : '',
			'REPRODUCTION_METHOD' 		=> unparse($result['reproduction_method']),
			'AUTHOR' 					=> !empty($result['login']) ? '<a href="' . PATH_TO_ROOT . '/member/member.php?id=' . $result['user_id'] . '" class="' . level_to_status($result['level']) . '">' . $result['login'] . '</a>': $LANG['guest'],
			'ASSIGNED_TO'				=> $assigned_to,
			'DATE' 						=> gmdate_format('date_format_short', $result['submit_date'])
		));
	}
	else
	{
		$Template->assign_block_vars('edit', array(
			'ID' 						=> $id,
			'TITLE' 					=> $result['title'],
			'CONTENTS' 					=> unparse($result['contents']),
			'REPRODUCTIBLE_ENABLED' 	=> ($result['reproductible']) ? 'checked="checked"' : '',
			'REPRODUCTIBLE_DISABLED' 	=> (!$result['reproductible']) ? 'checked="checked"' : ''
		));
	}
	
	$Template->assign_vars(array(
		'C_DISPLAY_TYPES' 		=> sizeof($types) > 1 ? true : false,
		'C_DISPLAY_CATEGORIES' 	=> $display_categories,
		'C_DISPLAY_VERSIONS' 	=> sizeof($versions) > 1 ? true : false,
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
		'KERNEL_EDITOR' 		=> display_editor(),
		'KERNEL_EDITOR_EXTEND' 	=> display_editor('extend_contents'),
		'TOKEN'					=> $Session->get_token()
	));
	
	//Types
	$selected = (empty($result['type'])) ? 'selected="selected"' : '';
	$Template->assign_block_vars('edit.select_type', array(
		'TYPE' => '<option value="" ' . $selected . '></option>'
	));
	foreach ($types as $type)
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
	foreach ($categories as $category)
	{
		$selected = ($result['category'] == $category) ? 'selected="selected"' : '';
		$Template->assign_block_vars('edit.select_category', array(
			'CATEGORY' => '<option value="' . $category . '" ' . $selected . '>' . stripslashes($category) . '</option>'
		));
	}
	
	//Statut
	$possible_new_status = new_status_possible($result['status']);
	foreach ($possible_new_status as $s)
	{
		$selected = ($result['status'] == $s) ? 'selected="selected"' : '';
		$Template->assign_block_vars('edit.select_status', array(
			'STATUS' => '<option value="' . $s . '" ' . $selected . '>' . $LANG['bugs.status.' . $s] . '</option>'
		));
	}
	
	//Gravit�s
	foreach ($severity as $s)
	{
		$selected = ($result['severity'] == $s) ? 'selected="selected"' : '';
		$Template->assign_block_vars('edit.select_severity', array(
			'SEVERITY' => '<option value="' . $s . '" ' . $selected . '>' . $LANG['bugs.severity.' . $s] . '</option>'
		));
	}
	
	//Priorit�s
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
	foreach ($versions as $version)
	{
		$selected = ($result['detected_in'] == $version) ? 'selected="selected"' : '';
		$Template->assign_block_vars('edit.select_detected_in', array(
			'VERSION' => '<option value="' . $version . '" ' . $selected . '>' . stripslashes($version) . '</option>'
		));
	}
	
	//Fixed in
	$selected = ($result['fixed_in'] == '') ? 'selected="selected"' : '';
	$Template->assign_block_vars('edit.select_fixed_in', array(
		'VERSION' => '<option value="" ' . $selected . '></option>'
	));
	foreach ($versions as $version)
	{
		$selected = ($result['fixed_in'] == $version) ? 'selected="selected"' : '';
		$Template->assign_block_vars('edit.select_fixed_in', array(
			'VERSION' => '<option value="' . $version . '" ' . $selected . '>' . stripslashes($version) . '</option>'
		));
	}
	$Sql->query_close($result);
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'success')
		$Errorh->handler($LANG['bugs.error.e_edit_success'], E_USER_SUCCESS);
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	if ($get_error == 'bad_status')
		$Errorh->handler($LANG['bugs.error.e_bad_status'], E_USER_WARNING);
	if ($get_error == 'unexist_user')
		$Errorh->handler($LANG['e_unexist_user'], E_USER_WARNING);
	if ($get_error == 'no_user_assigned')
		$Errorh->handler($LANG['bugs.error.e_no_user_assigned'], E_USER_ERROR);
}
else if (isset($_GET['history']) && is_numeric($id)) // Affichage de l'historique du bug
{
	//checking authorization
	if ($Session->data['level'] != 2)
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}
	
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
			$old_value = (strlen($row['old_value']) > 25 ) ? substr($row['old_value'], 0, 25) . '...' : $row['old_value']; // On raccourcis le titre pour ne pas d�former le tableau
			$new_value = (strlen($row['new_value']) > 25 ) ? substr($row['new_value'], 0, 25) . '...' : $row['new_value']; // On raccourcis le titre pour ne pas d�former le tableau
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
			'DATE' 			=> gmdate_format('date_format_short', $row['update_date'])
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
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}
	
  	$Template->set_filenames(array(
		'bugtracker' => 'bugtracker/bugtracker.tpl'
	));
	
	//R�cup�ration de l'id de l'auteur du bug
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
	
	if (($Session->data['user_id'] == $result['author_id'] && $result['author_id'] != '-1') || $Session->data['level'] == 2)
	{
		$Template->assign_vars(array(
			'C_EDIT_BUG'	 		=> true,
			'L_UPDATE'	 			=> $LANG['update']
		));
	}
	
	if ($Session->data['level'] == 2)
	{
		$Template->assign_vars(array(
			'C_HISTORY_BUG'		=> true,
			'L_HISTORY'	 		=> $LANG['bugs.actions.history'],
			'C_DELETE_BUG'		=> true,
			'L_CONFIRM_DEL_BUG' => $LANG['bugs.actions.confirm.del_bug'],
			'L_DELETE' 			=> $LANG['delete']
		));
	}
	
	$Template->assign_vars(array(
		'C_EMPTY_TYPES' 		=> empty($types) ? true : false,
		'C_EMPTY_CATEGORIES' 	=> empty($categories) ? true : false,
		'C_EMPTY_VERSIONS' 		=> empty($versions) ? true : false,
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
		'L_COM' 				=> $result['nbr_com'] > 0 ? sprintf($LANG['display_coms'], $result['nbr_com']) : $LANG['post_com'],
		'L_ON' 					=> $LANG['on'],
		'C_COM' 				=> ($bugtracker_config->get_comments_activated() == true) ? true : false
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
		'U_COM' 				=> BugtrackerUrlBuilder::get_link_item_com($id),
		'TITLE' 				=> $result['title'],
		'CONTENTS' 				=> second_parse($result['contents']),
		'STATUS' 				=> $LANG['bugs.status.' . $result['status']],
		'TYPE'					=> !empty($result['type']) ? stripslashes($result['type']) : $LANG['bugs.notice.none'],
		'CATEGORY'				=> !empty($result['category']) ? stripslashes($result['category']) : $LANG['bugs.notice.none_e'],
		'PRIORITY' 				=> $LANG['bugs.priority.' . $result['priority']],
		'SEVERITY' 				=> $LANG['bugs.severity.' . $result['severity']],
		'REPRODUCTIBLE'			=> ($result['reproductible'] == true) ? $LANG['yes'] : $LANG['no'],
		'REPRODUCTION_METHOD'	=> second_parse($result['reproduction_method']),
		'DETECTED_IN' 			=> !empty($result['detected_in']) ? $result['detected_in'] : $LANG['bugs.notice.not_defined'],
		'FIXED_IN' 				=> !empty($result['fixed_in']) ? $result['fixed_in'] : $LANG['bugs.notice.not_defined'],
		'USER_ASSIGNED'			=> $user_assigned,
		'AUTHOR' 				=> !empty($result['login']) ? '<a href="' . PATH_TO_ROOT . '/member/member.php?id=' . $result['user_id'] . '" class="' . level_to_status($result['level']) . '">' . $result['login'] . '</a>': $LANG['guest'],
		'SUBMIT_DATE'			=> gmdate_format('date_format_short', $result['submit_date'])
	));
	$Sql->query_close($result);
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

//Affichage des commentaires
if (isset($_GET['com']) && is_numeric($id))
{
	$Template->assign_vars(array(
		'COMMENTS' => display_comments('bugtracker', $id, BugtrackerUrlBuilder::get_link_item_com($id,'%s'))
	));
}

$Template->pparse('bugtracker');

include_once('../kernel/footer.php');
?>