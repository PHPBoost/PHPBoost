<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.1 - 2015 05 22
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
	new UrlControllerMapper('AdminSmileysListController', '`^/(?:management/?)?$`'),
	new UrlControllerMapper('AdminSmileysFormController', '`^/add/?$`'),
	new UrlControllerMapper('AdminSmileysFormController', '`^/([0-9]+)/edit/?$`', ['id']),
	new UrlControllerMapper('AdminSmileysDeleteController', '`^/([0-9]+)/delete/?$`', ['id'])
];
DispatchManager::dispatch($url_controller_mappers);
?>
