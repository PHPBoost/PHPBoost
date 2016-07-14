<?php
/*##################################################
 *                          NewsletterUrlBuilder.class.php
 *                            -------------------
 *   begin                : September 19, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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
 * @desc
 */
class NewsletterUrlBuilder
{
	const DEFAULT_SORT_FIELD = 'date';
	const DEFAULT_SORT_MODE = 'bottom';

	private static $dispatcher = '/newsletter';

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}
	
	/**
	 * @return Url
	 */
	public static function add_newsletter($type = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $type);
	}
	
	/**
	 * @return Url
	 */
	public static function manage_streams()
	{
		return DispatchManager::get_url(self::$dispatcher, '/streams/');
	}
	
	/**
	 * @return Url
	 */
	public static function add_stream()
	{
		return DispatchManager::get_url(self::$dispatcher, '/stream/add/');
	}
	
	/**
	 * @return Url
	 */
	public static function edit_stream($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/stream/' . $id .'/edit/');
	}
	
	/**
	 * @return Url
	 */
	public static function delete_stream($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/stream/' . $id .'/delete/');
	}
	
	/**
	 * @return Url
	 */
	public static function subscribers($id_stream = 0, $rewrited_name = '', $sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1)
	{
		$stream = $id_stream > 0 ? $id_stream . '-' . $rewrited_name . '/' : '';
		$page = $page !== 1 ? $page . '/': '';
		$sort_field = ($sort_field !== self::DEFAULT_SORT_FIELD && $sort_field !== '') || $sort_mode !== self::DEFAULT_SORT_MODE ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== self::DEFAULT_SORT_MODE && $sort_mode !== '' ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/subscribers/' . $stream . $sort_field . $sort_mode . $page);
	}

	/**
	 * @return Url
	 */
	public static function subscribe()
	{
		return DispatchManager::get_url(self::$dispatcher, '/subscribe/');
	}

	/**
	 * @return Url
	 */
	public static function unsubscribe()
	{
		return DispatchManager::get_url(self::$dispatcher, '/unsubscribe/');
	}

	/**
	 * @return Url
	 */
	public static function edit_subscriber($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/subscriber/' . $id . '/edit/');
	}
	
	/**
	 * @return Url
	 */
	public static function delete_subscriber($id, $stream_id = null)
	{
		return DispatchManager::get_url(self::$dispatcher, '/subscriber/' . $id . '/delete/' . (!empty($stream_id) ? $stream_id : ''));
	}

	/**
	 * @return Url
	 */
	public static function archives($id_stream = 0, $rewrited_name = '', $sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1)
	{
		$stream = $id_stream > 0 ? $id_stream . '-' . $rewrited_name . '/' : '';
		$page = $page !== 1 ? $page . '/': '';
		$sort_field = ($sort_field !== self::DEFAULT_SORT_FIELD && $sort_field !== '') || $sort_mode !== self::DEFAULT_SORT_MODE ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== self::DEFAULT_SORT_MODE && $sort_mode !== '' ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/archives/' . $stream . $sort_field . $sort_mode . $page);
	}

	/**
	 * @return Url
	 */
	public static function archive($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/archive/' . $id);
	}
	
	/**
	 * @return Url
	 */
	public static function delete_archive($id, $id_stream)
	{
		return DispatchManager::get_url(self::$dispatcher, '/delete/' . $id . '/' . $id_stream . '/?token=' . AppContext::get_session()->get_token());
	}
	
	/**
	 * @return Url
	 */
	public static function home($page = 1)
	{
		$page = $page !== 1 ? $page . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $page);
	}
}
?>