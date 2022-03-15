<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 11
 * @since       PHPBoost 1.6 - 2007 03 05
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');

$lang = LangLoader::get_all_langs();

define('TITLE', $lang['menu.menus.management']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$id = (int)retrieve(GET, 'id', 0);
$switchtheme = retrieve(GET, 'theme', '');
$theme_name = !empty($switchtheme) ? $switchtheme : AppContext::get_current_user()->get_theme();
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
		Menu::BLOCK_POSITION__TOP_HEADER     => 'mod_topheader',
		Menu::BLOCK_POSITION__HEADER         => 'mod_header',
		Menu::BLOCK_POSITION__SUB_HEADER     => 'mod_subheader',
		Menu::BLOCK_POSITION__TOP_CENTRAL    => 'mod_topcentral',
		Menu::BLOCK_POSITION__NOT_ENABLED    => 'mod_central',
		Menu::BLOCK_POSITION__BOTTOM_CENTRAL => 'mod_bottomcentral',
		Menu::BLOCK_POSITION__TOP_FOOTER     => 'mod_topfooter',
		Menu::BLOCK_POSITION__FOOTER         => 'mod_footer',
		Menu::BLOCK_POSITION__LEFT           => 'mod_left',
		Menu::BLOCK_POSITION__RIGHT          => 'mod_right'
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

if ($action == 'save') // Save menus positions.
{
	save_position(Menu::BLOCK_POSITION__TOP_HEADER);
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
	$columns_disabled->set_disable_top_header(!AppContext::get_request()->get_bool('top_header_enabled', false));
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

	AppContext::get_response()->redirect('menus.php#saved');
}


$view = new FileTemplate('admin/menus/menus.tpl');
$view->add_lang($lang);

$menu_template = new FileTemplate('admin/menus/menu.tpl');
$menu_template->add_lang($lang);
$menu_template->put_all(array(
	'I_TOPHEADER'     => Menu::BLOCK_POSITION__TOP_HEADER,
	'I_HEADER'        => Menu::BLOCK_POSITION__HEADER,
	'I_SUBHEADER'     => Menu::BLOCK_POSITION__SUB_HEADER,
	'I_TOPCENTRAL'    => Menu::BLOCK_POSITION__TOP_CENTRAL,
	'I_BOTTOMCENTRAL' => Menu::BLOCK_POSITION__BOTTOM_CENTRAL,
	'I_TOPFOOTER'     => Menu::BLOCK_POSITION__TOP_FOOTER,
	'I_FOOTER'        => Menu::BLOCK_POSITION__FOOTER,
	'I_LEFT'          => Menu::BLOCK_POSITION__LEFT,
	'I_RIGHT'         => Menu::BLOCK_POSITION__RIGHT,

	'U_TOKEN' => AppContext::get_session()->get_token(),
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
			'C_DISPLAY'    => $enabled,
			'C_EDIT'       => !empty($edit_link),
			'C_DELETE'     => !empty($del_link),
			'C_UP'         => $block_id != Menu::BLOCK_POSITION__NOT_ENABLED && $i > 0,
			'C_DOWN'       => $block_id != Menu::BLOCK_POSITION__NOT_ENABLED && $i < $max - 1,
			'C_VERTICAL'   => $vertical_position,
			'C_HORIZONTAL' => !$vertical_position,
			'C_IS_MODULE'  => $menu->is_mini_from_module(),

			'MENU_TITLE'     => $menu->get_formated_title(),
			'MENU_ID'        => $id,
			'CONTENT'        => $menu->admin_display(),
			'DISPLAY_STATUS' => ($enabled ? 'disable' : 'enable'),

			'U_EDIT'   => menu_admin_link($menu, 'edit'),
			'U_DELETE' => menu_admin_link($menu, 'delete'),
			'U_UP'     => menu_admin_link($menu, 'up'),
			'U_DOWN'   => menu_admin_link($menu, 'down'),
			'U_MOVE'   => menu_admin_link($menu, 'move'),
		));

		$view->assign_block_vars(get_block($block_id), array('MENU' => $menu_tpl->render()));
		$i++;
	}
}

foreach(ThemesManager::get_activated_themes_map() as $theme => $properties)
{
	$configuration = $properties->get_configuration();
	$selected = (empty($theme_name) ? AppContext::get_current_user()->get_theme() == $theme : $theme_name == $theme) ? ' selected="selected"' : '';
	$view->assign_block_vars('themes', array(
		'NAME'     => $configuration->get_name(),
		'THEME_ID' => $theme,
		'SELECTED' => $selected
	));
}

$columns_disable = ThemesManager::get_theme($theme_name)->get_columns_disabled();

$view->put_all(array(
	'C_RIGHT_COLUMN'          => $columns_disable->right_columns_is_disabled(),
	'C_LEFT_COLUMN'           => $columns_disable->left_columns_is_disabled(),
	'C_FOOTER_COLUMN'         => $columns_disable->footer_is_disabled(),
	'C_TOP_FOOTER_COLUMN'     => $columns_disable->top_footer_is_disabled(),
	'C_BOTTOM_CENTRAL_COLUMN' => $columns_disable->bottom_central_is_disabled(),
	'C_TOP_CENTRAL_COLUMN'    => $columns_disable->top_central_is_disabled(),
	'C_SUB_HEADER_COLUMN'     => $columns_disable->sub_header_is_disabled(),
	'C_TOP_HEADER_COLUMN'     => $columns_disable->top_header_is_disabled(),
	'C_HEADER_COLUMN'         => $columns_disable->header_is_disabled(),

	'THEME_NAME' => $theme_name,
	'CHECKED_RIGHT_COLUMN'          => !$columns_disable->right_columns_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_LEFT_COLUMN'           => !$columns_disable->left_columns_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_FOOTER_COLUMN'         => !$columns_disable->footer_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_TOP_FOOTER_COLUMN'     => !$columns_disable->top_footer_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_BOTTOM_CENTRAL_COLUMN' => !$columns_disable->bottom_central_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_TOP_CENTRAL_COLUMN'    => !$columns_disable->top_central_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_SUB_HEADER_COLUMN'     => !$columns_disable->sub_header_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_TOP_HEADER_COLUMN'     => !$columns_disable->top_header_is_disabled() ? 'checked="checked"' : '',
	'CHECKED_HEADER_COLUMN'         => !$columns_disable->header_is_disabled() ? 'checked="checked"' : '',
	'I_TOPHEADER'     => Menu::BLOCK_POSITION__TOP_HEADER,
	'I_HEADER'        => Menu::BLOCK_POSITION__HEADER,
	'I_SUBHEADER'     => Menu::BLOCK_POSITION__SUB_HEADER,
	'I_TOPCENTRAL'    => Menu::BLOCK_POSITION__TOP_CENTRAL,
	'I_BOTTOMCENTRAL' => Menu::BLOCK_POSITION__BOTTOM_CENTRAL,
	'I_TOPFOOTER'     => Menu::BLOCK_POSITION__TOP_FOOTER,
	'I_FOOTER'        => Menu::BLOCK_POSITION__FOOTER,
	'I_LEFT'          => Menu::BLOCK_POSITION__LEFT,
	'I_RIGHT'         => Menu::BLOCK_POSITION__RIGHT,
	'AVAILABLE_MENUS_NUMBER'      => count($menus_blocks[Menu::BLOCK_POSITION__NOT_ENABLED]),
	'HEADER_MENUS_NUMBER'         => count($menus_blocks[Menu::BLOCK_POSITION__HEADER]),
	'TOP_HEADER_MENUS_NUMBER'     => count($menus_blocks[Menu::BLOCK_POSITION__TOP_HEADER]),
	'SUB_HEADER_MENUS_NUMBER'     => count($menus_blocks[Menu::BLOCK_POSITION__SUB_HEADER]),
	'TOP_CENTRAL_MENUS_NUMBER'    => count($menus_blocks[Menu::BLOCK_POSITION__TOP_CENTRAL]),
	'BOTTOM_CENTRAL_MENUS_NUMBER' => count($menus_blocks[Menu::BLOCK_POSITION__BOTTOM_CENTRAL]),
	'TOP_FOOTER_MENUS_NUMBER'     => count($menus_blocks[Menu::BLOCK_POSITION__TOP_FOOTER]),
	'FOOTER_MENUS_NUMBER'         => count($menus_blocks[Menu::BLOCK_POSITION__FOOTER]),
	'LEFT_MENUS_NUMBER'           => count($menus_blocks[Menu::BLOCK_POSITION__LEFT]),
	'RIGHT_MENUS_NUMBER'          => count($menus_blocks[Menu::BLOCK_POSITION__RIGHT]),

	'U_TOKEN' => AppContext::get_session()->get_token(),
));
$view->display();
require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
