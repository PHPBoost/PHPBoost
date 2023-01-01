<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 22
 * @since       PHPBoost 6.0 - 2021 10 22
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	// Configuration
	new UrlControllerMapper('AdminHistoryConfigController', '`^/admin(?:/config)?/?$`'),
	
	// History
	new UrlControllerMapper('AdminHistoryController', '`^(?:/admin/history)?/?$`')
);
DispatchManager::dispatch($url_controller_mappers);
?>
