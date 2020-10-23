<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 10 23
 * @since       PHPBoost 5.2 - 2020 06 15
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$config = PagesConfig::load();
$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
if ($config->is_left_column_disabled())
	$columns_disabled->set_disable_left_columns(true);
if ($config->is_right_column_disabled())
	$columns_disabled->set_disable_right_columns(true);


$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminPagesConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`'),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Management
	new UrlControllerMapper('PagesItemsManagerController', '`^/manage/?$`'),
	new UrlControllerMapper('PagesItemFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('PagesItemFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('PagesDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('PagesItemController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	new UrlControllerMapper('PagesMemberItemsController', '`^/my_items/?$`'),
	new UrlControllerMapper('PagesTagController', '`^/tag/([a-z0-9-_]+)?/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('tag', 'field', 'sort', 'page')),
	new UrlControllerMapper('PagesPendingItemsController', '`^/pending(?:/([a-z_]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),
	new UrlControllerMapper('PagesReorderItemsController', '`^/reorder/([0-9]+)-?([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name')),

	new UrlControllerMapper('PagesHomeController', '`^/?$`'),
	new UrlControllerMapper('PagesCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'page', 'subcategories_page')),

);
DispatchManager::dispatch($url_controller_mappers);
?>
