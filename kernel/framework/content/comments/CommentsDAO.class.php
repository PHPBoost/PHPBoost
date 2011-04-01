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
	 * DB_TABLE_COMMENTS => id, module_name, id_module, number_comments, is_locked
	 * DB_TABLE_POSTED_COMMENTS => id, user_id, name_visitor, ip_visitor, note, message
	 *
	*/
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function add_comment(Comment $comment)
	{
		$columns = array(
			'user_id' => $comment->get_user_id(),
			'name_visitor' => $comment->get_name_visitor(),
			'ip_visitor' => $comment->get_ip_visitor(),
			'message' => $comment->get_message()
		);
		self::$db_querier->insert(DB_TABLE_POSTED_COMMENTS, $columns);
		
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
			self::$db_querier->update(DB_TABLE_COMMENTS, $columns, $condition, $parameters);
		}
		else
		{
			$columns = array(
				'module_name' => $comment->get_module_name(),
				'id_module' => $comment->get_id_module(),
				'number_comments' => 0,
				'is_locked' => 0
			);
			self::$db_querier->insert(DB_TABLE_COMMENTS, $columns);
		}
	}
	
	public static function edit_comment(Comment $comment)
	{
		$columns = array(
			'message' => $comment->get_message()
		);
		$condition = "WHERE id_module = :id_module AND module_name = :module_name";
		$parameters = array(
			'id_module' => $comment->get_id_module(),
			'module_name' => $comment->get_module_name()
		);
		self::$db_querier->update(DB_TABLE_POSTED_COMMENTS, $columns, $condition, $parameters);
		
		$columns = array(
			'number_comments' => $number_comments + 1
		);
		$condition = "WHERE id_module = :id_module AND module_name = :module_name";
		$parameters = array(
			'id_module' => $comment->get_id_module(),
			'module_name' => $comment->get_module_name()
		);
		self::$db_querier->update(DB_TABLE_COMMENTS, $columns, $condition, $parameters);
	}
	
	private static function get_number_comments_by_module(Comment $comment)
	{
		$parameters = array(
			'id_module' => $comment->get_id_module(),
			'module_name' => $comment->get_module_name()
		);
		return self::$db_querier->count(DB_TABLE_COMMENTS, "WHERE id_module = :id_module AND module_name = :module_name", $parameters);
	}
}
?>