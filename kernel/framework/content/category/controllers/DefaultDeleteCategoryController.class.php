<?php
/**
 * @package     Content
 * @subpackage  Category\controllers
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 18
 * @since       PHPBoost 6.0 - 2019 11 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultDeleteCategoryController extends AbstractDeleteCategoryController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected function get_categories_management_url()
	{
		return CategoriesUrlBuilder::manage(Environment::get_running_module_name());
	}

	protected function get_delete_category_url(Category $category)
	{
		return CategoriesUrlBuilder::delete($category->get_id(), Environment::get_running_module_name());
	}

	protected function get_module_home_page_url()
	{
		return ModulesUrlBuilder::home();
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
			if (ClassLoader::is_class_registered_and_valid($cache_class) && is_subclass_of($cache_class, 'CacheData'))
				call_user_func($cache_class .'::invalidate');
		}
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
