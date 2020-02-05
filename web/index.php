<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 05
 * @since       PHPBoost 4.1 - 2014 08 21
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminWebConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`'),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Management
	new UrlControllerMapper('WebManageController', '`^/manage/?$`'),
	new UrlControllerMapper('WebFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('WebFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('WebDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('WebDisplayWebLinkController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	//Keywords
	new UrlControllerMapper('WebDisplayWebLinkTagController', '`^/tag/([a-z0-9-_]+)?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('tag', 'field', 'sort', 'page')),

	new UrlControllerMapper('WebDisplayPendingWebLinksController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),

	new UrlControllerMapper('WebVisitWebLinkController', '`^/visit/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('WebDeadLinkController', '`^/dead_link/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('WebDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'field', 'sort', 'page', 'subcategories_page'))
);
DispatchManager::dispatch($url_controller_mappers);
?>
