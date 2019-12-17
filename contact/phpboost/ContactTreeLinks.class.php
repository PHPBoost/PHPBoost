<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 07 20
 * @since       PHPBoost 4.0 - 2013 11 23
 * @contributor xela <xela@phpboost.com>
*/

class ContactTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();

		$manage_fields_link = new AdminModuleLink(LangLoader::get_message('admin.fields.manage', 'common', 'contact'), ContactUrlBuilder::manage_fields());
		$manage_fields_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('admin.fields.manage', 'common', 'contact'), ContactUrlBuilder::manage_fields()));
		$manage_fields_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('fields.action.add_field', 'admin-user-common'), ContactUrlBuilder::add_field()));
		$tree->add_link($manage_fields_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), ContactUrlBuilder::configuration()));

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('contact')->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
