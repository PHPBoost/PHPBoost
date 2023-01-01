<?php
/**
 * This class represents the contact object field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 3.0 - 2010 05 02
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	// Fields
	new UrlControllerMapper('AdminContactFieldsListController', '`^/admin/fields(?:/list)?/?$`'),
	new UrlControllerMapper('AdminContactFieldFormController', '`^/admin/fields/add/?$`'),
	new UrlControllerMapper('AdminContactFieldFormController', '`^/admin/fields/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('ContactAjaxDeleteFieldController', '`^/admin/fields/delete/?$`'),
	new UrlControllerMapper('ContactAjaxChangeFieldDisplayController', '`^/admin/fields/change_display/?$`'),
	new UrlControllerMapper('ContactAjaxCheckFieldNameController', '`^/admin/fields/check_name/?$`'),

	// Configuration
	new UrlControllerMapper('AdminContactConfigController', '`^/admin(?:/config)?/?([a-z]+)?/?$`', array('message')),

	// Homepage
	new UrlControllerMapper('ContactController')
);

DispatchManager::dispatch($url_controller_mappers);
?>
