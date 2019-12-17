<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 28
 * @since       PHPBoost 2.0 - 2009 01 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$id = (int)retrieve(REQUEST, 'id', 0);
$post = (int)retrieve(POST, 'id', -1) >= 0;

$menu = MenuService::load($id);

if ($menu == null)
	AppContext::get_response()->redirect('auth.php');

if ($post)
{   // Edit a Menu authorizations
	$menu->enabled(retrieve(POST, 'activ', Menu::MENU_NOT_ENABLED));
	if ($menu->is_enabled())
	{
		$menu->set_block(retrieve(POST, 'location', Menu::BLOCK_POSITION__NOT_ENABLED));
	}
	$menu->set_hidden_with_small_screens((bool)retrieve(POST, 'hidden_with_small_screens', false));
	$menu->set_auth(Authorizations::build_auth_array_from_form(Menu::MENU_AUTH_BIT));

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
	'L_HIDDEN_WITH_SMALL_SCREENS' => $LANG['hidden_with_small_screens'],
	'L_AUTHS' => $LANG['auths'],
	'L_ENABLED' => LangLoader::get_message('enabled', 'common'),
	'L_DISABLED' => LangLoader::get_message('disabled', 'common'),
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

//Localisation possibles.
$block = $menu->get_block();
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

$locations = '';
foreach ($array_location as $key => $name)
{
	$locations .= '<option value="' . $key . '" ' . (($block == $key) ? 'selected="selected"' : '') . '>' . $name . '</option>';
}


$tpl->put_all(array(
	'IDMENU' => $id,
	'NAME' => $menu->get_formated_title(),
	'C_MENU_HIDDEN_WITH_SMALL_SCREENS' => $menu->is_hidden_with_small_screens(),
	'LOCATIONS' => $locations,
	'AUTH_MENUS' => Authorizations::generate_select(Menu::MENU_AUTH_BIT, $menu->get_auth()),
	'C_ENABLED' => $menu->is_enabled(),
));

//Filtres
MenuAdminService::add_filter_fieldset($menu, $tpl);

$tpl->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
