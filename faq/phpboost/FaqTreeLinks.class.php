<?php
/*##################################################
 *                               FaqTreeLinks.class.php
 *                            -------------------
 *   begin                : September 2, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

class FaqTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'faq');
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new AdminModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), FaqUrlBuilder::manage_categories());
		$manage_categories_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), FaqUrlBuilder::manage_categories()));
		$manage_categories_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('category.add', 'categories-common'), FaqUrlBuilder::add_category()));
		$tree->add_link($manage_categories_link);
		
		$manage_link = new AdminModuleLink($lang['faq.manage'], FaqUrlBuilder::manage());
		$manage_link->add_sub_link(new AdminModuleLink($lang['faq.manage'], FaqUrlBuilder::manage()));
		$manage_link->add_sub_link(new AdminModuleLink($lang['faq.actions.add'], FaqUrlBuilder::add(AppContext::get_request()->get_getstring('id_category', 0))));
		$tree->add_link($manage_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), FaqUrlBuilder::configuration()));
		
		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($lang['faq.actions.add'], FaqUrlBuilder::add(AppContext::get_request()->get_getstring('id_category', 0)), FaqAuthorizationsService::check_authorizations()->write() || FaqAuthorizationsService::check_authorizations()->contribution()));
		}
		
		$tree->add_link(new ModuleLink($lang['faq.pending'], FaqUrlBuilder::display_pending(), FaqAuthorizationsService::check_authorizations()->write() || FaqAuthorizationsService::check_authorizations()->contribution() || FaqAuthorizationsService::check_authorizations()->moderation()));
		
		return $tree;
	}
}
?>
