<?php
/**
 * @package     PHPBoost
 * @subpackage  Langs
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 01 19
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

		throw new IOException('Lang "' . $id . '" config.ini not found in ' . $config_ini_file);
	}
}
?>
