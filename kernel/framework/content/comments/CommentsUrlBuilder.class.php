<?php
/**
 * This class represents the comments topic
 * <div class="message-helper bgc notice">Do not use this class, but one of its children like for your module</div>
 * @package     Content
 * @subpackage  Comments
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 08
 * @since       PHPBoost 3.0 - 2012 05 02
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CommentsUrlBuilder
{
	public static function edit(string $comment_path, int $id): Url
	{
		return self::build_url($comment_path, 'edit_comment=' . $id . '#add-comment');
	}

	public static function delete(string $comment_path, int $id, string $return_path = ''): Url
	{
		return self::build_url($comment_path, 'delete_comment=' . $id . '&token=' . AppContext::get_session()->get_token() . ($return_path ? '&return_path=' . $return_path : '#comments-section'));
	}

	public static function lock_and_unlock(string $comment_path, int $lock): Url
	{
		return self::build_url($comment_path, 'lock=' . (int)$lock . '&token=' . AppContext::get_session()->get_token() . '#comments-section');
	}

	public static function comment_added(string $comment_path, int $id_comment): Url
	{
		return new Url($comment_path . '#com' . $id_comment);
	}

	private static function build_url(string $comment_path, mixed $parameters): Url
	{
		if (TextHelper::strpos($comment_path, '?') === false)
		{
			return new Url($comment_path . '?' . $parameters);
		}
		else
		{
			return new Url($comment_path . '&' . $parameters);
		}
	}
}
?>
