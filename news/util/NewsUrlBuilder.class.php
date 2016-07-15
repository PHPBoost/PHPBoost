<?php
/*##################################################
 *		                NewsUrlBuilder.class.php
 *                            -------------------
 *   begin                : February 13, 2013
 *   copyright            : (C) 2013 Kevin MASSY
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
 */
class NewsUrlBuilder
{
	const DEFAULT_SORT_FIELD = 'date';
	const DEFAULT_SORT_MODE = 'desc';
	
	private static $dispatcher = '/news';
	
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}
	
	public static function add_category($id_parent = null)
	{
		$id_parent = !empty($id_parent) ? $id_parent . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/categories/add/' . $id_parent);
	}
	
	public static function edit_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/'. $id .'/edit/');
	}
	
	public static function delete_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/'. $id .'/delete/');
	}
	
	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/');
	}
	
	public static function manage_news()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}
	
	public static function category_syndication($id)
	{
		return SyndicationUrlBuilder::rss('news', $id);
	}
	
	public static function display_news($id_category, $rewrited_name_category, $id_news, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_news . '-' . $rewrited_title . '/');
	}
	
	public static function display_comments_news($id_category, $rewrited_name_category, $id_news, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_news . '-' . $rewrited_title . '/#comments-list');
	}
	
	public static function display_category($id, $rewrited_name, $page = 1)
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name .'/' : '';
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . $page);
	}
	
	public static function display_tag($rewrited_name, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/tag/'. $rewrited_name .'/' . $page);
	}
	
	public static function display_pending_news($page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $page);
	}
	
	public static function add_news($id_category = null)
	{
		$id_category = !empty($id_category) ? $id_category . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
	}
	
	public static function edit_news($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}
	
	public static function delete_news($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}
	
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>