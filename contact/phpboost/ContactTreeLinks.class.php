<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 4.0 - 2013 11 23
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();
		$lang = LangLoader::get_all_langs();

		$tree->add_link(new AdminModuleLink($lang['form.fields.management'], ContactUrlBuilder::manage_fields()));
		$tree->add_link(new AdminModuleLink($lang['form.field.add'], ContactUrlBuilder::add_field()));

		$tree->add_link(new AdminModuleLink($lang['form.configuration'], ContactUrlBuilder::configuration()));

		if (ModulesManager::get_module('contact')->get_configuration()->get_documentation())
			$tree->add_link(new AdminModuleLink($lang['form.documentation'], ModulesManager::get_module('contact')->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
