<?php
/*##################################################
 *                               admin_gallery_cat_add.php
 *                            -------------------
 *   begin                : August  01, 2007
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

include_once('../includes/admin_begin.php');
include_once('../gallery/lang/' . $CONFIG['lang'] . '/gallery_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

$idcat = !empty($_GET['idcat']) ? numeric($_GET['idcat']) : 0;

//Si c'est confirmé on execute
if( !empty($_POST['add']) ) //Nouvelle galerie/catégorie.
{
	$cache->load_file('gallery');
	
	$parent_category = !empty($_POST['category']) ? numeric($_POST['category']) : 0;
	$name = !empty($_POST['name']) ? securit($_POST['name']) : '';
	$contents = !empty($_POST['desc']) ? securit($_POST['desc']) : '';
	$aprob = isset($_POST['aprob']) ? numeric($_POST['aprob']) : 0;   
	$status = isset($_POST['status']) ? numeric($_POST['status']) : 0;   
	$auth_read = isset($_POST['groups_authr']) ? $_POST['groups_authr'] : ''; 
	$auth_write = isset($_POST['groups_authw']) ? $_POST['groups_authw'] : ''; 
	$auth_edit = isset($_POST['groups_authx']) ? $_POST['groups_authx'] : ''; 
		
	//Génération du tableau des droits.
	$array_auth_all = $groups->return_array_auth($auth_read, $auth_write, $auth_edit);
	
	if( !empty($name) )
	{	
		if( isset($CAT_GALLERY[$parent_category]) ) //Insertion sous galerie de niveau x.
		{
			//Galerie parente de la galerie cible.
			$list_parent_cats = '';
			$result = $sql->query_while("SELECT id
			FROM ".PREFIX."gallery_cats 
			WHERE id_left <= '" . $CAT_GALLERY[$parent_category]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$parent_category]['id_right'] . "'", __LINE__, __FILE__);
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
				
			$id_left = $CAT_GALLERY[$parent_category]['id_right'];
			$sql->query_inject("UPDATE ".PREFIX."gallery_cats SET id_right = id_right + 2 WHERE " . $clause_parent, __LINE__, __FILE__);
			$sql->query_inject("UPDATE ".PREFIX."gallery_cats SET id_right = id_right + 2, id_left = id_left + 2 WHERE id_left > '" . $id_left . "'", __LINE__, __FILE__);
			$level = $CAT_GALLERY[$parent_category]['level'] + 1;
			
		}
		else //Insertion galerie niveau 0.
		{
			$id_left = $sql->query("SELECT MAX(id_right) FROM ".PREFIX."gallery_cats", __LINE__, __FILE__);
			$id_left++;
			$level = 0;
		}
			
		$sql->query_inject("INSERT INTO ".PREFIX."gallery_cats (id_left, id_right, level, name, contents, nbr_pics_aprob, nbr_pics_unaprob, status, aprob, auth) VALUES('" . $id_left . "', '" . ($id_left + 1) . "', '" . $level . "', '" . $name . "', '" . $contents . "', 0, 0, '" . $status . "', '" . $aprob . "', '" . securit(serialize($array_auth_all), HTML_NO_PROTECT) . "')", __LINE__, __FILE__);	

		###### Regénération du cache #######
		$cache->generate_module_file('gallery');
			
		header('location:' . HOST . DIR . '/gallery/admin_gallery_cat.php');	
		exit;
	}	
	else
	{
		header('location:' . HOST . DIR . '/gallery/admin_gallery_cat_add.php?error=incomplete#errorh');
		exit;
	}
}
else	
{		
	$template->set_filenames(array(
		'admin_gallery_cat_add' => '../templates/' . $CONFIG['theme'] . '/gallery/admin_gallery_cat_add.tpl'
	));
			
	//Listing des catégories disponibles, sauf celle qui va être supprimée.			
	$galleries = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = $sql->query_while("SELECT id, name, level
	FROM ".PREFIX."gallery_cats 
	ORDER BY id_left", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{	
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$galleries .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
	}
	$sql->close($result);
	
	//Création du tableau des groupes.
	$array_groups = array();
	foreach($_array_groups_auth as $idgroup => $array_group_info)
		$array_groups[$idgroup] = $array_group_info[0];	
		
	//Création du tableau des rangs.
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
		
	//Génération d'une liste à sélection multiple des rangs et groupes
	function generate_select_groups($auth_id)
	{
		global $array_groups, $array_ranks, $LANG;
		
		$j = 0;
		//Liste des rangs
		$select_groups = '<select id="groups_auth' . $auth_id . '" name="groups_auth' . $auth_id . '[]" size="8" multiple="multiple" onclick="document.getElementById(\'' . $auth_id . 'r3\').selected = true;"><optgroup label="' . $LANG['ranks'] . '">';
		foreach($array_ranks as $idgroup => $group_name)
		{
			$selected = ($idgroup == 2) ? 'selected="selected"' : '';			
			$select_groups .=  '<option value="r' . $idgroup . '" id="' . $auth_id . 'r' . $j . '" ' . $selected . ' onclick="check_select_multiple_ranks(\'' . $auth_id . 'r\', ' . $j . ')">' . $group_name . '</option>';
			$j++;
		}
		$select_groups .=  '</optgroup>';
		
		//Liste des groupes.
		$j = 0;
		$select_groups .= '<optgroup label="' . $LANG['groups'] . '">';
		foreach($array_groups as $idgroup => $group_name)
		{
			$select_groups .= '<option value="' . $idgroup . '" id="' . $auth_id . 'g' . $j . '">' . $group_name . '</option>';
			$j++;
		}
		$select_groups .= '</optgroup></select>';
		
		return $select_groups;
	}
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? $_GET['error'] : '';
	if( $get_error == 'incomplete' )
		$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE);	
	
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'MODULE_DATA_PATH' => $template->module_data_path('gallery'),
		'NBR_GROUP' => count($array_groups),
		'CATEGORIES' => $galleries,
		'AUTH_READ' => generate_select_groups('r'),
		'AUTH_WRITE' => generate_select_groups('w'),
		'AUTH_EDIT' => generate_select_groups('x'),
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_GALLERY_MANAGEMENT' => $LANG['gallery_management'], 
		'L_GALLERY_PICS_ADD' => $LANG['gallery_pics_add'], 
		'L_GALLERY_CAT_MANAGEMENT' => $LANG['gallery_cats_management'], 
		'L_GALLERY_CAT_ADD' => $LANG['gallery_cats_add'],
		'L_GALLERY_CONFIG' => $LANG['gallery_config'],
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
		'L_LOCK' => $LANG['gallery_lock'],
		'L_UNLOCK' => $LANG['gallery_unlock'],
		'L_GUEST' => $LANG['guest'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_ADD' => $LANG['add'],
		'L_AUTH_READ' => $LANG['auth_read'],
		'L_AUTH_WRITE' => $LANG['auth_upload'],
		'L_AUTH_EDIT' => $LANG['auth_edit'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none']
	));
	
	$template->pparse('admin_gallery_cat_add'); // traitement du modele	
}

include_once('../includes/admin_footer.php');

?>