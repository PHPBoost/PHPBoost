<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 05 20
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
		$component_back = new AdminModuleLink($lang['title.admin.component'], SandboxUrlBuilder::admin_builder());
		$component_back->add_sub_link(new AdminModuleLink($lang['title.builder'], SandboxUrlBuilder::admin_builder()));
		$component_back->add_sub_link(new AdminModuleLink($lang['title.component'], SandboxUrlBuilder::admin_component()));
		$tree->add_link($component_back);

		return $tree;
	}
}
?>
