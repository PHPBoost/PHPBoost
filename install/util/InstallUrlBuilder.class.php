<?php
/*##################################################
 *                          InstallUrlBuilder.class.php
 *                            -------------------
 *   begin                : June 13, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 */
class InstallUrlBuilder
{
    private static $dispatcher = '/install/index.php';

    private static $locale = InstallController::DEFAULT_LOCALE;

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
    public static function check_database()
    {
        return self::url('/database/check');
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
    	if (self::$locale  != InstallController::DEFAULT_LOCALE)
    	{
    		$path .= '?lang=' . self::$locale;
    	}
        return DispatchManager::get_url(self::$dispatcher, $path);
    }
}
?>