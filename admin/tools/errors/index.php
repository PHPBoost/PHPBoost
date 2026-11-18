<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2009 12 13
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
	new UrlControllerMapper('AdminErrors404ListController', '`^/404(?:/list)?/?$`'),
	new UrlControllerMapper('AdminErrors404ClearController', '`^/404/clear/?$`'),
	new UrlControllerMapper('AdminErrors404DeleteController', '`^/404/([0-9]+)/delete/?$`', ['id']),
	new UrlControllerMapper('AdminLoggedErrorsListController', '`^/(?:list/?)?$`'),
	new UrlControllerMapper('AdminLoggedErrorsClearController', '`^/clear/?$`'),
];
DispatchManager::dispatch($url_controller_mappers);

?>
