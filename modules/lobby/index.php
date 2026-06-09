<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 06 06
 * @since       PHPBoost 6.1 - 2026 03 21
 */

define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
	new UrlControllerMapper('AdminLobbyConfigController',              '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('AdminLobbyAddModulesController',          '`^/admin/add/?$`'),
	new UrlControllerMapper('AdminLobbyModulesPositionController',     '`^/admin/positions/?$`'),
	new UrlControllerMapper('LobbyAjaxChangeModuleDisplayController',  '`^/admin/positions/change_display/?$`'),
	new UrlControllerMapper('LobbyHomeController',                     '`^(?:/([0-9]+))?/?$`'),
];
DispatchManager::dispatch($url_controller_mappers);
