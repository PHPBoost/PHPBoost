<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 02 24
 * @since   	PHPBoost 4.0 - 2013 11 24
 * @contributor xela <xela@phpboost.com>
*/

class MediaTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'media');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), MediaUrlBuilder::manage_categories(), MediaAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), MediaUrlBuilder::manage_categories(), MediaAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), MediaUrlBuilder::add_category(), MediaAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_media_link = new ModuleLink($lang['media.manage'], MediaUrlBuilder::manage(), MediaAuthorizationsService::check_authorizations()->moderation());
		$manage_media_link->add_sub_link(new ModuleLink($lang['media.manage'], MediaUrlBuilder::manage(), MediaAuthorizationsService::check_authorizations()->moderation()));
		$manage_media_link->add_sub_link(new ModuleLink($lang['media.actions.add'], MediaUrlBuilder::add(), MediaAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_media_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), MediaUrlBuilder::configuration()));

		if (!MediaAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['media.actions.add'], MediaUrlBuilder::add(), MediaAuthorizationsService::check_authorizations()->write() || MediaAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('media')->get_configuration()->get_documentation(), MediaAuthorizationsService::check_authorizations()->write() || MediaAuthorizationsService::check_authorizations()->contribution() || MediaAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
