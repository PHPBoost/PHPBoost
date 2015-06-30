<?php
/*##################################################
/**
 *                        ModuleConfigurationManager.class.php
 *                            -------------------
 *   begin                : December 12, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 *###################################################
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
 *###################################################
 */

/**
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 */
class ModuleConfigurationManager
{
	/**
	 * @var DataStore
	 */
	private static $cache_manager = null;

	/**
	 * @desc Returns the <code>$module_id</code> ModuleConfiguration
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
			self::$cache_manager = DataStoreFactory::get_ram_store(__CLASS__);
		}
		return self::$cache_manager;
	}

	/**
	 * @return ModuleConfiguration
	 */
	private static function get_module_configuration($module_id)
	{
		$config_ini_file = PATH_TO_ROOT . '/' . $module_id . '/config.ini';
		$desc_ini_file = self::find_desc_ini_file($module_id);
		return new ModuleConfiguration($config_ini_file, $desc_ini_file);
	}

	private static function find_desc_ini_file($module_id)
	{
		$desc_ini_folder = PATH_TO_ROOT . '/' . $module_id . '/lang/';

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
		throw new IOException('Module "' . $module_id . '" description desc.ini not found in' .
			    '/' . $module_id . '/lang/');
	}
}
?>