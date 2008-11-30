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

$id = retrieve(GET, 'id', 0);
$idmodule = retrieve(GET, 'idmodule', '', TSTRING_UNSECURE);
$edit = retrieve(GET, 'edit', false);
$type = retrieve(GET, 'type', 1);
$id_post = retrieve(POST, 'id', 0);
$action = retrieve(POST, 'action', '');

//Si c'est confirmé on execute
if ($action == 'edit' && !empty($id_post)) //Modification d'un menu déjà existant.
{
    
    $menu = MenuService::load($id_post);
    $menu->enabled(retrieve(POST, 'activ', MENU_NOT_ENABLED));
    if ($menu->is_enabled())
        $menu->set_block(retrieve(POST, 'location', BLOCK_POSITION__NOT_ENABLED));
    $menu->set_auth(Authorizations::build_auth_array_from_form(AUTH_MENUS));
    $menu->set_content(!empty($_POST['contents']) ? strparse($_POST['contents']) : '');
    
    MenuService::save($menu);
    MenuService::generate_cache();
    
    redirect(HOST . DIR . '/admin/menus/menus.php#m' . $id_post);
}
elseif ($action == 'add') //Ajout d'un menu.
{
    $menu = new ContentMenu(retrieve(POST, 'name', ''));
    $menu->enabled(retrieve(POST, 'activ', MENU_NOT_ENABLED));
    if ($menu->is_enabled())
        $menu->set_block(retrieve(POST, 'location', BLOCK_POSITION__NOT_ENABLED));
    $menu->set_auth(Authorizations::build_auth_array_from_form(AUTH_MENUS));
    $menu->set_content(!empty($_POST['contents']) ? strparse($_POST['contents']) : '');
    
    MenuService::save($menu);
    MenuService::generate_cache();
    
    redirect(HOST . DIR . '/admin/menus/menus.php#m' . $last_menu_id);
}
elseif ($action =='delete' && !empty($id)) //Suppression du menu.
{
    MenuService::delete($id);
    MenuService::generate_cache();
    
    redirect(HOST . DIR . '/admin/menus/menus.php');
}
else
{
    $tpl = new Template('admin/menus/panel.tpl');
    $tpl->assign_vars(array(
        'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
        'L_ADD_CONTENT_MENUS' => $LANG['menus_content_add'],
        'L_ADD_LINKS_MENUS' => $LANG['menus_links_add'],
    ));
    $tpl->parse();
    
    $tpl = new Template('admin/menus/links.tpl');

    $tpl->assign_vars(array(
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
        'L_USER' => $LANG['member'],
        'L_MODO' => $LANG['modo'],
        'L_ADMIN' => $LANG['admin'],
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
        'L_RESET' => $LANG['reset'],
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
    
    $array_auth_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
    if ($edit)
    {
        $menu = MenuService::load($id);
        
        //Localisation possibles.
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
        
        $locations = '';
        foreach ($array_location as $key => $name)
        {
            $selected = ($menu->get_block() == $key) ? 'selected="selected"' : '';
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

        $tpl->assign_vars(array(
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
            'CONTENTS' => !empty($menu['contents']) ? unparse($menu['contents']) : ''
        ));
    }
    
    $tpl->parse();
    require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
}

?>