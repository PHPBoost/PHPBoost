<?php
/*##################################################
 *                       ArticlesUrlBuilder.class.php
 *                            -------------------
 *   begin                : October 14, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class ArticlesUrlBuilder
{
	private static $dispatcher = '/articles';
	
	public static function articles_management($title = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/article/' . $title);
	}
	
	public static function articles_category_management()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/');
	}
	
	public static function add_category()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/add/');
	}
	
	public static function print_article($title = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/print/' . $title);
	}
	
	public static function articles_configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}
	
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/home/');
	}
}
?>