<?php
/*##################################################
 *		        ArticlesCategoriesManageController.class.php
 *                            -------------------
 *   begin                : March 04, 2013
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

class ArticlesCategoriesManageController extends AbstractCategoriesManageController
{
	protected function generate_response(View $view)
	{
		return new AdminNewsDisplayResponse($view, LangLoader::get_message('admin.categories.manage', 'articles-common', 'articles'));
	}
	
	protected function get_categories_manager()
	{
		return ArticlesService::get_categories_manager();
	}
	
	protected function get_edit_category_url($id)
	{
		return ArticlesUrlBuilder::edit_category($id);
	}
	
	protected function get_delete_category_url($id)
	{
		return ArticlesUrlBuilder::delete_category($id);
	}
}
?>