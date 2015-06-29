<?php
/*##################################################
 *                       DataStoreFatory.class.php
 *                            -------------------
 *   begin                : December 09, 2009
 *   copyright            : (C) 2009 Benoit Sautel, Loic Rouchon
 *   email                : ben.popeye@phpboost.com, horn@phpboost.com
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
 * @package {@package}
 * @desc This factory returns you the data store that are the best for your requirements.
 * @author Benoit Sautel <ben.popeye@phpboost.com>, Loic Rouchon <horn@phpboost.com>
 */
class DataStoreFactory
{
	private static $apc_available = null;
	private static $apc_enabled = null;

	/**
	 * @desc Returns an efficient data store whose life span can be not infinite.
	 * @param string $id Identifier of the data store.
	 * @return DataStore The best data store you can use with the current configuration
	 */
	public static function get_ram_store($id)
	{
		if (self::is_apc_available() && self::is_apc_enabled())
		{
			return new APCDataStore($id);
		}
		return new RAMDataStore();
	}

	/**
	 * @desc Returns an infinite-life span data store that can be not very efficient.
	 * @param string $id Identifier of the data store.
	 * @return DataStore The best data store you can use with the current configuration
	 */
	public static function get_filesystem_store($id)
	{
		if (self::is_apc_available() && self::is_apc_enabled())
		{
			return new APCDataStore($id);
		}
		return new FileSystemDataStore($id);
	}

	public static function is_apc_available()
	{
		if (self::$apc_available === null)
		{
			if (function_exists('apc_cache_info') && (extension_loaded('apc') || extension_loaded('apcu')) && ini_get('apc.enabled'))
			{
				self::$apc_available = true;
			}
			else
			{
				self::$apc_available = false;
			}
		}
		return self::$apc_available;
	}
	
	public static function is_apc_enabled()
	{
		if (self::$apc_available !== null && self::$apc_enabled === null)
		{
			$file = new File(PATH_TO_ROOT . '/cache/apc.php');
			if ($file->exists())
			{
				include $file->get_path();
				if (isset($enable_apc))
				{
					return $enable_apc;
				}
			}
		}
		return false;
	}
	
	/**
	 * Enables or disables APC. Writes in the file /cache/apc.php
	 * @param bool $enabled
	 * @throws IOException If the file cannot be written
	 */
	public static function set_apc_enabled($enabled)
	{
		$file = new File(PATH_TO_ROOT . '/cache/apc.php');
		$file->write('<?php $enable_apc = ' . ($enabled ?  'true' : 'false') . ';');
		$file->close();
	}
}
?>