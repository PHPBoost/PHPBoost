<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 10 16
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class InstallUrlBuilder
{
    private static $dispatcher = '/install/index.php';

    private static $locale = '';

    public static function set_locale($locale)
    {
        self::$locale = $locale;
    }

    /**
     * @return Url
     */
    public static function welcome()
    {
        return self::url('/welcome');
    }

    /**
     * @return Url
     */
    public static function license()
    {
        return self::url('/license');
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
    public static function website()
    {
        return self::url('/website');
    }

    /**
     * @return Url
     */
    public static function admin()
    {
        return self::url('/admin');
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
        if (self::$locale  != InstallationServices::get_default_lang())
        {
            $path .= '?lang=' . self::$locale;
        }
		return DispatchManager::get_url(self::$dispatcher, $path, true);
    }
}
?>
