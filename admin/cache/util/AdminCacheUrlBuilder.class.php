<?php
/*##################################################
 *                          AdminCacheUrlBuilder.class.php
 *                            -------------------
 *   begin                : September 19, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc
 */
class AdminCacheUrlBuilder
{
    private static $dispatcher = '/admin/cache';
    
	/**
	 * @return Url
	 */
    public static function clear_cache()
	{
		return DispatchManager::get_url(self::$dispatcher, '/data');
	}
	
	/**
	 * @return Url
	 */
    public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/config');
	}
	
	/**
	 * @return Url
	 */
    public static function clear_css_cache()
	{
		return DispatchManager::get_url(self::$dispatcher, '/css');
	}
	
	/**
	 * @return Url
	 */
    public static function clear_syndication_cache()
	{
		return DispatchManager::get_url(self::$dispatcher, '/syndication');
	}
}
?>