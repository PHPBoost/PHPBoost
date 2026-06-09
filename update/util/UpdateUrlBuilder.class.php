<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 02 29
*/

class UpdateUrlBuilder
{
    private static $dispatcher = '/update/index.php';

    private static $locale = UpdateController::DEFAULT_LOCALE;

    public static function set_locale($locale)
    {
        self::$locale = $locale;
    }

    public static function introduction(): Url
    {
        return self::url('/introduction');
    }

    public static function server_configuration(): Url
    {
        return self::url('/server');
    }

    public static function database(): Url
    {
        return self::url('/database');
    }

    public static function update(): Url
    {
        return self::url('/execute');
    }

    public static function config(): Url
    {
        return self::url('/config');
    }

    public static function finish(): Url
    {
        return self::url('/finish');
    }

    private static function url(string $path): Url
    {
        if (self::$locale  != UpdateController::DEFAULT_LOCALE)
        {
            $path .= '?lang=' . self::$locale;
        }
		return DispatchManager::get_url(self::$dispatcher, $path, true);
    }
}
?>
