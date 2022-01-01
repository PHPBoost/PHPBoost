<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 2.0 - 2009 01 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');

$lang = LangLoader::get_all_langs();

define('TITLE', $lang['menu.administration']);
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

	// Filters
	MenuAdminService::set_retrieved_filters($menu);

	MenuService::save($menu);
	MenuService::generate_cache();

	AppContext::get_response()->redirect('menus.php#m' . $id);
}

// Display the Menu dispositions
include('lateral_menu.php');
lateral_menu();

$view = new FileTemplate('admin/menus/auth.tpl');
$view->add_lang($lang);

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('contents');

$view->put_all(array(
	'KERNEL_EDITOR' => $editor->display(),
	'ACTION'        => 'save',
));

// Possible Locations.
$block = $menu->get_block();
$array_location = array(
	Menu::BLOCK_POSITION__TOP_HEADER     => $lang['menu.top.header'],
	Menu::BLOCK_POSITION__HEADER         => $lang['menu.header'],
	Menu::BLOCK_POSITION__SUB_HEADER     => $lang['menu.sub.header'],
	Menu::BLOCK_POSITION__LEFT           => $lang['menu.left'],
	Menu::BLOCK_POSITION__TOP_CENTRAL    => $lang['menu.top.central'],
	Menu::BLOCK_POSITION__BOTTOM_CENTRAL => $lang['menu.bottom.central'],
	Menu::BLOCK_POSITION__RIGHT          => $lang['menu.right'],
	Menu::BLOCK_POSITION__TOP_FOOTER     => $lang['menu.top.footer'],
	Menu::BLOCK_POSITION__FOOTER         => $lang['menu.footer']
);

$locations = '';
foreach ($array_location as $key => $name)
{
	$locations .= '<option value="' . $key . '" ' . (($block == $key) ? 'selected="selected"' : '') . '>' . $name . '</option>';
}

$view->put_all(array(
	'C_ENABLED'                        => $menu->is_enabled(),
	'C_MENU_HIDDEN_WITH_SMALL_SCREENS' => $menu->is_hidden_with_small_screens(),

	'MENU_ID'    => $id,
	'NAME'       => $menu->get_formated_title(),
	'LOCATIONS'  => $locations,
	'AUTH_MENUS' => Authorizations::generate_select(Menu::MENU_AUTH_BIT, $menu->get_auth()),
));

// Filters
MenuAdminService::add_filter_fieldset($menu, $view);

$view->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
