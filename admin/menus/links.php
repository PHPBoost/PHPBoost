<?php
/*##################################################
 *                           admin_link_menus.php
 *                            -------------------
 *   begin                : November, 13 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : horn@phpboost.com
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

import('core/menu_service');

$id = retrieve(REQUEST, 'id', 0);
$id_post = retrieve(POST, 'id', 0);

$action = retrieve(REQUEST, 'action', '');
$action_post = retrieve(POST, 'action', '');

if ($action_post == 'save')
{   // Save a Menu (New / Edit)
    import('content/parser/parser');
    $menu = null;
    
    if (!empty($id_post))
    {   // Edit the Menu
        $menu = MenuService::load($id_post);
        $menu->set_title(retrieve(POST, 'name', ''));
    }
    else
    {   // Add the new Menu
        $menu = new ContentMenu(retrieve(POST, 'name', ''));
    }
    
    if (!of_class($menu, LINKS_MENU__CLASS))
        redirect('menus.php');
    
    $menu->enabled(retrieve(POST, 'activ', MENU_NOT_ENABLED));
    if ($menu->is_enabled())
        $menu->set_block(retrieve(POST, 'location', BLOCK_POSITION__NOT_ENABLED));
    $menu->set_auth(Authorizations::build_auth_array_from_form(AUTH_MENUS));
    
    //TODO Parser le menu (ajouts des fils ici)
    
    MenuService::save($menu);
    MenuService::generate_cache();
    
    redirect('menus.php#m' . $id_post);
}
elseif ($action == 'delete' && !empty($id))
{   // Delete a Menu
    MenuService::delete($id);
    MenuService::generate_cache();
    
    redirect('menus.php');
}

// Display the Menu administration
$edit = !empty($id);

include('lateral_menu.php');
lateral_menu();

$tpl = new Template('admin/menus/links.tpl');

$tpl->assign_vars(array(
	'L_REQUIRE_TITLE' => $LANG['require_title'],
	'L_REQUIRE_TEXT' => $LANG['require_text'],
	'L_NAME' => $LANG['name'],
	'L_STATUS' => $LANG['status'],
	'L_AUTHS' => $LANG['auths'],
	'L_ENABLED' => $LANG['enabled'],
	'L_DISABLED' => $LANG['disabled'],
	'L_ACTIVATION' => $LANG['activation'],
	'L_GUEST' => $LANG['guest'],
	'L_USER' => $LANG['member'],
	'L_MODO' => $LANG['modo'],
	'L_ADMIN' => $LANG['admin'],
	'L_LOCATION' => $LANG['location'],
	'L_ACTION_MENUS' => ($edit) ? $LANG['menus_edit'] : $LANG['add'],
	'L_ACTION' => ($edit) ? $LANG['update'] : $LANG['submit'],
	'L_RESET' => $LANG['reset'],
    'ACTION' => 'save',
    'L_TYPE' => $LANG['type'],
    'L_VERTICAL_MENU' => $LANG['vertical_menu'],
    'L_HORIZONTAL_MENU' => $LANG['horizontal_menu'],
    'L_TREE_MENU' => $LANG['tree_menu'],
    'L_VERTICAL_SCROLL_MENU' => $LANG['vertical_scrolling_menu'],
    'L_HORIZONTAL_SCROLL_MENU' => $LANG['horizontal_scrolling_menu']
));

//Localisation possibles.
$block = BLOCK_POSITION__HEADER;
$array_location = array(
    BLOCK_POSITION__HEADER => $LANG['menu_header'],
    BLOCK_POSITION__SUB_HEADER => $LANG['menu_subheader'],
    BLOCK_POSITION__LEFT => $LANG['menu_left'],
    BLOCK_POSITION__TOP_CENTRAL => $LANG['menu_top_central'],
    BLOCK_POSITION__BOTTOM_CENTRAL => $LANG['menu_bottom_central'],
    BLOCK_POSITION__RIGHT => $LANG['menu_right'],
    BLOCK_POSITION__TOP_FOOTER => $LANG['menu_top_footer'],
    BLOCK_POSITION__FOOTER => $LANG['menu_top_footer']
);

if ($edit)
{
	/*
	$menu = MenuService::load($id);
	
    if (!of_class($menu, LINKS_MENU__CLASS))
        redirect('menus.php');*/
	
	$edit_menu_tpl = new Template('admin/menus/menu_edition.tpl');
	
	$auth = array('r2' => 1, '1' => 0);
	$menu = new LinksMenu('Google', 'http://www.google.com', '', VERTICAL_SCROLLING_MENU);
	$menu1 = new LinksMenu('Menu 1', 'http://www.google.com');
	$menu2 = new LinksMenu('Menu 2', 'http://www.google.com');
	$menu3 = new LinksMenu('Menu 3', 'http://www.google.com');
	$menu4 = new LinksMenu('Menu 4', 'http://www.google.com');
	$menu5 = new LinksMenu('Menu 5', 'http://www.google.com');
	$menu6 = new LinksMenu('Menu 6', 'http://www.google.com');
	$menu7 = new LinksMenu('Menu 7', 'http://www.google.com');
	$element1 = new LinksMenuLink('Element 1', '/forum/index.php');
	$element1->set_auth($auth);
	$element2 = new LinksMenuLink('Element 2', 'http://www.google.com');
	
	$aelts0 = array($element1, $element2, $element1, $element1);
	$menu7->add_array($aelts0);
	$aelts1 = array($menu7, $element1, $element1, $element1, $element1);
	$menu6->add_array($aelts1);
	$aelts2 = array($menu6, $element1, $element1, $element2, $element2);
	$aelts3 = array($element1, $element1, $element2, $element2);
	$aelts4 = array($element2, $element2, $element1, $element1);
	$aelts5 = array($element2, $element2, $element2, $element2);
	
	$menu1->add_array($aelts1);
	$menu2->add_array($aelts2);
	$menu3->add_array($aelts3);
	$menu4->add_array($aelts4);
	$menu5->add_array($aelts5);
	
	$amenu = array($menu1, $menu2, $menu3, $menu4, $menu5, $element1, $element2);
	
	$menu->add_array($amenu);
    
	$block = $menu->get_block();
	
	$tpl->assign_vars(array(
		'IDMENU' => $id,
		'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, $menu->get_auth()),
        'C_ENABLED' => $menu->is_enabled(),
		'TEST' => $menu->display($edit_menu_tpl)
	));
}
else
{
   $tpl->assign_vars(array(
       'C_ENABLED' => true,
       'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, array(), array(-1 => true, 0 => true, 1 => true, 2 => true))
   ));
}

$locations = '';
foreach ($array_location as $key => $name)
    $locations .= '<option value="' . $key . '" ' . (($block == $key) ? 'selected="selected"' : '') . '>' . $name . '</option>';

$tpl->assign_vars(array('LOCATIONS' => $locations));
$tpl->parse();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');

?>