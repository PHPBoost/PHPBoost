<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 09 16
 * @since       PHPBoost 3.0 - 2012 01 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminLangsInstalledListController', '`^/(?:installed/?)?$`'),
	new UrlControllerMapper('AdminLangsNotInstalledListController', '`^/install/?$`'),
	new UrlControllerMapper('AdminUninstallLangController', '`^/([A-Za-z0-9-_]+)/uninstall/?$`', array('id')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
