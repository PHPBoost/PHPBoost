<?php
/*##################################################
 *                              CommentsManager.class.php
 *                            -------------------
 *   begin                : September 25, 2011
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
 * @package {@package}
 */
class CommentsManager
{
	private static $user;
	
	public static function __static()
	{
		self::$user = AppContext::get_current_user();
	}
	
	public static function add_comment($module_id, $id_in_module, $message, $topic_path, $name_visitor = '')
	{
		if (!CommentsTopicDAO::topic_exists($module_id, $id_in_module))
		{
			CommentsTopicDAO::create_topic($module_id, $id_in_module, $topic_path);
		}
		
		if(self::$user->check_level(MEMBER_LEVEL))
		{
			CommentsDAO::add_comment($module_id, $id_in_module, $message, self::$user->get_id());
		}
		else
		{
			CommentsDAO::add_comment($module_id, $id_in_module, $message, '', $name_visitor, USER_IP);
		}

		CommentsTopicDAO::update_number_comment_topic($module_id, $id_in_module);
		
		self::regenerate_cache();
	}
	
	public static function edit_comment($comment_id, $message)
	{
		if (self::comment_exists($comment_id))
		{
			CommentsDAO::edit_comment($comment_id, $message);
			self::regenerate_cache();
		}
	}
	
	public static function delete_comment($comment_id)
	{
		if (self::comment_exists($comment_id))
		{
			$comment = CommentsCache::load()->get_comment($comment_id);
			CommentsDAO::delete_comment($comment_id);
			CommentsTopicDAO::update_number_comment_topic($comment['module_id'], $comment['id_in_module']);
			self::regenerate_cache();
		}
	}
	
	public static function comment_exists($comment_id)
	{
		return CommentsDAO::comment_exists($comment_id);
	}
	
	public static function delete_comments_module($module_id)
	{
		$topic_id = CommentsTopicDAO::get_id_topic_module($module_id);
		CommentsDAO::delete_comments_by_topic($topic_id);
		CommentsTopicDAO::delete_topics_module($module_id);
		self::regenerate_cache();
	}
	
	public static function delete_comments_topic_module($module_id, $id_in_module)
	{
		CommentsDAO::delete_comments_topic_module($module_id, $id_in_module);
		CommentsTopicDAO::delete_topic_module($module_id, $id_in_module);
		self::regenerate_cache();
	}
	
	public static function get_number_comments($module_id, $id_in_module)
	{
		return CommentsDAO::get_number_comments($module_id, $id_in_module);
	}
	
	public static function get_user_id_posted_comment($comment_id)
	{
		return CommentsDAO::get_user_id_posted_comment($comment_id);
	}
	
	public static function get_last_comment_added($module_id, $id_in_module, $user_id = 0)
	{
		return CommentsDAO::get_last_comment_added($module_id, $id_in_module, $user_id = 0);
	}
	
	public static function comment_topic_locked($module_id, $id_in_module)
	{
		if (CommentsTopicDAO::topic_exists($module_id, $id_in_module))
		{
			return CommentsTopicDAO::comments_topic_locked($module_id, $id_in_module);
		}
		return false;
	}
	
	public static function lock_topic($module_id, $id_in_module)
	{
		CommentsTopicDAO::lock_topic($module_id, $id_in_module);
	}
	
	public static function unlock_topic($module_id, $id_in_module)
	{
		CommentsTopicDAO::unlock_topic($module_id, $id_in_module);
	}
	
	private static function regenerate_cache()
	{
		CommentsCache::invalidate();
	}
}
?>