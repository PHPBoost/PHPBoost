<?php
/*##################################################
 *                          BugtrackerUrlBuilder.class.php
 *                            -------------------
 *   begin                : October 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
 * @author Julien BRISWALTER <julien.briswalter@gmail.com>
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
	public static function configuration_success($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/success/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function configuration_error($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/error/' . $param);
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
		return DispatchManager::get_url(self::$dispatcher, '/admin/delete/default/' . $parameter);
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
	public static function add_error($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/error/' . $param);
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
	public static function edit_error($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/edit/error/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function edit_success($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/edit/success/' . $param);
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
	public static function delete($id = '', $page = 1, $back_page = '', $back_filter = '', $filter_id = 0)
	{
		return DispatchManager::get_url(self::$dispatcher, '/delete/' . $id . '/' . $page . '/' . $back_page . '/' . $back_filter . '/' . $filter_id);
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
		return DispatchManager::get_url(self::$dispatcher, '/delete_filter/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function reject($id = '', $page = 1, $back_page = '', $back_filter = '', $filter_id = 0)
	{
		return DispatchManager::get_url(self::$dispatcher, '/reject/' . $id . '/' . $page . '/' . $back_page . '/' . $back_filter . '/' . $filter_id);
	}
	
	/**
	 * @return Url
	 */
	public static function reopen($id = '', $page = 1, $back_page = '', $back_filter = '', $filter_id = 0)
	{
		return DispatchManager::get_url(self::$dispatcher, '/reopen/' . $id . '/' . $page . '/' . $back_page . '/' . $back_filter . '/' . $filter_id);
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