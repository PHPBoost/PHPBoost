<?php
/*##################################################
 *                           content.php
 *                          -------------------
 *   begin                : February 17, 2009
 *   copyright            : (C) 2009 Loïc Rouchon
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
{
    // Save a Menu (New / Edit)
    import('content/parser/parser');
    $menu = null;
    
    $menu_name = retrieve(POST, 'name', '', TSTRING_UNCHANGE);
    
    if (!empty($id_post))
    {   // Edit the Menu
        $menu = MenuService::load($id_post);
        $menu->set_title($menu_name);
    }
    else
    {   // Add the new Menu
        $menu = new ContentMenu($menu_name);
    }
    
    if (!of_class($menu, CONTENT_MENU__CLASS))
        redirect('menus.php');
    
    $menu->enabled(retrieve(POST, 'activ', MENU_NOT_ENABLED));
    if ($menu->is_enabled())
        $menu->set_block(retrieve(POST, 'location', BLOCK_POSITION__NOT_ENABLED));
    $menu->set_auth(Authorizations::build_auth_array_from_form(AUTH_MENUS));
    $menu->set_content((string) $_POST['contents']);
    
    MenuService::save($menu);
    MenuService::generate_cache();
	
	redirect('menus.php#m' . $id_post);
}

// Display the Menu administration
$edit = !empty($id);

include('lateral_menu.php');
lateral_menu();

$tpl = new Template('admin/menus/feed.tpl');

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
	$menu = MenuService::load($id);
	
    if (!of_class($menu, FEED_MENU__CLASS))
        redirect('menus.php');
    
	$block = $menu->get_block();
	$feed_url = $menu->get_url();
	
	$tpl->assign_vars(array(
		'IDMENU' => $id,
		'NAME' => $menu->get_title(),
		'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, $menu->get_auth()),
        'C_ENABLED' => $menu->is_enabled(),
	));
}
else
{
   $tpl->assign_vars(array(
       'C_ENABLED' => true,
       'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, array(), array(-1 => true, 0 => true, 1 => true, 2 => true))
   ));
}

import('util/url');
import('modules/modules_discovery_service');

$modules = new ModulesDiscoveryService();
$feeds_modules = $modules->get_available_modules('get_feeds_list');

function build_feed_urls(&$list, $module_id, $level = 0)
{
	$urls = array();
	
	foreach ($list as $elt)
	{
		foreach ($elt['feeds_names'] as $name)
		{
			$urls[] = array(
				'name' => $elt['name'],
				'url' => '/syndication.php?m=' . $module_id . '&amp;cat=' . $elt['id'] . '&amp;name=' . $name,
				'level' => $level,
				'feed_name' => $name
			);
		}
		
		$urls = array_merge($urls, build_feed_urls($elt['children'], $module_id, ++$level));
	}
	
	return $urls;
}

foreach ($feeds_modules as $module)
{
	$list = $module->functionnality('get_feeds_list');
	$urls = build_feed_urls($list, $module->get_id());
	$root_feed_url = new Url($urls[0]['url']);
	$tpl->assign_block_vars('modules', array('NAME' => $module->get_id(), 'URL' => $root_feed_url->absolute()));
	foreach ($urls as $url)
	{
		$tpl->assign_block_vars('modules.feeds_urls', array(
			'URL' => $url['url'],
			'NAME' => $url['name'],
			'SPACE' => '--' . str_repeat('------', $url['level']),
			'FEED_NAME' => $url['feed_name'] != 'master' ? $url['feed_name'] : null
		));
	}
}

$locations = '';
foreach ($array_location as $key => $name)
    $locations .= '<option value="' . $key . '" ' . (($block == $key) ? 'selected="selected"' : '') . '>' . $name . '</option>';

$tpl->assign_vars(array('LOCATIONS' => $locations));
$tpl->parse();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');

?>