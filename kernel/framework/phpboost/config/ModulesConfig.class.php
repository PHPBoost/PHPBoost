<?php
/**
 * This class contains the cache data of the modules which module users having common criteria.
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 06 21
 * @since       PHPBoost 3.0 - 2009 12 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
	 * Returns the list of the modules
	 * @return array id_module => module properties (map)
	 */
	public function get_modules()
	{
		return $this->get_property(self::$modules_property);
	}

	/**
	 * Returns the requested module
	 * @param $module_id the id of the module
	 * @return Module the requested module
	 */
	public function get_module($module_id)
	{
		$modules = $this->get_property(self::$modules_property);
		return isset($modules[$module_id]) ? $modules[$module_id] : null;
	}

	/**
	 * Sets the modules list
	 * @param Module[string] $modules_list The modules list
	 */
	public function set_modules(array $modules)
	{
		$this->set_property(self::$modules_property, $modules);
	}

	/**
	 * Install a new module
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
	 * Loads and returns the modules cached data.
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
