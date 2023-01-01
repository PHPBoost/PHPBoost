<?php
/**
 * @package     Content
 * @subpackage  Category\controllers
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 6.0 - 2019 11 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultCategoriesManagementController extends AbstractCategoriesManagementController
{
	protected function get_display_category_url(Category $category)
	{
		return CategoriesUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), Environment::get_running_module_name());
	}

	protected function get_edit_category_url(Category $category)
	{
		return CategoriesUrlBuilder::edit($category->get_id(), Environment::get_running_module_name());
	}

	protected function get_delete_category_url(Category $category)
	{
		return CategoriesUrlBuilder::delete($category->get_id(), Environment::get_running_module_name());
	}

	protected function get_categories_management_url()
	{
		return CategoriesUrlBuilder::manage(Environment::get_running_module_name());
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
		if (!CategoriesAuthorizationsService::check_authorizations()->manage())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
