<?php
/*##################################################
 *                              CommentsDAO.class.php
 *                            -------------------
 *   begin                : March 31, 2011
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
class CommentsDAO
{
	private static $db_querier;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function delete_all_comments_by_module_name(Comments $comments)
	{
		$id_comments_topic = self::$db_querier->get_column_value(DB_TABLE_COMMENTS_TOPIC, 'id', "WHERE module_name = :module_name", array('module_name' => $comment->get_module_name()));
		
		$condition = "WHERE id_topic = :id_topic";
		$parameters = array('id_topic' => $id_comments_topic);
		self::$db_querier->delete(DB_TABLE_COMMENTS, $condition, $parameters);
		
		$condition = "WHERE module_name = :module_name";
		$parameters = array('module_name' => $comments->get_module_name());
		self::$db_querier->delete(DB_TABLE_COMMENTS_TOPIC, $condition, $parameters);
	}
	
	public static function delete_comments_id_in_module(Comments $comments)
	{
		$id_comments_topic = self::$db_querier->get_column_value(DB_TABLE_COMMENTS_TOPIC, 'id', "WHERE module_name = :module_name AND id_in_module = :id_in_module", 
		array('module_name' => $comment->get_module_name(), 'id_in_module' => $comments->get_id_in_module()));
		
		$condition = "WHERE id_topic = :id_topic";
		$parameters = array('id_topic' => $id_comments_topic);
		self::$db_querier->delete(DB_TABLE_COMMENTS, $condition, $parameters);
		
		$condition = "WHERE module_name = :module_name AND id_in_module = :id_in_module";
		$parameters = array('module_name' => $comment->get_module_name(), 'id_in_module' => $comments->get_id_in_module());
		self::$db_querier->delete(DB_TABLE_COMMENTS_TOPIC, $condition, $parameters);
	}
	
	public static function change_visibility(Comments $comments)
	{
		$columns = array(
			'visibility' => $comments->get_visibility()
		);
		$condition = "WHERE id_in_module = :id_in_module AND module_name = :module_name";
		$parameters = array(
			'id_in_module' => $comments->get_id_in_module(),
			'module_name' => $comments->get_module_name()
		);
		self::$db_querier->update(DB_TABLE_COMMENTS_TOPIC, $columns, $condition, $parameters);	
	}
	
	public static function get_user_id_posted_comment(Comment $comment)
	{
		return self::$db_querier->get_column_value(DB_TABLE_COMMENTS, 'user_id', "WHERE id = :id", array('id' => $comment->get_id()));
	}
	
	public static function get_data_comment(Comment $comment)
	{
		return self::$db_querier->select_single_row(DB_TABLE_COMMENTS, array('*'), "WHERE id = :id", array('id' => $comment->get_id()));
	}
	
	public static function get_last_comment_user(Comment $comment)
	{
		if ($comment->get_user_id() > 0)
		{
			$user_id_existed = self::$db_querier->count(DB_TABLE_COMMENTS, "WHERE user_id = :user_id", array('user_id' => $comment->get_user_id()));
			if ($user_id_existed > 0)
			{
				return self::$db_querier->get_column_value(DB_TABLE_COMMENTS, 'timestamp', "WHERE user_id = :user_id", array('user_id' => $comment->get_user_id()));
			}
			return null;
		}
		else
		{
			$ip_visitor_existed = self::$db_querier->count(DB_TABLE_COMMENTS, "WHERE ip_visitor = :ip_visitor", array('ip_visitor' => $comment->get_ip_visitor()));
			if ($ip_visitor_existed > 0)
			{
				return self::$db_querier->get_column_value(DB_TABLE_COMMENTS, 'timestamp', "WHERE ip_visitor = :ip_visitor", array('ip_visitor' => $comment->get_ip_visitor()));
			}
			return null;
		}
	}
	
	public static function number_comments(Comments $comments)
	{
		$row_exist = self::$db_querier->count(DB_TABLE_COMMENTS_TOPIC, "WHERE module_name = :module_name AND id_in_module = :id_in_module", array('module_name' => $comments->get_module_name(), 'id_in_module' => $comments->get_id_in_module())) > 0 ? true : false;
		if ($row_exist)
		{
			return self::$db_querier->get_column_value(DB_TABLE_COMMENTS_TOPIC, 'number_comments', "WHERE module_name = :module_name AND id_in_module = :id_in_module", 
			array('module_name' => $comments->get_module_name(), 'id_in_module' => $comments->get_id_in_module()));
		}
		else
		{
			return 0;
		}
	}
	
	public static function comment_exist(Comment $comment)
	{
		return self::$db_querier->count(DB_TABLE_COMMENTS, "WHERE id = :id", array('id' => $comment->get_id()));
	}
	
	public static function add_comment(Comment $comment)
	{
		$number_comments = self::$db_querier->get_column_value(DB_TABLE_COMMENTS_TOPIC, 'number_comments', "WHERE module_name = :module_name AND id_in_module = :id_in_module", 
		array('module_name' => $comment->get_module_name(), 'id_in_module' => $comment->get_id_in_module()));
		$columns = array(
			'number_comments' => $number_comments + 1
		);
		$condition = "WHERE id_in_module = :id_in_module AND module_name = :module_name";
		$parameters = array(
			'id_in_module' => $comment->get_id_in_module(),
			'module_name' => $comment->get_module_name()
		);
		self::$db_querier->update(DB_TABLE_COMMENTS_TOPIC, $columns, $condition, $parameters);
		
		$id_comments_topic = self::$db_querier->get_column_value(DB_TABLE_COMMENTS_TOPIC, 'id', "WHERE module_name = :module_name AND id_in_module = :id_in_module", 
		array('module_name' => $comment->get_module_name(), 'id_in_module' => $comment->get_id_in_module()));
		$columns = array(
			'id_topic' => $id_comments_topic,
			'user_id' => $comment->get_user_id(),
			'name_visitor' => htmlspecialchars($comment->get_name_visitor()),
			'ip_visitor' => htmlspecialchars($comment->get_ip_visitor()),
			'timestamp' => time(),
			'message' => htmlspecialchars($comment->get_message())
		);
		self::$db_querier->insert(DB_TABLE_COMMENTS, $columns);
	}
	
	public static function create_comments_topic(Comments $comments)
	{
		$columns = array(
			'module_name' => $comments->get_module_name(),
			'id_in_module' => $comments->get_id_in_module(),
			'number_comments' => 0,
			'visibility' => (string)$comments->get_visibility(),
			'is_locked' => (string)$comments->get_is_locked()
		);
		self::$db_querier->insert(DB_TABLE_COMMENTS_TOPIC, $columns);
	}
	
	public static function edit_comment(Comment $comment)
	{
		$columns = array(
			'message' => htmlspecialchars($comment->get_message())
		);
		$condition = "WHERE id = :id";
		$parameters = array(
			'id' => $comment->get_id()
		);
		self::$db_querier->update(DB_TABLE_COMMENTS, $columns, $condition, $parameters);
	}
	
	public static function get_existed_comments_topic(Comments $comments)
	{
		$parameters = array(
			'id_in_module' => $comments->get_id_in_module(),
			'module_name' => $comments->get_module_name()
		);
		return self::$db_querier->count(DB_TABLE_COMMENTS_TOPIC, "WHERE id_in_module = :id_in_module AND module_name = :module_name", $parameters) > 0 ? true : false;
	}

	public static function get_number_comments_by_module(Comment $comment)
	{
		$parameters = array(
			'id_in_module' => $comment->get_id_in_module(),
			'module_name' => $comment->get_module_name()
		);
		return self::$db_querier->count(DB_TABLE_COMMENTS_TOPIC, "WHERE id_in_module = :id_in_module AND module_name = :module_name", $parameters);
	}
}
?>