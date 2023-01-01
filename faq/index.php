<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 08
 * @since       PHPBoost 4.0 - 2014 09 02
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminFaqConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`'),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Management
	new UrlControllerMapper('FaqItemsManagerController', '`^/manage/?$`'),
	new UrlControllerMapper('FaqItemFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('FaqItemFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('FaqDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('FaqReorderItemsController', '`^/reorder/([0-9]+)-?([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name')),

	new UrlControllerMapper('FaqPendingItemsController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?$`', array('field', 'sort')),

	new UrlControllerMapper('FaqCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'subcategories_page')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
