<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 28
 * @since       PHPBoost 1.6 - 2007 03 05
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$id = (int)retrieve(GET, 'id', 0);
$switchtheme = retrieve(GET, 'theme', '');
$name_theme = !empty($switchtheme) ? $switchtheme : AppContext::get_current_user()->get_theme();
$theme_post = retrieve(POST, 'theme', '');

$action = retrieve(GET, 'action', '');
$move = retrieve(GET, 'move', '');

function menu_admin_link($menu, $mode)
{
	$link = '';
	switch ($mode)
	{
		case 'edit':
			if (($menu instanceof LinksMenu))
				$link = 'links.php?';
			elseif (($menu instanceof ContentMenu))
				$link = 'content.php?';
			elseif (($menu instanceof FeedMenu))
				$link = 'feed.php?';
			else
				$link = 'auth.php?';
			break;
		case 'delete':
			if (($menu instanceof ContentMenu) || ($menu instanceof LinksMenu) || ($menu instanceof FeedMenu))
				$link = 'menus.php?action=delete&amp;';
			else
				return '';
			break;
		case 'up':
			$link = 'menus.php?action=up&amp;';
		break;
		case 'down':
			$link = 'menus.php?action=down&amp;';
		break;
	}
	return $link . 'id=' . $menu->get_id() . '&amp;token=' . AppContext::get_session()->get_token();
}

if (!empty($id))
{
	$menu = MenuService::load($id);
	if ($menu == null)
		AppContext::get_response()->redirect('menus.php');

	// In GET mode so we check it
	AppContext::get_session()->csrf_get_protect();

	switch ($action)
	{
		case 'enable':
			MenuService::enable($menu);
			break;
		case 'disable':
			MenuService::disable($menu);
			break;
		case 'delete':
			MenuService::delete($id);
			break;
		case 'up':
		case 'down':
			// Move up or down a Menu in a block
			if ($action == 'up')
				MenuService::change_position($menu, MenuService::MOVE_UP);
			else
				MenuService::change_position($menu, MenuService::MOVE_DOWN);
		break;
		default:
			if (!empty($move))
			{   // Move a Menu
				MenuService::move($menu, $move);
			}
			break;
	}

	MenuService::generate_cache();
	AppContext::get_response()->redirect('menus.php' . ($action != 'delete' ? '#m' . $id : ''));
}

// Try to find out new mini-modules and delete old ones
MenuService::update_mini_modules_list(false);

// Retrieves all the menu
$menus_blocks = MenuService::get_menus_map();

function get_block($position)
{
	$blocks = array(
		Menu::BLOCK_POSITION__HEADER => 'mod_header',
		Menu::BLOCK_POSITION__SUB_HEADER => 'mod_subheader',
		Menu::BLOCK_POSITION__TOP_CENTRAL => 'mod_topcentral',
		Menu::BLOCK_POSITION__NOT_ENABLED => 'mod_central',
		Menu::BLOCK_POSITION__BOTTOM_CENTRAL => 'mod_bottomcentral',
		Menu::BLOCK_POSITION__TOP_FOOTER => 'mod_topfooter',
		Menu::BLOCK_POSITION__FOOTER => 'mod_footer',
		Menu::BLOCK_POSITION__LEFT => 'mod_left',
		Menu::BLOCK_POSITION__RIGHT => 'mod_right'
	);

	return $blocks[$position];
}

function save_position($block_position)
{
	$menus = MenuService::get_menu_list();

	$menus_tree = json_decode(TextHelper::html_entity_decode(AppContext::get_request()->get_value('menu_tree_' . get_block($block_position))));

	foreach ($menus_tree as $position => $tree)
	{
		$id = $tree->id;

		if (array_key_exists($id, $menus))
		{
			$menu = $menus[$id];
			$menu->set_block_position(($position + 1));
			MenuService::move($menu, $block_position, $menu->get_block_position());
		}
	}
}

if ($action == 'save') //Save menus positions.
{
	save_position(Menu::BLOCK_POSITION__HEADER);
	save_position(Menu::BLOCK_POSITION__SUB_HEADER);
	save_position(Menu::BLOCK_POSITION__TOP_CENTRAL);
	save_position(Menu::BLOCK_POSITION__BOTTOM_CENTRAL);
	save_position(Menu::BLOCK_POSITION__TOP_FOOTER);
	save_position(Menu::BLOCK_POSITION__FOOTER);
	save_position(Menu::BLOCK_POSITION__LEFT);
	save_position(Menu::BLOCK_POSITION__RIGHT);
	save_position(Menu::BLOCK_POSITION__NOT_ENABLED);

	$columns_disabled = new ColumnsDisabled();
	$columns_disabled->set_disable_header(!AppContext::get_request()->get_bool('header_enabled', false));
	$columns_disabled->set_disable_sub_header(!AppContext::get_request()->get_bool('sub_header_enabled', false));
	$columns_disabled->set_disable_top_central(!AppContext::get_request()->get_bool('top_central_enabled', false));
	$columns_disabled->set_disable_bottom_central(!AppContext::get_request()->get_bool('bottom_central_enabled', false));
	$columns_disabled->set_disable_top_footer(!AppContext::get_request()->get_bool('top_footer_enabled', false));
	$columns_disabled->set_disable_footer(!AppContext::get_request()->get_bool('footer_enabled', false));
	$columns_disabled->set_disable_left_columns(!AppContext::get_request()->get_bool('left_column_enabled', false));
	$columns_disabled->set_disable_right_columns(!AppContext::get_request()->get_bool('right_column_enabled', false));
	ThemesManager::change_columns_disabled($theme_post, $columns_disabled);

	MenuService::generate_cache();

	AppContext::get_response()->redirect('menus.php');
}


$tpl = new FileTemplate('admin/menus/menus.tpl');

$menu_template = new FileTemplate('admin/menus/menu.tpl');
$menu_template->put_all(array(
	'L_ENABLED' => LangLoader::get_message('enabled', 'common'),
	'L_DISABLED' => LangLoader::get_message('disabled', 'common'),
	'I_HEADER' => Menu::BLOCK_POSITION__HEADER,
	'I_SUBHEADER' => Menu::BLOCK_POSITION__SUB_HEADER,
	'I_TOPCENTRAL' => Menu::BLOCK_POSITION__TOP_CENTRAL,
	'I_BOTTOMCENTRAL' => Menu::BLOCK_POSITION__BOTTOM_CENTRAL,
	'I_TOPFOOTER' => Menu::BLOCK_POSITION__TOP_FOOTER,
	'I_FOOTER' => Menu::BLOCK_POSITION__FOOTER,
	'I_LEFT' => Menu::BLOCK_POSITION__LEFT,
	'I_RIGHT' => Menu::BLOCK_POSITION__RIGHT,
	'U_TOKEN' => AppContext::get_session()->get_token()
));

foreach ($menus_blocks as $block_id => $menus)
{
	// For each block
	$i = 0;
	$max = count($menus);
	foreach ($menus as $menu)
	{   // For each Menu in this block
		$menu_tpl = clone $menu_template;

		$id = $menu->get_id();
		$enabled = $menu->is_enabled();

		if (!$enabled)
		   $block_id = Menu::BLOCK_POSITION__NOT_ENABLED;

		$edit_link = menu_admin_link($menu, 'edit');
		$del_link = menu_admin_link($menu, 'delete');

		$mini = in_array($block_id, array(Menu::BLOCK_POSITION__LEFT, Menu::BLOCK_POSITION__NOT_ENABLED, Menu::BLOCK_POSITION__RIGHT));
		$vertical_position = in_array($block_id, array(Menu::BLOCK_POSITION__LEFT, Menu::BLOCK_POSITION__RIGHT));

		$menu_tpl->put_all(array(
			'NAME' => $menu->get_formated_title(),
			'IDMENU' => $id,
			'CONTENTS' => $menu->admin_display(),
			'ACTIV' => ($enabled ? 'disable' : 'enable'),
			'UNACTIV' => ($enabled ? 'enable' : 'disable'),
			'C_MENU_ACTIVATED' => $enabled,
			'C_EDIT' => !empty($edit_link),
			'C_DEL' => !empty($del_link),
			'C_UP' => $block_id != Menu::BLOCK_POSITION__NOT_ENABLED && $i > 0,
			'C_DOWN' => $block_id != Menu::BLOCK_POSITION__NOT_ENABLED && $i < $max - 1,
			'C_VERTICAL' => $vertical_position,
			'C_HORIZONTAL' => !$vertical_position,
			'L_DEL' => LangLoader::get_message('delete', 'common'),
			'L_EDIT' => LangLoader::get_message('edit', 'common'),
			'L_ACTIVATE' => LangLoader::get_message('enable', 'common'),
			'L_UNACTIVATE' => LangLoader::get_message('disable', 'common'),
			'L_MOVE_UP' => $LANG['move_up'],
			'L_MOVE_DOWN' => $LANG['move_down'],
			'U_EDIT' => menu_admin_link($menu, 'edit'),
			'U_DELETE' => menu_admin_link($menu, 'delete'),
			'U_UP' => menu_admin_link($menu, 'up'),
			'U_DOWN' => menu_admin_link($menu, 'down'),
			'U_MOVE' => menu_admin_link($menu, 'move'),
		));

		$tpl->assign_block_vars(get_block($block_id), array('MENU' => $menu_tpl->render()));
		$i++;
	}
}

foreach(ThemesManager::get_activated_themes_map() as $theme => $properties)
{
	$configuration = $properties->get_configuration();
	$selected = (empty($name_theme) ? AppContext::get_current_user()->get_theme() == $theme : $name_theme == $theme) ? ' selected="selected"' : '';
	$tpl->assign_block_vars('themes', array(
		'NAME' => $configuration->get_name(),
		'IDNAME' => $theme,
		'SELECTED' => $selected
	));
}

$columns_disable = ThemesManager::get_theme($name_theme)->get_columns_disabled();

$tpl->put_all(array(
	'NAME_THEME' => $name_theme,
	'CHECKED_RIGHT_COLUMN' => !$columns_disable->right_columns_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_LEFT_COLUMN' => !$columns_disable->left_columns_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_FOOTER_COLUMN' => !$columns_disable->footer_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_TOP_FOOTER_COLUMN' => !$columns_disable->top_footer_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_BOTTOM_CENTRAL_COLUMN' => !$columns_disable->bottom_central_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_TOP_CENTRAL_COLUMN' => !$columns_disable->top_central_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_SUB_HEADER_COLUMN' => !$columns_disable->sub_header_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_HEADER_COLUMN' => !$columns_disable->header_is_disabled() ? 'checked="checked"' : '',
	'C_RIGHT_COLUMN' => $columns_disable->right_columns_is_disabled(),
	'C_LEFT_COLUMN' => $columns_disable->left_columns_is_disabled(),
	'C_FOOTER_COLUMN' => $columns_disable->footer_is_disabled(),
	'C_TOP_FOOTER_COLUMN' => $columns_disable->top_footer_is_disabled(),
	'C_BOTTOM_CENTRAL_COLUMN' => $columns_disable->bottom_central_is_disabled(),
	'C_TOP_CENTRAL_COLUMN' => $columns_disable->top_central_is_disabled(),
	'C_SUB_HEADER_COLUMN' => $columns_disable->sub_header_is_disabled(),
	'C_HEADER_COLUMN' => $columns_disable->header_is_disabled(),
	'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
	'START_PAGE' => Environment::get_home_page(),
	'L_INDEX' => $LANG['home'],
	'L_CONFIRM_DEL_MENU' => LangLoader::get_message('confirm.delete', 'status-messages-common'),
	'L_MOVETO' => $LANG['moveto'],
	'L_GUEST' => $LANG['guest'],
	'L_USER' => $LANG['member'],
	'L_MODO' => $LANG['modo'],
	'L_ADMIN' => $LANG['admin'],
	'L_HEADER' => $LANG['menu_header'],
	'L_SUB_HEADER' => $LANG['menu_subheader'],
	'L_LEFT_MENU' => $LANG['menu_left'],
	'L_RIGHT_MENU' => $LANG['menu_right'],
	'L_TOP_CENTRAL_MENU' => $LANG['menu_top_central'],
	'L_BOTTOM_CENTRAL_MENU' => $LANG['menu_bottom_central'],
	'L_TOP_FOOTER' => $LANG['menu_top_footer'],
	'L_FOOTER' => $LANG['menu_footer'],
	'L_ADD_MENU' => $LANG['menus_add'],
	'L_ADD_CONTENT_MENUS' => $LANG['menus_content_add'],
	'L_ADD_LINKS_MENUS' => $LANG['menus_links_add'],
	'L_ADD_FEED_MENUS' => $LANG['menus_feed_add'],
	'L_VALID_POSTIONS' => $LANG['valid_position_menus'],
	'L_THEME_MANAGEMENT' => $LANG['theme_management'],
	'I_HEADER' => Menu::BLOCK_POSITION__HEADER,
	'I_SUBHEADER' => Menu::BLOCK_POSITION__SUB_HEADER,
	'I_TOPCENTRAL' => Menu::BLOCK_POSITION__TOP_CENTRAL,
	'I_BOTTOMCENTRAL' => Menu::BLOCK_POSITION__BOTTOM_CENTRAL,
	'I_TOPFOOTER' => Menu::BLOCK_POSITION__TOP_FOOTER,
	'I_FOOTER' => Menu::BLOCK_POSITION__FOOTER,
	'I_LEFT' => Menu::BLOCK_POSITION__LEFT,
	'I_RIGHT' => Menu::BLOCK_POSITION__RIGHT,
	'L_MENUS_AVAILABLE' => count($menus_blocks[Menu::BLOCK_POSITION__NOT_ENABLED]) ? $LANG['available_menus'] : $LANG['no_available_menus'],
	'L_SUBMIT' => $LANG['submit'],
	'L_RESET' => $LANG['reset'],
	'U_TOKEN' => AppContext::get_session()->get_token()
));
$tpl->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
