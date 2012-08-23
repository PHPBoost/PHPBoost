<?php
/*##################################################
 *                              CommentsUrlBuilder.class.php
 *                            -------------------
 *   begin                : May 02, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class CommentsUrlBuilder
{
	public static function edit($comment_path, $id)
    {
    	return self::build_url($comment_path, 'edit_comment=' . $id . '#comments_message');
    }
    
	public static function delete($comment_path, $id)
    {
    	return self::build_url($comment_path, 'delete_comment=' . $id . '#comments_list');
    }
    
    /*
     * var lock is integer
     */
	public static function lock_and_unlock($comment_path, $lock)
    {
    	return self::build_url($comment_path, 'lock=' . (int)$lock . '#comments_list');
    }
    
	public static function comment_added($comment_path, $id_comment)
    {
    	return self::build_url($comment_path, 'refresh_all=1#com' . $id_comment);
    }
    
    private static function build_url($comment_path, $parameters)
    {
    	if (strpos($comment_path, '?') === false)
		{
			return new Url($comment_path . '?' . $parameters);
		}
		else
		{
			return new Url($comment_path . '&' . $parameters);
		}
    }
}
?>