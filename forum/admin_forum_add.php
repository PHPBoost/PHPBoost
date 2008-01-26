<?php
/*##################################################
 *                               admin_forum_add.php
 *                            -------------------
 *   begin                : July  21, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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
 *
###################################################*/

require_once('../includes/admin_begin.php');
load_module_lang('forum', $CONFIG['lang']); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$idcat = !empty($_GET['idcat']) ? numeric($_GET['idcat']) : 0;
$class = !empty($_GET['id']) ? numeric($_GET['id']) : 0;

//Si c'est confirmé on execute
if( !empty($_POST['add']) ) //Nouveau forum/catégorie.
{
	$cache->load_file('forum');
	
	$parent_category = !empty($_POST['category']) ? numeric($_POST['category']) : 0;
	$name = !empty($_POST['name']) ? securit($_POST['name']) : '';
	$subname = !empty($_POST['desc']) ? securit($_POST['desc']) : '';
	$aprob = isset($_POST['aprob']) ? numeric($_POST['aprob']) : 0;   
	$status = isset($_POST['status']) ? numeric($_POST['status']) : 0;   
	$auth_read = isset($_POST['groups_authr']) ? $_POST['groups_authr'] : ''; 
	$auth_write = isset($_POST['groups_authw']) ? $_POST['groups_authw'] : ''; 
	$auth_edit = isset($_POST['groups_authx']) ? $_POST['groups_authx'] : ''; 

	//Génération du tableau des droits.
	$array_auth_all = $groups->return_array_auth($auth_read, $auth_write, $auth_edit);

	if( !empty($name) )
	{	
		if( isset($CAT_FORUM[$parent_category]) ) //Insertion sous forum de niveau x.
		{
			//Forums parent du forum cible.
			$list_parent_cats = '';
			$result = $sql->query_while("SELECT id
			FROM ".PREFIX."forum_cats 
			WHERE id_left <= '" . $CAT_FORUM[$parent_category]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$parent_category]['id_right'] . "'", __LINE__, __FILE__);
			while( $row = $sql->sql_fetch_assoc($result) )
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$sql->close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
						
			if( empty($list_parent_cats) )
				$clause_parent = "id = '" . $parent_category . "'";
			else
				$clause_parent = "id IN (" . $list_parent_cats . ")";
			
			$id_left = $CAT_FORUM[$parent_category]['id_right'];
			$sql->query_inject("UPDATE ".PREFIX."forum_cats SET id_right = id_right + 2 WHERE " . $clause_parent, __LINE__, __FILE__);
			$sql->query_inject("UPDATE ".PREFIX."forum_cats SET id_right = id_right + 2, id_left = id_left + 2 WHERE id_left > '" . $id_left . "'", __LINE__, __FILE__);
			$level = $CAT_FORUM[$parent_category]['level'] + 1;

		}
		else //Insertion forum niveau 0.
		{
			$id_left = $sql->query("SELECT MAX(id_right) FROM ".PREFIX."forum_cats", __LINE__, __FILE__);
			$id_left++;
			$level = 0;
		}
		
		$sql->query_inject("INSERT INTO ".PREFIX."forum_cats (id_left,id_right,level,name,subname,nbr_topic,nbr_msg,last_topic_id,status,aprob,auth) VALUES('" . $id_left . "', '" . ($id_left + 1) . "', '" . $level . "', '" . $name . "', '" . $subname . "', 0, 0, 0, '" . $status . "', '" . $aprob . "', '" . securit(serialize($array_auth_all), HTML_NO_PROTECT) . "')", __LINE__, __FILE__);	

		###### Regénération du cache des catégories (liste déroulante dans le forum) #######
		$cache->generate_module_file('forum');
			
		redirect(HOST . DIR . '/forum/admin_forum.php');	
	}	
	else
		redirect(HOST . DIR . '/forum/admin_forum_add.php?error=incomplete#errorh');
}
else	
{		
	$template->set_filenames(array(
		'admin_forum_add' => '../templates/' . $CONFIG['theme'] . '/forum/admin_forum_add.tpl'
	));
			
	//Listing des catégories disponibles, sauf celle qui va être supprimée.			
	$forums = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = $sql->query_while("SELECT id, name, level
	FROM ".PREFIX."forum_cats 
	ORDER BY id_left", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{	
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$forums .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
	}
	$sql->close($result);
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE);	
	
	$array_groups = $groups->create_groups_array(); //Création du tableau des groupes.
	
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'MODULE_DATA_PATH' => $template->module_data_path('forum'),
		'NBR_GROUP' => count($array_groups),
		'CATEGORIES' => $forums,
		'AUTH_READ' => $groups->generate_select_groups('r', array(), -1, array(0 => true, 1 => true, 2 => true)),
		'AUTH_WRITE' => $groups->generate_select_groups('w', array(), -1, array(1 => true, 2 => true)),
		'AUTH_EDIT' => $groups->generate_select_groups('x', array(), -1, array(2 => true)),
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
		'L_CAT_MANAGEMENT' => $LANG['cat_management'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_FORUM_CONFIG' => $LANG['forum_config'],
		'L_FORUM_GROUPS' => $LANG['forum_groups_config'],
		'L_REQUIRE' => $LANG['require'],
		'L_APROB' => $LANG['aprob'],
		'L_STATUS' => $LANG['status'],
		'L_RANK' => $LANG['rank'],
		'L_DELETE' => $LANG['delete'],
		'L_PARENT_CATEGORY' => $LANG['parent_category'],
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_RESET' => $LANG['reset'],		
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_LOCK' => $LANG['lock'],
		'L_UNLOCK' => $LANG['unlock'],
		'L_GUEST' => $LANG['guest'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_ADD' => $LANG['add'],
		'L_AUTH_READ' => $LANG['auth_read'],
		'L_AUTH_WRITE' => $LANG['auth_write'],
		'L_AUTH_EDIT' => $LANG['auth_edit'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none']
	));
	
	$template->pparse('admin_forum_add'); // traitement du modele	
}

require_once('../includes/admin_footer.php');

?>