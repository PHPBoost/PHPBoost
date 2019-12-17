<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 28
 * @since       PHPBoost 2.0 - 2008 11 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$menu_id = (int)retrieve(REQUEST, 'id', 0);
$action = retrieve(GET, 'action', '');

if ($action == 'save')
{   // Save a Menu (New / Edit)
	$menu_uid = (int)retrieve(POST, 'menu_uid', 0);

	//Properties of the menu we are creating/editing
	$type = retrieve(POST, 'menu_element_' . $menu_uid . '_type', LinksMenu::AUTOMATIC_MENU);

	function build_menu_from_form($elements_ids, $level = 0)
	{
		$menu = null;
		$menu_element_id = $elements_ids['id'];
		$menu_name = retrieve(POST, 'menu_element_' . $menu_element_id . '_name', '', TSTRING_UNCHANGE);
		$menu_url = retrieve(POST, 'menu_element_' . $menu_element_id . '_url', '');
		$menu_image = retrieve(POST, 'menu_element_' . $menu_element_id . '_image', '');

		$array_size = count($elements_ids);

		if ($array_size == 1 && $level > 0)
		{   // If it's a menu, there's only one element;
			$menu = new LinksMenuLink($menu_name, $menu_url, $menu_image);
		}
		else
		{
			$menu = new LinksMenu($menu_name, $menu_url, $menu_image);

			// We unset the id key of the array
			unset($elements_ids['id']);

			$array_size = count($elements_ids);
			for ($i = 0; $i < $array_size; $i++)
			{
				// We build all its children and add it to its father
				$menu->add(build_menu_from_form($elements_ids[$i], $level + 1));
			}
		}

		$menu->set_auth(Authorizations::build_auth_array_from_form(
			Menu::MENU_AUTH_BIT, 'menu_element_' . $menu_element_id . '_auth')
		);
		return $menu;
	}

	function build_menu_children_tree($element)
	{
		$menu = array();

		if (isset($element->children))
		{
			$children = array();
			foreach($element->children[0] as $p => $t)
			{
				$menu_child_name = retrieve(POST, 'menu_element_' . $t->id . '_name', '', TSTRING_UNCHANGE);
				if (!empty($menu_child_name))
					$children[$p] = build_menu_children_tree($t);
			}
			$menu = array_merge(
				array('id' => $element->id),
				$children
			);
		}
		else
			$menu = array('id' => $element->id);

		return $menu;
	}

	$menu_tree = array('id' => $menu_uid);
	$links_list = json_decode(TextHelper::html_entity_decode(AppContext::get_request()->get_value('menu_tree')));

	foreach($links_list as $position => $tree)
	{
		$menu_tree[$position] = build_menu_children_tree($tree);
	}

	// We build the menu
	$menu = build_menu_from_form($menu_tree);
	$menu->set_type($type);

	$previous_menu = null;
	//If we edit the menu
	if ($menu_id > 0)
	{   // Edit the Menu
		$menu->id($menu_id);
		$previous_menu = MenuService::load($menu_id);
	}

	//Menu enabled?
	$menu->enabled(retrieve(POST, 'menu_element_' . $menu_uid . '_enabled', Menu::MENU_NOT_ENABLED));
	$menu->set_hidden_with_small_screens((bool)retrieve(POST, 'menu_element_' . $menu_uid . '_hidden_with_small_screens', false));
	$menu->set_block(retrieve(POST, 'menu_element_' . $menu_uid . '_location', Menu::BLOCK_POSITION__NOT_ENABLED));
	$menu->set_auth(Authorizations::build_auth_array_from_form(
		Menu::MENU_AUTH_BIT, 'menu_element_' . $menu_uid . '_auth'
	));

	//Filters
	MenuAdminService::set_retrieved_filters($menu);

	if ($menu->is_enabled())
	{
		if ($previous_menu != null && $menu->get_block() == $previous_menu->get_block())
		{   // Save the menu if enabled
			$menu->set_block_position($previous_menu->get_block_position());
			MenuService::save($menu);
		}
		else
		{   // Move the menu to its new location and save it
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

include('lateral_menu.php');
lateral_menu();

$tpl = new FileTemplate('admin/menus/links.tpl');

$tpl->put_all(array(
	'L_NAME' => $LANG['name'],
	'L_URL' => $LANG['url'],
	'L_IMAGE' => LangLoader::get_message('form.picture', 'common'),
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
	'L_ACTION_MENUS' => ($menu_id > 0) ? $LANG['menus_edit'] : LangLoader::get_message('add', 'common'),
	'L_ACTION' => ($menu_id > 0) ? $LANG['update'] : $LANG['submit'],
	'L_RESET' => $LANG['reset'],
	'ACTION' => 'save',
	'L_TYPE' => $LANG['type'],
	'L_CONTENT' => $LANG['content'],
	'L_AUTHORIZATIONS' => $LANG['authorizations'],
	'L_ADD' => LangLoader::get_message('add', 'common'),
	'L_REQUIRE_NAME' => $LANG['require_name'],
	'J_AUTH_FORM' => str_replace(array("&quot;", "<!--", "-->"), array('"', "", ""), TextHelper::to_js_string(Authorizations::generate_select(Menu::MENU_AUTH_BIT, array('r-1' => Menu::MENU_AUTH_BIT, 'r0' => Menu::MENU_AUTH_BIT, 'r1' => Menu::MENU_AUTH_BIT), array(), 'menu_element_##UID##_auth'))),
	'JL_AUTHORIZATIONS' => TextHelper::to_js_string($LANG['authorizations']),
	'JL_PROPERTIES' => TextHelper::to_js_string($LANG['properties']),
	'JL_NAME' => TextHelper::to_js_string($LANG['name']),
	'JL_URL' => TextHelper::to_js_string($LANG['url']),
	'JL_IMAGE' => TextHelper::to_js_string(LangLoader::get_message('form.picture', 'common')),
	'JL_DELETE_ELEMENT' => TextHelper::to_js_string(LangLoader::get_message('confirm.delete', 'status-messages-common')),
	'JL_MORE' => TextHelper::to_js_string($LANG['more_details']),
	'JL_DELETE' => TextHelper::to_js_string(LangLoader::get_message('delete', 'common')),
	'JL_ADD_SUB_ELEMENT' => TextHelper::to_js_string($LANG['add_sub_element']),
	'JL_ADD_SUB_MENU' => TextHelper::to_js_string($LANG['add_sub_menu']),
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

$edit_menu_tpl = new FileTemplate('admin/menus/menu_edition.tpl');
$edit_menu_tpl->put_all(array(
	'L_NAME' => $LANG['name'],
	'L_IMAGE' => LangLoader::get_message('form.picture', 'common'),
	'L_URL' => $LANG['url'],
	'L_PROPERTIES' => $LANG['properties'],
	'L_AUTHORIZATIONS' => $LANG['authorizations'],
	'L_ADD_SUB_ELEMENT' => $LANG['add_sub_element'],
	'L_ADD_SUB_MENU' => $LANG['add_sub_menu'],
	'L_MORE' => $LANG['more_details'],
	'L_DELETE' => LangLoader::get_message('delete', 'common')
));

$menu = null;
if ($menu_id > 0)
{
	$menu = MenuService::load($menu_id);

	if (!($menu instanceof LinksMenu))
		AppContext::get_response()->redirect('menus.php');

	$block = $menu->get_block();
}
else
{   // Create a new generic menu
	$menu = new LinksMenu('', '', '', LinksMenu::AUTOMATIC_MENU);
}

$tpl->put_all(array(
	'IDMENU' => $menu_id,
	'AUTH_MENUS' => Authorizations::generate_select(
		Menu::MENU_AUTH_BIT, $menu->get_auth(), array(), 'menu_element_' . $menu->get_uid() . '_auth'
	),
	'C_ENABLED' => !empty($menu_id) ? $menu->is_enabled() : true,
	'C_MENU_HIDDEN_WITH_SMALL_SCREENS' => $menu->is_hidden_with_small_screens(),
	'MENU_ID' => $menu->get_id(),
	'MENU_TREE' => $menu->display($edit_menu_tpl, LinksMenuElement::LINKS_MENU_ELEMENT__FULL_DISPLAYING),
	'MENU_NAME' => $menu->get_title(),
	'MENU_URL' => $menu->get_url(true),
	'MENU_IMG' => $menu->get_image(true),
	'ID' => $menu->get_uid()
));

foreach (LinksMenu::get_menu_types_list() as $type_name)
{
	$tpl->assign_block_vars('type', array(
		'NAME' => $type_name,
		'L_NAME' => $LANG[$type_name . '_menu'],
		'SELECTED' => $menu->get_type() == $type_name ? ' selected="selected"' : ''
	));
}

foreach ($array_location as $key => $name)
{
	$tpl->assign_block_vars('location', array(
		'C_SELECTED' => $block == $key,
		'VALUE' => $key,
		'NAME' => $name
	));
}

//Filtres
MenuAdminService::add_filter_fieldset($menu, $tpl);

$tpl->put_all(array(
	'ID_MAX' => AppContext::get_uid()
));

$tpl->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');

?>
