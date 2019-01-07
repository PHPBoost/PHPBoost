<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 02 24
 * @since   	PHPBoost 4.0 - 2013 11 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class NewsTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'news');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), NewsUrlBuilder::manage_categories(), NewsAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), NewsUrlBuilder::manage_categories(), NewsAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), NewsUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), NewsAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_news_link = new ModuleLink($lang['news.manage'], NewsUrlBuilder::manage_news(), NewsAuthorizationsService::check_authorizations()->moderation());
		$manage_news_link->add_sub_link(new ModuleLink($lang['news.manage'], NewsUrlBuilder::manage_news(), NewsAuthorizationsService::check_authorizations()->moderation()));
		$manage_news_link->add_sub_link(new ModuleLink($lang['news.add'], NewsUrlBuilder::add_news(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), NewsAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_news_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), NewsUrlBuilder::configuration()));

		if (!NewsAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['news.add'], NewsUrlBuilder::add_news(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), NewsAuthorizationsService::check_authorizations()->write() || NewsAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['news.pending'], NewsUrlBuilder::display_pending_news(), NewsAuthorizationsService::check_authorizations()->write() || NewsAuthorizationsService::check_authorizations()->contribution() || NewsAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('news')->get_configuration()->get_documentation(), NewsAuthorizationsService::check_authorizations()->write() || NewsAuthorizationsService::check_authorizations()->contribution() || NewsAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
