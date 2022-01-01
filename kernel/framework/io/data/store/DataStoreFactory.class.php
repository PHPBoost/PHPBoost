<?php
/**
 * This factory returns you the data store that are the best for your requirements.
 * @package     IO
 * @subpackage  Data\store
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 06 29
 * @since       PHPBoost 3.0 - 2009 12 09
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class DataStoreFactory
{
	private static $apc_available = null;
	private static $apc_enabled = null;

	/**
	 * Returns an efficient data store whose life span can be not infinite.
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
	 * Returns an infinite-life span data store that can be not very efficient.
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
