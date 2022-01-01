<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 19
 * @since       PHPBoost 3.0 - 2011 10 09
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserUserExtensionPoint implements UserExtensionPoint
{
	public function get_publications_module_view($user_id)
	{
		return UserUrlBuilder::comments('', $user_id)->rel();
	}

	public function get_publications_module_name()
	{
		return LangLoader::get_message('comment.comments', 'comment-lang');
	}

	public function get_publications_module_id()
	{
		return '';
	}

	public function get_publications_module_icon()
	{
		return 'far fa-comments';
	}

	public function get_publications_number($user_id)
	{
		$parameters = array('user_id' => $user_id);
		return PersistenceContext::get_querier()->count(DB_TABLE_COMMENTS, 'WHERE user_id = :user_id', $parameters);
	}
}
?>
