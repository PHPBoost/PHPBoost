<?php
/*##################################################
 *                         AdminSearchUrlBuilder.class.php
 *                            -------------------
 *   begin                : April 10, 2010
 *   copyright            : (C) 2009 Loïc Rouchon
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
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @desc
 */
class AdminSearchUrlBuilder
{
    private static $dispatcher = '/search/index.php';
    
    /**
     * @return Url
     */
    public static function config()
    {
        return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
    }
    
    /**
     * @return Url
     */
    public static function weight()
    {
        return DispatchManager::get_url(self::$dispatcher, '/admin/weight/');
    }
    
    /**
     * @return Url
     */
    public static function clear_cache()
    {
    	$token = AppContext::get_session()->get_token();
        return DispatchManager::get_url(self::$dispatcher, '/admin/cache/clear/?token=' . $token);
    }
}
?>