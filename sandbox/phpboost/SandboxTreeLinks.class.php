<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get_all_langs('sandbox');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($lang['form.configuration'], SandboxUrlBuilder::config()));
		$component_back = new AdminModuleLink($lang['sandbox.admin.render'], SandboxUrlBuilder::admin_builder());
		$component_back->add_sub_link(new AdminModuleLink($lang['sandbox.forms'], SandboxUrlBuilder::admin_builder()));
		$component_back->add_sub_link(new AdminModuleLink($lang['sandbox.components'], SandboxUrlBuilder::admin_component()));
		$tree->add_link($component_back);

		return $tree;
	}
}
?>
