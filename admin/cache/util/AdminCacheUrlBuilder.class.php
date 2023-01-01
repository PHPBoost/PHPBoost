<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 03 14
 * @since       PHPBoost 3.0 - 2011 09 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminCacheUrlBuilder
{
    private static $dispatcher = '/admin/cache';

	/**
	 * @return Url
	 */
    public static function clear_cache()
	{
		return DispatchManager::get_url(self::$dispatcher, '/data', true);
	}

	/**
	 * @return Url
	 */
    public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/config', true);
	}

	/**
	 * @return Url
	 */
    public static function clear_css_cache()
	{
		return DispatchManager::get_url(self::$dispatcher, '/css', true);
	}

	/**
	 * @return Url
	 */
    public static function clear_syndication_cache()
	{
		return DispatchManager::get_url(self::$dispatcher, '/syndication', true);
	}
}
?>
