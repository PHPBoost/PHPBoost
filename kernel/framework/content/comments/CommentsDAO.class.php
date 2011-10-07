<?php
/*##################################################
 *                              CommentsDAO.class.php
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
class CommentsDAO
{
	private static $comments_cache;
	private static $db_querier;
	
	public static function __static()
	{
		self::$comments_cache = CommentsCache::load();
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function delete_comments_module($module_id)
	{
		$condition = "WHERE module_id = :module_id";
		$parameters = array('module_id' => $module_id);
		self::$db_querier->delete(DB_TABLE_COMMENTS, $condition, $parameters);
	}
	
	public static function delete_comments_topic_module($module_id, $id_in_module)
	{
		$condition = "WHERE module_id = :module_id AND id_in_module = :id_in_module";
		$parameters = array('module_id' => $module_id, 'id_in_module' => $id_in_module);
		self::$db_querier->delete(DB_TABLE_COMMENTS, $condition, $parameters);
	}

	public static function delete_comment($comment_id)
	{
		$condition = "WHERE id = :id";
		$parameters = array('id' => $comment_id);
		self::$db_querier->delete(DB_TABLE_COMMENTS, $condition, $parameters);
	}
	
	public static function get_user_id_posted_comment($comment_id)
	{
		$comment = self::$comments_cache->get_comment($comment_id);
		return $comment['user_id'];
	}
	
	public static function get_last_comment_added($module_id, $id_in_module, $user_id = 0)
	{
		if ($user_id > 0)
		{
			$comments = self::get_comments_user($module_id, $id_in_module, $user_id);
		}
		else
		{
			$comments = self::get_comments_visitor($module_id, $id_in_module, USER_IP);
		}
		
		if (count($comments) > 0)
		{
			$last_comment = array_shift($comments);
			return $last_comment['timestamp'];
		}
		return 0;
	}

	public static function get_number_comments($module_id, $id_in_module)
	{
		$comments = self::$comments_cache->get_comments_by_module($module_id, $id_in_module);
		if (!empty($comments))
		{
			return count($comments);
		}
		return 0;
	}
	
	public static function comment_exists($comment_id)
	{
		return self::$comments_cache->comment_exists($comment_id);
	}
	
	public static function add_comment($module_id, $id_in_module, $message, $user_id = '', $name_visitor = '', $ip_visitor = '')
	{
		$id_comments_topic = CommentsTopicDAO::get_id_topic_module($module_id, $id_in_module);
		$columns = array(
			'id_topic' => $id_comments_topic,
			'user_id' => $user_id,
			'name_visitor' => htmlspecialchars($name_visitor),
			'ip_visitor' => htmlspecialchars($ip_visitor),
			'timestamp' => time(),
			'message' => htmlspecialchars($message)
		);
		self::$db_querier->insert(DB_TABLE_COMMENTS, $columns);
	}
	
	public static function edit_comment($comment_id, $message)
	{
		$columns = array(
			'message' => htmlspecialchars($message)
		);
		$condition = "WHERE id = :id";
		$parameters = array(
			'id' => $comment_id
		);
		self::$db_querier->update(DB_TABLE_COMMENTS, $columns, $condition, $parameters);
	}
	
	private static function get_comments_user($module_id, $id_in_module, $user_id)
	{
		$comments = self::$comments_cache->get_comments_by_module($module_id, $id_in_module);
		
		$comments_user = array();
		foreach ($comments as $id => $values)
		{
			if ($values['user_id'] == $user_id)
			{
				$comments_user[$id] = $values;
			}
		}
		return $comments_user;
	}
	
	private static function get_comments_visitor($module_id, $id_in_module, $ip_visitor)
	{
		$comments = self::$comments_cache->get_comments_by_module($module_id, $id_in_module);
		
		$comments_visito = array();
		foreach ($comments as $id => $values)
		{
			if ($values['ip_visitor'] == $ip_visitor)
			{
				$comments_visito[$id] = $values;
			}
		}
		return $comments_visito;
	}
}
?>