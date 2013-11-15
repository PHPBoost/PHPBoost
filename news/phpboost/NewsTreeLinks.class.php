<?php
/*##################################################
 *		                         NewsTreeLinks.class.php
 *                            -------------------
 *   begin                : November 15, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class NewsTreeLinks
{
	public static function get_tree_links()
	{
		$lang = LangLoader::get('common', 'news');
		$tree = new ModuleTreeLinks();

		// Actions links
		$manage_categories_link = new ModuleLink($lang['admin.categories.manage'], NewsUrlBuilder::manage_categories(), AppContext::get_current_user()->check_level(User::ADMIN_LEVEL));
		$manage_categories_link->add_sub_link(new ModuleLink($lang['admin.categories.manage'], NewsUrlBuilder::manage_categories(), AppContext::get_current_user()->check_level(User::ADMIN_LEVEL)));
		$manage_categories_link->add_sub_link(new ModuleLink($lang['admin.categories.add'], NewsUrlBuilder::add_category(), AppContext::get_current_user()->check_level(User::ADMIN_LEVEL)));
		$tree->add_actions_tree_links($manage_categories_link);
		
		if (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$manage_news_link = new ModuleLink($lang['news.manage'], NewsUrlBuilder::manage_news(), AppContext::get_current_user()->check_level(User::ADMIN_LEVEL));
			$manage_news_link->add_sub_link(new ModuleLink($lang['news.manage'], NewsUrlBuilder::manage_news(), AppContext::get_current_user()->check_level(User::ADMIN_LEVEL)));
			$manage_news_link->add_sub_link(new ModuleLink($lang['news.add'], NewsUrlBuilder::add_news(), NewsAuthorizationsService::check_authorizations()->write() || NewsAuthorizationsService::check_authorizations()->contribution()));
			$tree->add_actions_tree_links($manage_news_link);
		}
		else
		{
			$tree->add_actions_tree_links(new ModuleLink($lang['news.add'], NewsUrlBuilder::add_news(), NewsAuthorizationsService::check_authorizations()->write() || NewsAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_actions_tree_links(new ModuleLink($lang['news.pending'], NewsUrlBuilder::display_pending_news(), NewsAuthorizationsService::check_authorizations()->write() || NewsAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_actions_tree_links(new ModuleLink($lang['admin.config'], NewsUrlBuilder::config(), AppContext::get_current_user()->check_level(User::ADMIN_LEVEL)));
		
		return $tree;
	}
}
?>