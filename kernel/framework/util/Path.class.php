<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 11 14
 * @since       PHPBoost 1.6 - 2006 11 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class Path
{
	private static $fs_root_directory;

	/**
	 * @return string the phpboost home directory path
	 */
	public static function phpboost_path()
	{
		if (empty(self::$fs_root_directory))
		{
			self::$fs_root_directory = preg_replace('`^(.+)/kernel/framework/util/?$`iu', '$1',
			self::real_path(dirname(__FILE__)));
		}
		return self::$fs_root_directory;
	}

	/**
	 * Returns the class package
	 * @param string $class_file the class file
	 * @return string the class package
	 * The package is under the form </code>'package/subpackage/../sub..subpackage'</code>
	 */
	public static function get_package($class_file)
	{
		return ltrim(self::get_path_from_root(dirname($class_file)), '/');
	}

	/**
	 * Returns the path from the phpboost root directory
	 * @param string $path the file path
	 * @return string the path from the phpboost root directory beginning by <code>'/'</code>
	 */
	public static function get_path_from_root($path)
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
	 * @param string $class_file the class file path or filename
	 * @return string the classname
	 */
	public static function get_classname($class_file)
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
	 * Uniformizes the path getting its real path (absolute one) and replacing all
	 * <code>'\'</code> by <code>'/'</code>
	 * @param string $path the path to uniformize
	 * @return string the absolute path
	 */
	public static function real_path($path)
	{
		return self::uniformize_path(realpath($path));
	}

	/**
	 * Uniformizes the path replacing all <code>'\'</code> by <code>'/'</code>
	 * @param string $path the path to uniformize
	 * @return string the uniformized path
	 */
	public static function uniformize_path($path)
	{
		return str_replace('\\', '/', $path);
	}
}
?>
