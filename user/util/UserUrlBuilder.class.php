<?php
/*##################################################
 *                          UserUrlBuilder.class.php
 *                            -------------------
 *   begin                : October 07, 2011
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
 */
class UserUrlBuilder
{
    private static $dispatcher = '/user';

	public static function maintain()
    {
    	return DispatchManager::get_url(self::$dispatcher, '/maintain/');
    }
    
    public static function forget_password()
    {
    	return DispatchManager::get_url(self::$dispatcher, '/password/lost/');
    }
    
	public static function change_password($key)
    {
    	return DispatchManager::get_url(self::$dispatcher, '/password/change/' . $key);
    }
    
    public static function home_profile()
	{
		return DispatchManager::get_url(self::$dispatcher, '/profile/home/');
	}
	
    public static function profile($user_id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/profile/' . $user_id);
	}
	
    public static function registration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/registration/');
	}
	
	public static function contribution_success()
	{
		return DispatchManager::get_url(self::$dispatcher, '/contribution/success/');
	}
	
 	public static function contribution_panel()
	{
		$param = !empty($id) ? '?' . $id : '';
		return new Url(self::$dispatcher . '/contribution_panel.php' . $param);
	}
	
	public static function moderation_panel($type = '', $user_id = '')
	{
		$param = !empty($type) ? '?action=' . $type . '&' . $user_id : '';
		return new Url(self::$dispatcher . '/moderation_panel.php' . $param);
	}
	
	public static function personnal_message($user_id = 0)
	{
		$param = !empty($user_id) ? url('.php?pm=' . $user_id, '-' . $user_id . '.php') : '.php';
		return new Url(self::$dispatcher . '/pm' . url('.php?pm=' . $user_id, '-' . $user_id . '.php'));
	}
	
	public static function upload_files_panel()
	{
		return new Url(self::$dispatcher . '/upload.php');
	}
	
	public static function upload_popup()
	{
		return new Url(self::$dispatcher . '/upload_popup.php');
	}
	
	public static function edit_profile($user_id = '')
	{
		if (!empty($user_id))
		{
			return DispatchManager::get_url(self::$dispatcher, '/profile/'. $user_id .'/edit/');
		}
		return DispatchManager::get_url(self::$dispatcher, '/profile/'. AppContext::get_current_user()->get_attribute('user_id') .'/edit/');
	}
	
	public static function users($field = '', $sort = '', $page = '')
	{
		return DispatchManager::get_url(self::$dispatcher, $field . '/' . $sort . '/' . $page);
	}

	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '');
	}
	
	public static function error_404()
	{
		return DispatchManager::get_url(self::$dispatcher, '/error/404/');
	}
	
	public static function messages($user_id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/messages/'. $user_id);
	}
	
	public static function comments($module = '', $user_id = null)
	{
		if (!empty($user_id))
		{
			return DispatchManager::get_url(self::$dispatcher, '/messages/'. $user_id . '/comments/' . $module);
		}
		return DispatchManager::get_url(self::$dispatcher, '/messages/comments/' . $module);
	}
	
	public static function group($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/group/' . $id);
	}
	
	public static function connect()
	{
		return DispatchManager::get_url(self::$dispatcher, '/login');
	}
	
	public static function disconnect()
	{
		return new Url('/index.php?disconnect=true&amp;token=' . AppContext::get_session()->get_token());
	}
	
	public static function administration()
	{
		return new Url('/admin/admin_index.php');
	}
	
	public static function groups()
	{
		return DispatchManager::get_url(self::$dispatcher, '/groups/');
	}
}
?>