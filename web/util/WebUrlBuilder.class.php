<?php
/*##################################################
 *                               WebUrlBuilder.class.php
 *                            -------------------
 *   begin                : August 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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

class WebUrlBuilder
{
	const DEFAULT_SORT_FIELD = 'name';
	const DEFAULT_SORT_MODE = 'asc';
	
	private static $dispatcher = '/web';
	
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
	public static function manage($sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		$sort_field = $sort_field !== self::DEFAULT_SORT_FIELD ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== self::DEFAULT_SORT_MODE ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/admin/manage/' . $sort_field . $sort_mode . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name, $sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1)
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name .'/' : '';
		$page = $page !== 1 ? $page . '/': '';
		$sort_field = $sort_field !== self::DEFAULT_SORT_FIELD ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== self::DEFAULT_SORT_MODE ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . $sort_field . $sort_mode . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function display_tag($rewrited_name, $sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1)
	{
		$page = $page !== 1 ? $page . '/': '';
		$sort_field = $sort_field !== self::DEFAULT_SORT_FIELD ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== self::DEFAULT_SORT_MODE ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/tag/' . $rewrited_name . '/' . $sort_field . $sort_mode . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function display_pending($sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1)
	{
		$page = $page !== 1 ? $page . '/': '';
		$sort_field = $sort_field !== self::DEFAULT_SORT_FIELD ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== self::DEFAULT_SORT_MODE ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $sort_field . $sort_mode . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function add($id_category = null)
	{
		$id_category = !empty($id_category) ? $id_category . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
	}
	
	/**
	 * @return Url
	 */
	public static function edit($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}
	
	/**
	 * @return Url
	 */
	public static function delete($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?token=' . AppContext::get_session()->get_token());
	}
	
	/**
	 * @return Url
	 */
	public static function display($id_category, $rewrited_name_category, $id, $rewrited_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_name . '/');
	}
	
	/**
	 * @return Url
	 */
	public static function display_comments($id_category, $rewrited_name_category, $id, $rewrited_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_name . '/#comments_list');
	}
	
	/**
	 * @return Url
	 */
	public static function visit($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/visit/' . $id);
	}
	
	/**
	 * @return Url
	 */
	public static function dead_link($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/dead_link/' . $id);
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
