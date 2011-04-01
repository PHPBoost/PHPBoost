<?php
/*##################################################
 *                              CommentsDAO.class.php
 *                            -------------------
 *   begin                : March 31, 2010
 *   copyright            : (C) 2010 Kévin MASSY
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
	
	/*
	 * DataBase Fields :
	 * DB_TABLE_COMMENTS_TOPIC => id, module_name, id_module, number_comments, is_locked, visibility, authorizations
	 * DB_TABLE_COMMENTS => id, id_topic, user_id, name_visitor, ip_visitor, note, message
	 *
	*/
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
		$parameters = array('module_name' => $comment->get_module_name());
		self::$db_querier->delete(DB_TABLE_COMMENTS_TOPIC, $condition, $parameters);
	}
	
	public static function change_visibility(Comments $comments)
	{
		$columns = array(
			'visibility' => $comments->get_visibility();
		);
		$condition = "WHERE id_module = :id_module AND module_name = :module_name";
		$parameters = array(
			'id_module' => $comments->get_id_module(),
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
		return self::$db_querier->select_rows(DB_TABLE_COMMENTS, array('*'), "WHERE id = :id", array('id' => $comment->get_id());
	}
	
	public static function get_last_comment_user(Comment $comment)
	{
		if ($comment->get_user_id() > 0)
		{
			return self::$db_querier->get_column_value(DB_TABLE_COMMENTS, 'timestamp', "WHERE user_id = :user_id", array('user_id' => $comment->get_user_id()));
		}
		else
		{
			return self::$db_querier->get_column_value(DB_TABLE_COMMENTS, 'timestamp', "WHERE ip_visitor = :ip_visitor", array('ip_visitor' => $comment->get_ip_visitor()));
		}
	}
	
	public static function add_comment(Comment $comment)
	{
		$number_comments = self::get_number_comments_by_module($comment);
		if ($number_comments > 0)
		{
			$columns = array(
				'number_comments' => $number_comments + 1
			);
			$condition = "WHERE id_module = :id_module AND module_name = :module_name";
			$parameters = array(
				'id_module' => $comment->get_id_module(),
				'module_name' => $comment->get_module_name()
			);
			self::$db_querier->update(DB_TABLE_COMMENTS_TOPIC, $columns, $condition, $parameters);
		}
		else
		{
			$columns = array(
				'module_name' => $comment->get_module_name(),
				'id_module' => $comment->get_id_module(),
				'number_comments' => 0,
				'is_locked' => 0
			);
			self::$db_querier->insert(DB_TABLE_COMMENTS_TOPIC, $columns);
		}
		
		$id_comments_topic = self::$db_querier->get_column_value(DB_TABLE_COMMENTS_TOPIC, 'id', "WHERE module_name = :module_name AND id_module = :id_module", 
		array('module_name' => $comment->get_module_name(), 'id_module' => $comment->get_id_module()));
		$columns = array(
			'id_topic' => $id_comments_topic,
			'user_id' => $comment->get_user_id(),
			'name_visitor' => $comment->get_name_visitor(),
			'ip_visitor' => $comment->get_ip_visitor(),
			'timestamp' => time(),
			'message' => $comment->get_message()
		);
		self::$db_querier->insert(DB_TABLE_COMMENTS, $columns);
	}
	
	public static function edit_comment(Comment $comment)
	{
		$columns = array(
			'message' => $comment->get_message()
		);
		$condition = "WHERE id = :id";
		$parameters = array(
			'id' => $comment->get_id()
		);
		self::$db_querier->update(DB_TABLE_COMMENTS, $columns, $condition, $parameters);
		
		$id_topic = self::$db_querier->get_column_value(DB_TABLE_COMMENTS, 'id_topic', $condition, $parameters);
		
		$columns = array(
			'number_comments' => self::$db_querier->count(DB_TABLE_COMMENTS, "WHERE id_topic = :id_topic", array('id_topic' => $id_topic))
		);
		$condition = "WHERE id_topic = :id_topic";
		$parameters = array(
			'id_topic' => $id_topic
		);
		self::$db_querier->update(DB_TABLE_COMMENTS_TOPIC, $columns, $condition, $parameters);
	}

	public static function get_number_comments_by_module(Comment $comment)
	{
		$parameters = array(
			'id_module' => $comment->get_id_module(),
			'module_name' => $comment->get_module_name()
		);
		return self::$db_querier->count(DB_TABLE_COMMENTS_TOPIC, "WHERE id_module = :id_module AND module_name = :module_name", $parameters);
	}
}
?>