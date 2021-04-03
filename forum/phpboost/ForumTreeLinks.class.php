<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 03
 * @since       PHPBoost 4.0 - 2013 11 23
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ForumTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$module_id = 'forum';

		$lang = LangLoader::get('common', $module_id);
		$tree = new ModuleTreeLinks();

		$tree->add_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CategoriesUrlBuilder::manage($module_id), ForumAuthorizationsService::check_authorizations()->manage()));
		$tree->add_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), CategoriesUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), $module_id), ForumAuthorizationsService::check_authorizations()->manage()));
		
		$tree->add_link(new AdminModuleLink($lang['forum.ranks.manager'], ForumUrlBuilder::manage_ranks()));
		$tree->add_link(new AdminModuleLink($lang['forum.rank.add'], ForumUrlBuilder::add_rank()));
		
		$tree->add_link(new ModuleLink($lang['my.items'], ForumUrlBuilder::display_member_items(), ForumAuthorizationsService::check_authorizations()->write() || ForumAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), ForumUrlBuilder::configuration()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('moderation_panel', 'main'), ForumUrlBuilder::moderation_panel(), ForumAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink($lang['forum.no.answer'], ForumUrlBuilder::show_no_answer(), ForumAuthorizationsService::check_authorizations()->read() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)));

		if (ModulesManager::get_module($module_id)->get_configuration()->get_documentation())
			$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('forum')->get_configuration()->get_documentation(), ForumAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
