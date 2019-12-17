<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 08
 * @since       PHPBoost 4.0 - 2014 09 02
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminFaqConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('DefaultCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('DefaultRichCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('DefaultRichCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Management
	new UrlControllerMapper('FaqManageController', '`^/manage/?$`'),
	new UrlControllerMapper('FaqFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('FaqFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('FaqDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('FaqAjaxDeleteQuestionController', '`^/ajax_delete/?$`'),
	new UrlControllerMapper('FaqReorderCategoryQuestionsController', '`^/reorder/([0-9]+)-?([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name')),

	new UrlControllerMapper('FaqDisplayPendingFaqQuestionsController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?$`', array('field', 'sort')),

	new UrlControllerMapper('FaqDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'subcategories_page')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
