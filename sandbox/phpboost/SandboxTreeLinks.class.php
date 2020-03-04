<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 03
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
		$fwkboost_back = new AdminModuleLink($lang['title.admin.fwkboost'], SandboxUrlBuilder::admin_builder());
		$fwkboost_back->add_sub_link(new AdminModuleLink($lang['title.builder'], SandboxUrlBuilder::admin_builder()));
		$fwkboost_back->add_sub_link(new AdminModuleLink($lang['title.fwkboost'], SandboxUrlBuilder::admin_fwkboost()));
		$tree->add_link($fwkboost_back);

		return $tree;
	}
}
?>
