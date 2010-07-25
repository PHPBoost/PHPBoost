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
    
    /**
     * @return Url
     */
    public static function welcome()
    {
        return DispatchManager::get_url(self::$dispatcher, '/welcome');
    }
    
    /**
     * @return Url
     */
    public static function license()
    {
        return DispatchManager::get_url(self::$dispatcher, '/license');
    }
    
    /**
     * @return Url
     */
    public static function server_configuration()
    {
        return DispatchManager::get_url(self::$dispatcher, '/server');
    }
    
    /**
     * @return Url
     */
    public static function database()
    {
        return DispatchManager::get_url(self::$dispatcher, '/database');
    }
    
    /**
     * @return Url
     */
    public static function website()
    {
        return DispatchManager::get_url(self::$dispatcher, '/website');
    }
    
    /**
     * @return Url
     */
    public static function admin()
    {
        return DispatchManager::get_url(self::$dispatcher, '/admin');
    }
    
    /**
     * @return Url
     */
    public static function finish()
    {
        return DispatchManager::get_url(self::$dispatcher, '/finish');
    }
}
?>