<?php
/*##################################################
 *		      ArticlesDeleteCategoryController.class.php
 *                            -------------------
 *   begin                : March 04, 2013
 *   copyright            : (C) 2013 Kevin MASSY
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

class ArticlesDeleteCategoryController extends AbstractDeleteCategoryController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}
	
	protected function get_categories_manager()
	{
		return ArticlesService::get_categories_manager();
	}
	
	protected function get_categories_management_url()
	{
		return ArticlesUrlBuilder::manage_categories();
	}
	
	protected function get_delete_category_url(Category $category)
	{
		return ArticlesUrlBuilder::delete_category($category->get_id());
	}
	
	protected function get_module_home_page_url()
	{
		return ArticlesUrlBuilder::home();
	}
	
	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('articles', 'common', 'articles');
	}
	
	protected function check_authorizations()
	{
		if (!ArticlesAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>