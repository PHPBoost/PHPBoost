<?php
/**
 * This class manage comments with different functions
 * @package     Content
 * @subpackage  Comments
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 30
 * @since       PHPBoost 3.0 - 2011 09 25
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CommentsManager
{
	private static $user;

	public static function __static()
	{
		self::$user = AppContext::get_current_user();
	}

	public static function add_comment($module_id, $id_in_module, $topic_identifier, $topic_path, $message, $pseudo = '', $email = '')
	{
		if (!CommentsTopicDAO::topic_exists($module_id, $id_in_module, $topic_identifier))
		{
			$id_topic = CommentsTopicDAO::create_topic($module_id, $id_in_module, $topic_identifier, $topic_path);
		}
		else
		{
			$id_topic = CommentsTopicDAO::get_id_topic_module($module_id, $id_in_module, $topic_identifier);
		}

		if (self::$user->check_level(User::MEMBER_LEVEL))
		{
			$comment_id = CommentsDAO::add_comment($id_topic, $message, self::$user->get_id(), self::$user->get_display_name(), self::$user->get_email(), AppContext::get_request()->get_ip_address());
		}
		else
		{
			if(CommentsConfig::load()->is_visitor_email_enabled())
				$comment_id = CommentsDAO::add_comment($id_topic, $message, self::$user->get_id(), $pseudo, AppContext::get_request()->get_ip_address(), $email);
			else
				$comment_id = CommentsDAO::add_comment($id_topic, $message, self::$user->get_id(), $pseudo, '', AppContext::get_request()->get_ip_address());
		}

		CommentsTopicDAO::increment_comments_number_topic($id_topic);
		
		$properties = array(
			'id'           => $comment_id,
			'id_in_module' => $id_in_module,
			'message'      => $message,
			'user_id'      => self::$user->get_id(),
			'user_name'    => self::$user->check_level(User::MEMBER_LEVEL) ? self::$user->get_display_name() : $pseudo,
			'url'          => CommentsUrlBuilder::comment_added($topic_path, $comment_id)->rel()
		);
		HooksService::execute_hook_action('add_comment', $module_id, $properties);

		self::regenerate_cache();
		return $comment_id;
	}

	public static function edit_comment($comment_id, $message)
	{
		CommentsDAO::edit_comment($comment_id, $message);
		self::regenerate_cache();
		
		$comment = CommentsCache::load()->get_comment($comment_id);
		$properties = array(
			'id'        => $comment_id,
			'message'   => $message,
			'user_id'   => self::$user->get_id(),
			'user_name' => self::$user->get_display_name(),
			'url'       => CommentsUrlBuilder::comment_added($comment['path'], $comment_id)->rel()
		);
		HooksService::execute_hook_action('edit_comment', $comment['module_id'], $properties);
	}

	public static function delete_comment($comment_id)
	{
		$comment = CommentsCache::load()->get_comment($comment_id);
		CommentsDAO::delete_comment($comment_id);
		CommentsTopicDAO::decrement_comments_number_topic($comment['id_topic']);
		self::regenerate_cache();
		
		$properties = array(
			'id'        => $comment_id,
			'user_id'   => self::$user->get_id(),
			'user_name' => self::$user->get_display_name()
		);
		HooksService::execute_hook_action('delete_comment', $comment['module_id'], $properties);
	}

	public static function comment_exists($comment_id)
	{
		return CommentsDAO::comment_exists($comment_id);
	}

	public static function delete_comments_module($module_id)
	{
		CommentsDAO::delete_comments_module(CommentsTopicDAO::get_id_topics_module($module_id));
		CommentsTopicDAO::delete_topics_module($module_id);
		self::regenerate_cache();
	}

	public static function delete_comments_topic_module($module_id, $id_in_module)
	{
		$id_topic = CommentsTopicDAO::get_id_topic_module($module_id, $id_in_module);
		CommentsDAO::delete_comments_by_topic($id_topic);
		CommentsTopicDAO::delete_topic_module($module_id, $id_in_module);
		self::regenerate_cache();
	}

	public static function get_comments_number($module_id, $id_in_module = '', $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		return CommentsDAO::get_comments_number($module_id, $id_in_module, $topic_identifier);
	}

	public static function get_user_id_posted_comment($comment_id)
	{
		return CommentsDAO::get_user_id_posted_comment($comment_id);
	}

	public static function get_last_comment_added($user_id)
	{
		return CommentsDAO::get_last_comment_added($user_id);
	}

	public static function comment_topic_locked($module_id, $id_in_module, $topic_identifier)
	{
		if (CommentsTopicDAO::topic_exists($module_id, $id_in_module, $topic_identifier))
		{
			return CommentsTopicDAO::comments_topic_locked($module_id, $id_in_module, $topic_identifier);
		}
		return false;
	}

	public static function lock_topic($module_id, $id_in_module, $topic_identifier)
	{
		CommentsTopicDAO::lock_topic($module_id, $id_in_module, $topic_identifier);
	}

	public static function unlock_topic($module_id, $id_in_module, $topic_identifier)
	{
		CommentsTopicDAO::unlock_topic($module_id, $id_in_module, $topic_identifier);
	}

	private static function regenerate_cache()
	{
		CommentsCache::invalidate();
	}
}
?>
