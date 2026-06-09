<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2009 12 12
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ModuleConfigurationManager
{
	/**
	 * @var DataStore
	 */
	private static $cache_manager = null;

	/**
	 * Returns the <code>$module_id</code> ModuleConfiguration
	 * @param string $module_id the module id
	 * @return ModuleConfiguration the module configuration
	 */
	public static function get($module_id)
	{
		$cache_manager = self::get_cache_manager();
		if (!$cache_manager->contains($module_id))
		{
			$module_configuration = self::get_module_configuration($module_id);
			$cache_manager->store($module_id, $module_configuration);
		}
		return $cache_manager->get($module_id);
	}

	/**
	 * @return DataStore
	 */
	private static function get_cache_manager()
	{
		if (self::$cache_manager === null)
		{
			self::$cache_manager = DataStoreFactory::get_ram_store(self::class);
		}
		return self::$cache_manager;
	}

	/**
	 * @return ModuleConfiguration
	 */
	private static function get_module_configuration($module_id)
	{
		$config_ini_file = self::get_module_path($module_id) . '/config.ini';
		$desc_ini_file = self::find_desc_ini_file($module_id);
		return new ModuleConfiguration($config_ini_file, $desc_ini_file, $module_id);
	}

	/**
	 * Get the module path
	 * @param string $module_id the module id
	 * @return string the module path
	 */
	private static function get_module_path($module_id)
	{
		// Try modules folder first
		$modules_path = PATH_TO_ROOT . '/modules/' . $module_id;
		if (file_exists($modules_path))
		{
			return $modules_path;
		}
		// Fallback to root for backward compatibility
		return PATH_TO_ROOT . '/' . $module_id;
	}

	private static function find_desc_ini_file($module_id)
	{
		$module_path = self::get_module_path($module_id);

		// Module - Langs priority order
		//      /lang/$lang/modules/$module/desc.ini
		//      /modules/$module/lang/$lang/desc.ini
		//      /$module/lang/$lang/desc.ini (fallback for backward compatibility)
		$desc_ini_file = PATH_TO_ROOT . '/lang/' . AppContext::get_current_user()->get_locale() . '/modules/' . $module_id . '/desc.ini';
		if (file_exists($desc_ini_file))
		{
			return $desc_ini_file;
		}

		$desc_ini_folder = $module_path . '/lang/';

		$desc_ini_file = $desc_ini_folder . AppContext::get_current_user()->get_locale() . '/desc.ini';
		if (file_exists($desc_ini_file))
		{
			return $desc_ini_file;
		}

		$folder = new Folder($desc_ini_folder);
		$folders = $folder->get_folders();
		foreach ($folders as $lang_folder)
		{
			$desc_ini_file = $lang_folder->get_path() . '/desc.ini';
			if (file_exists($desc_ini_file))
			{
				return $desc_ini_file;
			}
		}
		throw new IOException('Module "' . $module_id . '" description desc.ini not found in /' . $module_path . '/lang/');
	}
}
?>
