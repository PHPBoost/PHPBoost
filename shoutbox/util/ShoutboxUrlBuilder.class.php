<?php
/*##################################################
 *                          ShoutboxUrlBuilder.class.php
 *                            -------------------
 *   begin                : October 14, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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
 * @desc Url builder of the shoutbox module
 */
class ShoutboxUrlBuilder
{
	private static $dispatcher = '/shoutbox';
	
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
	public static function home($page = 1, $id = null)
	{
		$page = $page !== 1 ? $page : '';
		$id = $id !== null ? '#m' . $id : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $page . $id);
	}
	
	/**
	 * @return Url
	 */
	public static function add($page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function edit($id, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/' . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function delete($id, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/' . $page . '?token=' . AppContext::get_session()->get_token());
	}
	
	/**
	 * @return Url
	 */
	public static function ajax_delete()
	{
		return DispatchManager::get_url(self::$dispatcher, '/delete/');
	}
	
	/**
	 * @return Url
	 */
	public static function ajax_refresh()
	{
		return DispatchManager::get_url(self::$dispatcher, '/refresh/');
	}
}
?>
