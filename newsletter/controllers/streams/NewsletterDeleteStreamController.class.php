<?php
/*##################################################
 *		                NewsletterDeleteStreamController.class.php
 *                            -------------------
 *   begin                : May 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class NewsletterDeleteStreamController extends AbstractDeleteCategoryController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}
	
	protected function get_categories_manager()
	{
		return NewsletterService::get_streams_manager();
	}
	
	protected function get_categories_management_url()
	{
		return NewsletterUrlBuilder::manage_streams();
	}
	
	protected function get_delete_category_url(Category $category)
	{
		return NewsletterUrlBuilder::delete_stream($category->get_id());
	}
	
	protected function get_module_home_page_url()
	{
		return NewsletterUrlBuilder::home();
	}
	
	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('newsletter', 'common', 'newsletter');
	}
	
	protected function get_title()
	{
		return LangLoader::get_message('stream.delete', 'common', 'newsletter');
	}
	
	protected function get_description()
	{
		return LangLoader::get_message('stream.delete.description', 'common', 'newsletter');
	}
	
	protected function get_success_message()
	{
		return LangLoader::get_message('stream.message.success.delete', 'common', 'newsletter');
	}
	
	protected function check_authorizations()
	{
		if (!NewsletterAuthorizationsService::default_authorizations()->manage_streams())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>