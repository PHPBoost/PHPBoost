<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 05 30
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminLoggedErrorsControllerList', '`^/(?:list/?)?$`'),
	new UrlControllerMapper('AdminLoggedErrorsControllerClear', '`^/clear/?$`'),
	new UrlControllerMapper('AdminErrorsController404List', '`^/404(?:/list)?/?$`'),
	new UrlControllerMapper('AdminErrorsController404Clear', '`^/404/clear/?$`'),
	new UrlControllerMapper('AdminErrorsController404Delete', '`^/404/([0-9]+)/delete/?$`', array('id')),
);
DispatchManager::dispatch($url_controller_mappers);

?>
