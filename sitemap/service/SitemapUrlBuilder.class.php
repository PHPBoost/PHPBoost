<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 01
 * @since       PHPBoost 3.0 - 2009 12 13
*/

class SitemapUrlBuilder
{
	private static $dispatcher = '/sitemap/index.php';

	/**
	 * @return Url
	 */
	public static function get_general_config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin');
	}

	/**
	 * @return Url
	 */
	public static function get_xml_file_generation()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/generate');
	}

	/**
	 * @return Url
	 */
	public static function view_sitemap_xml()
	{
		return DispatchManager::get_url(self::$dispatcher, '/view/xml');
	}

	/**
	 * @return Url
	 */
	public static function view_sitemap()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
