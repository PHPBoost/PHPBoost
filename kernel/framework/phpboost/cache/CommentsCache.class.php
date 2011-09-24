<?php
/*##################################################
 *                      	 CommentsCache.class.php
 *                            -------------------
 *   begin                : September 24, 2011
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
			SELECT comments.*, topic.*
			FROM " . DB_TABLE_COMMENTS . " comments
			LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " topic ON comments.id_topic = topic.id_topic
			ORDER BY comments.timestamp ASC"
		);
		
		while ($row = $result->fetch())
		{
			$this->comments[$row['id']] = array(
				'id' => $row['id'],
				'id_topic' => $row['id_topic'],
				'module_id' => $row['module_id'],
				'id_in_module' => $row['id_in_module'],
				'message' => $row['message'],
				'user_id' => $row['user_id'],
				'name_visitor' => $row['name_visitor'],
				'ip_visitor' => $row['ip_visitor'],
				'note' => $row['note'],
				'timestamp' => $row['timestamp']
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

	public function get_comments_by_module($module_id, $id_in_module)
	{
		$comments = array();
		foreach ($this->comments as $id_comment => $informations)
		{
			if ($informations['module_id'] == $module_id && $informations['id_in_module'] == $id_in_module)
			{
				$comments[$id_comment] = $informations;
			}
		}
		return $comments;
	}
	
	public function get_comments_sliced($module_id, $id_in_module, $offset, $lenght = 0)
	{
		$comments = $this->get_comments_by_module($module_id, $id_in_module);
		return $this->slice_comments($comments, $offset, $lenght);
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
			return array_slice($comments, $offset);
		}
		else
		{
			return array_slice($comments, $offset, $lenght, true);
		}
	}
}
