<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 02 24
 * @since   	PHPBoost 4.0 - 2014 09 02
 * @contributor xela <xela@phpboost.com>
*/

class FaqTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'faq');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), FaqUrlBuilder::manage_categories(), FaqAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), FaqUrlBuilder::manage_categories(), FaqAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), FaqUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), FaqAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_link = new ModuleLink($lang['faq.manage'], FaqUrlBuilder::manage(), FaqAuthorizationsService::check_authorizations()->moderation());
		$manage_link->add_sub_link(new ModuleLink($lang['faq.manage'], FaqUrlBuilder::manage(), FaqAuthorizationsService::check_authorizations()->moderation()));
		$manage_link->add_sub_link(new ModuleLink($lang['faq.actions.add'], FaqUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), FaqAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), FaqUrlBuilder::configuration()));

		if (!FaqAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['faq.actions.add'], FaqUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), FaqAuthorizationsService::check_authorizations()->write() || FaqAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['faq.pending'], FaqUrlBuilder::display_pending(), FaqAuthorizationsService::check_authorizations()->write() || FaqAuthorizationsService::check_authorizations()->contribution() || FaqAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('faq')->get_configuration()->get_documentation(), FaqAuthorizationsService::check_authorizations()->write() || FaqAuthorizationsService::check_authorizations()->contribution() || FaqAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
