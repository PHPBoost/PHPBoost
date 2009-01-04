<?php
/*##################################################
 *                               admin_menus.php
 *                            -------------------
 *   begin                : March, 05 2007
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

define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$id = retrieve(GET, 'id', 0);

$action = retrieve(GET, 'action', '');
$move = retrieve(GET, 'move', '');

import('core/menu_service');

function menu_admin_link(&$menu, $mode)
{
    if ($mode == 'edit')
    {
        if (of_class($menu, LINKS_MENU__CLASS))
            return 'links.php?';
        if (of_class($menu, CONTENT_MENU__CLASS))
            return 'content.php?';
        return 'auth.php?';
    }
    if ($mode == 'delete')
    {
        if (of_class($menu, LINKS_MENU__CLASS))
            return 'delete.php?';
        if (of_class($menu, CONTENT_MENU__CLASS))
            return 'delete.php?';
    }
    return '';
}

if (!empty($id))
{
    $menu = MenuService::load($id);
    if ($menu == null)
        redirect('menus.php');
    
    if ($action == 'enable')
    {   // Enable a Menu
        MenuService::enable($menu);
        MenuService::generate_cache();
    	redirect(HOST . SCRIPT . '#m' . $id);
    }
    elseif ($action == 'disable')
    {   // Disable a Menu
        MenuService::disable($menu);
        MenuService::generate_cache();
        redirect(HOST . SCRIPT . '#m' . $id);
    }
    elseif (!empty($move))
    {   // Move a Menu
        MenuService::move($menu, $move);
        
        MenuService::generate_cache();
        $Cache->Generate_file('css');
    	
    	redirect(HOST . SCRIPT . '#m' . $id);
    }
    elseif ($action == 'up' || $action == 'down')
    {   // Move up or down a Menu in a block
    	if ($action == 'up')
    	   MenuService::change_position($menu, MOVE_UP);
    	else
           MenuService::change_position($menu, MOVE_DOWN);
        
        MenuService::generate_cache();
        
        redirect(HOST . SCRIPT . '#m' . $id);
    }
}

// Try to find out new mini-modules and delete old ones
MenuService::update_mini_modules_list(false);
// The same with the mini menus
MenuService::update_mini_menus_list();

// Display the Menu dispositions
include('lateral_menu.php');
lateral_menu();

$tpl = new Template('admin/menus/menus.tpl');
$Cache->load('themes');

// Compute the column number
$right_column = $THEME_CONFIG[get_utheme()]['right_column'];
$left_column = $THEME_CONFIG[get_utheme()]['left_column'];
$colspan = 1 + (int) $right_column + (int) $left_column;

// Retrieves all the menu
$menus_blocks = MenuService::get_menus_map();
$blocks = array(
   BLOCK_POSITION__HEADER => 'mod_header',
   BLOCK_POSITION__SUB_HEADER => 'mod_subheader',
   BLOCK_POSITION__TOP_CENTRAL => 'mod_topcentral',
   BLOCK_POSITION__BOTTOM_CENTRAL => 'mod_bottomcentral',
   BLOCK_POSITION__TOP_FOOTER => 'mod_topfooter',
   BLOCK_POSITION__FOOTER => 'mod_footer',
   BLOCK_POSITION__LEFT => 'mod_left',
   BLOCK_POSITION__RIGHT => 'mod_right',
   BLOCK_POSITION__NOT_ENABLED => 'mod_main'
);

if (!$right_column)
{
    foreach ($menus_blocks[BLOCK_POSITION__RIGHT] as $menu)
    {
        $menu->enabled(false);
        MenuService::save($menu);
    }
}
elseif (!$left_column)
{
    foreach ($menus_blocks[BLOCK_POSITION__LEFT] as $menu)
    {
        $menu->enabled(false);
        MenuService::save($menu);
    }
}

$menu_template = new Template('admin/menus/menu.tpl');
$menu_template->assign_vars(array(
    'THEME' => get_utheme(),
    'L_ENABLED' => $LANG['enabled'],
    'L_DISABLED' => $LANG['disabled'],
    'I_HEADER' => BLOCK_POSITION__HEADER,
    'I_SUBHEADER' => BLOCK_POSITION__SUB_HEADER,
    'I_TOPCENTRAL' => BLOCK_POSITION__TOP_CENTRAL,
    'I_BOTTOMCENTRAL' => BLOCK_POSITION__BOTTOM_CENTRAL,
    'I_TOPFOOTER' => BLOCK_POSITION__TOP_FOOTER,
    'I_FOOTER' => BLOCK_POSITION__FOOTER,
    'I_LEFT' => BLOCK_POSITION__LEFT,
    'I_RIGHT' => BLOCK_POSITION__RIGHT,
    'L_HEADER' => $LANG['menu_header'],
    'L_SUB_HEADER' => $LANG['menu_subheader'],
    'L_LEFT_MENU' => $LANG['menu_left'],
    'L_RIGHT_MENU' => $LANG['menu_right'],
    'L_TOP_CENTRAL_MENU' => $LANG['menu_top_central'],
    'L_BOTTOM_CENTRAL_MENU' => $LANG['menu_bottom_central'],
    'L_TOP_FOOTER' => $LANG['menu_top_footer'],
    'L_FOOTER' => $LANG['menu_footer'],
    'L_MOVETO' => $LANG['moveto'],
));

foreach ($menus_blocks as $block_id => $menus)
{   // For each block
    $i = 0;
    $max = count($menus);
    foreach ($menus as $menu)
    {   // For each Menu in this block
        $menu_tpl = $menu_template->copy();
        
        $id = $menu->get_id();
        $enabled = $menu->is_enabled();
        
        if (!$enabled)
           $block_id = BLOCK_POSITION__NOT_ENABLED;
        
        $edit_link = menu_admin_link($menu, 'edit');
        $del_link = menu_admin_link($menu, 'delete');
        
        $menu_tpl->assign_vars(array(
            'NAME' => $menu->get_title(),
            'IDMENU' => $id,
            'U_ONCHANGE_ENABLED' => '\'menus.php?action=' . ($enabled ? 'disable' : 'enable') . '&amp;id=' . $id . '#m' . $id . '\'',
            'SELECT_ENABLED' => $enabled ? 'selected="selected"' : '',
            'SELECT_DISABLED' => !$enabled ? 'selected="selected"' : '',
            'CONTENTS' => $menu->admin_display(),
            'C_EDIT' => !empty($edit_link),
            'C_DEL' => !empty($del_link),
            'EDIT' => $edit_link . 'id=' . $id,
            'DEL' => $del_link . 'id=' . $id,
            'C_UP' => $block_id != BLOCK_POSITION__NOT_ENABLED && $i > 0,
            'C_DOWN' => $block_id != BLOCK_POSITION__NOT_ENABLED && $i < $max - 1,
            'C_MINI' => in_array($block_id, array(BLOCK_POSITION__LEFT, BLOCK_POSITION__NOT_ENABLED, BLOCK_POSITION__RIGHT)),
            'STYLE' => $block_id == BLOCK_POSITION__NOT_ENABLED ? 'margin:5px;margin-top:0px;float:left' : '',
        ));
        
        $tpl->assign_block_vars($blocks[$block_id], array('MENU' => $menu_tpl->parse(TEMPLATE_STRING_MODE)));
        $i++;
    }
}


$tpl->assign_vars(array(
    'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
    'COLSPAN' => $colspan,
    'LEFT_COLUMN' => $left_column,
    'RIGHT_COLUMN' => $right_column,
    'START_PAGE' => get_start_page(),
	'L_INDEX' => $LANG['reception'],
    'L_CONFIRM_DEL_MENU' => $LANG['confirm_del_menu'],
    'L_ACTIVATION' => $LANG['activation'],
    'L_MOVETO' => $LANG['moveto'],
    'L_GUEST' => $LANG['guest'],
    'L_USER' => $LANG['member'],
    'L_MODO' => $LANG['modo'],
    'L_ADMIN' => $LANG['admin'],
    'L_HEADER' => $LANG['menu_header'],
    'L_SUB_HEADER' => $LANG['menu_subheader'],
    'L_LEFT_MENU' => $LANG['menu_left'],
    'L_RIGHT_MENU' => $LANG['menu_right'],
    'L_TOP_CENTRAL_MENU' => $LANG['menu_top_central'],
    'L_BOTTOM_CENTRAL_MENU' => $LANG['menu_bottom_central'],
    'L_TOP_FOOTER' => $LANG['menu_top_footer'],
    'L_FOOTER' => $LANG['menu_footer'],
    'L_MENUS_AVAILABLE' => count($menus_blocks[BLOCK_POSITION__NOT_ENABLED]) ? $LANG['available_menus'] : $LANG['no_available_menus'],
    'L_INSTALL' => $LANG['install'],
    'L_UPDATE' => $LANG['update'],
    'L_RESET' => $LANG['reset'],
));
$tpl->parse();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
    
//	//Récupération du class le plus grand pour chaque positionnement possible.
//	$array_max = array();
//	$result = $Sql->query_while ("SELECT MAX(class) AS max, location
//	FROM ".PREFIX."menus
//	GROUP BY location
//	ORDER BY class", __LINE__, __FILE__);
//
//	while ($row = $Sql->fetch_assoc($result))
//		$array_max[$row['location']] = $row['max'];
//
//	$Sql->query_close($result);
//
//	$i = 0;
//	$uncheck_modules = $MODULES; //On récupère tous les modules installés.
//	$installed_menus_perso = array(); //Menu perso dans le dossier /menus
//	$installed_menus = array();
//	$uninstalled_menus = array();
//	$array_auth_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
//	$result = $Sql->query_while("SELECT id, class, name, contents, location, activ, auth, added
//	FROM ".PREFIX."menus
//	ORDER BY class", __LINE__, __FILE__);
//	while ($row = $Sql->fetch_assoc($result))
//	{
//		if ($row['added'] == 2) //Menu perso dans le dossier /menus
//			$installed_menus_perso[] = $row['name'];
//
//		if ($row['added'] == 0) //On récupère la liste des modules installés et non installés parmis la liste des menus qui y sont ratachés.
//		{
//			$config = load_ini_file('../' . $row['name'] . '/lang/', get_ulang());
//			if (is_array($config) && !empty($config))
//			{
//				unset($uncheck_modules[$row['name']]); //Module vérifié!
//				$array_menus = parse_ini_array($config['mini_module']);
//				foreach ($array_menus as $module_path => $location)
//				{
//					if (strpos($row['contents'], $module_path) !== false) //Module trouvé.
//					{
//						$installed_menus[$row['name']][$module_path] = $location;
//						if (isset($uninstalled_menus[$row['name']][$module_path]))
//							unset($uninstalled_menus[$row['name']][$module_path]);
//					}
//					else
//					{
//						$uninstalled_menus[$row['name']][$module_path] = $location;
//						if (isset($installed_menus[$row['name']][$module_path]))
//							unset($installed_menus[$row['name']][$module_path]);
//					}
//				}
//
//				$row['name'] = !empty($config['name']) ? $config['name'] : $row['name'];
//			}
//		}
//
//		$block_position = $row['location'];
//		if (($row['location'] == 'left' || $row['location'] == 'right') && (!$THEME_CONFIG[get_utheme()]['right_column'] && !$THEME_CONFIG[get_utheme()]['left_column']))
//			$block_position = 'main';
//		elseif (($row['location'] == 'left' || (!$THEME_CONFIG[get_utheme()]['right_column'] && $row['location'] == 'right')) && $THEME_CONFIG[get_utheme()]['left_column'])
//			$block_position = 'left'; //Si on atteint le premier ou le dernier id on affiche pas le lien inaproprié.
//		elseif (($row['location'] == 'right' || (!$THEME_CONFIG[get_utheme()]['left_column'] && $row['location'] == 'left')) && $THEME_CONFIG[get_utheme()]['right_column'])
//			$block_position = 'right';
//
//		if ($row['activ'] == 1 && !empty($block_position))
//		{
//			//Affichage réduit des différents modules.
//			$tpl->assign_block_vars('mod_' . $block_position, array(
//				'IDMENU' => $row['id'],
//				'NAME' => ucfirst($row['name']),
//				'EDIT' => '<a href="admin_menus_add.php?edit=1&amp;id=' . $row['id'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="" class="valign_middle" /></a>',
//				'DEL' => ($row['added'] == 1 || $row['added'] == 2) ? '<a href="admin_menus_add.php?del=1&amp;pos=' . $row['location'] . '&amp;id=' . $row['id'] . '" onclick="javascript:return Confirm_menu();"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" alt="" class="valign_middle" /></a>' : '',
//				'ACTIV_ENABLED' => ($row['activ'] == '1') ? 'selected="selected"' : '',
//				'ACTIV_DISABLED' => ($row['activ'] == '0') ? 'selected="selected"' : '',
//				'CONTENTS' => ($row['added'] == 1) ? '<br />' . second_parse($row['contents']) : '',
//				'UP' => ($row['class'] > 1) ? '<a href="admin_menus.php?top=1&amp;id=' . $row['id'] . '"><img src="../templates/' . get_utheme() . '/images/admin/up.png" alt="" /></a>' : '<div style="float:left;width:32px;">&nbsp;</div>',
//				'DOWN' => ($array_max[$row['location']] != $row['class']) ? '<a href="admin_menus.php?bot=1&amp;id=' . $row['id'] . '"><img src="../templates/' . get_utheme() . '/images/admin/down.png" alt="" /></a>' : '<div style="float:left;width:32px;">&nbsp;</div>',
//				'U_ONCHANGE_ACTIV' => "'admin_menus.php?id=" . $row['id'] . "&amp;pos=" . $row['location'] . "&amp;unactiv=' + this.options[this.selectedIndex].value"
//			));
//		}
//		else //Affichage des menus désactivés
//		{
//			$tpl->assign_block_vars('mod_main', array(
//				'IDMENU' => $row['id'],
//				'NAME' => ucfirst($row['name']),
//				'EDIT' => '<a href="admin_menus_add.php?edit=1&amp;id=' . $row['id'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="" class="valign_middle" /></a>',
//				'DEL' => ($row['added'] == 1 || $row['added'] == 2) ? '<a href="admin_menus_add.php?del=1&amp;pos=' . $row['location'] . '&amp;id=' . $row['id'] . '" onclick="javascript:return Confirm_menu();"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" alt="" class="valign_middle" /></a>' : '',
//				'CONTENTS' => ($row['added'] == 1) ? '<br />' . second_parse($row['contents']) : '',
//				'U_ONCHANGE_ACTIV' => "'admin_menus.php?id=" . $row['id'] . "&amp;pos=" . $row['location'] . "&amp;activ=' + this.options[this.selectedIndex].value"
//			));
//		}
//		$i++;
//	}
//	$Sql->query_close($result);
//
//	//On vérifie pour les modules qui n'ont pas de menu associé, qu'ils n'en ont toujours pas.
//	foreach ($uncheck_modules as $name => $auth)
//	{
//		$modules_config[$name] = load_ini_file('../' . $name . '/lang/', get_ulang());
//		if (!empty($modules_config[$name]['mini_module']))
//		{
//			$array_menus = parse_ini_array($modules_config[$name]['mini_module']);
//			foreach ($array_menus as $module_path => $location)
//				$uninstalled_menus[$name][$module_path] = $location; //On ajoute le menu.
//		}
//	}
//	//On liste les menus non installés.
//	foreach ($uninstalled_menus as $name => $array_menu)
//	{
//		$i = 1;
//		foreach ($array_menu as $path => $location)
//		{
//			if (file_exists('../' . $name . '/' . $path)) //Fichier présent.
//			{
//				$idmodule = $name . '+' . $i++;
//				$tpl->assign_block_vars('mod_main_uninstalled', array(
//					'NAME' => ucfirst($modules_config[$name]['name']),
//					'U_INSTALL' => "admin_menus_add.php?idmodule=" . $idmodule . "&amp;install=1"
//				));
//			}
//		}
//	}

	//On recupère les menus dans le dossier /menus
//	$rep = '../menus/';
//	if (is_dir($rep)) //Si le dossier existe
//	{
//		$file_array = array();
//		$dh = @opendir($rep);
//		while (!is_bool($file = readdir($dh)))
//		{
//			//Si c'est un repertoire, on affiche.
//			if (preg_match('`[a-z0-9()_-]\.php`i', $file) && $file != 'index.php' && !in_array(str_replace('.php', '', $file), $installed_menus_perso))
//				$file_array[] = $file; //On crée un array, avec les different dossiers.
//		}
//		closedir($dh); //On ferme le dossier
//
//		foreach ($file_array as $name)
//		{
//			$tpl->assign_block_vars('mod_main_uninstalled', array(
//				'NAME' => ucfirst(str_replace('.php', '', $name)),
//				'U_INSTALL' => "admin_menus_add.php?idmodule=" . $name . "+0&amp;install=1"
//			));
//		}
//	}

?>
