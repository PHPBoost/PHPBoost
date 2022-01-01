<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 25
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/begin.php';



$url_controller_mappers = array(
new UrlControllerMapper('MenuControllerConfigurationsList', '`^(?:/configs(?:/list)?)?/?$`'),
new UrlControllerMapper('MenuControllerConfigurationEdit', '`^/configs/([0-9]+)/edit/?$`',
array('menu_config_id'))
);
DispatchManager::dispatch($url_controller_mappers);

?>
