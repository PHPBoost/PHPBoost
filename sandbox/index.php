<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 03
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
    new UrlControllerMapper('AdminSandboxConfigController', '`^/admin(?:/config)?/?$`'),
    new UrlControllerMapper('AdminSandboxBuilderController', '`^/admin(?:/builder)?/?$`'),
    new UrlControllerMapper('AdminSandboxFWKBoostController', '`^/admin(?:/fwkboost)?/?$`'),
	new UrlControllerMapper('SandboxTableController', '`^/table/?$`'),
	new UrlControllerMapper('SandboxStringTemplateController', '`^/template/?`'),
	new UrlControllerMapper('SandboxBuilderController', '`^/builder/?`'),
	new UrlControllerMapper('SandboxComponentController', '`^/component/?`'),
	new UrlControllerMapper('SandboxLayoutController', '`^/layout/?`'),
	new UrlControllerMapper('SandboxMultitabsController', '`^/multitabs/?`'),
	new UrlControllerMapper('SandboxPluginsController', '`^/plugins/?`'),
	new UrlControllerMapper('SandboxBBCodeController', '`^/bbcode/?`'),
	new UrlControllerMapper('SandboxMenuController', '`^/menus/?`'),
	new UrlControllerMapper('SandboxIconsController', '`^/icons/?`'),
	new UrlControllerMapper('SandboxMailController', '`^/email/?`'),
	new UrlControllerMapper('SandboxHomeController', '`^.*$`')
);
DispatchManager::dispatch($url_controller_mappers);

?>
