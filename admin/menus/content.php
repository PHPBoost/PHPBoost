<?php
/*##################################################
 *                           content.php
 *                          -------------------
 *   begin                : November 23, 2008
 *   copyright            : (C) 2008 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

$id = AppContext::get_request()->get_int('id', 0);
$id_post = AppContext::get_request()->get_postint('id', 0);

$action = AppContext::get_request()->get_string('action', '');
$action_post = AppContext::get_request()->get_poststring('action', '');

if ($action_post == 'save')
{
    // Save a Menu (New / Edit)
    $menu = null;
    
    $menu_name = trim(AppContext::get_request()->get_poststring('name', ''));
    
    if (!empty($id_post))
    {   // Edit the Menu
        $menu = MenuService::load($id_post);
        $menu->set_title($menu_name);
    }
    else
    {   // Add the new Menu
        $menu = new ContentMenu($menu_name);
    }
    
    if (!($menu instanceof ContentMenu))
    {
        AppContext::get_response()->redirect('menus.php');
    }
    
    $menu->enabled(AppContext::get_request()->get_postvalue('activ', Menu::MENU_NOT_ENABLED));
    if ($menu->is_enabled())
    {
        $menu->set_block(AppContext::get_request()->get_postvalue('location', Menu::BLOCK_POSITION__NOT_ENABLED));
    }
    $menu->set_auth(Authorizations::build_auth_array_from_form(AUTH_MENUS));
    $menu->set_display_title(AppContext::get_request()->get_postbool('display_title', false));
    $menu->set_content(trim(AppContext::get_request()->get_poststring('contents', '')));
    
    //Filters
    MenuAdminService::set_retrieved_filters($menu);
    
    MenuService::save($menu);
    MenuService::generate_cache();
	
	AppContext::get_response()->redirect('menus.php#m' . $id_post);
}

// Display the Menu administration
$edit = !empty($id);

include('lateral_menu.php');
lateral_menu();

$tpl = new FileTemplate('admin/menus/content.tpl');

$tpl->put_all(array(
	'KERNEL_EDITOR' => display_editor(),
	'L_REQUIRE_TITLE' => TextHelper::to_js_string($LANG['require_title']),
	'L_REQUIRE_TEXT' => TextHelper::to_js_string($LANG['require_text']),
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
    'L_DISPLAY_TITLE' => $LANG['display_title']
));

//Localisation possibles.
$block = retrieve(GET, 's', Menu::BLOCK_POSITION__HEADER, TINTEGER);
$array_location = array(
    Menu::BLOCK_POSITION__HEADER => $LANG['menu_header'],
    Menu::BLOCK_POSITION__SUB_HEADER => $LANG['menu_subheader'],
    Menu::BLOCK_POSITION__LEFT => $LANG['menu_left'],
    Menu::BLOCK_POSITION__TOP_CENTRAL => $LANG['menu_top_central'],
    Menu::BLOCK_POSITION__BOTTOM_CENTRAL => $LANG['menu_bottom_central'],
    Menu::BLOCK_POSITION__RIGHT => $LANG['menu_right'],
    Menu::BLOCK_POSITION__TOP_FOOTER => $LANG['menu_top_footer'],
    Menu::BLOCK_POSITION__FOOTER => $LANG['menu_footer']
);

if ($edit)
{
	$menu = MenuService::load($id);
	
    if (!($menu instanceof ContentMenu))
    {
        AppContext::get_response()->redirect('menus.php');
    }
    
	$block = $menu->get_block();
	$content = $menu->get_content();
	
	$tpl->put_all(array(
		'IDMENU' => $id,
		'NAME' => $menu->get_title(),
		'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, $menu->get_auth()),
        'C_ENABLED' => $menu->is_enabled(),
		'CONTENTS' => !empty($content) ? FormatingHelper::unparse($content) : '',
	    'DISPLAY_TITLE_CHECKED' => $menu->get_display_title() ? 'checked="checked"' : ''
	));
}
else
{
   $tpl->put_all(array(
       'C_ENABLED' => true,
       'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, array(), array(-1 => true, 0 => true, 1 => true, 2 => true))
   ));
   
   // Create a new generic menu
    $menu = new ContentMenu('');
}

$locations = '';
foreach ($array_location as $key => $name) 
{
    $locations .= '<option value="' . $key . '" ' . (($block == $key) ? 'selected="selected"' : '') . '>' . $name . '</option>';
}


//Filtres
MenuAdminService::add_filter_fieldset($menu, $tpl);    


$tpl->put_all(array('LOCATIONS' => $locations));
$tpl->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');

?>