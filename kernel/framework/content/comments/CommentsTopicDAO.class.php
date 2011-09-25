<?php
/*##################################################
 *                              CommentsTopicDAO.class.php
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
class CommentsTopicDAO
{
	private static $comments_cache;
	private static $db_querier;
	
	public static function __static()
	{
		self::$comments_cache = CommentsCache::load();
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function get_id_topic_module($module_id, $id_in_module)
	{
		$condition = "WHERE module_id = :module_id";
		$parameters = array('module_id' => $module_id);
		return self::$db_querier->get_column_value(DB_TABLE_COMMENTS_TOPIC, 'id_topic', $condition, $parameters);
	}
	
	public static function create_topic($module_id, $id_in_module)
	{
		$columns = array(
			'module_id' => $module_id,
			'id_in_module' => $id_in_module,
			'number_comments' => 0,
			'is_locked' => 0
		);
		self::$db_querier->insert(DB_TABLE_COMMENTS_TOPIC, $columns);
	}
	
	public static function topic_exists($module_id, $id_in_module)
	{
		$condition = "WHERE id_in_module = :id_in_module AND module_id = :module_id";
		$parameters = array(
			'module_id' => $module_id,
			'id_in_module' => $id_in_module
		);
		return self::$db_querier->count(DB_TABLE_COMMENTS_TOPIC, $condition, $parameters) > 0 ? true : false;
	}
	
	public static function delete_topics_module($module_id)
	{	
		$condition = "WHERE module_id = :module_name";
		$parameters = array('module_id' => $module_id);
		self::$db_querier->delete(DB_TABLE_COMMENTS_TOPIC, $condition, $parameters);
	}
	
	public static function delete_topic_module($module_id, $id_in_module)
	{
		$condition = "WHERE module_id = :module_id AND id_in_module = :id_in_module";
		$parameters = array('module_id' => $module_id, 'id_in_module' => $id_in_module);
		self::$db_querier->delete(DB_TABLE_COMMENTS_TOPIC, $condition, $parameters);
	}
	
	public static function update_number_comment_topic($module_id, $id_in_module)
	{
		$number_comments = CommentsDAO::get_number_comments($module_id, $id_in_module);
		$columns = array('number_comments' => $number_comments + 1);
		$condition = "WHERE id_in_module = :id_in_module AND module_id = :module_id";
		$parameters = array(
			'id_in_module' => $id_in_module,
			'module_id' => $module_id
		);
		self::$db_querier->update(DB_TABLE_COMMENTS_TOPIC, $columns, $condition, $parameters);
	}
	
	public static function comments_topic_locked($module_id, $id_in_module)
	{
		$condition = "WHERE id_in_module = :id_in_module AND module_id = :module_id";
		$parameters = array(
			'id_in_module' => $id_in_module,
			'module_id' => $module_id
		);
		return self::$db_querier->get_column_value(DB_TABLE_COMMENTS_TOPIC, 'is_locked', $condition, $parameters) > 0 ? true : false;
	}
	
	public static function lock_topic($module_id, $id_in_module)
	{
		$columns = array('is_locked' => 1);
		$condition = "WHERE id_in_module = :id_in_module AND module_id = :module_id";
		$parameters = array(
			'id_in_module' => $id_in_module,
			'module_id' => $module_id
		);
		self::$db_querier->update(DB_TABLE_COMMENTS_TOPIC, $columns, $condition, $parameters);
	}
	
	public static function unlock_topic($module_id, $id_in_module)
	{
		$columns = array('is_locked' => 0);
		$condition = "WHERE id_in_module = :id_in_module AND module_id = :module_id";
		$parameters = array(
			'id_in_module' => $id_in_module,
			'module_id' => $module_id
		);
		self::$db_querier->update(DB_TABLE_COMMENTS_TOPIC, $columns, $condition, $parameters);
	}
}
?>