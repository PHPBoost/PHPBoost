<?php
/*##################################################
 *                          NewsletterUrlBuilder.class.php
 *                            -------------------
 *   begin                : September 19, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kevin MASSY <soldier.weasel@gmail.com>
 * @desc
 */
class NewsletterUrlBuilder
{
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
    public static function streams($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/streams' . $param);
	}
	
	/**
	 * @return Url
	 */
    public static function add_stream()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/stream/add/');
	}
	
	/**
	 * @return Url
	 */
    public static function edit_stream($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/stream/' . $id .'/edit/');
	}
	
	/**
	 * @return Url
	 */
    public static function delete_stream($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/stream/' . $id .'/delete/');
	}
	
	/**
	 * @return Url
	 */
    public static function subscribers($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/subscribers/' . $param);
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
    public static function archives($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/archives/' . $param);
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
    public static function home($page = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $page);
	}
	
	/**
	 * @return Url
	 */
    public static function image_preview()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax/image/preview/');
	}
}
?>