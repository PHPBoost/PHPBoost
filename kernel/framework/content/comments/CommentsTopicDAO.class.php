<?php
/*##################################################
 *                              CommentsTopicDAO.class.php
 *                            -------------------
 *   begin                : September 25, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
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

	public static function comments_topic_exists_by_module_id($module_id)
	{
		$parameters = array(
			'module_id' => $module_id,
		);
		return self::$db_querier->row_exists(DB_TABLE_COMMENTS_TOPIC, "WHERE module_id = :module_id", $parameters);
	}
	
	public static function get_id_topic_module($module_id, $id_in_module, $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		$condition = "WHERE id_in_module = :id_in_module AND module_id = :module_id AND topic_identifier=:topic_identifier";
		$parameters = array(
			'module_id' => $module_id,
			'id_in_module' => $id_in_module,
			'topic_identifier' => $topic_identifier,
		);
		return self::$db_querier->get_column_value(DB_TABLE_COMMENTS_TOPIC, 'id_topic', $condition, $parameters);
	}
	
	public static function get_id_topics_module($module_id)
	{
		$condition = "WHERE module_id = :module_id";
		$parameters = array(
			'module_id' => $module_id
		);
		
		$id_topics = array();
		$result = self::$db_querier->select_rows(DB_TABLE_COMMENTS_TOPIC, array('id_topic'), $condition, $parameters);
		while ($row = $result->fetch())
		{
			$id_topics[] = $row['id_topic'];
		}
		$result->dispose();
		
		return $id_topics;
	}
	
	public static function create_topic($module_id, $id_in_module, $topic_identifier, $path)
	{
		$columns = array(
			'module_id' => $module_id,
			'id_in_module' => $id_in_module,
			'topic_identifier' => $topic_identifier,
			'number_comments' => 0,
			'is_locked' => 0,
			'path' => $path
		);
		$result = self::$db_querier->insert(DB_TABLE_COMMENTS_TOPIC, $columns);
		return $result->get_last_inserted_id();
	}
	
	public static function topic_exists($module_id, $id_in_module, $topic_identifier)
	{
		$condition = "WHERE id_in_module = :id_in_module AND module_id = :module_id AND topic_identifier=:topic_identifier";
		$parameters = array(
			'module_id' => $module_id,
			'id_in_module' => $id_in_module,
			'topic_identifier' => $topic_identifier
		);
		return self::$db_querier->row_exists(DB_TABLE_COMMENTS_TOPIC, $condition, $parameters);
	}
	
	public static function delete_topics_module($module_id)
	{	
		$condition = "WHERE module_id = :module_id";
		$parameters = array('module_id' => $module_id);
		self::$db_querier->delete(DB_TABLE_COMMENTS_TOPIC, $condition, $parameters);
	}
	
	public static function delete_topic_module($module_id, $id_in_module)
	{
		$condition = "WHERE module_id = :module_id AND id_in_module = :id_in_module";
		$parameters = array('module_id' => $module_id, 'id_in_module' => $id_in_module);
		Debug::dump(self::$db_querier->delete(DB_TABLE_COMMENTS_TOPIC, $condition, $parameters));
	}
	
	public static function incremente_number_comments_topic($id_topic)
	{
		self::$db_querier->inject("UPDATE ". DB_TABLE_COMMENTS_TOPIC ." SET number_comments = number_comments + 1 WHERE id_topic = '" . $id_topic . "'");
	}
	
	public static function decremente_number_comments_topic($id_topic)
	{
		self::$db_querier->inject("UPDATE ". DB_TABLE_COMMENTS_TOPIC ." SET number_comments = number_comments - 1 WHERE id_topic = '" . $id_topic . "'");
	}
	
	public static function comments_topic_locked($module_id, $id_in_module, $topic_identifier)
	{
		$condition = "WHERE id_in_module = :id_in_module AND module_id = :module_id AND topic_identifier=:topic_identifier";
		$parameters = array(
			'id_in_module' => $id_in_module,
			'module_id' => $module_id,
			'topic_identifier' => $topic_identifier
		);
		return self::$db_querier->get_column_value(DB_TABLE_COMMENTS_TOPIC, 'is_locked', $condition, $parameters) > 0 ? true : false;
	}
	
	public static function lock_topic($module_id, $id_in_module, $topic_identifier)
	{
		$columns = array('is_locked' => 1);
		$condition = "WHERE id_in_module = :id_in_module AND module_id = :module_id AND topic_identifier=:topic_identifier";
		$parameters = array(
			'id_in_module' => $id_in_module,
			'module_id' => $module_id,
			'topic_identifier' => $topic_identifier
		);
		self::$db_querier->update(DB_TABLE_COMMENTS_TOPIC, $columns, $condition, $parameters);
	}
	
	public static function unlock_topic($module_id, $id_in_module, $topic_identifier)
	{
		$columns = array('is_locked' => 0);
		$condition = "WHERE id_in_module = :id_in_module AND module_id = :module_id AND topic_identifier=:topic_identifier";
		$parameters = array(
			'id_in_module' => $id_in_module,
			'module_id' => $module_id,
			'topic_identifier' => $topic_identifier
		);
		self::$db_querier->update(DB_TABLE_COMMENTS_TOPIC, $columns, $condition, $parameters);
	}
}
?>