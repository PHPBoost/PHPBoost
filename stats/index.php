<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 21
 * @since       PHPBoost 6.0 - 2021 11 23
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
	// Configurations
	new UrlControllerMapper('AdminStatsConfigController', '`^/admin(?:/config)?/?$`'),

	new UrlControllerMapper('StatsDisplayController',  '`^/([a-z]+)/table/([0-9]+)?/?$`', ['section', 'page']),
	new UrlControllerMapper('StatsDisplayController',  '`^/([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', ['section', 'year', 'month', 'day']),
	new UrlControllerMapper('StatsDisplayController',  '`^/?$`'),
];
DispatchManager::dispatch($url_controller_mappers);
?>
