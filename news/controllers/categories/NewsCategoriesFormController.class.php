<?php
/*##################################################
 *		                NewsCategoriesFormController.class.php
 *                            -------------------
 *   begin                : February 13, 2013
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
class NewsCategoriesFormController extends AbstractRichCategoriesFormController
{
	protected function generate_response(View $view)
	{
		$page_name = $this->get_id_category() == 0 ? LangLoader::get_message('admin.categories.add', 'common', 'news') : LangLoader::get_message('admin.categories.edit', 'common', 'news');
		return new AdminNewsDisplayResponse($view, $page_name);
	}
	
	protected function get_categories_manager()
	{
		return NewsService::get_categories_manager();
	}
	
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}
	
	protected function get_categories_management_url()
	{
		return NewsUrlBuilder::manage_categories();
	}
}
?>