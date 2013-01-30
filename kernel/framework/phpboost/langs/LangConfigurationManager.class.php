<?php
/*##################################################
 *                        LangConfigurationManager.class.php
 *                            -------------------
 *   begin                : January 19, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @package {@package}
 */
class LangConfigurationManager
{
	private static $cache_manager = null;

	public static function get($id)
	{
		$cache_manager = self::get_cache_manager();
		if (!$cache_manager->contains($id))
		{
			$configuration = self::get_configuration($id);
			$cache_manager->store($id, $configuration);
		}
		return $cache_manager->get($id);
	}

	private static function get_cache_manager()
	{
		if (self::$cache_manager === null)
		{
			self::$cache_manager = DataStoreFactory::get_ram_store(__CLASS__);
		}
		return self::$cache_manager;
	}

	private static function get_configuration($id)
	{
		$config_ini_file = self::find_config_ini_file($id);
		return new LangConfiguration($config_ini_file);
	}

	private static function find_config_ini_file($id)
	{
		$config_ini_file = PATH_TO_ROOT . '/lang/' . $id . '/config.ini';
		if (file_exists($config_ini_file))
		{
			return $config_ini_file;
		}

		throw new IOException('Lang "' . $id . '" config.ini not found in ' .
			    $config_ini_file);
	}
}
?>