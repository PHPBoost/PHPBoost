<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 19
 * @since       PHPBoost 5.1 - 2018 10 20
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get_all_langs();
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($lang['user.members.management'], AdminMembersUrlBuilder::management()));
		$tree->add_link(new AdminModuleLink($lang['user.add.member'], AdminMembersUrlBuilder::add()));
		$tree->add_link(new AdminModuleLink($lang['user.members.config'], AdminMembersUrlBuilder::configuration()));
		$tree->add_link(new AdminModuleLink($lang['user.members.punishment'], UserUrlBuilder::moderation_panel()));

		return $tree;
	}
}
?>
