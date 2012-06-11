<?php
/*##################################################
 *                               auth.php
 *                            -------------------
 *   begin                : January 2009
 *   copyright            : (C) 2009 Loic Rouchon
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

$id = retrieve(REQUEST, 'id', 0);
$post = retrieve(POST, 'id', -1) >= 0 ? true : false;

$menu = MenuService::load($id);

if ($menu == null)
    AppContext::get_response()->redirect('auth.php');
        
if ($post)
{   // Edit a Menu authorizations
    $menu->enabled(retrieve(POST, 'activ', Menu::MENU_NOT_ENABLED));
    $menu->set_auth(Authorizations::build_auth_array_from_form(AUTH_MENUS));
    
    //Filters
    MenuAdminService::set_retrieved_filters($menu);
    
    MenuService::save($menu);
    MenuService::generate_cache();
    
    AppContext::get_response()->redirect('menus.php#m' . $id);
}

// Display the Menu dispositions
include('lateral_menu.php');
lateral_menu();

$tpl = new FileTemplate('admin/menus/auth.tpl');

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('contents');

$tpl->put_all(array(
    'KERNEL_EDITOR' => $editor->display(),
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
    'L_ACTION_MENUS' => $LANG['menus_edit'],
    'L_ACTION' => $LANG['update'],
    'L_RESET' => $LANG['reset'],
    'ACTION' => 'save',
));


$tpl->put_all(array(
    'IDMENU' => $id,
    'NAME' => $menu->get_title(),
    'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, $menu->get_auth()),
    'C_ENABLED' => $menu->is_enabled(),
));

//Filtres
MenuAdminService::add_filter_fieldset($menu, $tpl);    

$tpl->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
