<?php
/**
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2011 09 24
* @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CommentsCache implements CacheData
{
	private array $comments = [];
	private array $users_comments_number = [];

	public function synchronize(): void
    {
		$this->comments = [];

		$result = PersistenceContext::get_querier()->select("
			SELECT comments.*, topic.*, member.*
			FROM " . DB_TABLE_COMMENTS . " comments
			LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " topic ON comments.id_topic = topic.id_topic
			LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = comments.user_id
			ORDER BY comments.timestamp " . CommentsConfig::load()->get_order_display_comments()
		);

		while ($row = $result->fetch())
		{
			$this->comments[$row['id']] = [
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
			];
			$this->users_comments_number[$row['user_id']] = (isset($this->users_comments_number[$row['user_id']]) && is_int($this->users_comments_number[$row['user_id']])) ? $this->users_comments_number[$row['user_id']]++ : 1;
		}
	}

	public function get_comments(): array
	{
		return $this->comments;
	}

	public function comment_exists(string $id): bool
	{
		return array_key_exists($id, $this->comments);
	}

	public function comment_exists_by_module(mixed $module_id, mixed $id_in_module): bool
	{
		$comments = $this->get_comments_by_module($module_id, $id_in_module);
		return !empty($comments);
	}

	public function get_comment(string $id): mixed
	{
		if ($this->comment_exists($id))
		{
			return $this->comments[$id];
		}
		return null;
	}

	public function get_comments_by_module(mixed $module_id, string $id_in_module = '', string $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER): mixed
	{
		$comments = [];
		foreach ($this->comments as $id_comment => $informations)
		{
			if ($informations['module_id'] == $module_id && $informations['topic_identifier'] == $topic_identifier)
			{
				if (($id_in_module && $informations['id_in_module'] == $id_in_module) || !$id_in_module)
                {
					$comments[$id_comment] = $informations;
                }
			}
		}
		return $comments;
	}

	public function get_count_comments_by_module(mixed $module_id, mixed $id_in_module, mixed $topic_identifier): int
	{
		return count($this->get_comments_by_module($module_id, $id_in_module, $topic_identifier));
	}

	public function get_count_comments(): int
	{
		return count($this->comments);
	}

	public function get_user_comments_number(mixed $user_id): mixed
	{
		return (isset($this->users_comments_number[$user_id]) && is_int($this->users_comments_number[$user_id])) ? $this->users_comments_number[$user_id] : 0;
	}

	/**
	 * Loads and returns the comments cached data.
	 * @return CommentsCache The cached data
	 */
	public static function load(): CommentsCache
	{
		return CacheManager::load(self::class, 'kernel', 'comments');
	}

	/**
	 * Invalidates the current comments cached data.
	 */
	public static function invalidate(): void
	{
		CacheManager::invalidate('kernel', 'comments');
	}

	private function slice_comments(array $comments, int $offset, int $lenght = 0)
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
