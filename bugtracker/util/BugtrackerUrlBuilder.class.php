<?php
/*##################################################
 *                          BugtrackerUrlBuilder.class.php
 *                            -------------------
 *   begin                : October 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 * @desc Url builder of the bugtracker module
 */
class BugtrackerUrlBuilder
{
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
	public static function add($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function edit($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/edit/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function detail_success($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/detail/success/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function history($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/history/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function history_success($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/history/success/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function roadmap_success($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/roadmap/success/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function roadmap($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/roadmap/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function stats($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/stats/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function stats_success($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/stats/success/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function delete($id = '', $back_page = '', $page = 1, $back_filter = '', $filter_id = 0)
	{
		return DispatchManager::get_url(self::$dispatcher, '/delete/' . $id . ($back_page ? '/' . $back_page : '') . ($page > 1 ? '/' . $page : '') . ($back_filter ? '/' . $back_filter . '/' . $filter_id : '') . '/?token=' . AppContext::get_session()->get_token());
	}
	
	/**
	 * @return Url
	 */
	public static function add_filter($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/add_filter/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function delete_filter($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/delete_filter/' . $param . '/?token=' . AppContext::get_session()->get_token());
	}
	
	/**
	 * @return Url
	 */
	public static function change_status($id = '', $back_page = '', $page = 1, $back_filter = '', $filter_id = 0)
	{
		return DispatchManager::get_url(self::$dispatcher, '/change_status/' . $id . ($back_page ? '/' . $back_page : '') . ($page > 1 ? '/' . $page : '') . ($back_filter ? '/' . $back_filter . '/' . $filter_id : ''));
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
    public static function unsolved($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/unsolved/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function unsolved_success($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/unsolved/success/' . $param);
	}
	
	/**
	 * @return Url
	 */
    public static function solved($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/solved/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function solved_success($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/solved/success/' . $param);
	}
}
?>
