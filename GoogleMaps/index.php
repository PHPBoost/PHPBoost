<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 04 13
 * @since       PHPBoost 5.0 - 2017 03 26
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(new UrlControllerMapper('AdminGoogleMapsConfigController', '`^/admin(?:/config)?/?$`'));
DispatchManager::dispatch($url_controller_mappers);
?>
