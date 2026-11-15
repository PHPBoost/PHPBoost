<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2011 10 09
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserUserExtensionPoint implements UserExtensionPoint
{
	public function get_publications_module_view($user_id): string
	{
		return UserUrlBuilder::comments('', $user_id)->rel();
	}

	public function get_publications_module_name(): string
	{
		return LangLoader::get_message('comment.comments', 'comment-lang');
	}

	public function get_publications_module_id(): string
	{
		return '';
	}

	public function get_publications_module_icon(): string
	{
		return 'far fa-comments';
	}

	public function get_publications_number(int $user_id): int
	{
		$parameters = ['user_id' => $user_id];
		return PersistenceContext::get_querier()->count(DB_TABLE_COMMENTS, 'WHERE user_id = :user_id', $parameters);
	}
}
?>
