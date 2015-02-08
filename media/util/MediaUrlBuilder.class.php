<?php
/*##################################################
 *                               MediaUrlBuilder.class.php
 *                            -------------------
 *   begin                : February 2, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

class MediaUrlBuilder
{
	private static $dispatcher = '/media';
	
	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}
	
	/**
	 * @return Url
	 */
	public static function add_category()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/add/');
	}
	
	/**
	 * @return Url
	 */
	public static function edit_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/'. $id .'/edit/');
	}
	
	/**
	 * @return Url
	 */
	public static function delete_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/'. $id .'/delete/');
	}
	
	/**
	 * @return Url
	 */
	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/');
	}
	
	/**
	 * @return Url
	 */
	public static function manage()
	{
		return new Url('/media/moderation_media.php');
	}
	
	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name, $page = 1)
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name .'/' : '';
		$page = $page !== 1 ? '&p=' . $page : '';
		return new Url('media/media.php?cat=' . $id . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function add($id_category = null)
	{
		return new Url('/media/media_action.php');
	}
	
	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
