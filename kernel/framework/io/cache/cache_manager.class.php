<?php
/*##################################################
 *                         cache_manager.class.php
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

import('io/filesystem/file');
import('io/cache/cache_data');

/**
 * @package io
 * @subpackage cache 
 * @desc This class manages data loading. It makes a two-level lazy loading:
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
class CacheManager
{
    /**
     * @var The top-level cache which associates a name to the corresponding data. 
     */
	private static $cached_data = array();
	
	/**
	 * Load the data whose key is $name.
	 * @param $module_name Name of the module owning the entry to load
	 * @param $entry_name If the module wants to manage several entries, 
	 * it's the name of the entry you want to load
	 * @return CacheData The loaded data
	 */
	public static function load($module_name, $entry_name = '')
	{
	    $name = self::compute_entry_name($module_name, $entry_name);
		if (self::is_memory_cached($name))
		{
			return self::get_memory_cached_data($name);
		}
		else if (self::is_file_cached($name))
		{
            $data = self::get_file_cached_data($name);
            self::memory_cache_data($name, $data);
            return $data;
		}
		else
		{
			$data = self::load_in_db($name);
			self::file_cache_data($name, $data);
			self::memory_cache_data($name, $data);
			return $data;
		}
	}
	
	/**
	 * Saves in the data base (DB_TABLE_CONFIGS table) the data and has it become persistent.
	 * @param string $module_name Name of the module owning this entry
 	 * @param CacheData $data Data to save
 	 * @param string $entry_name The name of the entry if the module uses several entries
	 */
	public static function save($module_name, CacheData $data, $entry_name)
	{
	    $name = self::compute_entry_name($module_name, $entry_name);
	    
	    $data->synchronize();
	    
		self::save_in_db($name, $data);
		self::file_cache_data($name, $data);
		self::memory_cache_data($name, $data);
	}
	
	private static function load_in_db($name)
	{
		global $Sql;
		$result = $Sql->query_array(DB_TABLE_CONFIGS, 'value', "WHERE name = '" . 
			$name . "'", __LINE__, __FILE__);
		$required_value = unserialize($result['value']);
		return $required_value;
	}
	
	private static function save_in_db($name, CacheData $data)
	{
		global $Sql;
		$serialized_data = addslashes(serialize($data));
		$secure_name = addslashes($name);
		
		$resource = $Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '"
			 . $serialized_data . "' WHERE name = '" . $secure_name . "'", __LINE__, __FILE__);

		// If no entry exists in the data base, we create it
		if ($Sql->affected_rows($resource) == 0)
		{
		    $count = (int)$Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_CONFIGS . 
		    	" WHERE name = '" . $secure_name . "'", __LINE__, __FILE__);
		    if ($count == 0)
		    {
			    $Sql->query_inject("INSERT INTO " . DB_TABLE_CONFIGS . " (name, value) " .
    				"VALUES('" . $secure_name . "', '" . $serialized_data . "')",
	    			__LINE__, __FILE__);
		    }
		}
	}
	
	private static function compute_entry_name($module_name, $entry_name)
	{
	    if (!empty($entry_name))
	    {
    	    return url_encode_rewrite($module_name . '-' . $entry_name);
	    }
	    else
	    {
	        return url_encode_rewrite($module_name);
	    }
	}
	
	//Top-level (memory) cache management
	private static function is_memory_cached($name)
	{
		return !empty(self::$cached_data);
	}
	
	private static function get_memory_cached_data($name)
	{
		return self::$cached_data[$name];
	}
	
	private static function memory_cache_data($name, CacheData  $value)
	{
		self::$cached_data[$name] = $value;
	}
	
	//Filesystem cache
	private static function get_file($name)
	{
	    return new File(PATH_TO_ROOT . '/cache/' . $name . '.data');
	}
	
    private static function is_file_cached($name)
	{
	    $file = self::get_file($name);
	    return $file->exists();
	}
	
	private static function get_file_cached_data($name)
	{
	    $file = self::get_file($name);
		$content = $file->get_contents();
		$data = unserialize($content);
		return $data;
	}
	
	private static function file_cache_data($name, CacheData $value)
	{
		$file = self::get_file($name);
		$data_to_write = serialize($value);
		$file->write($data_to_write, ERASE);
	}
}

?>