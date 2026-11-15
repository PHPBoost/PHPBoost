<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 6.1 - 2026 11 15
 */

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
	new UrlControllerMapper('AdminAddonsConfigController', '`^(?:/config)?/?$`'),
];
DispatchManager::dispatch($url_controller_mappers);
?>