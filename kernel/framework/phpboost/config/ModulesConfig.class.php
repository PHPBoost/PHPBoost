<?php
/*##################################################
 *                      	 ModulesConfig.class.php
 *                            -------------------
 *   begin                : December 12, 2009
 *   copyright            : (C) 2009 Loic Rouchon
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
 * This class contains the cache data of the modules which module users having common criteria.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class ModulesConfig extends AbstractConfigData
{
	private static $modules_property = 'modules';

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::$modules_property => array()
		);
	}

	/**
	 * @desc Returns the list of the modules
	 * @return array id_module => module properties (map)
	 */
	public function get_modules()
	{
		return $this->get_property(self::$modules_property);
	}

	/**
	 * @desc Returns the requested module
	 * @param $module_id the id of the module
	 * @return Module the requested module
	 */
	public function get_module($module_id)
	{
		$modules = $this->get_property(self::$modules_property);
		return isset($modules[$module_id]) ? $modules[$module_id] : null;
	}

	/**
	 * @desc Sets the modules list
	 * @param Module[string] $modules_list The modules list
	 */
	public function set_modules(array $modules)
	{
		$this->set_property(self::$modules_property, $modules);
	}

	/**
	 * @desc Install a new module
	 * @param Module $modules The module to add (~ install)
	 */
	public function add_module(Module $module)
	{
		$modules = $this->get_property(self::$modules_property);
		$modules[$module->get_id()] = $module;
		$this->set_property(self::$modules_property, $modules);
	}

	public function remove_module(Module $module)
	{
		$modules = $this->get_property(self::$modules_property);
		unset($modules[$module->get_id()]);
		$this->set_property(self::$modules_property, $modules);
	}

	public function remove_module_by_id($module_id)
	{
		$modules = $this->get_property(self::$modules_property);
		unset($modules[$module_id]);
		$this->set_property(self::$modules_property, $modules);
	}
	
	public function update(Module $module)
	{
		$modules = $this->get_property(self::$modules_property);
		$modules[$module->get_id()] = $module;

		$this->set_property(self::$modules_property, $modules);
	}

	/**
	 * @desc Loads and returns the modules cached data.
	 * @return ModulesConfig The cached data
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'modules');
	}

	/**
	 * Invalidates the current modules cached data.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'modules');
	}
}
?>