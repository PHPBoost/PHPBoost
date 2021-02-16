<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 16
 * @since       PHPBoost 6.0 - 2021 02 16
*/

class DownloadUserExtensionPoint implements UserExtensionPoint
{
	public function get_publications_module_view($user_id)
	{
		return DownloadUrlBuilder::display_member_items($user_id)->rel();
	}

	public function get_publications_module_name()
	{
		return LangLoader::get_message('module.title', 'common', 'download');
	}

	public function get_publications_module_id()
	{
		return 'download';
	}

	public function get_publications_module_icon()
	{
		return 'fa fa-download';
	}

	public function get_publications_number($user_id)
	{
		$parameters = array('user_id' => $user_id);
		return PersistenceContext::get_querier()->count(PREFIX . 'download', 'WHERE author_user_id = :user_id', $parameters);
	}
}
?>
