<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
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

    /**
     * @return Url
     */
    public static function introduction()
    {
        return self::url('/introduction');
    }

    /**
     * @return Url
     */
    public static function server_configuration()
    {
        return self::url('/server');
    }

    /**
     * @return Url
     */
    public static function database()
    {
        return self::url('/database');
    }

    /**
     * @return Url
     */
    public static function update()
    {
        return self::url('/execute');
    }

    /**
     * @return Url
     */
    public static function config()
    {
        return self::url('/config');
    }

    /**
     * @return Url
     */
    public static function finish()
    {
        return self::url('/finish');
    }

    /**
     * @param string the url path from the dispatcher root
     * @return Url
     */
    private static function url($path)
    {
        if (self::$locale  != UpdateController::DEFAULT_LOCALE)
        {
            $path .= '?lang=' . self::$locale;
        }
		return DispatchManager::get_url(self::$dispatcher, $path, true);
    }
}
?>
