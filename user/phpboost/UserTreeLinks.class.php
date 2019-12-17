<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 20
 * @since       PHPBoost 5.1 - 2018 10 20
*/

class UserTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('admin-user-common');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($lang['members.members-management'], AdminMembersUrlBuilder::management()));
		$tree->add_link(new AdminModuleLink($lang['members.add-member'], AdminMembersUrlBuilder::add()));
		$tree->add_link(new AdminModuleLink($lang['members.config-members'], AdminMembersUrlBuilder::configuration()));
		$tree->add_link(new AdminModuleLink($lang['members.members-punishment'], UserUrlBuilder::moderation_panel()));

		return $tree;
	}
}
?>
