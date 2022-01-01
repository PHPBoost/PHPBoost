<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
    new UrlControllerMapper('AdminSandboxConfigController', '`^/admin(?:/config)?/?$`'),
    new UrlControllerMapper('AdminSandboxBuilderController', '`^/admin(?:/builder)?/?$`'),
    new UrlControllerMapper('AdminSandboxFWKBoostController', '`^/admin(?:/component)?/?$`'),
	new UrlControllerMapper('SandboxTableController', '`^/table/?$`'),
	new UrlControllerMapper('SandboxStringTemplateController', '`^/template/?`'),
	new UrlControllerMapper('SandboxBuilderController', '`^/builder/?`'),
	new UrlControllerMapper('SandboxComponentController', '`^/component/?`'),
	new UrlControllerMapper('SandboxLayoutController', '`^/layout/?`'),
	new UrlControllerMapper('SandboxBBCodeController', '`^/bbcode/?`'),
	new UrlControllerMapper('SandboxMenusNavController', '`^/menus/nav/?`'),
	new UrlControllerMapper('SandboxMenusContentController', '`^/menus/content/?`'),
	new UrlControllerMapper('SandboxIconsController', '`^/icons/?`'),
	new UrlControllerMapper('SandboxEmailController', '`^/email/?`'),
	new UrlControllerMapper('SandboxLangController', '`^/lang/?`'),
	new UrlControllerMapper('SandboxHomeController', '`^.*$`')
);
DispatchManager::dispatch($url_controller_mappers);

?>
