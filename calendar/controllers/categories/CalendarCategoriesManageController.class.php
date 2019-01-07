<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 07 19
 * @since   	PHPBoost 3.0 - 2013 02 25
*/

class CalendarCategoriesManageController extends AbstractCategoriesManageController
{
	protected function get_categories_manager()
	{
		return CalendarService::get_categories_manager();
	}

	protected function get_display_category_url(Category $category)
	{
		return CalendarUrlBuilder::home();
	}

	protected function get_edit_category_url(Category $category)
	{
		return CalendarUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return CalendarUrlBuilder::delete_category($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return CalendarUrlBuilder::manage_categories();
	}

	protected function get_module_home_page_url()
	{
		return CalendarUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('module_title', 'common', 'calendar');
	}

	protected function check_authorizations()
	{
		if (!CalendarAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
