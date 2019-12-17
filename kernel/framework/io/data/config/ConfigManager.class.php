<?php
/**
 * This class manages config loading and saving. It makes a two-level lazy loading:
 * <ul>
 * 	<li>A top-level cache which avoids loading a data if it has already been done since the
 * beginning of the current page generation. This cache has a short life span: it's flushed
 * as of the PHP interpreter reaches the end of the page generation.</li>
 * 	<li>A filesystem or shared RAM cache to avoid querying the database many times to obtain the same value.
 * This cache is less powerful than the previous one but it has an infinite life span. Indeed, it's
 * valid until the value changes and the manager is asked to store it</li>
 * </ul>
 * @package     IO
 * @subpackage  Data\config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 03 13
 * @since       PHPBoost 3.0 - 2009 09 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ConfigManager
{
	/**
	 * Loads the data identified by the parameters.
	 * @param $classname Name of the class which is expected to be returned
	 * @param $module_name Name of the module owning the entry to load
	 * @param $entry_name If the module wants to manage several entries,
	 * it's the name of the entry you want to load
	 * @return ConfigData The loaded data
	 */
	public static function load($classname, $module_name, $entry_name = '')
	{
		try
		{
			return CacheManager::try_load($classname, $module_name, $entry_name);
		}
		catch(CacheDataNotFoundException $ex)
		{
			$data = null;
			try
			{
				$data = self::load_in_db($module_name, $entry_name);
                CacheManager::save($data, $module_name, $entry_name);
			}
			catch(ConfigNotFoundException $ex)
			{
				$data = new $classname();
				$data->set_default_values();
				$name = self::compute_entry_name($module_name, $entry_name);
				self::save_in_db($name, $data);
                CacheManager::save($data, $module_name, $entry_name);
			}
			catch (PHPBoostNotInstalledException $ex)
			{
				$data = new $classname();
                $data->set_default_values();
			}
			catch (MySQLUnexistingDatabaseException $ex)
			{
				$data = new $classname();
                $data->set_default_values();
			}
			catch (MySQLQuerierException $ex)
			{
				$data = new $classname();
                $data->set_default_values();
			}

			return $data;
		}
	}

	/**
	 * @return ConfigData
	 */
	private static function load_in_db($module_name, $entry_name = '')
	{
		$name = self::compute_entry_name($module_name, $entry_name);

		try
		{
			$result = PersistenceContext::get_querier()->select_single_row(DB_TABLE_CONFIGS, array('value'), 'WHERE name = :name', array('name' => $name));
		}
		catch(RowNotFoundException $ex)
		{
			throw new ConfigNotFoundException($name);
		}

		$required_value = @unserialize($result['value']);
		if ($required_value === false)
		{
			throw new ConfigNotFoundException($name);
		}

		return $required_value;
	}

	/**
	 * @return string
	 */
	private static function compute_entry_name($module_name, $entry_name)
	{
		if (!empty($entry_name))
		{
			return Url::encode_rewrite($module_name . '-' . $entry_name);
		}
		else
		{
			return Url::encode_rewrite($module_name);
		}
	}

	/**
	 * Saves in the data base (DB_TABLE_CONFIGS table) the data and has it become persistent.
	 * @param string $module_name Name of the module owning this entry
	 * @param ConfigData $data Data to save
	 * @param string $entry_name The name of the entry if the module uses several entries
	 */
	public static function save($module_name, ConfigData $data, $entry_name = '')
	{
		$name = self::compute_entry_name($module_name, $entry_name);

		self::save_in_db($name, $data);

		CacheManager::save($data, $module_name, $entry_name);
	}

	/**
	 * Delete an configuration in the database and the cache
	 * @param string $module_name Name of the module owning this entry
	 * @param string $entry_name The name of the entry if the module uses several entries
	 */
	public static function delete($module_name, $entry_name = '')
	{
		$name = self::compute_entry_name($module_name, $entry_name);

		try {
			PersistenceContext::get_querier()->delete(DB_TABLE_CONFIGS, 'WHERE name=:name', array('name' => $name));
		} catch (MySQLQuerierException $e) {
		}

		CacheManager::invalidate($module_name, $entry_name);
	}

	private static function save_in_db($name, ConfigData $data)
	{
		$serialized_data = TextHelper::serialize($data);

		$update = PersistenceContext::get_querier()->inject('UPDATE ' . DB_TABLE_CONFIGS . ' SET value = :value WHERE name = :name', array('value' => $serialized_data, 'name' => $name));

		if ($update->get_affected_rows() == 0)
		{
			// If the update requests finds the row but has nothing to update, it affects 0 rows
			$count = PersistenceContext::get_querier()->count(DB_TABLE_CONFIGS, 'WHERE name = :name', array('name' => $name));

			if ($count == 0)
			{
				PersistenceContext::get_querier()->inject('INSERT INTO ' . DB_TABLE_CONFIGS . ' (name, value) VALUES (:name, :value)', array('name' => $name, 'value' => $serialized_data));
			}
		}
	}
}
?>
