<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2023 04 05
 */

class WikiDeleteCategoryController extends AbstractDeleteCategoryController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected static function get_categories_manager()
	{
		return WikiService::get_categories_manager();
	}

	protected function get_categories_management_url()
	{
		return CategoriesUrlBuilder::manage(Environment::get_running_module_name());
	}

	/**
	 * @param Category $category
	 */
	protected function get_delete_category_url(Category $category)
	{
		return CategoriesUrlBuilder::delete($category->get_id(), Environment::get_running_module_name());
	}

	protected function get_module_home_page_url()
	{
		return WikiUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('wiki.module.title', 'common', 'wiki');
	}

	protected function check_authorizations()
	{
		if (!WikiAuthorizationsService::check_authorizations()->manage())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
