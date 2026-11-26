<?php
/**
 * @package     Content
 * @subpackage  Comments
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 26
 * @since       PHPBoost 3.0 - 2011 09 25
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CommentsDAO
{
	private static CommentsCache $comments_cache;
	private static DBQuerier $db_querier;

	public static function __static()
	{
		self::$comments_cache = CommentsCache::load();
		self::$db_querier = PersistenceContext::get_querier();
	}

	public static function delete_comments_by_topic(int $id_topic): void
	{
		$condition = "WHERE id_topic = :id_topic";
		$parameters = ['id_topic' => $id_topic];
		self::$db_querier->delete(DB_TABLE_COMMENTS, $condition, $parameters);
	}

	public static function delete_comments_topic_module(string $module_id, int $id_in_module): void
	{
		$condition = "WHERE module_id = :module_id AND id_in_module = :id_in_module";
		$parameters = ['module_id' => $module_id, 'id_in_module' => $id_in_module];
		self::$db_querier->delete(DB_TABLE_COMMENTS, $condition, $parameters);
	}

	public static function delete_comments_module(array $id_topics): void
	{
		if ($id_topics)
		{
			$condition= 'WHERE id_topic IN (' . implode(',', $id_topics) . ')';
			$parameters= ['id_topics'=> $id_topics];
			self::$db_querier->delete(DB_TABLE_COMMENTS, $condition, $parameters);
		}
	}

	public static function delete_comment(int $comment_id): void
	{
		$condition = "WHERE id = :id";
		$parameters = ['id' => $comment_id];
		self::$db_querier->delete(DB_TABLE_COMMENTS, $condition, $parameters);
	}

	public static function get_user_id_posted_comment(int $comment_id): mixed
	{
		$comment = self::$comments_cache->get_comment($comment_id);
		return $comment['user_id'];
	}

	public static function get_last_comment_added(int $user_id): int
	{
		if ($user_id !== -1)
		{
			return (int) self::$db_querier->get_column_value(DB_TABLE_COMMENTS, 'MAX(timestamp)', 'WHERE user_id=:user_id', ['user_id' => $user_id]);
		}
		else
		{
			return (int) self::$db_querier->get_column_value(DB_TABLE_COMMENTS, 'MAX(timestamp)', 'WHERE user_ip=:user_ip', ['user_ip' => AppContext::get_request()->get_ip_address()]);
		}
	}

	public static function get_comments_number(string $module_id, int $id_in_module, string $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		$comments = self::$comments_cache->get_comments_by_module($module_id, $id_in_module, $topic_identifier);
		if (!empty($comments))
		{
			return count($comments);
		}
		return 0;
	}

	public static function comment_exists(int $comment_id): bool
	{
		return self::$comments_cache->comment_exists($comment_id);
	}

	public static function add_comment(int $id_topic, string $message, int $user_id, ?string $pseudo, string $user_ip, string $visitor_email): int
	{
		$columns = [
			'id_topic'      => $id_topic,
			'user_id'       => $user_id,
			'visitor_email' => TextHelper::htmlspecialchars($visitor_email),
			'pseudo'        => TextHelper::htmlspecialchars($pseudo),
			'user_ip'       => TextHelper::htmlspecialchars($user_ip),
			'timestamp'     => time(),
			'message'       => $message
		];
		$result = self::$db_querier->insert(DB_TABLE_COMMENTS, $columns);
		return $result->get_last_inserted_id();
	}

	public static function edit_comment(int $comment_id, string $message): void
	{
		$columns = [
			'message' => $message
		];
		$condition = "WHERE id = :id";
		$parameters = [
			'id' => $comment_id
		];
		self::$db_querier->update(DB_TABLE_COMMENTS, $columns, $condition, $parameters);
	}
}
?>
