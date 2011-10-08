<?php
/*##################################################
 *                          UserUrlBuilder.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class UserUrlBuilder
{
    private static $dispatcher = '/user';

    public static function forget_password()
    {
    	return DispatchManager::get_url(self::$dispatcher, '/password/lost/');
    }
    
	public static function change_password($key)
    {
    	return DispatchManager::get_url(self::$dispatcher, '/password/change/' . $key);
    }
    
    public static function profile($user_id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/profile/' . $user_id);
	}
	
    public static function registration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/registration/');
	}
	
 	public static function contribution_panel()
	{
		return new Url(self::$dispatcher . '/contribution_panel.php');
	}
	
	public static function moderation_panel()
	{
		return new Url(self::$dispatcher . '/moderation_panel.php');
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
	
	public static function edit_profile()
	{
		return DispatchManager::get_url(self::$dispatcher, '/profile/edit/');
	}
	
	public static function users()
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
	
	public static function group($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/group/' . $id);
	}
}
?>