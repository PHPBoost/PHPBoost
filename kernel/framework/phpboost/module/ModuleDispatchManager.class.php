<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 14
 * @since       PHPBoost 5.3 - 2020 02 07
*/

class ModuleDispatchManager extends DispatchManager
{
	/**
	 * Redirect the request to the right controller using the url controller mappes list
	 * @param UrlControllerMapper[] $url_controller_mappers the url controllers mapper list
	 * @param string $module id of the module
	 */
	public static function dispatch(array $module_url_controller_mappers, $module_id)
	{
		$module_configuration = ModulesManager::get_module($module_id)->get_configuration();
		$url_controller_mappers = $module_url_controller_mappers;
		
		if ($module_configuration->has_categories())
		{
			//Categories
			$url_controller_mappers[] = new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`');
			$url_controller_mappers[] = new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent'));
			$url_controller_mappers[] = new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id'));
			$url_controller_mappers[] = new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id'));
		}

		if ($module_configuration->get_configuration_name())
		{
			//Configuration
			$url_controller_mappers[] = new UrlControllerMapper('DefaultConfigurationController', '`^/admin(?:/config)?/?$`');
		}

		if ($module_configuration->has_items())
		{
			//Items management
			$url_controller_mappers[] = new UrlControllerMapper('DefaultItemsManagementController', '`^/manage/?$`');
			$url_controller_mappers[] = new UrlControllerMapper('DefaultDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id'));

			//Items display
			$url_controller_mappers[] = new UrlControllerMapper('DefaultSeveralItemsController', '`^/pending/?([0-9]+)?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('page', 'field', 'sort', 'page'));
			if ($module_configuration->feature_is_enabled('keywords'))
				$url_controller_mappers[] = new UrlControllerMapper('DefaultSeveralItemsController', '`^/tag/([a-z0-9-_]+)/?([0-9]+)?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('tag', 'page', 'field', 'sort', 'page'));
			
			//Categories and home page display
			$url_controller_mappers[] = new UrlControllerMapper('DefaultSeveralItemsController', '`^/([0-9]+)-([a-z0-9-_]+)/?([0-9]+)?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'page', 'field', 'sort', 'page', 'subcategories_page'));
			$url_controller_mappers[] = new UrlControllerMapper('DefaultSeveralItemsController', '`^/?$`');
		}
		
		try
		{
			$dispatcher = new Dispatcher($url_controller_mappers);
			$dispatcher->dispatch();
		}
		catch (NoUrlMatchException $ex)
		{
			self::handle_dispatch_exception($ex);
		}
	}
}
?>
