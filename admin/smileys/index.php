<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 05 22
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminSmileysListController', '`^/(?:management/?)?$`'),
	new UrlControllerMapper('AdminSmileysFormController', '`^/add/?$`'),
	new UrlControllerMapper('AdminSmileysFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('AdminSmileysDeleteController', '`^/([0-9]+)/delete/?$`', array('id'))
);
DispatchManager::dispatch($url_controller_mappers);
?>
