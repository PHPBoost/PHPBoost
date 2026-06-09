<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 04 12
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
	new UrlControllerMapper('AdminMailConfigController', '`^/mail/?$`'),
	new UrlControllerMapper('AdminAddonsConfigController', '`^/addons/?$`'),
	new UrlControllerMapper('AdminGeneralConfigController', '`^/(?:general/?)?$`'),
	new UrlControllerMapper('AdminAdvancedConfigController', '`^/advanced/?$`'),
];
DispatchManager::dispatch($url_controller_mappers);
?>