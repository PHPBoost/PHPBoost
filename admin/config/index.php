<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 05 30
 * @since       PHPBoost 3.0 - 2010 04 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminMailConfigController', '`^/mail/?$`'),
	new UrlControllerMapper('AdminGeneralConfigController', '`^/(?:general/?)?$`'),
	new UrlControllerMapper('AdminAdvancedConfigController', '`^/advanced/?$`'),
);
DispatchManager::dispatch($url_controller_mappers);
?>
