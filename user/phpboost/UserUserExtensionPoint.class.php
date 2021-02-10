<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 10
 * @since       PHPBoost 3.0 - 2011 10 09
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserUserExtensionPoint implements UserExtensionPoint
{
	public function get_messages_list_url($user_id)
	{
		return UserUrlBuilder::comments('', $user_id)->rel();
	}

	public function get_messages_list_link_name()
	{
		return LangLoader::get_message('comments', 'comments-common');
	}

	public function get_messages_list_link_img()
	{
		return 'far fa-comments';
	}

	public function get_number_messages($user_id)
	{
		$parameters = array('user_id' => $user_id);
		return PersistenceContext::get_querier()->count(DB_TABLE_COMMENTS, 'WHERE user_id = :user_id', $parameters);
	}
}
?>
