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
 * @subpackage cache
 * @desc This class manages cache. It makes a two-level lazy loading:
 * <ul>
 * 	<li>A top-level cache which avoids loading a data if it has already been done since the
 * beginning of the current page generation. This cache has a short life span: it's flushed
 * as of the PHP interpreter reaches the end of the page generation.</li>
 * 	<li>A filesystem or shared RAM (via APC) cache to avoid querying the database every time to obtain the same value.
 * This cache is less powerful than the previous but has an infinite life span. Indeed, it's
 * valid until the value changes and the manager is asked to store it</li>
 * </ul>
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class CacheManager
{
	/**
	 * @var CacheManager
	 */
	private static $cache_manager_instance = null;

	/**
	 * @var CacheContainer The RAM cache
	 */
	protected $ram_cache = null;

	protected function __construct()
	{
		$this->ram_cache = new RAMCacheContainer();
	}

	/**
	 * Loads the data which is identified by the parameters
	 * @param $classname Name of the class of which the result will be an instance
	 * @param $module_name Name of the module owning the entry to load
	 * @param $entry_name If the module wants to manage several entries,
	 * it's the name of the entry you want to load
	 * @return CacheData The loaded data
	 */
	public static function load($classname, $module_name, $entry_name = '')
	{
		return self::get_cache_manager_instance()->load_data($classname, $module_name, $entry_name);
	}

	/**
	 * Invalidates an entry which is cached. If the corresponding data are loaded agin,
	 * they will be regenerated.
	 * @param $module_name Name of the module owning the entry to invalidate
	 * @param $entry_name If the module wants to manage several entries,
	 * it's the name of the entry you want to invalidate
	 */
	public static function invalidate($module_name, $entry_name = '')
	{
		$name = self::compute_entry_name($module_name, $entry_name);
		self::get_cache_manager_instance()->invalidate_file_cache($name);
		self::get_cache_manager_instance()->invalidate_memory_cache($name);
	}

	/**
	 * @return CacheManager
	 */
	private static function get_cache_manager_instance()
	{
		if (self::$cache_manager_instance === null)
		{
			self::$cache_manager_instance = new CacheManager();
		}
		return self::$cache_manager_instance;
	}

	/**
	 * @return CacheData
	 */
	protected function load_data($classname, $module_name, $entry_name = '')
	{
		$name = self::compute_entry_name($module_name, $entry_name);
		if ($this->is_memory_cached($name))
		{
			return $this->get_memory_cached_data($name);
		}
		else if ($this->is_file_cached($name))
		{
			$data = $this->get_file_cached_data($name);
			if ($data instanceof $classname)
			{
				$this->memory_cache_data($name, $data);
				return $data;
			}
		}

		//Not cached anywhere, we create it
		$data = new $classname();
		$data->synchronize();
		$this->file_cache_data($name, $data);
		$this->memory_cache_data($name, $data);
		return $data;
	}

	protected function invalidate_file_cache($name)
	{
		try
		{
			$this->get_file($name)->delete();
		}
		catch(IOException $ex)
		{

		}
	}

	protected function invalidate_memory_cache($name)
	{
		$this->ram_cache->delete($name);
	}

	/**
	 * @return string
	 */
	protected static function compute_entry_name($module_name, $entry_name)
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

	//RAM cache management
	/**
	 * @return bool
	 */
	protected function is_memory_cached($name)
	{
		return $this->ram_cache->contains($name);
	}

	/**
	 * @return CacheData
	 */
	protected function get_memory_cached_data($name)
	{
		return $this->ram_cache->get($name);
	}

	protected function memory_cache_data($name, CacheData  $value)
	{
		return $this->ram_cache->store($name, $value);
	}

	//Filesystem cache
	/**
	 * @return File
	 */
	protected function get_file($name)
	{
		return new File(PATH_TO_ROOT . '/cache/' . $name . '.data');
	}

	/**
	 * @return bool
	 */
	protected function is_file_cached($name)
	{
		$file = $this->get_file($name);
		return $file->exists();
	}

	/**
	 * @return string
	 */
	protected function get_file_cached_data($name)
	{
		// TODO Make a cache system that uses either the filesystem or the RAM via APC 
		$file = $this->get_file($name);
		$content = $file->read();
		$data = unserialize($content);
		return $data;
	}

	protected function file_cache_data($name, CacheData $value)
	{
		$file = $this->get_file($name);
		$data_to_write = serialize($value);
		$file->write($data_to_write);
	}
}

?>