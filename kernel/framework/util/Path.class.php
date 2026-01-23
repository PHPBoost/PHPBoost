<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 01 23
 * @since       PHPBoost 1.6 - 2006 11 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Path
{
    private static string $fs_root_directory = '';

    /**
     * @return string The PHPBoost home directory path
     */
    public static function phpboost_path(): string
    {
        if (empty(self::$fs_root_directory))
        {
            self::$fs_root_directory = preg_replace('`^(.+)/kernel/framework/util/?$`iu', '$1', self::real_path(dirname(__FILE__)));
        }
        return self::$fs_root_directory;
    }

    /**
     * Returns the class package
     * @param string $class_file The class file
     * @return string The class package
     */
    public static function get_package(string $class_file): string
    {
        return ltrim(self::get_path_from_root(dirname($class_file)), '/');
    }

    /**
     * Returns the path from the PHPBoost root directory
     * @param string $path The file path
     * @return string The path from the PHPBoost root directory beginning by '/'
     */
    public static function get_path_from_root(string $path): string
    {
        $path_from_root = trim(str_replace(self::phpboost_path(), '', self::real_path($path)), '/');
        if (!empty($path_from_root))
        {
            return '/' . $path_from_root;
        }
        return '';
    }

    /**
     * Deduces the classname regarding the filename
     * @param string $class_file The class file path or filename
     * @return string The classname
     */
    public static function get_classname(string $class_file): string
    {
        $class_file = self::uniformize_path($class_file);
        if (($i = TextHelper::strpos($class_file, '.')) !== false)
        {
            $class_file = TextHelper::substr($class_file, 0, $i);
        }
        if (($i = TextHelper::strrpos($class_file, '/')) !== false)
        {
            $class_file = TextHelper::substr($class_file, $i + 1);
        }
        return $class_file;
    }

    /**
     * Uniformizes the path getting its real path (absolute one) and replacing all '\' by '/'
     * @param string $path The path to uniformize
     * @return string The absolute path
     */
    public static function real_path(string $path): string
    {
        return self::uniformize_path(realpath($path));
    }

    /**
     * Uniformizes the path replacing all '\' by '/'
     * @param string $path The path to uniformize
     * @return string The uniformized path
     */
    public static function uniformize_path(?string $path): string
    {
        return str_replace('\\', '/', $path ?? '');
    }
}
