<?php
/*##################################################
 *                      	 CommentsCache.class.php
 *                            -------------------
 *   begin                : September 24, 2011
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
 */
class CommentsCache implements CacheData
{
	private $comments = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->comments = array();

		$result = PersistenceContext::get_querier()->select("
			SELECT comments.*, topic.*, member.*
			FROM " . DB_TABLE_COMMENTS . " comments
			LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " topic ON comments.id_topic = topic.id_topic
			LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = comments.user_id
			ORDER BY comments.timestamp " . CommentsConfig::load()->get_order_display_comments()
		);
		
		while ($row = $result->fetch())
		{
			$this->comments[$row['id']] = array(
				'id' => $row['id'],
				'id_topic' => $row['id_topic'],
				'module_id' => $row['module_id'],
				'id_in_module' => $row['id_in_module'],
				'topic_identifier' => $row['topic_identifier'],
				'message' => $row['message'],
				'note' => $row['note'],
				'timestamp' => $row['timestamp'],
				'path' => $row['path'],
				'user_id' => $row['user_id']
			);
		}
	}

	public function get_comments()
	{
		return $this->comments;
	}
	
	public function comment_exists($id)
	{
		return array_key_exists($id, $this->comments);
	}

	public function comment_exists_by_module($module_id, $id_in_module)
	{
		$comments = $this->get_comments_by_module($module_id, $id_in_module);
		return !empty($comments);
	}
	
	public function get_comment($id)
	{
		if ($this->comment_exists($id))
		{
			return $this->comments[$id];
		}
		return null;
	}

	public function get_comments_by_module($module_id, $id_in_module, $topic_identifier)
	{
		$comments = array();
		foreach ($this->comments as $id_comment => $informations)
		{
			if ($informations['module_id'] == $module_id && $informations['id_in_module'] == $id_in_module && $informations['topic_identifier'] == $topic_identifier)
			{
				$comments[$id_comment] = $informations;
			}
		}
		return $comments;
	}
	
	public function get_count_comments_by_module($module_id, $id_in_module, $topic_identifier)
	{
		return count($this->get_comments_by_module($module_id, $id_in_module, $topic_identifier));
	}
	
	public function get_count_comments()
	{
		return count($this->comments);
	}
	
	/**
	 * Loads and returns the comments cached data.
	 * @return CommentsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'comments');
	}
	
	/**
	 * Invalidates the current comments cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'comments');
	}
	
	private function slice_comments(Array $comments, $offset, $lenght = 0)
	{
		if (empty($lenght))
		{
			return array_slice($comments, $offset, count($comments), true);
		}
		else
		{
			return array_slice($comments, $offset, $lenght, true);
		}
	}
}
?>