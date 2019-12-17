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
		$tree->add_link(new AdminModuleLink($lang['title.admin.form'], SandboxUrlBuilder::admin_form()));
		$tree->add_link(new ModuleLink($lang['title.form.builder'], SandboxUrlBuilder::form()));
		$tree->add_link(new ModuleLink($lang['title.css'], SandboxUrlBuilder::css()));
		$tree->add_link(new ModuleLink($lang['title.plugins'], SandboxUrlBuilder::plugins()));
		$tree->add_link(new ModuleLink($lang['title.bbcode'], SandboxUrlBuilder::bbcode()));
		$tree->add_link(new ModuleLink($lang['title.menu'], SandboxUrlBuilder::menu()));
		$tree->add_link(new ModuleLink($lang['title.icons'], SandboxUrlBuilder::icons()));
		$tree->add_link(new ModuleLink($lang['title.table.builder'], SandboxUrlBuilder::table()));
		$tree->add_link(new ModuleLink($lang['title.mail.sender'], SandboxUrlBuilder::mail()));
		$tree->add_link(new ModuleLink($lang['title.string.template'], SandboxUrlBuilder::template()));

		return $tree;
	}
}
?>
