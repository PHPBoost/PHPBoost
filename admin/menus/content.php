<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 03 24
 * @since       PHPBoost 2.0 - 2008 11 23
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$id = (int)retrieve(REQUEST, 'id', 0);
$id_post = (int)retrieve(POST, 'id', 0);

$action = retrieve(REQUEST, 'action', '');
$action_post = retrieve(POST, 'action', '');

if ($action_post == 'save')
{
	// Save a Menu (New / Edit)
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

	if (!($menu instanceof ContentMenu))
	{
		AppContext::get_response()->redirect('menus.php');
	}

	$menu->enabled(retrieve(POST, 'activ', Menu::MENU_NOT_ENABLED));
	$menu->set_hidden_with_small_screens((bool)retrieve(POST, 'hidden_with_small_screens', false));
	$menu->set_auth(Authorizations::build_auth_array_from_form(Menu::MENU_AUTH_BIT));
	$menu->set_display_title(retrieve(POST, 'display_title', false));
	$menu->set_content(retrieve(POST, 'contents', '', TSTRING_UNCHANGE));

	//Filters
	MenuAdminService::set_retrieved_filters($menu);

	if ($menu->is_enabled())
	{
		$menu->set_block(retrieve(POST, 'location', Menu::BLOCK_POSITION__NOT_ENABLED));
	}
	if ($menu->is_enabled())
	{
		$block = retrieve(POST, 'location', Menu::BLOCK_POSITION__NOT_ENABLED);

		if ($menu->get_block() == $block)
		{   // Save the menu if enabled
			$menu->set_block_position($menu->get_block_position());
			MenuService::save($menu);
		}
		else
		{   // Move the menu to its new location and save it
			$menu->set_block($block);
			MenuService::move($menu, $menu->get_block());
		}
	}
	else
	{   // The menu is not enabled, we only save it with its block location
		// When enabling it, the menu will be moved to this block location
		$block = $menu->get_block();
		// Disable the menu and move it to the disabled position computing new positions
		MenuService::move($menu, Menu::BLOCK_POSITION__NOT_ENABLED);

		// Restore its position and save it
		$menu->set_block($block);
		MenuService::save($menu);
	}
	MenuService::generate_cache();
	AppContext::get_response()->redirect('menus.php#m' . $menu->get_id());
}

// Display the Menu administration
$edit = !empty($id);

include('lateral_menu.php');
lateral_menu();

$tpl = new FileTemplate('admin/menus/content.tpl');

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('contents');

$tpl->put_all(array(
	'KERNEL_EDITOR' => $editor->display(),
	'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
	'L_REQUIRE_NAME' => TextHelper::to_js_string($LANG['require_name']),
	'L_REQUIRE_TEXT' => TextHelper::to_js_string($LANG['require_text']),
	'L_NAME' => $LANG['name'],
	'L_CONTENT' => $LANG['content'],
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
	'L_ACTION_MENUS' => ($edit) ? $LANG['menus_edit'] : LangLoader::get_message('add', 'common'),
	'L_ACTION' => ($edit) ? $LANG['update'] : $LANG['submit'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
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
		'AUTH_MENUS' => Authorizations::generate_select(Menu::MENU_AUTH_BIT, $menu->get_auth()),
		'C_MENU_HIDDEN_WITH_SMALL_SCREENS' => $menu->is_hidden_with_small_screens(),
		'C_ENABLED' => $menu->is_enabled(),
		'CONTENTS' => !empty($content) ? FormatingHelper::unparse($content) : '',
		'DISPLAY_TITLE_CHECKED' => $menu->get_display_title() ? 'checked="checked"' : ''
	));
}
else
{
	$tpl->put_all(array(
		'C_ENABLED' => true,
		'AUTH_MENUS' => Authorizations::generate_select(Menu::MENU_AUTH_BIT, array(), array(-1 => true, 0 => true, 1 => true, 2 => true)),
		'DISPLAY_TITLE_CHECKED' => 'checked="checked"'
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
