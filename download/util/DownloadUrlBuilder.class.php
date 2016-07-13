<?php
/*##################################################
 *                               DownloadUrlBuilder.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class DownloadUrlBuilder
{
	const DEFAULT_SORT_FIELD = 'date_updated';
	const DEFAULT_SORT_MODE = 'desc';
	
	private static $dispatcher = '/download';
	
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
	public static function add_category($id_parent = null)
	{
		$id_parent = !empty($id_parent) ? $id_parent . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/categories/add/' . $id_parent);
	}
	
	/**
	 * @return Url
	 */
	public static function edit_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/' . $id . '/edit/');
	}
	
	/**
	 * @return Url
	 */
	public static function delete_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/' . $id . '/delete/');
	}
	
	/**
	 * @return Url
	 */
	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/');
	}
	
	/**
	 * @return Url
	 */
	public static function manage()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}
	
	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name, $sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1, $subcategories_page = 1)
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name . '/' : '';
		$page = $page !== 1 || $subcategories_page !== 1 ? $page . '/' : '';
		$subcategories_page = $subcategories_page !== 1 ? $subcategories_page . '/' : '';
		$sort_field = $sort_field !== self::DEFAULT_SORT_FIELD ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== self::DEFAULT_SORT_MODE ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . $sort_field . $sort_mode . $page . $subcategories_page);
	}
	
	/**
	 * @return Url
	 */
	public static function display_tag($rewrited_name, $sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		$sort_field = $sort_field !== self::DEFAULT_SORT_FIELD ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== self::DEFAULT_SORT_MODE ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/tag/' . $rewrited_name . '/' . $sort_field . $sort_mode . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function display_pending($sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		$sort_field = $sort_field !== self::DEFAULT_SORT_FIELD ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== self::DEFAULT_SORT_MODE ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $sort_field . $sort_mode . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function add($id_category = null)
	{
		$id_category = !empty($id_category) ? $id_category . '/' : '';
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
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
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
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_name . '/#comments-list');
	}
	
	/**
	 * @return Url
	 */
	public static function download($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/download/' . $id);
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
