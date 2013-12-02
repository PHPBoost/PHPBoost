<?php
/*##################################################
 *		    ArticlesTreeLinks.class.php
 *                            -------------------
 *   begin                : November 29, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class ArticlesTreeLinks implements ModuleTreeLinksExtensionPoint
{
	private $category;
	
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'articles');
		$this->category = $this->get_category();
		
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new AdminModuleLink($lang['admin.categories.manage'], ArticlesUrlBuilder::manage_categories());
		$manage_categories_link->add_sub_link(new AdminModuleLink($lang['admin.categories.manage'], ArticlesUrlBuilder::manage_categories()));
		$manage_categories_link->add_sub_link(new AdminModuleLink($lang['admin.categories.add'], ArticlesUrlBuilder::add_category()));
		$tree->add_link($manage_categories_link);
	
		$manage_articles_link = new AdminModuleLink($lang['articles_management'], ArticlesUrlBuilder::manage_articles());
		$manage_articles_link->add_sub_link(new AdminModuleLink($lang['articles_management'], ArticlesUrlBuilder::manage_articles()));
		$manage_articles_link->add_sub_link(new AdminModuleLink($lang['articles.add'], ArticlesUrlBuilder::add_article($this->category->get_id())));
		$tree->add_link($manage_articles_link);
		
		$tree->add_link(new AdminModuleLink($lang['articles_configuration'], ArticlesUrlBuilder::articles_configuration()));
	
		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($lang['articles.add'], ArticlesUrlBuilder::add_article($this->category->get_id()), ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->contribution()));	
		}

		$tree->add_link(new ModuleLink($lang['articles.pending_articles'], ArticlesUrlBuilder::display_pending_articles(), ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->moderation()));
	
		return $tree;
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id', 0);
			if (!empty($id))
			{
				try {
					$row = PersistenceContext::get_querier()->select_single_row(ArticlesSetup::$articles_cats_table, array('*'), 'WHERE id=:id', array('id' => $id));

					$category = new RichCategory();
					$category->set_properties($row);
					$this->category = $category;
				} 
				catch (RowNotFoundException $e) 
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}
}
?>
