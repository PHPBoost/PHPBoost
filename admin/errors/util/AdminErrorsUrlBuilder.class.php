<?php
/*##################################################
 *                          AdminErrorsUrlBuilder.class.php
 *                            -------------------
 *   begin                : December 13, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 */
class AdminErrorsUrlBuilder
{
	private static $dispatcher = '/admin/errors';

	/**
	 * @return Url
	 */
	public static function list_404_errors()
	{
		return DispatchManager::get_url(self::$dispatcher, '/404/list/');
	}

	/**
	 * @return Url
	 */
	public static function clear_404_errors()
	{
		return DispatchManager::get_url(self::$dispatcher, '/404/clear/?token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function delete_404_error($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/404/' . $id . '/delete/?token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function logged_errors()
	{
		return DispatchManager::get_url(self::$dispatcher, '/list/');
	}

	/**
	 * @return Url
	 */
	public static function clear_logged_errors()
	{
		return DispatchManager::get_url(self::$dispatcher, '/clear/?token=' . AppContext::get_session()->get_token());
	}
}
?>