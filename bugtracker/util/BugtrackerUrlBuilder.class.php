<?php
/*##################################################
 *                          BugtrackerUrlBuilder.class.php
 *                            -------------------
 *   begin                : October 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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
 * @desc Url builder of the bugtracker module
 */
class BugtrackerUrlBuilder
{
	const DEFAULT_SORT_FIELD = 'date';
	const DEFAULT_SORT_MODE = 'desc';
	
	private static $dispatcher = '/bugtracker';
	
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
	public static function authorizations()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/authorizations');
	}
	
	/**
	 * @return Url
	 */
	public static function delete_parameter($parameter, $id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/delete/' . $parameter . '/' . $id);
	}
	
	/**
	 * @return Url
	 */
	public static function delete_default_parameter($parameter)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/delete/default/' . $parameter . '/?token=' . AppContext::get_session()->get_token());
	}
	
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
	public static function detail($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/detail/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function detail_comments($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/detail/' . $param, true);
	}
	
	/**
	 * @return Url
	 */
	public static function add()
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
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
	public static function history($id, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/history/' . $id . '/' . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function roadmap($version = 0, $version_name = null, $status = null, $sort_field = '', $sort_mode = '', $page = 1)
	{
		$version = $version !== 0 ? $version . '-' : '';
		$version_name = $version_name !== null ? $version_name . '/' : '';
		$status = $status !== null ? $status . '/' : '';
		$sort_field = $sort_field !== '' ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== '' ? $sort_mode . '/' : '';
		$page = $page !== 1 ? $page . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/roadmap/' . $version . $version_name . $status . $sort_field . $sort_mode . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function stats()
	{
		return DispatchManager::get_url(self::$dispatcher, '/stats/');
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
	public static function add_filter($page = 1, $filter = null, $filter_id = 0)
	{
		$page = $page !== 1 ? $page . '/' : '';
		$filter = $filter !== null ? $filter . '/' : '';
		$filter_id = $filter !== null && $filter_id !== 0 ? $filter_id . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add_filter/' . $page . $filter . $filter_id);
	}
	
	/**
	 * @return Url
	 */
	public static function delete_filter()
	{
		return DispatchManager::get_url(self::$dispatcher, '/delete_filter/');
	}
	
	/**
	 * @return Url
	 */
	public static function change_status($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/change_status/' . $id);
	}
	
	/**
	 * @return Url
	 */
	public static function check_status_changed()
	{
		return DispatchManager::get_url(self::$dispatcher, '/check_status_changed/');
	}
	
	/**
	 * @return Url
	 */
    public static function unsolved($sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1, $filter = null, $filter_id = 0)
	{
		$sort_field = $sort_field !== '' ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== '' ? $sort_mode . '/' : '';
		$page = $page !== 1 ? $page . '/' : '';
		$filter = $filter !== null ? $filter . '/' : '';
		$filter_id = $filter !== null && $filter_id !== 0 ? $filter_id : '';
		return DispatchManager::get_url(self::$dispatcher, '/unsolved/' . $sort_field . $sort_mode . $page . $filter . $filter_id);
	}
	
	/**
	 * @return Url
	 */
    public static function solved($sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1, $filter = null, $filter_id = 0)
	{
		$sort_field = $sort_field !== '' ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== '' ? $sort_mode . '/' : '';
		$page = $page !== 1 ? $page . '/' : '';
		$filter = $filter !== null ? $filter . '/' : '';
		$filter_id = $filter !== null && $filter_id !== 0 ? $filter_id : '';
		return DispatchManager::get_url(self::$dispatcher, '/solved/' . $sort_field . $sort_mode . $page . $filter . $filter_id);
	}
}
?>
