<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 22
 * @since       PHPBoost 4.0 - 2013 11 23
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();
		$form_lang = LangLoader::get('form-lang');

		$tree->add_link(new AdminModuleLink($form_lang['form.fields.management'], ContactUrlBuilder::manage_fields()));
		$tree->add_link(new AdminModuleLink($form_lang['form.field.add'], ContactUrlBuilder::add_field()));

		$tree->add_link(new AdminModuleLink($form_lang['form.configuration'], ContactUrlBuilder::configuration()));

		if (ModulesManager::get_module('contact')->get_configuration()->get_documentation())
			$tree->add_link(new AdminModuleLink($form_lang['form.documentation'], ModulesManager::get_module('contact')->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
