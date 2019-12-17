<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 07
 * @since       PHPBoost 4.0 - 2015 02 02
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminMediaConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('DefaultCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('MediaCategoriesFormController', '`^/categories/add/?$`'),
	new UrlControllerMapper('MediaCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('MediaDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	new UrlControllerMapper('MediaDisplayCategoryController',  '`^/([0-9]+)-([a-z0-9-_]+)/?$`', array('cat', 'rewrited_name')),
	new UrlControllerMapper('MediaDisplayCategoryController', '`^/?$`'),
);
DispatchManager::dispatch($url_controller_mappers);
?>
