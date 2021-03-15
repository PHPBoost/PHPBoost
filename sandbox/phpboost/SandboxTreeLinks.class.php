<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'sandbox');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($lang['title.config'], SandboxUrlBuilder::config()));
		$tree->add_link(new AdminModuleLink($lang['title.builder'] . ' (<span class="small">' . $lang['title.admin.component'] . '</span>)', SandboxUrlBuilder::admin_builder()));
		$tree->add_link(new AdminModuleLink($lang['title.component'] . ' (<span class="small">' . $lang['title.admin.component'] . '</span>)', SandboxUrlBuilder::admin_component()));

		return $tree;
	}
}
?>
