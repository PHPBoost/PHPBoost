<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 11 09
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
    new UrlControllerMapper('AdminSandboxConfigController', '`^/admin(?:/config)?/?$`'),
    new UrlControllerMapper('AdminSandboxFormController', '`^/admin(?:/form)?/?$`'),
	new UrlControllerMapper('SandboxTableController', '`^/table/?$`'),
	new UrlControllerMapper('SandboxStringTemplateController', '`^/template/?`'),
	new UrlControllerMapper('SandboxFormController', '`^/form/?`'),
	new UrlControllerMapper('SandboxGraphicsCSSController', '`^/css/?`'),
	new UrlControllerMapper('SandboxGraphicsCSSController', '`^/css/?`'),
	new UrlControllerMapper('SandboxMultitabsController', '`^/multitabs/?`'),
	new UrlControllerMapper('SandboxPluginsController', '`^/plugins/?`'),
	new UrlControllerMapper('SandboxBBCodeController', '`^/bbcode/?`'),
	new UrlControllerMapper('SandboxMenuController', '`^/menu/?`'),
	new UrlControllerMapper('SandboxIconsController', '`^/icons/?`'),
	new UrlControllerMapper('SandboxMailController', '`^/mail/?`'),
	new UrlControllerMapper('SandboxHomeController', '`^.*$`')
);
DispatchManager::dispatch($url_controller_mappers);

?>
