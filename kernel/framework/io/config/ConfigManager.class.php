<?php
/*##################################################
 *                         config_manager.class.php
 *                            -------------------
 *   begin                : September 16, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

import('io/config/ConfigData');

/**
 * @package io
 * @subpackage config
 * @desc This class manages config loading and saving. It makes a two-level lazy loading:
 * <ul>
 * 	<li>A top-level cache which avoids loading a data if it has already been done since the
 * beginning of the current page generation. This cache has a short life span: it's flushed
 * as of the PHP interpreter reaches the end of the page generation.</li>
 * 	<li>A filesystem cache to avoid querying the database every time to obtain the same value.
 * This cache is less powerful than the previous but has an infinite life span. Indeed, it's
 * valid until the value changes and the manager is asked to store it</li>
 * </ul>
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class ConfigManager extends CacheManager
{
	/**
	 * @var ConfigManager
	 */
	private static $config_manager_instance = null;

	/**
	 * @var Sql
	 */
	private $db_connection;

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
		return self::get_config_manager_instance()->load_config($classname, $module_name, $entry_name);
	}

	/**
	 * Saves in the data base (DB_TABLE_CONFIGS table) the data and has it become persistent.
	 * @param string $module_name Name of the module owning this entry
	 * @param ConfigData $data Data to save
	 * @param string $entry_name The name of the entry if the module uses several entries
	 */
	public static function save($module_name, ConfigData $data, $entry_name = '')
	{
		self::get_config_manager_instance()->save_config($module_name, $data, $entry_name);
	}

	/**
	 * @return ConfigManager
	 */
	private static function get_config_manager_instance()
	{
		if (self::$config_manager_instance === null)
		{
			self::$config_manager_instance = new ConfigManager();
			self::$config_manager_instance->db_connection = AppContext::get_sql();
		}
		return self::$config_manager_instance;
	}

	/**
	 * @return ConfigData
	 */
	private function load_config($classname, $module_name, $entry_name = '')
	{
		$name = $this->compute_entry_name($module_name, $entry_name);
		if ($this->is_memory_cached($name))
		{
			return $this->get_memory_cached_data($name);
		}
		else if ($this->is_file_cached($name))
		{
			$data = $this->get_file_cached_data($name);
			$this->memory_cache_data($name, $data);
			return $data;
		}
		else
		{
			try
			{
				$data = $this->load_in_db($name);
			}
			catch(ConfigNotFoundException $ex)
			{
				$data = new $classname();
				$data->set_default_values();
				$this->save_in_db($name, $data);
			}
			$this->file_cache_data($name, $data);
			$this->memory_cache_data($name, $data);
			return $data;
		}
	}

	private function save_config($module_name, ConfigData $data, $entry_name = '')
	{
		$name = $this->compute_entry_name($module_name, $entry_name);
			
		$this->save_in_db($name, $data);
		$this->file_cache_data($name, $data);
		$this->memory_cache_data($name, $data);
	}

	/**
	 * @return ConfigData
	 */
	private function load_in_db($name)
	{
		$result = $this->db_connection->query_array(DB_TABLE_CONFIGS, 'value', "WHERE name = '" .
		$name . "'", __LINE__, __FILE__);

		if ($result === false)
		{
			throw new ConfigNotFoundException($name);
		}
		$required_value = unserialize($result['value']);
		return $required_value;
	}

	private function save_in_db($name, ConfigData $data)
	{
		$serialized_data = addslashes(serialize($data));
		$secure_name = addslashes($name);

		$resource = $this->db_connection->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '"
		. $serialized_data . "' WHERE name = '" . $secure_name . "'", __LINE__, __FILE__);

		// If no entry exists in the data base, we create it
		if ($this->db_connection->affected_rows($resource) == 0)
		{
			$count = (int) $this->db_connection->query("SELECT COUNT(*) FROM " . DB_TABLE_CONFIGS .
		    	" WHERE name = '" . $secure_name . "'", __LINE__, __FILE__);
			if ($count == 0)
			{
				$this->db_connection->query_inject("INSERT INTO " . DB_TABLE_CONFIGS . " (name, value) " .
    				"VALUES('" . $secure_name . "', '" . $serialized_data . "')",
				__LINE__, __FILE__);
			}
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/cache/CacheManager#get_file($name)
	 */
	protected function get_file($name)
	{
		return new File(PATH_TO_ROOT . '/cache/' . $name . '.cfg');
	}
}

?>