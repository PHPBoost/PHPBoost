<?php
/*##################################################
 *                               admin_menus_add.php
 *                            -------------------
 *   begin                : March 06, 2007
 *   copyright            : (C) 2007 Viarre Régis
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$id = retrieve(GET, 'id', 0);
$idmodule = retrieve(GET, 'idmodule', '', TSTRING_UNSECURE);
$edit = retrieve(GET, 'edit', false);
$del = retrieve(GET, 'del', false);
$install = retrieve(GET, 'install', false);
$type = retrieve(GET, 'type', 1);
$id_post = retrieve(POST, 'id', 0);
$action = retrieve(POST, 'action', '');

//Si c'est confirmé on execute
if ($action == 'edit' && !empty($id_post)) //Modification d'un menu déjà existant.
{
	$name = retrieve(POST, 'name', '');
	$activ = retrieve(POST, 'activ', 0);  
	$auth = retrieve(POST, 'auth', 0); 
	$array_auth = Authorizations::build_auth_array_from_form(AUTH_MENUS);	
	$contents = !empty($_POST['contents']) ? strparse($_POST['contents']) : '';	
	$location = retrieve(POST, 'location', 'left');
	$use_tpl = !empty($_POST['use_tpl']) ? 1 : 0;

	$previous = $Sql->query_array("menus", "location", "added", "WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
	$clause_class = '';
	if ($previous['location'] != $location)
	{	
		$class = $Sql->query("SELECT MAX(class) FROM ".PREFIX."menus WHERE activ = 1 AND location = '" . $location . "'", __LINE__, __FILE__);
		$clause_class .= " class = '" . ($class + 1) . "', ";
	}
	if ($previous['added'] == 1)
		$clause_class .= " name = '" . $name . "', contents = '" . $contents . "', use_tpl = '" . $use_tpl . "', ";

	$Sql->query_inject("UPDATE ".PREFIX."menus SET " . $clause_class . " location = '" . $location . "', activ = '" . $activ . "', auth = '" . addslashes(serialize($array_auth)) . "' WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
	
	$Cache->Generate_file('menus');		
	$Cache->Generate_file('css');
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id_post);	
}
elseif ($action == 'install' && !empty($idmodule)) //Module non installé => insertion dans la bdd
{
	if (preg_match('`([a-zA-Z0-9._-]+) ([0-9]+)`', $idmodule, $array_get))
	{	
		$activ = retrieve(POST, 'activ', 0);
		$array_auth = Authorizations::build_auth_array_from_form(AUTH_MENUS);
		$module_name = addslashes($array_get[1]);
		$idmodule = $array_get[2];
		
		if (strpos($module_name, '.php') === false) //Menu associé à un module.
		{
			//Récupération des infos de config.
			$info_module = load_ini_file('../' . $module_name . '/lang/', get_ulang());
			//Installation du mini module s'il existe
			if (!empty($info_module['mini_module']))
			{
				$i = 1;
				$array_menus = parse_ini_array($info_module['mini_module']);
				foreach ($array_menus as $path => $location)
				{
					if ($idmodule == $i)
					{
						$path = addslashes($path);
						$menu_path = '../' . $module_name . '/' . $path;
						if (file_exists($menu_path))
						{	
							if (!empty($move))
							{
								$location = $move;
								$activ = 1; //Activation.
							}
							else
								$location = addslashes($location);
				
							$check_menu = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."menus WHERE name = '" .  $module_name . "' AND contents = '" . $path . "'", __LINE__, __FILE__);
							if (empty($check_menu))
							{
								$class = $Sql->query("SELECT MAX(class) FROM ".PREFIX."menus WHERE location = '" .  $location . "'", __LINE__, __FILE__) + 1;
								$Sql->query_inject("INSERT INTO ".PREFIX."menus (class, name, contents, location, auth, activ, added, use_tpl) VALUES ('" . $class . "', '" . $module_name . "', '" . $path . "', '" . $location . "', '" . addslashes(serialize($array_auth)) . "', '" . $activ . "', 0, 0)", __LINE__, __FILE__);
								
								$Cache->Generate_file('menus');
								$Cache->Generate_file('css');
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
			$menu_path = '../menus/' . $module_name;
			if (!empty($move))
			{
				$location = $move;
				$activ = 1; //Activation.
			}
			else
				$location = 'left';

			$check_menu = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."menus WHERE name = '" .  str_replace('.php', '', $module_name) . "' AND contents = '" . $module_name . "'", __LINE__, __FILE__);
			if (empty($check_menu))
			{
				$class = $Sql->query("SELECT MAX(class) FROM ".PREFIX."menus WHERE location = '" .  $location . "'", __LINE__, __FILE__) + 1;
				$Sql->query_inject("INSERT INTO ".PREFIX."menus (class, name, contents, location, auth, activ, added, use_tpl) VALUES ('" . $class . "', '" . str_replace('.php', '', $module_name) . "', '" . $module_name . "', '" . $location . "', '" . addslashes(serialize($array_auth)) . "', '" . $activ . "', 2, 0)", __LINE__, __FILE__);
			
				$Cache->Generate_file('menus');
			}
			redirect(HOST . DIR . '/admin/admin_menus.php#m' . $class);
		}
	}
	else
		redirect(HOST . SCRIPT);
}
elseif ($action == 'add') //Ajout d'un menu.
{		
	$name = retrieve(POST, 'name', '');
	$activ = retrieve(POST, 'activ', 0);  
	$array_auth = Authorizations::build_auth_array_from_form(AUTH_MENUS);	
	$contents = !empty($_POST['contents']) ? strparse($_POST['contents']) : '';	
	$location = retrieve(POST, 'location', 'left');
	$use_tpl = !empty($_POST['use_tpl']) ? 1 : 0;
	
	if (!$activ)
		$location = '';
	
	$class = $Sql->query("SELECT MAX(class) FROM ".PREFIX."menus WHERE activ = 1 AND location = '" . $location . "'", __LINE__, __FILE__);
	$Sql->query_inject("INSERT INTO ".PREFIX."menus (class, name, contents, location, auth, activ, added, use_tpl) VALUES 
	('" . ($class + 1) . "', '" . $name . "', '" . $contents ."', '" . $location . "', '" . addslashes(serialize($array_auth)) . "', '" . $activ . "', 1, '" . $use_tpl . "')", __LINE__, __FILE__);
	$last_menu_id = $Sql->insert_id("SELECT MAX(id) FROM ".PREFIX."menus");
	
	$Cache->Generate_file('menus');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $last_menu_id);	
}
elseif (!empty($del) && !empty($id)) //Suppression du menu.
{
	$info_menu = $Sql->query_array("menus", "class", "location", "WHERE id = " . $id, __LINE__, __FILE__);
	$Sql->query_inject("DELETE FROM ".PREFIX."menus WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	//Réordonnement du classement.
	$Sql->query_inject("UPDATE ".PREFIX."menus SET class = class - 1 WHERE class > '" . $info_menu['class'] . "' AND location = '" . addslashes($info_menu['location']) . "' AND activ = 1", __LINE__, __FILE__);
	
	$Cache->Generate_file('menus');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php');	
}
else	
{		
	$Template->set_filenames(array(
		'admin_menus_add'=> 'admin/admin_menus_add.tpl'
	));

	$Template->assign_vars(array(
		'KERNEL_EDITOR' => display_editor(),
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
		'L_ADD_CONTENT_MENUS' => $LANG['menus_content_add'],
		'L_ADD_LINKS_MENUS' => $LANG['menus_links_add'],
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
		'L_ACTION_MENUS' => ($edit) ? $LANG['menus_edit'] : $LANG['add'],
		'L_ACTION' => ($edit) ? $LANG['update'] : $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));
	
	$array_auth_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	if ($edit)
	{
		$menu = $Sql->query_array('menus', 'id', 'name', 'contents', 'activ', 'auth', 'location', 'use_tpl', 'added', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		//Localisation possibles.
		$array_location = array('header' => $LANG['menu_header'], 'subheader' => $LANG['menu_subheader'], 'left' => $LANG['menu_left'], 'topcentral' => $LANG['menu_top_central'], 'bottomcentral' => $LANG['menu_bottom_central'], 'right' => $LANG['menu_right'], 'topfooter' => $LANG['menu_top_footer'], 'footer' => $LANG['menu_top_footer']);
		$locations = '';
		foreach ($array_location as $key => $name)
		{
			$selected = ($menu['location'] == $key) ? 'selected="selected"' : '';
			$locations .= '<option value="' . $key . '" ' . $selected . '>' . $name . '</option>';
		}
		
		//Nom du menus.
		if (!empty($menu['name']))
		{
			$config = load_ini_file('../' . $menu['name'] . '/lang/', get_ulang());
			if (is_array($config))
				$menu['name'] = !empty($config['name']) ? $config['name'] : $menu['name'];	
		}
		
		//Récupération des tableaux des autorisations et des groupes.
		$array_auth = !empty($menu['auth']) ? unserialize($menu['auth']) : array();

		$Template->assign_vars(array(
			'C_EDIT_MENU' => true,
			'C_MENUS_ADDED' => ($menu['added'] == 1) ? true : false,
			'C_MENUS_NOT_ADDED' => ($menu['added'] == 1) ? false : true,
			'ACTION' => 'edit',
			'IDMENU' => $id,
			'NAME' => $menu['name'],
			'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, $array_auth),
			'LOCATIONS' => $locations,
			'ACTIV_ENABLED' => ($menu['activ'] == '1') ? 'selected="selected"' : '',
			'ACTIV_DISABLED' => ($menu['activ'] == '0') ? 'selected="selected"' : '',
			'USE_TPL' => ($menu['use_tpl'] == '1') ? 'checked="checked"' : '',
			'CONTENTS' => !empty($menu['contents']) ? unparse($menu['contents']) : ''
		));
	}
	elseif ($install && preg_match('`([a-zA-Z0-9._-]+) ([0-9]+)`', $idmodule, $array_get))
	{
		//Localisation possibles.
		$array_location = array('header' => $LANG['menu_header'], 'subheader' => $LANG['menu_subheader'], 'left' => $LANG['menu_left'], 'topcentral' => $LANG['menu_top_central'], 'bottomcentral' => $LANG['menu_bottom_central'], 'right' => $LANG['menu_right'], 'topfooter' => $LANG['menu_top_footer'], 'footer' => $LANG['menu_top_footer']);
		$locations = '';
		foreach ($array_location as $id => $name)
		{
			$selected = ('left' == $id) ? 'selected="selected"' : '';
			$locations .= '<option value="' . $id . '" ' . $selected . '>' . $name . '</option>';
		}
		
		$module_name = $array_get[1];
		$idmodule = $array_get[2];
		
		//Nom du menus.
		$config = load_ini_file('../' . $module_name . '/lang/', get_ulang());
		$name = $module_name;
		if (is_array($config))
			$name = !empty($config['name']) ? $config['name'] : $name;	
		
		$Template->assign_vars(array(
			'C_EDIT_MENU' => true,
			'C_MENUS_ADDED' => false,
			'C_MENUS_NOT_ADDED' => true,
			'IDMODULE' => '?idmodule=' . $module_name . '+' . $idmodule,
			'ACTION' => 'install',
			'NAME' => $name,
			'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, array(), array(-1 => true, 0 => true, 1 => true, 2 => true)),
			'LOCATIONS' => $locations,
			'ACTIV_ENABLED' => 'selected="selected"',
			'L_ACTION' => $LANG['install']
		));
	}
	elseif ($type == 2) //Ajout d'un menu de lien.
	{
		$Template->assign_vars(array(
			'C_ADD_MENU' => true,
			'C_ADD_MENU_LINKS' => true,
			'C_MENUS_ADDED' => true,
			'ACTION' => 'add',
			'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, array(), array(-1 => true, 0 => true, 1 => true, 2 => true)),
			'L_TYPE' => $LANG['type'],
			'L_ACTION' => $LANG['add'],
			'L_VERTICAL_MENU' => $LANG['vertical_menu'],
			'L_HORIZONTAL_MENU' => $LANG['horizontal_menu'],
			'L_TREE_MENU' => $LANG['tree_menu'],
			'L_VERTICAL_SCROLL_MENU' => $LANG['vertical_scrolling_menu'],
			'L_HORIZONTAL_SCROLL_MENU' => $LANG['horizontal_scrolling_menu']
		));		
	}
	else //Ajout d'un menu de contenu.
	{
		$Template->assign_vars(array(
			'C_ADD_MENU' => true,
			'C_ADD_MENU_CONTENT' => true,
			'C_MENUS_ADDED' => true,
			'ACTION' => 'add',
			'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, array(), array(-1 => true, 0 => true, 1 => true, 2 => true)),
		));		
	}
	
	$Template->pparse('admin_menus_add');
}

require_once('../admin/admin_footer.php');

?>