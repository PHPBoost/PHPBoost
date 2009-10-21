<?php
/*##################################################
 *                                 menus.php
 *                            -------------------
 *   begin                : March, 05 2007
 *   copyright            : (C) 2009 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, horn@phpboost.com
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

import('core/MenuService');

function menu_admin_link(&$menu, $mode)
{
    $link = '';
    switch ($mode)
    {
        case 'edit':
            if (of_class($menu, LINKS_MENU__CLASS))
                $link = 'links.php?';
            elseif (of_class($menu, CONTENT_MENU__CLASS))
                $link = 'content.php?';
            elseif (of_class($menu, FEED_MENU__CLASS))
                $link = 'feed.php?';
            else
                $link = 'auth.php?';
            break;
        case 'delete':
            if (of_class($menu, CONTENT_MENU__CLASS) || of_class($menu, LINKS_MENU__CLASS) || of_class($menu, FEED_MENU__CLASS))
                $link = 'menus.php?action=delete&amp;';
            else
            	return '';
            break;
    }
    global $Session;
    return $link . 'id=' . $menu->get_id() . '&amp;token=' . $Session->get_token();
}

if (!empty($id))
{
    $menu = MenuService::load($id);
    if ($menu == null)
        redirect('menus.php');
    
    // In GET mode so we check it
    $Session->csrf_get_protect();
        
    switch ($action)
    {
    	case 'enable':
            MenuService::enable($menu);
        	break;
        case 'disable':
            MenuService::disable($menu);
            break;
        case 'delete':
            MenuService::delete($id);
            break;
        default:
            if (!empty($move))
            {   // Move a Menu
                MenuService::move($menu, $move);
            }
            break;
    }
    
    MenuService::generate_cache();
    
    import('core/cache/ModulesCssFilesCache');
    ModulesCssFilesCache::invalidate();
    
    redirect('menus.php#m' . $id);
}

// Try to find out new mini-modules and delete old ones
MenuService::update_mini_modules_list(false);
// The same with the mini menus
MenuService::update_mini_menus_list();

$Cache->load('themes');

// Compute the column number
$right_column = $THEME_CONFIG[get_utheme()]['right_column'];
$left_column = $THEME_CONFIG[get_utheme()]['left_column'];

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

if ($action == 'save') //Save menus positions.
{
	// We build the array representing the tree
    $menu_tree = array();
    preg_match_all('`([a-z_]+)\[\]=([0-9]+)`', retrieve(POST, 'menu_tree', '', TSTRING_HTML), $matches);
    if (is_array($matches[1]))
    {
    	foreach($matches[1] as $key => $container)
	    {
	    	$menu_tree[$matches[2][$key]] = $container;
	    }
    }
    
    //Update all menus.
    $changes = 0;
   	$rebuild_block_list = array();
    foreach ($menus_blocks as $block_id => $menus)
	{   
		// For each block
	    foreach ($menus as $menu)
	    {
			$new_block = $menu_tree[$menu->get_id()];
       		$enabled = $menu->is_enabled();
        	
       		if ($enabled && $new_block == 'mod_central') //Disable menu
       		{
       			MenuService::disable($menu);
       			$changes++;
       		}
       		elseif(!$enabled && $new_block != 'mod_central') //Enable menu
       		{
       			MenuService::enable($menu);
       			$rebuild_block_list[$new_block] = true; //We add a marker to rebuild this container.
       			$changes++;
       		}
       		
       		if ($new_block != $blocks[$menu->get_block()]) //Move the menu if enabled
       		{
       			$new_block_id = array_search($new_block, $blocks);
       			if ($new_block_id !== false)
       			{
       				MenuService::move($menu, $new_block_id);
       				$rebuild_block_list[$new_block] = true; //We add a marker to rebuild this container.
       				$changes++;
       			}
       		}
	    }
	}
	
	/*//We build all modified blocks.
	foreach ($rebuild_block_list as $block_to_build => $value)
	{
		foreach ($menu_tree as $block_id => $block_in_tree) //Retrieve position's menu in the sorted tree.
		{
			if ($block_to_build == $block_in_tree)
			{
				//Update position's menus in the modified block.
				foreach($menus_blocks[array_search($block_to_build, $blocks)] as $menu)
				{
					echo $menu->get_id()."\n";
				}
			}
		}
	}
	print_r($menu_tree);
	print_r(array_flip($menu_tree));
    exit;*/
    
	if ($changes > 0) //Update cache if necessary.
	{
	    MenuService::generate_cache();
	    import('core/cache/ModulesCssFilesCache');
    	ModulesCssFilesCache::invalidate();
	}

	/*
	$left_column = !empty($_POST['left_column_enabled']) ? 1 : 0; 
	$right_column = !empty($_POST['right_column_enabled']) ? 1 : 0; 
	$Sql->query_inject("UPDATE " . DB_TABLE_THEMES . " SET left_column = '" . $left_column . "', right_column = '" . $right_column . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
	$Cache->Generate_file('themes'); //Régénération du cache.
	*/
	
	redirect('menus.php');
}


$tpl = new Template('admin/menus/menus.tpl');

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
	'U_TOKEN' => $Session->get_token()
));

foreach ($menus_blocks as $block_id => $menus)
{   
	// For each block
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
        
		$mini = in_array($block_id, array(BLOCK_POSITION__LEFT, BLOCK_POSITION__NOT_ENABLED, BLOCK_POSITION__RIGHT));
		
        $menu_tpl->assign_vars(array(
            'NAME' => $menu->get_formated_title(),
            'IDMENU' => $id,
            'CONTENTS' => $menu->admin_display(),
            'ACTIV' => ($enabled ? 'disable' : 'enable'),
            'UNACTIV' => ($enabled ? 'enable' : 'disable'),
			'C_MENU_ACTIVATED' => $enabled,
            'C_EDIT' => !empty($edit_link),
            'C_DEL' => !empty($del_link),
			'C_UP' => $block_id != BLOCK_POSITION__NOT_ENABLED && $i > 0,
            'C_DOWN' => $block_id != BLOCK_POSITION__NOT_ENABLED && $i < $max - 1,
			'L_ACTIVATE' => $LANG['activate'],
			'L_UNACTIVATE' => $LANG['unactivate'],
			'L_DEL' => $LANG['delete'],
			'L_EDIT' => $LANG['edit'],
			'U_EDIT' => menu_admin_link($menu, 'edit'),
            'U_DELETE' => menu_admin_link($menu, 'delete'),
            'U_UP' => menu_admin_link($menu, 'up'),
            'U_DOWN' => menu_admin_link($menu, 'down'),
            'U_MOVE' => menu_admin_link($menu, 'move'),
        ));
        
        $tpl->assign_block_vars($blocks[$block_id], array('MENU' => $menu_tpl->parse(Template::TEMPLATE_PARSER_STRING)));
        $i++;
    }
}

$tpl->assign_vars(array(
    'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
    'C_LEFT_COLUMN' => $left_column,
    'C_RIGHT_COLUMN' => $right_column,
    'START_PAGE' => get_start_page(),
	'L_INDEX' => $LANG['home'],
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
    'L_ADD_MENU' => $LANG['menus_add'],
	'L_ADD_CONTENT_MENUS' => $LANG['menus_content_add'],
	'L_ADD_LINKS_MENUS' => $LANG['menus_links_add'],
	'L_ADD_FEED_MENUS' => $LANG['menus_feed_add'],
	'I_HEADER' => BLOCK_POSITION__HEADER,
    'I_SUBHEADER' => BLOCK_POSITION__SUB_HEADER,
    'I_TOPCENTRAL' => BLOCK_POSITION__TOP_CENTRAL,
    'I_BOTTOMCENTRAL' => BLOCK_POSITION__BOTTOM_CENTRAL,
    'I_TOPFOOTER' => BLOCK_POSITION__TOP_FOOTER,
    'I_FOOTER' => BLOCK_POSITION__FOOTER,
    'I_LEFT' => BLOCK_POSITION__LEFT,
    'I_RIGHT' => BLOCK_POSITION__RIGHT,
    'L_MENUS_AVAILABLE' => count($menus_blocks[BLOCK_POSITION__NOT_ENABLED]) ? $LANG['available_menus'] : $LANG['no_available_menus'],
    'L_INSTALL' => $LANG['install'],
    'L_SUBMIT' => $LANG['submit'],
    'L_RESET' => $LANG['reset'],
	'U_TOKEN' => $Session->get_token()
));
$tpl->parse();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
