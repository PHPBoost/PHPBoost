<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 25
 * @since       PHPBoost 3.0 - 2011 08 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminCustomizeInterfaceController', '`^/admin/interface(?:/([A-Za-z0-9-_]+))?/?$`', array('theme')),
	new UrlControllerMapper('AdminCustomizeFaviconController', '`^/admin/favicon/?$`'),
	new UrlControllerMapper('AdminCustomizeEditorCSSFilesController', '`^/admin/editor/css(?:/([A-Za-z0-9-_]+))?/?(.+)?/?$`', array('id_theme', 'file_name')),
	new UrlControllerMapper('AdminCustomizeEditorTPLFilesController', '`^/admin/editor/tpl(?:/([A-Za-z0-9-_]+))?/?(.+)?/?$`', array('id_theme', 'file_name'))
);
DispatchManager::dispatch($url_controller_mappers);

?>
