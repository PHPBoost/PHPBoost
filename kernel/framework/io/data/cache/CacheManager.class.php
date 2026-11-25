<?php
/**
 * This class manages cache. It makes a two-level lazy loading:
 * <ul>
 *     <li>A top-level cache which avoids loading a data if it has already been done since the
 * beginning of the current page generation. This cache has a short life span: it's flushed
 * as of the PHP interpreter reaches the end of the page generation.</li>
 *     <li>A filesystem or shared RAM (via APC) cache to avoid querying the database every time to obtain the same value.
 * This cache is less powerful than the previous but has an infinite life span. Indeed, it's
 * valid until the value changes and the manager is asked to store it</li>
 * </ul>
 * @package     IO
 * @subpackage  Data\cache
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 25
 * @since       PHPBoost 3.0 - 2009 09 16
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class CacheManager
{
    private static ?RAMDataStore $ram_cache = null;
    private static ?DataStore $fs_cache = null;

    private static function get_ram_cache(): RAMDataStore
    {
        if (self::$ram_cache == null) {
            self::$ram_cache = new RAMDataStore();
        }
        return self::$ram_cache;
    }

    private static function get_fs_cache(): DataStore
    {
        if (self::$fs_cache === null) {
            self::$fs_cache = DataStoreFactory::get_filesystem_store(__CLASS__);
        }
        return self::$fs_cache;
    }

    /**
     * Loads the data which is identified by the parameters
     * @param string $classname Name of the class of which the result will be an instance
     * @param string $module_name Name of the module owning the entry to load
     * @param string $entry_name If the module wants to manage several entries,
     * it's the name of the entry you want to load
     * @return CacheData The loaded data
     */
    public static function load(string $classname, string $module_name, string $entry_name = ''): CacheData
    {
        $name = self::compute_entry_name($module_name, $entry_name);
        try
        {
            return self::try_load($classname, $module_name, $entry_name);
        } catch (CacheDataNotFoundException $ex) {
            //Not cached anywhere, we create it
            $data = new $classname($module_name);
            $data->synchronize();
            self::file_cache_data($name, $data);
            self::memory_cache_data($name, $data);
            return $data;
        }
    }

    /**
     * Tries to load the data which is identified by the parameters
     * @param string $classname Name of the class of which the result will be an instance
     * @param string $module_name Name of the module
     * @param string $entry_name Name of the entry of the module
     * @return CacheData The loaded data
     * @throws CacheDataNotFoundException if the cache doesn't exist
     */
    public static function try_load(string $classname, string $module_name, string $entry_name): CacheData
    {
        $name = self::compute_entry_name($module_name, $entry_name);

        if (self::is_memory_cached($name))
        {
            $data = self::get_memory_cached_data($name);
            if ($data instanceof $classname)
            {
                return $data;
            }
        }
        if (self::is_file_cached($name))
        {
            try
            {
                $data = self::get_file_cached_data($name);
                if ($data instanceof $classname)
                {
                    self::memory_cache_data($name, $data);
                    return $data;
                }
            }
            catch (DataStoreException $ex)
            {
                // The cache entry may have disappeared or failed validation after the existence check.
            }
        }
        throw new CacheDataNotFoundException($name);
    }

    /**
     * Invalidates an entry which is cached. If the corresponding data are loaded agin,
     * they will be regenerated.
     * @param string $module_name Name of the module owning the entry to invalidate
     * @param string $entry_name If the module wants to manage several entries,
     * it's the name of the entry you want to invalidate
     */
    public static function invalidate(string $module_name, string $entry_name = ''): void
    {
        $name = self::compute_entry_name($module_name, $entry_name);
        self::invalidate_file_cache($name);
        self::invalidate_memory_cache($name);
    }

    /**
     * Invalidates all the cached data
     */
    public static function clear(): void
    {
        self::get_ram_cache()->clear();
        self::get_fs_cache()->clear();
    }

    /**
     * Caches the data corresponding to the given identifier
     * @param mixed $data The data to cache
     * @param string $module_name Name of the module owning the entry to save
     * @param string $entry_name Name of the entry to save
     */
    public static function save(mixed $data, string $module_name, string $entry_name = ''): void
    {
        $name = self::compute_entry_name($module_name, $entry_name);
        self::file_cache_data($name, $data);
        self::memory_cache_data($name, $data);
    }

    private static function compute_entry_name(string $module_name, string $entry_name): string
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

    //RAM cache management
    private static function is_memory_cached(string $name): bool
    {
        return self::get_ram_cache()->contains($name);
    }

    private static function get_memory_cached_data(string $name): CacheData
    {
        return self::get_ram_cache()->get($name);
    }

    private static function memory_cache_data(string $name, CacheData $value): void
    {
        self::get_ram_cache()->store($name, $value);
    }

    private static function invalidate_memory_cache(string $name): void
    {
        self::get_ram_cache()->delete($name);
    }

    private static function get_file_name(string $name): string
    {
        return $name . '.data';
    }

    private static function is_file_cached(string $name): bool
    {
        return self::get_fs_cache()->contains(self::get_file_name($name));
    }

    private static function get_file_cached_data(string $name): CacheData
    {
        return self::get_fs_cache()->get(self::get_file_name($name));
    }

    private static function file_cache_data(string $name, CacheData $value): void
    {
        self::get_fs_cache()->store(self::get_file_name($name), $value);
    }

    private static function invalidate_file_cache(string $name): void
    {
        self::get_fs_cache()->delete(self::get_file_name($name));
    }
}
