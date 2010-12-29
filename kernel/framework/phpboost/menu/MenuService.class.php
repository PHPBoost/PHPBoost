<?php
/*##################################################
 *                             menu_service.class.php
 *                            -------------------
 *   begin                : November 13, 2008
 *   copyright            : (C) 2008 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc This service manage kernel menus by adding the persistance to menus objects.
 * It also provides all moving and disabling methods to change the website appearance.
 * @static
 * @package {@package}
 */
class MenuService
{
	const MOVE_UP = -1;
	const MOVE_DOWN = 1;

	/**
	 * @var DBQuerier
	 */
	private static $querier;

	/**
	 * @var string[] the columns needed to instanciate a menu
	 */
	private static $columns = array('id', 'object', 'block', 'position', 'enabled');

	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}

	## Menus ##
	/**
	* @desc
	* @param $block
	* @param $enabled
	* @return Menu[]
	*/
	public static function get_menu_list($class = Menu::MENU__CLASS, $block = Menu::BLOCK_POSITION__ALL, $enabled = Menu::MENU_ENABLE_OR_NOT)
	{
		$fragment = self::build_menu_list_query_conditions($class, $block, $enabled);
		$menus = array();
		$results = self::$querier->select_rows(DB_TABLE_MENUS, self::$columns, $fragment->get_query(), $fragment->get_parameters());
		foreach ($results as $row)
		{
			$menus[] = MenuService::initialize($row);
		}
		return $menus;
	}

	/**
	 * @desc
	 * @return unknown_type
	 */
	public static function get_menus_map()
	{
		$menus = MenuService::initialize_menus_map();
		$results = self::$querier->select_rows(DB_TABLE_MENUS, self::$columns, 'ORDER BY position ASC');
		foreach ($results as $row)
		{
			if ($row['enabled'] != Menu::MENU_ENABLED)
			{
				$menus[Menu::BLOCK_POSITION__NOT_ENABLED][] = MenuService::initialize($row);
			}
			else
			{
				$menus[$row['block']][] = MenuService::initialize($row);
			}
		}
		return $menus;
	}

	/**
	 * @desc Retrieve a Menu Object from the database by its id
	 * @param int $id the id of the Menu to retrieve from the database
	 * @return Menu the requested Menu if it exists else, null
	 */
	public static function load($id)
	{
		try
		{
			$result = self::$querier->select_single_row(DB_TABLE_MENUS, self::$columns, 'WHERE id=:id', array('id' => $id));
			return MenuService::initialize($result);
		} catch (RowNotFoundException $ex)
		{
			return null;
		}
	}

	/**
	 * @desc save a Menu in the database
	 * @param Menu $menu The Menu to save
	 * @return bool true if the save have been correctly done
	 */
	public static function save($menu)
	{
		$block_position = $menu->get_block_position();

		if (($block = $menu->get_block()) != Menu::MENU_NOT_ENABLED && ($block_position = $menu->get_block_position()) == -1)
		{
			$block_position = self::get_next_position($block);
		}

		$query = '';
		$id_menu = $menu->get_id();

		$columns = array(
			'title' => $menu->get_title(),
			'object' => serialize($menu),
			'class' => get_class($menu),
			'enabled' => (int) $menu->is_enabled(),
			'block' => $block,
			'position' => $menu->get_block_position()
		);
		if ($id_menu > 0)
		{
			self::$querier->update(DB_TABLE_MENUS, $columns, 'WHERE id=:id', array('id' => $id_menu));
		}
		else
		{
			$result = self::$querier->insert(DB_TABLE_MENUS, $columns);
			$menu->id($result->get_last_inserted_id());
		}

		return true;
	}

	/**
	 * @desc Delete a Menu from the database
	 * @param mixed $menu The (Menu) Menu or its (int) id to delete from the database
	 */
	public static function delete($menu)
	{
		if (!is_object($menu))
		{
			$menu = MenuService::load($menu);
		}
		MenuService::disable($menu);
		self::$querier->delete(DB_TABLE_MENUS, 'WHERE id=:id', array('id' => $menu->get_id()));
	}

	/**
	 * @desc Enable a menu
	 * @param Menu $menu the menu to enable
	 */
	public static function enable($menu)
	{
		// Commputes the new Menu position and save it
		MenuService::move($menu, $menu->get_block());
	}

	/**
	 * @desc Disable a menu
	 * @param Menu $menu the menu to disable
	 */
	public static function disable($menu)
	{
		// Commputes menus positions of the previous block and save the current menu
		MenuService::move($menu, Menu::BLOCK_POSITION__NOT_ENABLED);
	}

	/**
	 * @desc Move a menu into a block and save it. Enable or disable it according to the destination block
	 * @param Menu $menu the menu to move
	 * @param int $block the destination block
	 * @param bool $save if true, save also the menu
	 */
	public static function move($menu, $block, $save = true)
	{
		if ($menu->get_id() > 0 && $menu->is_enabled())
		{   // Updates the previous block position counter
			// Only for already existing menu that are enabled, not for new ones
			$parameters = array('block' => $menu->get_block(), 'position' => $menu->get_block_position());
			self::$querier->inject('UPDATE ' . DB_TABLE_MENUS . ' SET position=position - 1
				WHERE block=:block AND position > :position', $parameters);
		}

		// Disables the menu if the destination block is the NOT_ENABLED block position
		$menu->enabled($block == Menu::BLOCK_POSITION__NOT_ENABLED ? Menu::MENU_NOT_ENABLED : Menu::MENU_ENABLED);

		// If not enabled, we do not move it so we can restore its position by reactivating it
		if ($menu->is_enabled())
		{   // Moves the menu into the destination block
			$menu->set_block($block);

			// Computes the new block position for the menu
			$position_query = self::get_next_position($menu->get_block());
			$menu->set_block_position($position_query);
		}

		if ($save)
		{
			MenuService::save($menu);
		}
	}

	/**
	 * @desc Change the menu position in a block
	 * @param Menu $menu The menu to move
	 * @param int $diff the direction to move it. positives integers move down, negatives, up.
	 */
	public static function change_position($menu, $direction = self::MOVE_UP)
	{
		$block_position = $menu->get_block_position();
		$new_block_position = $block_position;
		$update_query = '';

		$parameters = array('block' => $menu->get_block());
		if ($direction > 0)
		{   // Moving the menu down
			$max_position = self::get_next_position($menu->get_block()) - 1;
			$new_block_position = ($menu->get_block_position() + $direction);
			$new_block_position = min(array($new_block_position, $max_position));
			$parameters['min_position'] = $block_position + 1;
			$parameters['max_position'] = $new_block_position;
		}
		else if ($direction < 0)
		{   // Moving the menu up
			$new_block_position = $menu->get_block_position() + $direction;
			$new_block_position = max(array($new_block_position, 0));
			$parameters['min_position'] = $new_block_position;
			$parameters['max_position'] = $block_position - 1;
		}

		if ($block_position != $new_block_position)
		{   // Updating other menus
			self::$querier->inject('UPDATE ' . DB_TABLE_MENUS . ' SET position=position - 1
				WHERE block=:block AND position BETWEEN :min_position AND :max_position', $parameters);
			// Updating the current menu
			$menu->set_block_position($new_block_position);
			MenuService::save($menu);
		}
	}


	/**
	 * @desc Enables or disables all menus
	 * @param bool $enable if true enables all menus otherwise, disables them
	 */
	public static function enable_all($enable = true)
	{
		$menus = MenuService::get_menu_list();
		foreach($menus as $menu)
		{
			if ($enable === true)
			{
				MenuService::enable($menu);
			}
			else
			{
				MenuService::disable($menu);
			}
		}
	}

	## Cache ##

	/**
	 * @desc Generate the cache
	 */
	public static function generate_cache($return_string = false)
	{
		// $MENUS global var initialization
		$cache_str = '$MENUS = array();';
		$cache_str .= '$MENUS[Menu::BLOCK_POSITION__HEADER] = \'\';';
		$cache_str .= '$MENUS[Menu::BLOCK_POSITION__SUB_HEADER] = \'\';';
		$cache_str .= '$MENUS[Menu::BLOCK_POSITION__TOP_CENTRAL] = \'\';';
		$cache_str .= '$MENUS[Menu::BLOCK_POSITION__BOTTOM_CENTRAL] = \'\';';
		$cache_str .= '$MENUS[Menu::BLOCK_POSITION__TOP_FOOTER] = \'\';';
		$cache_str .= '$MENUS[Menu::BLOCK_POSITION__FOOTER] = \'\';';
		$cache_str .= '$MENUS[Menu::BLOCK_POSITION__LEFT] = \'\';';
		$cache_str .= '$MENUS[Menu::BLOCK_POSITION__RIGHT] = \'\';';
		$cache_str .= 'global $User;' . "\n";
		
		$menus_map = MenuService::get_menus_map();
//        unset($menus_map[1][0]);
//        unset($menus_map[2][0]);
//        unset($menus_map[5][0]);
//        unset($menus_map[7][0]);
//        unset($menus_map[7][1]);
//        unset($menus_map[7][2]);
//        unset($menus_map[7][3]);
//        unset($menus_map[8][0]);
//        unset($menus_map[8][1]);
//        unset($menus_map[8][2]);
//        unset($menus_map[8][3]);
//        unset($menus_map[8][4]);
//        Debug::dump($menus_map);
		
		foreach ($menus_map as $block => $block_menus)
		{
			if ($block != Menu::BLOCK_POSITION__NOT_ENABLED)
			{
				foreach ($block_menus as $menu)
				{
					if ($menu->is_enabled())
					{
						$filters = '';
						$has_filter = false;
						foreach ($menu->get_filters() as $filter) 
						{
							$has_filter = ($filter != '/') ? true : false;
							$filters .= 'strpos(REWRITED_SCRIPT, "' . $filter . '") !== false || ';
						}
						$filters = trim($filters, '|| ');
						
						$cache_str .= ($has_filter) ? 'if (' . $filters . ') {' . "\n" : '';
						$cache_str .= '$__menu=\'' . $menu->cache_export() . '\';' . "\n";
						$cache_str .= '$MENUS[' . $menu->get_block() . '].=$__menu;' . "\n";
						$cache_str .= ($has_filter) ? '}' . "\n" : '';
					}
				}
			}
		}

		// Cache compression
		$cache_str = preg_replace(
		array('`\t*`', '`\s*\n\s*\n\s*`', '`[ ]{2,}`', '`>\s`', '`\n `', '`\'\.\'`', '`\$__menu\.=\'\';`'),
		array('', "\n", ' ', '> ', "\n", '', ''),
		$cache_str
		);

		if ($return_string)
		{
			return $cache_str;
		}

		// TODO replace this old cache system.
		$Cache = new Cache();
		$Cache->write('menus', $cache_str);
		return '';

	}

	## Mini Menus ##
	/**
	* @desc Add the menu named in the $menu folder
	* @param Folder $menu the menu folder
	* @return bool true if the menu has been installed, else, false
	*/
	public static function add_mini_menu($menu, $installed_menus_names)
	{
		// Break if no mini menu file found
		$i = 0;
		$menu_name = $menu->get_name();
		$files = $menu->get_files('`.+\.php`');

		foreach ($files as $file)
		{
			$file_name = $file->get_name_without_extension();
			// We do not installed the mini menu if it's already installed or
			// if it's not correct
			if (in_array($menu_name . '/' . $file_name, $installed_menus_names) ||
			!(@include $file->get_path()) ||
			!function_exists('menu_' . $menu_name . '_' . $file_name))
			{
				continue;
			}
			$menu = new MiniMenu($menu_name, $file_name);
			MenuService::save($menu);

			$i++;
		}

		return $i > 0;
	}

	/**
	 * @desc delete the mini menu named $menu
	 * @param string $menu the mini menu name
	 */
	public static function delete_mini_menu($menu)
	{
		$conditions = 'WHERE class=:class AND title LIKE :like_title';
		$parameters = array(
			'class' => strtolower(MiniMenu::MINI_MENU__CLASS),
			'like_title' => strtolower($menu) . '/%'
		);
		$results = self::$querier->select_rows(DB_TABLE_MENUS, self::$columns, $conditions, $parameters);
		foreach ($results as $row)
		{
			MenuService::delete(MenuService::initialize($row));
		}
	}

	/**
	 * @desc Update the mini menus list by adding new ones and delete old ones
	 * @param bool $update_cache if true it will also regenerate the cache
	 */
	public static function update_mini_menus_list($update_cache = true)
	{
		$m_menus_directory = new Folder(PATH_TO_ROOT . '/menus');
		$m_menus_list = $m_menus_directory->get_folders();

		$menus_names = array();
		$installed_menus_names = array();
		$processed_folders = array();
		foreach ($m_menus_list as $menu)
		{
			$menus_names[] = $menu->get_name();
		}
		$conditions = 'WHERE class=:class';
		$parameters = array('class' => strtolower(MiniMenu::MINI_MENU__CLASS));
		$results = self::$querier->select_rows(DB_TABLE_MENUS, array('title'), $conditions, $parameters);
		foreach ($results as $row)
		{
			$menu_folder = substr($row['title'], 0, strpos($row['title'], '/'));
			if (!in_array($menu_folder, $processed_folders))
			{
				if (!in_array($menu_folder, $menus_names))
				{
					MenuService::delete_mini_menu($menu_folder);
				}
				else
				{
					$installed_menus_names[] = $row['title'];
				}
				$processed_folders[] = $menu_folder;
			}
		}

		foreach ($m_menus_list as $menu)
		{
			MenuService::add_mini_menu($menu, $installed_menus_names);
		}

		if ($update_cache)
		{
			MenuService::generate_cache();
		}
	}

	## Mini Modules ##
	/**
	* @desc Add the module named $module mini modules
	* @param string $module the module name
	* @return bool true if the module has been installed, else, false
	*/
	public static function add_mini_module($module_id, $generate_cache = true)
	{
		$configuration = ModulesManager::get_module($module_id)->get_configuration();

		$mini_modules_menus = $configuration->get_mini_modules();
		if (empty($mini_modules_menus))
		{
			return false;
		}

		$installed = false;
		foreach ($mini_modules_menus as $filename => $location)
		{   // For each mini module for the current module
			// Check the mini module file
			if (file_exists(PATH_TO_ROOT . '/' . $module_id . '/' . $filename))
			{
				$file = explode('.', $filename, 2);
				if (!is_array($file) || count($file) < 1)
				{
					continue;
				}

				// Check the mini module function
				include_once PATH_TO_ROOT . '/' . $module_id . '/' . $filename;
				if (!function_exists($file[0]))
				{
					continue;
				}

				$menu = new ModuleMiniMenu($module_id, $file[0]);
				$menu->enabled(false);
				$menu->set_auth(array('r1' => Menu::MENU_AUTH_BIT, 'r0' => Menu::MENU_AUTH_BIT, 'r-1' => Menu::MENU_AUTH_BIT));
				$menu->set_block(MenuService::str_to_location($location));
				MenuService::save($menu);
				if ($generate_cache)
				{
					MenuService::generate_cache();
				}

				$installed = true;
			}
		}
		return $installed;
	}

	/**
	 * @desc delete the mini module $module
	 * @param string $module the mini module name
	 */
	public static function delete_mini_module($module)
	{
		$conditions = 'WHERE class=:class AND title LIKE :like_title';
		$parameters = array(
			'class' => strtolower(ModuleMiniMenu::MODULE_MINI_MENU__CLASS),
			'like_title' => strtolower($module) . '/%'
		);
		$results = self::$querier->select_rows(DB_TABLE_MENUS, self::$columns, $conditions, $parameters);
		foreach ($results as $row)
		{
			MenuService::delete(MenuService::initialize($row));
		}
	}

	/**
	 * @desc Update the mini modules list by adding new ones and delete old ones
	 * @param bool $update_cache if true it will also regenerate the cache
	 */
	public static function update_mini_modules_list($update_cache = true)
	{
		global $Sql;

		// Retrieves the mini modules already installed
		$installed_minimodules = array();
		$modules = array();
		// Build the availables modules list
		foreach (ModulesManager::get_installed_modules_map() as $module)
		{
			if ($module->is_activated())
			{
				$modules[] = $module->get_id();
			}
		}

		$conditions = 'WHERE class=:class';
		$parameters = array('class' => strtolower(ModuleMiniMenu::MODULE_MINI_MENU__CLASS));
		$results = self::$querier->select_rows(DB_TABLE_MENUS, array('id', 'title'), $conditions, $parameters);
		foreach ($results as $row)
		{
			// Build the module name from the mini module file_path
			$title = explode('/', strtolower($row['title']) , 2);
			if (!is_array($title) || count($title) < 1)
			{
				continue;
			}

			$module = $title[0];
			if (in_array($module, $modules))
			{   // The Menu is installed and we gonna keep it
				$installed_minimodules[] = $module;
			}
			else
			{   // The menu is not available anymore, so we delete it
				MenuService::delete($row['id']);
			}
		}

		$new_modules = array_diff($modules, $installed_minimodules);
		foreach ($new_modules as $module)
		{   // Browse availables modules without mini modules
			MenuService::add_mini_module($module, false);
		}
		if ($update_cache)
		{
			MenuService::generate_cache();
		}
	}


	/**
	 * @desc Delete all the feeds menus with the this module id
	 * @param string $module_id the module id
	 */
	public static function delete_module_feeds_menus($module_id)
	{
		$feeds_menus = MenuService::get_menu_list(FeedMenu::FEED_MENU__CLASS);
		foreach($feeds_menus as $feed_menu)
		{
			if ($module_id == $feed_menu->get_module_id())
			{
				MenuService::delete($feed_menu);
			}
		}
	}

	/**
	 * @desc Return a menu with links to modules
	 * @param int $menu_type the menu type
	 * @return LinksMenu the menu with links to modules
	 */
	public static function website_modules($menu_type = LinksMenu::VERTICAL_MENU)
	{
		$modules_menu = new LinksMenu('PHPBoost', '/', '', $menu_type);
		$modules = ModulesManager::get_installed_modules_map_sorted_by_localized_name();
		foreach ($modules as $module)
		{
			$configuration = $module->get_configuration();
			$start_page = $configuration->get_home_page();
			if (!empty($start_page))
			{
				$img = '';
				$img_url = PATH_TO_ROOT . '/' . $module->get_id() . '/' . $module->get_id();

				foreach (array('_mini.png', '_mini.gif', '_mini.jpg') as $extension)
				{
					$file = new File($img_url . $extension);
					if ($file->exists())
					{
						$img = '/' . $module->get_id() . '/' . $file->get_name();
						break;
					}
				}
				$modules_menu->add(new LinksMenuLink($configuration->get_name(), '/' . $module->get_id() . '/' . $start_page, $img));
			}
		}
		return $modules_menu;
	}


	/**
	 * @desc Assigns the positions conditions for different printing modes
	 * @param Template $template the template to use
	 * @param int $position the menu position
	 */
	public static function assign_positions_conditions($template, $position)
	{
		$vertical_position = in_array($position, array(Menu::BLOCK_POSITION__LEFT, Menu::BLOCK_POSITION__RIGHT));
		$template->put_all(array(
            'C_HEADER' => $position == Menu::BLOCK_POSITION__HEADER,
            'C_SUBHEADER' => $position == Menu::BLOCK_POSITION__SUB_HEADER,
            'C_TOP_CENTRAL' => $position == Menu::BLOCK_POSITION__TOP_CENTRAL,
            'C_BOTTOM_CENTRAL' => $position == Menu::BLOCK_POSITION__BOTTOM_CENTRAL,
            'C_TOP_FOOTER' => $position == Menu::BLOCK_POSITION__TOP_FOOTER,
            'C_FOOTER' => $position == Menu::BLOCK_POSITION__FOOTER,
            'C_LEFT' => $position == Menu::BLOCK_POSITION__LEFT,
            'C_RIGHT' => $position == Menu::BLOCK_POSITION__RIGHT,
            'C_VERTICAL' => $vertical_position,
            'C_HORIZONTAL' => !$vertical_position
		));
	}
	## Tools ##

	/**
	 * @desc Convert the string location the int location
	 * @param string $str_location the location
	 * @return int the corresponding location
	 */
	public static function str_to_location($str_location)
	{
		switch ($str_location)
		{
			case 'header':
				return Menu::BLOCK_POSITION__HEADER;
			case 'subheader':
				return Menu::BLOCK_POSITION__SUB_HEADER;
			case 'topcentral':
				return Menu::BLOCK_POSITION__TOP_CENTRAL;
			case 'left':
				return Menu::BLOCK_POSITION__LEFT;
			case 'right':
				return Menu::BLOCK_POSITION__RIGHT;
			case 'bottomcentral':
				return Menu::BLOCK_POSITION__BOTTOM_CENTRAL;
			case 'topfooter':
				return Menu::BLOCK_POSITION__TOP_FOOTER;
			case 'footer':
				return Menu::BLOCK_POSITION__FOOTER;
			default:
				return Menu::BLOCK_POSITION__NOT_ENABLED;
		}
	}

	/**
	 * @desc
	 * @param int $class
	 * @param int_type $block
	 * @param boolean $enabled
	 * @return SQLFragment
	 */
	private static function build_menu_list_query_conditions($class, $block, $enabled)
	{
		$conditions = array();
		$parameters = array();
		if ($class != Menu::MENU__CLASS)
		{
			$conditions[] = 'class=:class';
			$parameters['class'] = strtolower($class);
		}
		if ($block != Menu::BLOCK_POSITION__ALL)
		{
			$conditions[] = 'block=:block';
			$parameters['block'] = $block;
		}
		if ($enabled !== Menu::MENU_ENABLE_OR_NOT)
		{
			$conditions[] = 'enabled=:enabled';
			$parameters['enabled'] = $enabled;
		}
		$condition = '';
		if (!empty($conditions))
		{
			$condition .= 'WHERE ' . implode(' AND ', $conditions);
		}
		return new SQLFragment($condition, $parameters);
	}

	private static function get_next_position($block)
	{
		$column = 'MAX(position) + 1 AS newPosition';
		$condition = 'WHERE block=:block AND enabled=1';
		$parameters = array('block' => $block);
		return (int) self::$querier->get_column_value(DB_TABLE_MENUS, $column, $condition, $parameters);
	}

	/**
	 * @access private
	 * @return array[] initialize the menus map structure
	 */
	private static function initialize_menus_map()
	{
		return array(
		Menu::BLOCK_POSITION__HEADER => array(),
		Menu::BLOCK_POSITION__SUB_HEADER => array(),
		Menu::BLOCK_POSITION__TOP_CENTRAL => array(),
		Menu::BLOCK_POSITION__BOTTOM_CENTRAL => array(),
		Menu::BLOCK_POSITION__TOP_FOOTER => array(),
		Menu::BLOCK_POSITION__FOOTER => array(),
		Menu::BLOCK_POSITION__LEFT => array(),
		Menu::BLOCK_POSITION__RIGHT => array(),
		Menu::BLOCK_POSITION__NOT_ENABLED => array()
		);
	}

	/**
	 * @access private
	 * @desc Build a Menu object from a database result
	 * @param string[key] $db_result the map from the database with the Menu id and serialized object
	 * @return Menu the menu object from the serialized one
	 */
	private static function initialize($db_result)
	{
		$menu = unserialize($db_result['object']);

		// Synchronize the object and the database
		$menu->id($db_result['id']);
		$menu->enabled($db_result['enabled']);
		$menu->set_block($db_result['block']);
		$menu->set_block_position($db_result['position']);

		if (($menu instanceof LinksMenu) || ($menu instanceof LinksMenuLink))
		{
			$menu->update_uid();
		}

		return $menu;
	}
}
?>