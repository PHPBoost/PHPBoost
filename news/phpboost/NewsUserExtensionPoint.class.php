<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 11
 * @since       PHPBoost 6.0 - 2021 02 10
*/

class NewsUserExtensionPoint implements UserExtensionPoint
{
	public function get_messages_list_url($user_id)
	{
		return NewsUrlBuilder::display_member_items($user_id)->rel();
	}

	public function get_messages_list_link_name()
	{
		return LangLoader::get_message('module.title', 'common', 'news');
	}

	public function get_module_id()
	{
		return 'news';
	}

	public function get_messages_list_link_img()
	{
		return 'far fa-newspaper';
	}

	public function get_number_messages($user_id)
	{
		$parameters = array('user_id' => $user_id);
		return PersistenceContext::get_querier()->count(PREFIX . 'news', 'WHERE author_user_id = :user_id', $parameters);
	}
}
?>
