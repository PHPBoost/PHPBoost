<?php
/**
 * @package     Ajax
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 28
 * @since       PHPBoost 3.0 - 2010 05 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

define('PATH_TO_ROOT', '../../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

AppContext::get_session()->no_session_location();

$url_controller_mappers = array(
	//new UrlControllerMapper('AjaxCommentsNotationController', '`^/comments/notation/?$`'),
	new UrlControllerMapper('AjaxCommentsDisplayController', '`^/comments/display/?$`'),
	new UrlControllerMapper('AjaxUserAutoCompleteController','`^/users_autocomplete/?$`'),
	new UrlControllerMapper('AjaxSearchUserAutoCompleteController','`^/search_users_autocomplete/?$`'),
	new UrlControllerMapper('AjaxImagePreviewController', '`^/image/preview/?$`'),
	new UrlControllerMapper('AjaxModuleCategoriesListController', '`^/categories/list/?$`'),
	new UrlControllerMapper('AjaxKeywordsAutoCompleteController','`^/tags/?$`'),
	new UrlControllerMapper('AjaxUrlValidationController', '`^/url_validation/?$`')
);

DispatchManager::dispatch($url_controller_mappers);
?>
