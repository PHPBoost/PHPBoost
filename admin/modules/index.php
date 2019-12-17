<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2018 01 10
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminModulesManagementController', '`^/(?:installed/?)?$`'),
	new UrlControllerMapper('AdminModuleAddController', '`^/add/?$`'),
	new UrlControllerMapper('AdminModuleUpdateController', '`^/update(?:/([A-Za-z0-9_-]+))?/?$`', array('id_module')),
	new UrlControllerMapper('AdminModuleDeleteController', '`^/([A-Za-z0-9-_]+)/delete/?$`', array('id')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
