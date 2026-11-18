<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2011 09 20
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
	new UrlControllerMapper('AdminModulesManagementController', '`^/(?:installed/?)?$`'),
	new UrlControllerMapper('AdminModuleAddController', '`^/add/?$`'),
	new UrlControllerMapper('AdminModuleUpdateController', '`^/update(?:/([A-Za-z0-9_-]+))?/?$`', ['id_module']),
	new UrlControllerMapper('AdminModuleDeleteController', '`^/([A-Za-z0-9-_]+)/delete/([0-9])+/?$`', ['id', 'level']),
];
DispatchManager::dispatch($url_controller_mappers);
?>
