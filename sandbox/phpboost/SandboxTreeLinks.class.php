<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 11 09
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
		$fwkboost_back = new AdminModuleLink($lang['title.admin.fwkboost'], SandboxUrlBuilder::admin_form());
		$fwkboost_back->add_sub_link(new AdminModuleLink($lang['title.form'], SandboxUrlBuilder::admin_form()));
		$fwkboost_back->add_sub_link(new AdminModuleLink($lang['title.framework'], SandboxUrlBuilder::admin_form()));
		$tree->add_link($fwkboost_back);

		$fwkboost_front = new AdminModuleLink($lang['title.theme.fwkboost'], SandboxUrlBuilder::css());
		$fwkboost_front->add_sub_link(new ModuleLink($lang['title.form'], SandboxUrlBuilder::form()));
		$fwkboost_front->add_sub_link(new ModuleLink($lang['title.framework'], SandboxUrlBuilder::css()));
		$fwkboost_front->add_sub_link(new ModuleLink($lang['title.multitabs'], SandboxUrlBuilder::multitabs()));
		$fwkboost_front->add_sub_link(new ModuleLink($lang['title.plugins'], SandboxUrlBuilder::plugins()));
		$fwkboost_front->add_sub_link(new ModuleLink($lang['title.bbcode'], SandboxUrlBuilder::bbcode()));
		$fwkboost_front->add_sub_link(new ModuleLink($lang['title.menu'], SandboxUrlBuilder::menus()));
		$fwkboost_front->add_sub_link(new ModuleLink($lang['title.icons'], SandboxUrlBuilder::icons()));
		$fwkboost_front->add_sub_link(new ModuleLink($lang['title.table'], SandboxUrlBuilder::table()));
		$fwkboost_front->add_sub_link(new ModuleLink($lang['title.email'], SandboxUrlBuilder::email()));
		$fwkboost_front->add_sub_link(new ModuleLink($lang['title.string.template'], SandboxUrlBuilder::template()));
		$tree->add_link($fwkboost_front);

		return $tree;
	}
}
?>
