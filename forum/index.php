<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 02 05
 * @since       PHPBoost 1.2 - 2005 10 25
 * @contributor Benoit SAUTEL <ben.popeye@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminForumConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('ForumCategoriesManagementController', '`^/categories/?$`'),
	new UrlControllerMapper('ForumCategoriesFormController', '`^/categories/add/?$`'),
	new UrlControllerMapper('ForumCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Home
	new UrlControllerMapper('ForumHomeController',  '`^/([0-9]+)-([a-z0-9-_]+)/?$`', array('cat', 'rewrited_name')),
	new UrlControllerMapper('ForumHomeController', '`^/?$`')
);
DispatchManager::dispatch($url_controller_mappers);
?>
