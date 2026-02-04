<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 04
 * @since       PHPBoost 3.0 - 2011 09 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCacheUrlBuilder
{
    private static string $dispatcher = '/admin/cache';

    /**
     * @return Url
     */
    public static function clear_cache(): Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/data', true);
    }

    /**
     * @return Url
     */
    public static function configuration(): Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/config', true);
    }

    /**
     * @return Url
     */
    public static function clear_css_cache(): Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/css', true);
    }

    /**
     * @return Url
     */
    public static function clear_syndication_cache(): Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/syndication', true);
    }
}
?>
