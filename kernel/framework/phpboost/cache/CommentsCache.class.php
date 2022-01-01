<?php
/**
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 10
 * @since       PHPBoost 3.0 - 2011 09 24
*/

class CommentsCache implements CacheData
{
	private $comments = array();
	private $users_comments_number = array();

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
			$this->users_comments_number[$row['user_id']] = (isset($this->users_comments_number[$row['user_id']]) && is_int($this->users_comments_number[$row['user_id']])) ? $this->users_comments_number[$row['user_id']]++ : 1;
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

	public function get_comments_by_module($module_id, $id_in_module = '', $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		$comments = array();
		foreach ($this->comments as $id_comment => $informations)
		{
			if ($informations['module_id'] == $module_id && $informations['topic_identifier'] == $topic_identifier)
			{
				if (($id_in_module && $informations['id_in_module'] == $id_in_module) || !$id_in_module)
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

	public function get_user_comments_number($user_id)
	{
		return (isset($this->users_comments_number[$user_id]) && is_int($this->users_comments_number[$user_id])) ? $this->users_comments_number[$user_id] : 0;
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
