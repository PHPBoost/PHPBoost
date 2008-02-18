<?php
/*##################################################
 *                               admin_menus_add.php
 *                            -------------------
 *   begin                : March 06, 2007
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
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$id = ( !empty($_GET['id'])) ? numeric($_GET['id']) : '' ;
$edit = !empty($_GET['edit']) ? true : false;
$id_post = ( !empty($_POST['id'])) ? numeric($_POST['id']) : '' ;
$action = !empty($_POST['action']) ? trim($_POST['action']) : '';
$del = !empty($_GET['del']) ? true : false;

//Si c'est confirmé on execute
if( $action == 'edit' && !empty($id_post) ) //Modification d'un menu déjà existant.
{	
	$name = !empty($_POST['name']) ? securit($_POST['name']) : '';
	$activ = isset($_POST['activ']) ? numeric($_POST['activ']) : '';  
	$secure = isset($_POST['secure']) ? numeric($_POST['secure']) : ''; 
	$contents = !empty($_POST['contents']) ? parse($_POST['contents'], array(), HTML_UNPROTECT) : '';	
	$location = !empty($_POST['location']) ? securit($_POST['location']) : 'left';
	$use_tpl = !empty($_POST['use_tpl']) ? 1 : 0;

	$previous_location = $Sql->Query("SELECT location FROM ".PREFIX."modules_mini WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
	$clause_class = '';
	if( $previous_location != $location )
	{	
		$class = $Sql->Query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE activ = 1 AND location = '" . $location . "'", __LINE__, __FILE__);
		$clause_class = " class = '" . ($class + 1) . "', ";
	}
	$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET " . $clause_class . " name = '" . $name . "', contents = '" . $contents ."', location = '" . $location . "', activ = '" . $activ . "', secure = '" . $secure . "', use_tpl = '" . $use_tpl . "' WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
	
	$Cache->Generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id_post);	
}
elseif( $action == 'add' ) //Ajout d'un menu.
{		
	$name = !empty($_POST['name']) ? securit($_POST['name']) : '';
	$activ = isset($_POST['activ']) ? numeric($_POST['activ']) : '';  
	$secure = isset($_POST['secure']) ? numeric($_POST['secure']) : '-1'; 
	$contents = !empty($_POST['contents']) ? parse($_POST['contents'], array(), HTML_UNPROTECT) : '';	
	$location = !empty($_POST['location']) ? securit($_POST['location']) : 'left';
	$use_tpl = isset($_POST['use_tpl']) ? numeric($_POST['use_tpl']) : '';
	
	if( empty($activ) )
		$location = '';
	
	$class = $Sql->Query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE activ = 1 AND location = '" . $location . "'", __LINE__, __FILE__);
	$Sql->Query_inject("INSERT INTO ".PREFIX."modules_mini (class, name, code, contents, location, secure, activ, added, use_tpl) VALUES 
	('" . ($class + 1) . "', '" . $name . "', '', '" . $contents ."', '" . $location . "', '" . $secure . "', '" . $activ . "', 1, '" . $use_tpl . "')", __LINE__, __FILE__);
	$last_menu_id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."modules_mini");
	
	$Cache->Generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $last_menu_id);	
}
elseif( !empty($del) && !empty($id) ) //Suppression du menu.
{
	$info_menu = $Sql->Query_array("modules_mini", "class", "location", "WHERE id = " . $id, __LINE__, __FILE__);
	$Sql->Query_inject("DELETE FROM ".PREFIX."modules_mini WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	//Réordonnement du classement.
	$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = class - 1 WHERE class > '" . $info_menu['class'] . "' AND location = '" . addslashes($info_menu['location']) . "' AND activ = 1", __LINE__, __FILE__);
	
	$Cache->Generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php');	
}
else	
{		
	$Template->Set_filenames(array(
		'admin_menus_add' => '../templates/' . $CONFIG['theme'] . '/admin/admin_menus_add.tpl'
	));
	
	$Template->Assign_vars(array(
		'IDMENU' => $id,
		'ACTION' => ($edit) ? 'edit' : 'add',
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_NAME' => $LANG['name'],
		'L_STATUS' => $LANG['status'],
		'L_RANK' => $LANG['rank'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIV' => $LANG['unactiv'],
		'L_ACTIVATION' => $LANG['activation'],
		'L_GUEST' => $LANG['guest'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
		'L_ADD_MENUS' => $LANG['menus_add'],
		'L_LOCATION' => $LANG['location'],
		'L_USE_TPL' => $LANG['use_tpl'],
		'L_SUB_HEADER' => $LANG['menu_subheader'],
		'L_LEFT_MENU' => $LANG['menu_left'],
		'L_RIGHT_MENU' => $LANG['menu_right'],
		'L_TOP_CENTRAL_MENU' => $LANG['menu_top_central'],
		'L_BOTTOM_CENTRAL_MENU' => $LANG['menu_bottom_central'],
		'L_EXPLAIN_MENUS' => $LANG['menus_explain'],
		'L_ACTION_MENUS' => ($edit) ? $LANG['menus_edit'] : $LANG['menus_add'],
		'L_ACTION' => ($edit) ? $LANG['update'] : $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));
	
	$array_auth_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	if( $edit )
	{
		$menu = $Sql->Query_array('modules_mini', 'id', 'name', 'contents', 'activ', 'secure', 'location', 'use_tpl', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		//Rangs d'autorisation.
		$ranks = '';
		foreach($array_auth_ranks as $rank => $name)
		{
			$selected = ($menu['secure'] == $rank) ? 'selected="selected"' : '';
			$ranks .= '<option value="' . $rank . '" ' . $selected . '>' . $name . '</option>';
		}
		
		//Rangs d'autorisation.
		$array_location = array('subheader' => $LANG['menu_subheader'], 'left' => $LANG['menu_left'], 'topcentral' => $LANG['menu_top_central'], 'bottomcentral' => $LANG['menu_bottom_central'], 'right' => $LANG['menu_right']);
		$locations = '';
		foreach($array_location as $id => $name)
		{
			$selected = ($menu['location'] == $id) ? 'selected="selected"' : '';
			$locations .= '<option value="' . $id . '" ' . $selected . '>' . $name . '</option>';
		}
		
		$Template->Assign_vars(array(
			'C_EDIT_MENU' => true,
			'NAME' => $menu['name'],
			'RANKS' => $ranks,
			'LOCATIONS' => $locations,
			'ACTIV_ENABLED' => ($menu['activ'] == '1') ? 'selected="selected"' : '',
			'ACTIV_DISABLED' => ($menu['activ'] == '0') ? 'selected="selected"' : '',
			'USE_TPL' => ($menu['use_tpl'] == '1') ? 'checked="checked"' : '',
			'CONTENTS' => !empty($menu['contents']) ? unparse($menu['contents']) : ''
		));
	}
	else
	{
		//Rangs d'autorisation.
		$ranks = '';
		foreach($array_auth_ranks as $rank => $name)
		{
			$selected = (-1 == $rank) ? 'selected="selected"' : '';
			$ranks .= '<option value="' . $rank . '" ' . $selected . '>' . $name . '</option>';
		}
		
		$Template->Assign_vars(array(
			'C_ADD_MENU' => true,
			'RANKS' => $ranks
		));		
	}
	
	include_once('../includes/bbcode.php');
	
	$Template->Pparse('admin_menus_add');
}

require_once('../includes/admin_footer.php');

?>