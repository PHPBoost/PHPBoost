<?php
/*##################################################
 *                                 menus.php
 *                            -------------------
 *   begin                : March, 05 2007
 *   copyright            : (C) 2009 Régis Viarre, Loic Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
 *
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
$switchtheme = retrieve(GET, 'theme', '');
$name_theme = !empty($switchtheme) ? $switchtheme : get_utheme();
$theme_post = retrieve(POST, 'theme', '');

$action = retrieve(GET, 'action', '');
$move = retrieve(GET, 'move', '');

function menu_admin_link($menu, $mode)
{
    $link = '';
    switch ($mode)
    {
        case 'edit':
            if (($menu instanceof LinksMenu))
                $link = 'links.php?';
            elseif (($menu instanceof ContentMenu))
                $link = 'content.php?';
            elseif (($menu instanceof FeedMenu))
                $link = 'feed.php?';
            else
                $link = 'auth.php?';
            break;
        case 'delete':
            if (($menu instanceof ContentMenu) || ($menu instanceof LinksMenu) || ($menu instanceof FeedMenu))
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
        AppContext::get_response()->redirect('menus.php');

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

    ModulesCssFilesCache::invalidate();

    AppContext::get_response()->redirect('menus.php#m' . $id);
}

// Try to find out new mini-modules and delete old ones
MenuService::update_mini_modules_list(false);
// The same with the mini menus
MenuService::update_mini_menus_list();

// Retrieves all the menu
$menus_blocks = MenuService::get_menus_map();
$blocks = array(
   Menu::BLOCK_POSITION__HEADER => 'mod_header',
   Menu::BLOCK_POSITION__SUB_HEADER => 'mod_subheader',
   Menu::BLOCK_POSITION__TOP_CENTRAL => 'mod_topcentral',
   Menu::BLOCK_POSITION__BOTTOM_CENTRAL => 'mod_bottomcentral',
   Menu::BLOCK_POSITION__TOP_FOOTER => 'mod_topfooter',
   Menu::BLOCK_POSITION__FOOTER => 'mod_footer',
   Menu::BLOCK_POSITION__LEFT => 'mod_left',
   Menu::BLOCK_POSITION__RIGHT => 'mod_right',
   Menu::BLOCK_POSITION__NOT_ENABLED => 'mod_main'
);

if ($action == 'save') //Save menus positions.
{
	// We build the array representing the tree
    $menu_tree = array();
    $reverse_menu_tree = array();
    preg_match_all('`([a-z_]+)\[\]=([0-9]+)`', retrieve(POST, 'menu_tree', '', TSTRING_HTML), $matches);
    if (is_array($matches[1]))
    {
    	foreach($matches[1] as $key => $container)
	    {
	    	$menu_tree[$matches[2][$key]] = $container;
	    	$reverse_menu_tree[$container][] = $matches[2][$key];
	    }
    }
    
    //Update all menus.
    $menu_list = array();
    foreach ($menus_blocks as $block_id => $menus)
	{
		// For each block
	    foreach ($menus as $menu)
	    {
			$menu_list[$menu->get_id()] = $menu;
			$new_block = $menu_tree[$menu->get_id()];
       		$enabled = $menu->is_enabled();

       		if ($enabled && $new_block == 'mod_central') //Disable menu
       		{
       			MenuService::disable($menu);
       		}
       		elseif(!$enabled && $new_block != 'mod_central') //Enable menu
       		{
       			MenuService::enable($menu);
       		}

       		if ($new_block != $blocks[$menu->get_block()]) //Move the menu if enabled
       		{
       			$new_block_id = array_search($new_block, $blocks);
       			if ($new_block_id !== false)
       			{
       				MenuService::move($menu, $new_block_id);
       			}
       		}
	    }
	}
	
	//Sort the menus according to their new positions
	$current_block_in_tree = '';
	foreach ($reverse_menu_tree as $block_in_tree => $menus) //Retrieve position's menu in the sorted tree.
	{
		$block_position = 0;
		foreach ($menus as $menu_id) 
		{
			MenuService::set_position($menu_list[$menu_id], $block_position);
			$block_position++;
		}
	}

	$columns_disabled = new ColumnsDisabled();
	$columns_disabled->set_disable_header(!(bool)$_POST['header_enabled']);
	$columns_disabled->set_disable_sub_header(!(bool)$_POST['sub_header_enabled']);
	$columns_disabled->set_disable_top_central(!(bool)$_POST['top_central_enabled']);
	$columns_disabled->set_disable_bottom_central(!(bool)$_POST['bottom_central_enabled']);
	$columns_disabled->set_disable_top_footer(!(bool)$_POST['top_footer_enabled']);
	$columns_disabled->set_disable_footer(!(bool)$_POST['footer_enabled']);
	$columns_disabled->set_disable_left_columns(!(bool)$_POST['left_column_enabled']);
	$columns_disabled->set_disable_right_columns(!(bool)$_POST['right_column_enabled']);
	ThemeManager::change_columns_disabled($theme_post, $columns_disabled);
	
	MenuService::generate_cache();
	
	AppContext::get_response()->redirect('menus.php');
}


$tpl = new FileTemplate('admin/menus/menus.tpl');

$menu_template = new FileTemplate('admin/menus/menu.tpl');
$menu_template->put_all(array(
    'L_ENABLED' => $LANG['enabled'],
    'L_DISABLED' => $LANG['disabled'],
    'I_HEADER' => Menu::BLOCK_POSITION__HEADER,
    'I_SUBHEADER' => Menu::BLOCK_POSITION__SUB_HEADER,
    'I_TOPCENTRAL' => Menu::BLOCK_POSITION__TOP_CENTRAL,
    'I_BOTTOMCENTRAL' => Menu::BLOCK_POSITION__BOTTOM_CENTRAL,
    'I_TOPFOOTER' => Menu::BLOCK_POSITION__TOP_FOOTER,
    'I_FOOTER' => Menu::BLOCK_POSITION__FOOTER,
    'I_LEFT' => Menu::BLOCK_POSITION__LEFT,
    'I_RIGHT' => Menu::BLOCK_POSITION__RIGHT,
	'U_TOKEN' => $Session->get_token()
));

foreach ($menus_blocks as $block_id => $menus)
{
	// For each block
    $i = 0;
    $max = count($menus);
    foreach ($menus as $menu)
    {   // For each Menu in this block
        $menu_tpl = clone $menu_template;

        $id = $menu->get_id();
        $enabled = $menu->is_enabled();

        if (!$enabled)
           $block_id = Menu::BLOCK_POSITION__NOT_ENABLED;

        $edit_link = menu_admin_link($menu, 'edit');
        $del_link = menu_admin_link($menu, 'delete');

		$mini = in_array($block_id, array(Menu::BLOCK_POSITION__LEFT, Menu::BLOCK_POSITION__NOT_ENABLED, Menu::BLOCK_POSITION__RIGHT));

        $menu_tpl->put_all(array(
            'NAME' => $menu->get_formated_title(),
            'IDMENU' => $id,
            'CONTENTS' => $menu->admin_display(),
			'C_MENU_ACTIVATED' => $enabled,
            'C_EDIT' => !empty($edit_link),
            'C_DEL' => !empty($del_link),
			'C_UP' => $block_id != Menu::BLOCK_POSITION__NOT_ENABLED && $i > 0,
            'C_DOWN' => $block_id != Menu::BLOCK_POSITION__NOT_ENABLED && $i < $max - 1,
			'L_DEL' => $LANG['delete'],
			'L_EDIT' => $LANG['edit'],
			'U_EDIT' => menu_admin_link($menu, 'edit'),
            'U_DELETE' => menu_admin_link($menu, 'delete'),
            'U_UP' => menu_admin_link($menu, 'up'),
            'U_DOWN' => menu_admin_link($menu, 'down'),
            'U_MOVE' => menu_admin_link($menu, 'move'),
        ));

		if($enabled)
		{
			$menu_tpl->put_all(array(
				'ACTIV' => ($enabled ? 'disable' : 'enable'),
				'UNACTIV' => ($enabled ? 'enable' : 'disable'),
				'L_ACTIVATE' => $LANG['activate'],
				'L_UNACTIVATE' => $LANG['unactivate'],

			));
        }
        $tpl->assign_block_vars($blocks[$block_id], array('MENU' => $menu_tpl->render()));
        $i++;
    }
}

foreach(ThemeManager::get_activated_themes_map() as $theme => $properties)
{
	$configuration = $properties->get_configuration();
	$selected = (empty($switchtheme) ? get_utheme() == $theme : $switchtheme == $theme) ? ' selected="selected"' : '';
	$tpl->assign_block_vars('themes', array(
		'NAME' => $configuration->get_name(),
		'IDNAME' => $theme,
		'SELECTED' => $selected
	));
}
	
$columns_disable = ThemeManager::get_theme(get_utheme())->get_columns_disabled();

$tpl->put_all(array(
	'NAME_THEME' => $name_theme,
	'CHECKED_RIGHT_COLUMM' => !$columns_disable->right_columns_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_LEFT_COLUMM' => !$columns_disable->left_columns_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_FOOTER_COLUMM' => !$columns_disable->footer_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_TOP_FOOTER_COLUMM' => !$columns_disable->top_footer_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_BOTTOM_CENTRAL_COLUMM' => !$columns_disable->bottom_central_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_TOP_CENTRAL_COLUMM' => !$columns_disable->top_central_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_SUB_HEADER_COLUMM' => !$columns_disable->sub_header_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_HEADER_COLUMM' => !$columns_disable->header_is_disabled() ? 'checked="checked"' : '',
    'C_RIGHT_COLUMN' => $columns_disable->right_columns_is_disabled(),
    'C_LEFT_COLUMN' => $columns_disable->left_columns_is_disabled(),
	'C_FOOTER_COLUMM' => $columns_disable->footer_is_disabled(),
	'C_TOP_FOOTER_COLUMM' => $columns_disable->top_footer_is_disabled(),
	'C_BOTTOM_CENTRAL_COLUMM' => $columns_disable->bottom_central_is_disabled(),
	'C_TOP_CENTRAL_COLUMM' => $columns_disable->top_central_is_disabled(),
	'C_SUB_HEADER_COLUMM' => $columns_disable->sub_header_is_disabled(),
	'C_HEADER_COLUMM' => $columns_disable->header_is_disabled(),
	'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
    'START_PAGE' => Environment::get_home_page(),
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
	'L_VALID_POSTIONS' => $LANG['valid_position_menus'],
	'L_THEME_MANAGEMENT' => $LANG['theme_management'],
	'I_HEADER' => Menu::BLOCK_POSITION__HEADER,
    'I_SUBHEADER' => Menu::BLOCK_POSITION__SUB_HEADER,
    'I_TOPCENTRAL' => Menu::BLOCK_POSITION__TOP_CENTRAL,
    'I_BOTTOMCENTRAL' => Menu::BLOCK_POSITION__BOTTOM_CENTRAL,
    'I_TOPFOOTER' => Menu::BLOCK_POSITION__TOP_FOOTER,
    'I_FOOTER' => Menu::BLOCK_POSITION__FOOTER,
    'I_LEFT' => Menu::BLOCK_POSITION__LEFT,
    'I_RIGHT' => Menu::BLOCK_POSITION__RIGHT,
    'L_MENUS_AVAILABLE' => count($menus_blocks[Menu::BLOCK_POSITION__NOT_ENABLED]) ? $LANG['available_menus'] : $LANG['no_available_menus'],
    'L_INSTALL' => $LANG['install'],
    'L_SUBMIT' => $LANG['submit'],
    'L_RESET' => $LANG['reset'],
	'U_TOKEN' => $Session->get_token()
));
$tpl->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
