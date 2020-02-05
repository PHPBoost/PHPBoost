<?php
/**
 * @package     Content
 * @subpackage  Category\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 05
 * @since       PHPBoost 5.3 - 2019 11 02
*/

class DefaultCategoriesManagementController extends AbstractCategoriesManagementController
{
	protected function get_display_category_url(Category $category)
	{
		return CategoriesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}

	protected function get_edit_category_url(Category $category)
	{
		return CategoriesUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return CategoriesUrlBuilder::delete_category($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return CategoriesUrlBuilder::manage_categories();
	}

	protected function get_module_home_page_url()
	{
		return ModulesUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return ModulesManager::get_module(Environment::get_running_module_name())->get_configuration()->get_name();
	}

	protected function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
