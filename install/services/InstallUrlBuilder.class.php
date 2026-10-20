<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 20
 * @since       PHPBoost 3.0 - 2009 12 13
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class InstallUrlBuilder
{
    private static $dispatcher = '/install/index.php';

    private static $locale = '';

    public static function set_locale(string $locale): void
    {
        self::$locale = $locale;
    }

    public static function welcome(): Url
    {
        return self::url('/welcome');
    }

    public static function license(): Url
    {
        return self::url('/license');
    }

    public static function server_configuration(): Url
    {
        return self::url('/server');
    }

    public static function database(): Url
    {
        return self::url('/database');
    }

    public static function website(): Url
    {
        return self::url('/website');
    }

    public static function admin(): Url
    {
        return self::url('/admin');
    }

    public static function finish(): Url
    {
        return self::url('/finish');
    }

    /** @param string $path the url path from the dispatcher root */
    private static function url(string $path): Url
    {
        if (self::$locale  != InstallationServices::get_default_lang()) {
            $path .= '?lang=' . self::$locale;
        }
		return DispatchManager::get_url(self::$dispatcher, $path, true);
    }
}
?>
