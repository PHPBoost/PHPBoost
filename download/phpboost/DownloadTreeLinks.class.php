<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 23
 * @since   	PHPBoost 4.0 - 2014 08 24
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadTreeLinks extends DefaultTreeLinks
{
	protected function get_module_additional_actions_tree_links(&$tree)
	{
		$module_id = 'download';
		$current_user = AppContext::get_current_user()->get_id();

		$tree->add_link(new ModuleLink(LangLoader::get_message('download.my.items', 'common', $module_id), DownloadUrlBuilder::display_member_items($current_user), DownloadAuthorizationsService::check_authorizations()->write() || DownloadAuthorizationsService::check_authorizations()->contribution() || DownloadAuthorizationsService::check_authorizations()->moderation()));

	}
}
?>
