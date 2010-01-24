<?php
/*##################################################
 *                         ConfigManager.class.php
 *                            -------------------
 *   begin                : September 16, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package io
 * @subpackage data/config
 * @desc This class manages config loading and saving. It makes a two-level lazy loading:
 * <ul>
 * 	<li>A top-level cache which avoids loading a data if it has already been done since the
 * beginning of the current page generation. This cache has a short life span: it's flushed
 * as of the PHP interpreter reaches the end of the page generation.</li>
 * 	<li>A filesystem or shared RAM cache to avoid querying the database many times to obtain the same value.
 * This cache is less powerful than the previous one but it has an infinite life span. Indeed, it's
 * valid until the value changes and the manager is asked to store it</li>
 * </ul>
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
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
			try
			{
				$data = self::load_in_db($module_name, $entry_name);
			}
			catch(ConfigNotFoundException $ex)
			{
				$data = new $classname();
				$data->set_default_values();
				$name = self::compute_entry_name($module_name, $entry_name);
				self::save_in_db($name, $data);
			}
			CacheManager::save($data, $module_name, $entry_name);
			return $data;
		}
	}

	/**
	 * @return ConfigData
	 */
	private static function load_in_db($module_name, $entry_name = '')
	{
		$name = self::compute_entry_name($module_name, $entry_name);

		$result = AppContext::get_sql()->query_array(DB_TABLE_CONFIGS, 'value', "WHERE name = '" . $name . "'", __LINE__, __FILE__);

		if ($result === false)
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

	private static function save_in_db($name, ConfigData $data)
	{
		$serialized_data = addslashes(serialize($data));
		$secure_name = addslashes($name);

		$resource = AppContext::get_sql()->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '"
		. $serialized_data . "' WHERE name = '" . $secure_name . "'", __LINE__, __FILE__);

		// If no entry exists in the data base, we create it
		if (AppContext::get_sql()->affected_rows($resource) == 0)
		{
			$count = (int) AppContext::get_sql()->query("SELECT COUNT(*) FROM " . DB_TABLE_CONFIGS .
		    	" WHERE name = '" . $secure_name . "'", __LINE__, __FILE__);
			if ($count == 0)
			{
				AppContext::get_sql()->query_inject("INSERT INTO " . DB_TABLE_CONFIGS . " (name, value) " .
    				"VALUES('" . $secure_name . "', '" . $serialized_data . "')",
				__LINE__, __FILE__);
			}
		}
	}
}

?>