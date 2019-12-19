<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 05
 * @since       PHPBoost 5.3 - 2019 11 02
*/

class DefaultDeleteCategoryController extends AbstractDeleteCategoryController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected function get_categories_manager()
	{
		return CategoriesService::get_categories_manager();
	}

	protected function get_categories_management_url()
	{
		return CategoriesUrlBuilder::manage_categories();
	}

	protected function get_delete_category_url(Category $category)
	{
		return CategoriesUrlBuilder::delete_category($category->get_id());
	}

	protected function get_module_home_page_url()
	{
		return CategoriesUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return ModulesManager::get_module(Environment::get_running_module_name())->get_configuration()->get_name();
	}

	protected function clear_cache()
	{
		$module_id = Environment::get_running_module_name();
		$cache_classes = array(ucfirst($module_id) . 'Cache', ucfirst($module_id) . 'MiniMenuCache');
		foreach ($cache_classes as $cache_class)
		{
			if (class_exists($cache_class) && is_subclass_of($cache_class, 'CacheData'))
				$categories_cache = call_user_func($cache_class .'::invalidate');
		}
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
