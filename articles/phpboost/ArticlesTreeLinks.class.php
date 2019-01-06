<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2017 02 24
 * @since   	PHPBoost 4.0 - 2013 11 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class ArticlesTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'articles');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), ArticlesUrlBuilder::manage_categories(), ArticlesAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), ArticlesUrlBuilder::manage_categories(), ArticlesAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), ArticlesUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), ArticlesAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_articles_link = new ModuleLink($lang['articles_management'], ArticlesUrlBuilder::manage_articles(), ArticlesAuthorizationsService::check_authorizations()->moderation());
		$manage_articles_link->add_sub_link(new ModuleLink($lang['articles_management'], ArticlesUrlBuilder::manage_articles(), ArticlesAuthorizationsService::check_authorizations()->moderation()));
		$manage_articles_link->add_sub_link(new ModuleLink($lang['articles.add'], ArticlesUrlBuilder::add_article(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), ArticlesAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_articles_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), ArticlesUrlBuilder::configuration()));

		if (!ArticlesAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['articles.add'], ArticlesUrlBuilder::add_article(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['articles.pending_articles'], ArticlesUrlBuilder::display_pending_articles(), ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->contribution() || ArticlesAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('articles')->get_configuration()->get_documentation(), ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->contribution() || ArticlesAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
