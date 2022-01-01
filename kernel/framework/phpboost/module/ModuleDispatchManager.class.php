<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 05
 * @since       PHPBoost 6.0 - 2020 02 07
*/

class ModuleDispatchManager extends DispatchManager
{
	/**
	 * Redirect the request to the right controller using the url controller mappes list
	 * @param UrlControllerMapper[] $url_controller_mappers the url controllers mapper list
	 * @param string $module_id id of the current module. If not set module id is retrieved from running module name in Environment class.
	 */
	public static function dispatch($url_controller_mappers = array(), $module_id = '')
	{
		$module_configuration = ModulesManager::get_module(!empty($module_id) ? $module_id : Environment::get_running_module_name())->get_configuration();
		
		if ($module_configuration->has_categories())
		{
			//Categories
			$url_controller_mappers[] = new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`', array(), $module_id);
			$url_controller_mappers[] = new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent'), $module_id);
			$url_controller_mappers[] = new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id'), $module_id);
			$url_controller_mappers[] = new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id'), $module_id);
		}

		if ($module_configuration->get_configuration_name())
		{
			//Configuration
			$url_controller_mappers[] = new UrlControllerMapper('DefaultConfigurationController', '`^/admin(?:/config)?/?$`', array(), $module_id);
		}

		if ($module_configuration->has_items() || $module_configuration->has_rich_items())
		{
			//Items management
			$url_controller_mappers[] = new UrlControllerMapper('DefaultItemsManagementController', '`^/manage/?$`', array(), $module_id);
			$url_controller_mappers[] = new UrlControllerMapper('DefaultDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id'), $module_id);
			$url_controller_mappers[] = new UrlControllerMapper('DefaultItemFormController', '`^/add/?([0-9]+)?/?$`', array('id_category'), $module_id);
			$url_controller_mappers[] = new UrlControllerMapper('DefaultItemFormController', '`^(?:/([0-9]+))/edit/?$`', array('id'), $module_id);

			//Item display
			if ($module_configuration->has_categories())
				$url_controller_mappers[] = new UrlControllerMapper('DefaultDisplayItemController', '`^(?:/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+))/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_title'), $module_id);
			else
				$url_controller_mappers[] = new UrlControllerMapper('DefaultDisplayItemController', '`^(?:/([0-9]+)-([a-z0-9-_]+))/?$`', array('id', 'rewrited_title'), $module_id);
			
			//Items display
			$url_controller_mappers[] = new UrlControllerMapper('DefaultSeveralItemsController', '`^/member/?([0-9]+)?/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('user_id', 'field', 'sort', 'page'), $module_id);
			$url_controller_mappers[] = new UrlControllerMapper('DefaultSeveralItemsController', '`^/pending/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page'), $module_id);
			if ($module_configuration->feature_is_enabled('keywords'))
				$url_controller_mappers[] = new UrlControllerMapper('DefaultSeveralItemsController', '`^/tag/?([a-z0-9-_]+)/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('tag', 'field', 'sort', 'page'), $module_id);
			
			//Categories and home page display
			if ($module_configuration->has_categories())
				$url_controller_mappers[] = new UrlControllerMapper('DefaultSeveralItemsController', '`^/([0-9]+)-([a-z0-9-_]+)/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'field', 'sort', 'page', 'subcategories_page'), $module_id);
			else
				$url_controller_mappers[] = new UrlControllerMapper('DefaultSeveralItemsController', '`^/([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page'), $module_id);

			$url_controller_mappers[] = new UrlControllerMapper('DefaultSeveralItemsController', '`^/?$`', array(), $module_id);
		}
		
		parent::dispatch($url_controller_mappers);
	}
}
?>
