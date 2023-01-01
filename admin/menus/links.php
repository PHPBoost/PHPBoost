<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 06
 * @since       PHPBoost 2.0 - 2008 11 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');

$lang = LangLoader::get_all_langs();

define('TITLE', $lang['menu.links.menu']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$menu_id = (int)retrieve(REQUEST, 'id', 0);
$action = retrieve(GET, 'action', '');

if ($action == 'save')
{   // Save a Menu (New / Edit)
	$menu_uid = (int)retrieve(POST, 'menu_uid', 0);

	// Properties of the menu we are creating/editing
	$type = retrieve(POST, 'menu_element_' . $menu_uid . '_type', LinksMenu::AUTOMATIC_MENU);

	function build_menu_from_form($elements_ids, $level = 0)
	{
		$menu = null;
		$menu_element_id = $elements_ids['id'];
		$menu_name  = retrieve(POST, 'menu_element_' . $menu_element_id . '_name', '', TSTRING_UNCHANGE);
		$menu_url   = retrieve(POST, 'menu_element_' . $menu_element_id . '_url', '');
		$menu_image = retrieve(POST, 'menu_element_' . $menu_element_id . '_image', '');
		$menu_icon  = retrieve(POST, 'menu_element_' . $menu_element_id . '_icon', '');

		$array_size = count($elements_ids);

		if ($array_size == 1 && $level > 0)
		{   // If it's a menu, there's only one element;
			$menu = new LinksMenuLink($menu_name, $menu_url, $menu_image, HTMLEmojisDecoder::decode_html_emojis($menu_icon));
		}
		else
		{
			$menu = new LinksMenu($menu_name, $menu_url, $menu_image, HTMLEmojisDecoder::decode_html_emojis($menu_icon));

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
	// If we edit the menu
	if ($menu_id > 0)
	{   // Edit the Menu
		$menu->id($menu_id);
		$previous_menu = MenuService::load($menu_id);
	}

	// Menu enabled?
	$menu->enabled(retrieve(POST, 'menu_element_' . $menu_uid . '_enabled', Menu::MENU_NOT_ENABLED));
	$menu->set_hidden_with_small_screens((bool)retrieve(POST, 'menu_element_' . $menu_uid . '_hidden_with_small_screens', false));
	$menu->set_disabled_body((bool)retrieve(POST, 'menu_element_' . $menu_uid . '_disabled_body', false));
	$menu->set_pushed_content((bool)retrieve(POST, 'menu_element_' . $menu_uid . '_pushed_content', false));
	$menu->set_block(retrieve(POST, 'menu_element_' . $menu_uid . '_location', Menu::BLOCK_POSITION__NOT_ENABLED));
	$menu->set_pushmenu_opening(retrieve(POST, 'menu_element_' . $menu_uid . '_open_type', Menu::PUSHMENU_LEFT));
	$menu->set_pushmenu_expanding(retrieve(POST, 'menu_element_' . $menu_uid . '_tab_type', Menu::PUSHMENU_OVERLAP));
	$menu->set_auth(Authorizations::build_auth_array_from_form(
		Menu::MENU_AUTH_BIT, 'menu_element_' . $menu_uid . '_auth'
	));

	// Filters
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

$view = new FileTemplate('admin/menus/links.tpl');
$view->add_lang($lang);

$view->put_all(array(
	'C_EDIT' => $menu_id > 0,
	'ACTION' => 'save',

	'J_AUTH_FORM' => str_replace(array("&quot;", "<!--", "-->"), array('"', "", ""), TextHelper::to_js_string(Authorizations::generate_select(Menu::MENU_AUTH_BIT, array('r-1' => Menu::MENU_AUTH_BIT, 'r0' => Menu::MENU_AUTH_BIT, 'r1' => Menu::MENU_AUTH_BIT), array(), 'menu_element_##UID##_auth'))),
));

// Possible locations
$block = retrieve(GET, 's', Menu::BLOCK_POSITION__HEADER, TINTEGER);
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

$edit_menu_tpl = new FileTemplate('admin/menus/menu_edition.tpl');
$edit_menu_tpl->add_lang($lang);

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
	$menu = new LinksMenu('', '', '', '', LinksMenu::AUTOMATIC_MENU);
}

$view->put_all(array(
	'C_ENABLED'                        => !empty($menu_id) ? $menu->is_enabled() : true,
	'C_MENU_HIDDEN_WITH_SMALL_SCREENS' => $menu->is_hidden_with_small_screens(),
	'C_PUSHMENU_DISABLED_BODY'         => $menu->is_disabled_body(),
	'C_PUSHMENU_PUSHED_CONTENT'        => $menu->is_pushed_content(),

	'AUTH_MENUS' => Authorizations::generate_select(Menu::MENU_AUTH_BIT, $menu->get_auth(), array(), 'menu_element_' . $menu->get_uid() . '_auth'),
	'MENU_ID'    => $menu->get_id(),
	'MENU_TREE'  => $menu->display($edit_menu_tpl, LinksMenuElement::LINKS_MENU_ELEMENT__FULL_DISPLAYING),
	'MENU_NAME'  => $menu->get_title(),
	'MENU_URL'   => $menu->get_url(true),
	'MENU_IMG'   => $menu->get_image(true),
	'MENU_ICON'  => $menu->get_icon(),
	'ID'         => $menu->get_uid()
));

foreach (LinksMenu::get_menu_types_list() as $type_name)
{
	$view->assign_block_vars('type', array(
		'NAME'     => $type_name,
		'L_NAME'   => $lang['menu.' . $type_name],
		'SELECTED' => $menu->get_type() == $type_name ? ' selected="selected"' : ''
	));
}

foreach ($array_location as $key => $name)
{
	$view->assign_block_vars('location', array(
		'C_SELECTED' => $block == $key,
		'VALUE'      => $key,
		'NAME'       => $name
	));
}

// Types of pushmenu opening
$array_opening = array(
	Menu::PUSHMENU_LEFT   => $lang['menu.push.opening.type.left'],
	Menu::PUSHMENU_RIGHT  => $lang['menu.push.opening.type.right'],
	Menu::PUSHMENU_TOP    => $lang['menu.push.opening.type.top'],
	Menu::PUSHMENU_BOTTOM => $lang['menu.push.opening.type.bottom']
);

foreach ($array_opening as $key => $name)
{
	$view->assign_block_vars('opening', array(
		'C_SELECTED' => $menu->get_pushmenu_opening() == $key,

		'VALUE' => $key,
		'NAME'  => $name
	));
}

// Types of pushmenu expanding tabs
$array_expanding = array(
	Menu::PUSHMENU_OVERLAP => $lang['menu.push.expansion.type.overlap'],
	Menu::PUSHMENU_EXPAND  => $lang['menu.push.expansion.type.expand'],
	Menu::PUSHMENU_NONE    => $lang['menu.push.expansion.type.none']
);

foreach ($array_expanding as $key => $name)
{
	$view->assign_block_vars('expanding', array(
		'C_SELECTED' => $menu->get_pushmenu_expanding() == $key,
		'VALUE' => $key,
		'NAME' => $name
	));
}

// Filters
MenuAdminService::add_filter_fieldset($menu, $view);

$view->put_all(array(
	'ID_MAX' => AppContext::get_uid()
));

$view->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');

?>
