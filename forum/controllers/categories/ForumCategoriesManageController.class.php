<?php
/*##################################################
 *                              ForumCategoriesManageController.class.php
 *                            -------------------
 *   begin                : May 15, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class ForumCategoriesManageController extends AbstractCategoriesManageController
{
	protected function generate_response(View $view)
	{
		return new AdminForumDisplayResponse($view, $this->get_title());
	}

	protected function get_categories_manager()
	{
		return ForumService::get_categories_manager();
	}
	
	protected function get_display_category_url(Category $category)
	{
		switch ($category->get_type())
		{
			case ForumCategory::TYPE_URL :
				$url = new Url($category->get_url());
				break;
			
			case ForumCategory::TYPE_FORUM :
				$url = ForumUrlBuilder::display_forum($category->get_id(), $category->get_rewrited_name());
				break;
			
			default :
				$url = ForumUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
				break;
		}
		
		return $url;
	}

	protected function get_edit_category_url(Category $category)
	{
		return ForumUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return ForumUrlBuilder::delete_category($category->get_id());
	}
}
?>
