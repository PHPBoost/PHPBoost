<?php
/*##################################################
 *                          SandboxUrlBuilder.class.php
 *                            -------------------
 *   begin                : December 17, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 * @desc Url builder of the sandbox module
 */
class SandboxUrlBuilder
{
	private static $dispatcher = '/sandbox';
	
	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
	
	/**
	 * @return Url
	 */
	public static function css()
	{
		return DispatchManager::get_url(self::$dispatcher, '/css');
	}
	
	/**
	 * @return Url
	 */
	public static function form()
	{
		return DispatchManager::get_url(self::$dispatcher, '/form');
	}
	
	/**
	 * @return Url
	 */
	public static function icons()
	{
		return DispatchManager::get_url(self::$dispatcher, '/icons');
	}
	
	/**
	 * @return Url
	 */
	public static function mail()
	{
		return DispatchManager::get_url(self::$dispatcher, '/mail');
	}
	
	/**
	 * @return Url
	 */
	public static function table()
	{
		return DispatchManager::get_url(self::$dispatcher, '/table');
	}
	
	/**
	 * @return Url
	 */
	public static function template()
	{
		return DispatchManager::get_url(self::$dispatcher, '/template');
	}
	
	/**
	 * @return Url
	 */
	public static function menu()
	{
		return DispatchManager::get_url(self::$dispatcher, '/menu');
	}
}
?>
