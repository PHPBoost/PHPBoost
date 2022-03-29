<?php
/**
 * @package     Core
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 29
 * @since       PHPBoost 6.0 - 2022 03 19
*/

class ModuleClassLoader extends ClassLoader
{
	public static function __static()
	{
		if (!self::inc(PATH_TO_ROOT . self::$modules_cache_file))
			self::generate_classlist();
	}

	/**
	 * Check if a module has a subclass of a parent class
	 */
	public static function has_module_subclass_of($module_id, $parent_class)
	{
		return (bool)self::get_module_subclass_of($module_id, $parent_class);
	}

	/**
	 * Get module subclass of a parent class if it has one
	 */
	public static function get_module_subclass_of($module_id, $parent_class)
	{
		$result = '';
		if (isset(self::$modules_classlist[$module_id]))
		{
			foreach (self::$modules_classlist[$module_id] as $class_name => $class_path)
			{
				if (self::is_class_registered_and_valid($class_name) && is_subclass_of($class_name, $parent_class))
				{
					$result = $class_name;
					break;
				}
			}
		}
		return $result;
	}
}
?>
