<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 05
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Admin
	new UrlControllerMapper('AdminNewsConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('DefaultCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('DefaultRichCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('DefaultRichCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Manage News
	new UrlControllerMapper('NewsManageController', '`^/manage/?$`'),
	new UrlControllerMapper('NewsFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('NewsFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('NewsDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),

	new UrlControllerMapper('NewsDisplayNewsTagController', '`^/tag/([a-z0-9-_]+)?/?([0-9]+)?/?$`', array('tag', 'page')),
	new UrlControllerMapper('NewsDisplayPendingNewsController', '`^/pending/([0-9]+)?/?$`', array('page')),

	new UrlControllerMapper('NewsDisplayNewsController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	new UrlControllerMapper('NewsDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'page')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
