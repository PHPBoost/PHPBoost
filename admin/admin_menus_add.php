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

require_once(PATH_TO_ROOT . '/kernel/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/kernel/admin_header.php');

$id = !empty($_GET['id']) ? numeric($_GET['id']) : '';
$idmodule = !empty($_GET['idmodule']) ? trim($_GET['idmodule']) : '';
$edit = !empty($_GET['edit']) ? true : false;
$install = !empty($_GET['install']) ? true : false;
$id_post = !empty($_POST['id']) ? numeric($_POST['id']) : '';
$action = !empty($_POST['action']) ? trim($_POST['action']) : '';
$del = !empty($_GET['del']) ? true : false;

//Si c'est confirmé on execute
if( $action == 'edit' && !empty($id_post) ) //Modification d'un menu déjà existant.
{	
	$name = !empty($_POST['name']) ? strprotect($_POST['name']) : '';
	$activ = isset($_POST['activ']) ? numeric($_POST['activ']) : '';  
	$auth = isset($_POST['auth']) ? numeric($_POST['auth']) : ''; 
	$array_auth = $Group->Return_array_auth(AUTH_MENUS);	
	$contents = !empty($_POST['contents']) ? strparse($_POST['contents'], array(), HTML_UNPROTECT) : '';	
	$location = !empty($_POST['location']) ? strprotect($_POST['location']) : 'left';
	$use_tpl = !empty($_POST['use_tpl']) ? 1 : 0;

	$previous = $Sql->Query_array("modules_mini", "location", "added", "WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
	$clause_class = '';
	if( $previous['location'] != $location )
	{	
		$class = $Sql->Query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE activ = 1 AND location = '" . $location . "'", __LINE__, __FILE__);
		$clause_class .= " class = '" . ($class + 1) . "', ";
	}
	if( $previous['added'] == 1 )
		$clause_class .= " name = '" . $name . "', contents = '" . $contents . "', use_tpl = '" . $use_tpl . "', ";

	$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET " . $clause_class . " location = '" . $location . "', activ = '" . $activ . "', auth = '" . strprotect(serialize($array_auth), HTML_NO_PROTECT) . "' WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
	
	$Cache->Generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id_post);	
}
elseif( $action == 'install' && !empty($idmodule) ) //Module non installé => insertion dans la bdd
{
	if( preg_match('`([a-zA-Z0-9._-]+) ([0-9]+)`', $idmodule, $array_get) )
	{	
		$activ = isset($_POST['activ']) ? numeric($_POST['activ']) : '';
		$array_auth = $Group->Return_array_auth(AUTH_MENUS);
		$module_name = $array_get[1];
		$idmodule = $array_get[2];
		
		if( strpos($module_name, '.php') === false ) //Menu associé à un module.
		{
			//Récupération des infos de config.
			$info_module = load_ini_file('../' . $module_name . '/lang/', $CONFIG['lang']);
			//Installation du mini module s'il existe
			if( !empty($info_module['mini_module']) )
			{
				$i = 1;
				$array_menus = parse_ini_array($info_module['mini_module']);
				foreach($array_menus as $path => $location)
				{
					if( $idmodule == $i )
					{
						$menu_path = '../' . addslashes($module_name) . '/' . addslashes($path);
						if( file_exists($menu_path) )
						{	
							if( !empty($move) )
							{
								$location = $move;
								$activ = 1; //Activation.
							}
							else
								$location = addslashes($location);
				
							$check_menu = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."modules_mini WHERE name = '" .  addslashes($module_name) . "' AND contents = '" . addslashes($path) . "'", __LINE__, __FILE__);
							if( empty($check_menu) )
							{
								$class = $Sql->Query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE location = '" .  $location . "'", __LINE__, __FILE__) + 1;
								$Sql->Query_inject("INSERT INTO ".PREFIX."modules_mini (class, name, contents, location, auth, activ, added, use_tpl) VALUES ('" . $class . "', '" . addslashes($module_name) . "', '" . addslashes($path) . "', '" . addslashes($location) . "', '" . strprotect(serialize($array_auth), HTML_NO_PROTECT) . "', '" . $activ . "', 0, 0)", __LINE__, __FILE__);
								
								$Cache->Generate_file('modules_mini');
							}
							redirect(HOST . DIR . '/admin/admin_menus.php#m' . $class);
						}
					}
					$i++;
				}
			}
		}
		else //Menu perso dans le dossier /menus.
		{
			$menu_path = '../menus/' . addslashes($module_name);
			if( !empty($move) )
			{
				$location = $move;
				$activ = 1; //Activation.
			}
			else
				$location = 'left';

			$check_menu = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."modules_mini WHERE name = '" .  str_replace('.php', '', addslashes($module_name)) . "' AND contents = '" . addslashes($module_name) . "'", __LINE__, __FILE__);
			if( empty($check_menu) )
			{
				$class = $Sql->Query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE location = '" .  $location . "'", __LINE__, __FILE__) + 1;
				$Sql->Query_inject("INSERT INTO ".PREFIX."modules_mini (class, name, contents, location, auth, activ, added, use_tpl) VALUES ('" . $class . "', '" . str_replace('.php', '', addslashes($module_name)) . "', '" . addslashes($module_name) . "', '" . $location . "', '" . strprotect(serialize($array_auth), HTML_NO_PROTECT) . "', '" . $activ . "', 2, 0)", __LINE__, __FILE__);
			
				$Cache->Generate_file('modules_mini');
			}
			redirect(HOST . DIR . '/admin/admin_menus.php#m' . $class);
		}
	}
	else
		redirect(HOST . SCRIPT);
}
elseif( $action == 'add' ) //Ajout d'un menu.
{		
	$name = !empty($_POST['name']) ? strprotect($_POST['name']) : '';
	$activ = isset($_POST['activ']) ? numeric($_POST['activ']) : '';  
	$array_auth = $Group->Return_array_auth(AUTH_MENUS);	
	$contents = !empty($_POST['contents']) ? strparse($_POST['contents'], array(), HTML_UNPROTECT) : '';	
	$location = !empty($_POST['location']) ? strprotect($_POST['location']) : 'left';
	$use_tpl = isset($_POST['use_tpl']) ? numeric($_POST['use_tpl']) : '';
	
	if( empty($activ) )
		$location = '';
	
	$class = $Sql->Query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE activ = 1 AND location = '" . $location . "'", __LINE__, __FILE__);
	$Sql->Query_inject("INSERT INTO ".PREFIX."modules_mini (class, name, contents, location, auth, activ, added, use_tpl) VALUES 
	('" . ($class + 1) . "', '" . $name . "', '" . $contents ."', '" . $location . "', '" . strprotect(serialize($array_auth), HTML_NO_PROTECT) . "', '" . $activ . "', 1, '" . $use_tpl . "')", __LINE__, __FILE__);
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
		'admin_menus_add'=> 'admin/admin_menus_add.tpl'
	));
	
	$Template->Assign_vars(array(
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_NAME' => $LANG['name'],
		'L_STATUS' => $LANG['status'],
		'L_AUTHS' => $LANG['auths'],
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
		'L_HEADER' => $LANG['menu_header'],
		'L_SUB_HEADER' => $LANG['menu_subheader'],
		'L_LEFT_MENU' => $LANG['menu_left'],
		'L_RIGHT_MENU' => $LANG['menu_right'],
		'L_TOP_CENTRAL_MENU' => $LANG['menu_top_central'],
		'L_BOTTOM_CENTRAL_MENU' => $LANG['menu_bottom_central'],
		'L_TOP_FOOTER' => $LANG['menu_top_footer'],
		'L_FOOTER' => $LANG['menu_footer'],
		'L_EXPLAIN_MENUS' => $LANG['menus_explain'],
		'L_ACTION_MENUS' => ($edit) ? $LANG['menus_edit'] : $LANG['menus_add'],
		'L_ACTION' => ($edit) ? $LANG['update'] : $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));
	
	$array_auth_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	if( $edit )
	{
		$menu = $Sql->Query_array('modules_mini', 'id', 'name', 'contents', 'activ', 'auth', 'location', 'use_tpl', 'added', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		//Localisation possibles.
		$array_location = array('header' => $LANG['menu_header'], 'subheader' => $LANG['menu_subheader'], 'left' => $LANG['menu_left'], 'topcentral' => $LANG['menu_top_central'], 'bottomcentral' => $LANG['menu_bottom_central'], 'right' => $LANG['menu_right'], 'topfooter' => $LANG['menu_top_footer'], 'footer' => $LANG['menu_top_footer']);
		$locations = '';
		foreach($array_location as $key => $name)
		{
			$selected = ($menu['location'] == $key) ? 'selected="selected"' : '';
			$locations .= '<option value="' . $key . '" ' . $selected . '>' . $name . '</option>';
		}
		
		//Nom du menus.
		$config = load_ini_file('../' . $menu['name'] . '/lang/', $CONFIG['lang']);
		if( is_array($config) )
			$menu['name'] = !empty($config['name']) ? $config['name'] : '';	
		
		//Récupération des tableaux des autorisations et des groupes.
		$array_auth = !empty($menu['auth']) ? unserialize($menu['auth']) : array();

		$Template->Assign_vars(array(
			'C_EDIT_MENU' => true,
			'C_MENUS_ADDED' => ($menu['added'] == 1) ? true : false,
			'C_MENUS_NOT_ADDED' => ($menu['added'] == 1) ? false : true,
			'ACTION' => 'edit',
			'IDMENU' => $id,
			'NAME' => $menu['name'],
			'AUTH_MENUS' => $Group->Generate_select_auth(AUTH_MENUS, $array_auth),
			'LOCATIONS' => $locations,
			'ACTIV_ENABLED' => ($menu['activ'] == '1') ? 'selected="selected"' : '',
			'ACTIV_DISABLED' => ($menu['activ'] == '0') ? 'selected="selected"' : '',
			'USE_TPL' => ($menu['use_tpl'] == '1') ? 'checked="checked"' : '',
			'CONTENTS' => !empty($menu['contents']) ? unparse($menu['contents']) : ''
		));
	}
	elseif( $install && preg_match('`([a-zA-Z0-9._-]+) ([0-9]+)`', $idmodule, $array_get) )
	{
		//Localisation possibles.
		$array_location = array('header' => $LANG['menu_header'], 'subheader' => $LANG['menu_subheader'], 'left' => $LANG['menu_left'], 'topcentral' => $LANG['menu_top_central'], 'bottomcentral' => $LANG['menu_bottom_central'], 'right' => $LANG['menu_right'], 'topfooter' => $LANG['menu_top_footer'], 'footer' => $LANG['menu_top_footer']);
		$locations = '';
		foreach($array_location as $id => $name)
		{
			$selected = ('left' == $id) ? 'selected="selected"' : '';
			$locations .= '<option value="' . $id . '" ' . $selected . '>' . $name . '</option>';
		}
		
		$module_name = $array_get[1];
		$idmodule = $array_get[2];
		
		//Nom du menus.
		$config = load_ini_file('../' . $module_name . '/lang/', $CONFIG['lang']);
		$name = $module_name;
		if( is_array($config) )
			$name = !empty($config['name']) ? $config['name'] : $name;	
		
		$Template->Assign_vars(array(
			'C_EDIT_MENU' => true,
			'C_MENUS_ADDED' => false,
			'C_MENUS_NOT_ADDED' => true,
			'IDMODULE' => '?idmodule=' . $module_name . '+' . $idmodule,
			'ACTION' => 'install',
			'NAME' => $name,
			'AUTH_MENUS' => $Group->Generate_select_auth(AUTH_MENUS, array(), array(-1 => true, 0 => true, 1 => true, 2 => true)),
			'LOCATIONS' => $locations,
			'ACTIV_ENABLED' => 'selected="selected"',
			'L_ACTION' => $LANG['install']
		));
	}
	else
	{
		$Template->Assign_vars(array(
			'C_ADD_MENU' => true,
			'C_MENUS_ADDED' => true,
			'ACTION' => 'add',
			'AUTH_MENUS' => $Group->Generate_select_auth(AUTH_MENUS, array(), array(-1 => true, 0 => true, 1 => true, 2 => true)),
		));		
	}
	
	include_once(PATH_TO_ROOT . '/kernel/framework/content/bbcode.php');
	
	$Template->Pparse('admin_menus_add');
}

require_once(PATH_TO_ROOT . '/kernel/admin_footer.php');

?>