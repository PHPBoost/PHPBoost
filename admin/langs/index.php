<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2011 04 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
	new UrlControllerMapper('AdminLangsManagementController', '`^/(?:installed/?)?$`'),
	new UrlControllerMapper('AdminLangAddController', '`^/install/?$`'),
	new UrlControllerMapper('AdminUninstallLangController', '`^/([A-Za-z0-9-_]+)/uninstall/?$`', ['id']),
];
DispatchManager::dispatch($url_controller_mappers);
?>
