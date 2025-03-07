<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 18
 * @since       PHPBoost 2.0 - 2009 02 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');

$lang = LangLoader::get_all_langs();

define('TITLE', $lang['menu.feed.menu']);
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
	$menu_url = retrieve(POST, 'feed_url', '', TSTRING_UNCHANGE);
	$menu_items_number = retrieve(POST, 'items_number', 0, TSTRING_UNCHANGE);
	$matches = array();
	preg_match('`/rss/(.+)/([0-9]+)/(.+)/`', $menu_url, $matches);

	if (!empty($id_post))
	{   // Edit the Menu
		$menu = MenuService::load($id_post);
		$menu->set_title($menu_name);
		$menu->set_module_id($matches[1]);
		$menu->set_cat($matches[2]);
		$menu->set_name($matches[3]);
		$menu->set_number($menu_items_number);
	}
	else
	{   // Add the new Menu
		$menu = new FeedMenu($menu_name, $matches[1], $matches[2], $matches[3], $menu_items_number);
	}

	if (!($menu instanceof FeedMenu))
	AppContext::get_response()->redirect('menus.php');

	$menu->enabled(retrieve(POST, 'activ', Menu::MENU_NOT_ENABLED));
	$menu->set_hidden_with_small_screens((bool)retrieve(POST, 'hidden_with_small_screens', false));

	$menu->set_auth(Authorizations::build_auth_array_from_form(Menu::MENU_AUTH_BIT));

	// Filters
	MenuAdminService::set_retrieved_filters($menu);

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

$view = new FileTemplate('admin/menus/feed.tpl');
$view->add_lang($lang);

$view->put_all(array(
	'C_EDIT' => $edit,
	'ACTION' => 'save',
));

// Possible locations.
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

$feed_url = '';
if ($edit)
{
	$menu = MenuService::load($id);

	if (!($menu instanceof FeedMenu))
	AppContext::get_response()->redirect('menus.php');

	$block = $menu->get_block();
	$feed_url = $menu->get_url(true);

	$view->put_all(array(
		'C_MENU_HIDDEN_WITH_SMALL_SCREENS' => $menu->is_hidden_with_small_screens(),
		'C_ENABLED' => $menu->is_enabled(),

		'MENU_ID'       => $id,
		'NAME'         => $menu->get_title(),
		'ITEMS_NUMBER' => $menu->get_number(),
		'AUTH_MENUS'   => Authorizations::generate_select(Menu::MENU_AUTH_BIT, $menu->get_auth()),
	));
}
else
{
	$view->put_all(array(
		'C_NEW'     => true,
		'C_ENABLED' => true,

		'ITEMS_NUMBER' => 10,
		'AUTH_MENUS'   => Authorizations::generate_select(Menu::MENU_AUTH_BIT, array(), array(-1 => true, 0 => true, 1 => true, 2 => true))
	));

	// Create a new generic menu
	$menu = new FeedMenu('', '', '');
}

function get_feeds($feed_cat, $module_id, $feed_type, $feed_url_edit = '', $level = 0)
{
	return get_feeds_children($feed_cat->get_children(), $module_id, $feed_type, $level +1, $feed_url_edit);
}

function get_feeds_children(Array $children, $module_id, $feed_type, $level, $feed_url_edit = '')
{
	if (!empty($children))
	{
		foreach ($children as $id => $feed_cat)
		{
			$url = $feed_cat->get_url($feed_type);

			$urls[] = array(
				'name'      => $feed_cat->get_category_name(),
				'url'       => $url,
				'level'     => $level,
				'feed_name' => $feed_type,
				'selected'  => $feed_url_edit == $url
			);
		}

		return array_merge($urls, get_feeds_children($feed_cat->get_children(), $module_id, $feed_type, $level +1, $feed_url_edit));
	}
	return array();
}

$feeds_modules = AppContext::get_extension_provider_service()->get_providers(FeedProvider::EXTENSION_POINT);

foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $id => $module)
{
	if (array_key_exists($module->get_id(), $feeds_modules))
	{
		$list = $feeds_modules[$module->get_id()]->get_extension_point(FeedProvider::EXTENSION_POINT);
		if ($list !== null && $list !== false)
		{
			$list = $list->get_feeds_list();

			foreach ($list->get_feeds_list() as $feed_type => $object)
			{
				$urls = get_feeds($object, $module->get_id(), $feed_type, $feed_url);

				$root[0] = array(
					'name'      => $object->get_category_name(),
					'url'       => $object->get_url($feed_type),
					'level'     => 0,
					'feed_name' => null,
					'selected'  => $feed_url == $object->get_url($feed_type)
				);
			}

			$urls = array_merge($root, $urls);
			$view->assign_block_vars('modules', array('NAME' => TextHelper::ucfirst($module->get_configuration()->get_name())));

			foreach ($urls as $url)
			{
				$view->assign_block_vars('modules.feeds_urls', array(
					'URL'       => $url['url'],
					'NAME'      => $url['name'],
					'SPACE'     => str_repeat('--', $url['level']),
					'FEED_NAME' => $url['feed_name'] != 'master' ? $url['feed_name'] : null,
					'SELECTED'  => $url['selected'] ? ' selected="selected"' : ''
				));
			}
		}
	}
}

$locations = '';
foreach ($array_location as $key => $name)
{
	$locations .= '<option value="' . $key . '" ' . (($block == $key) ? 'selected="selected"' : '') . '>' . $name . '</option>';
}

// Filters
MenuAdminService::add_filter_fieldset($menu, $view);

$view->put_all(array('LOCATIONS' => $locations));
$view->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');

?>
