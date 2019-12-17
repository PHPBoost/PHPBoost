<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 10 08
 * @since       PHPBoost 3.0 - 2011 08 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminCustomizeInterfaceController', '`^/interface(?:/([A-Za-z0-9-_]+))?/?$`', array('theme')),
	new UrlControllerMapper('AdminCustomizeFaviconController', '`^/favicon/?$`'),
	new UrlControllerMapper('AdminCustomizeEditorCSSFilesController', '`^/editor/css(?:/([A-Za-z0-9-_]+))?/?(.+)?/?$`', array('id_theme', 'file_name')),
	new UrlControllerMapper('AdminCustomizeEditorTPLFilesController', '`^/editor/tpl(?:/([A-Za-z0-9-_]+))?/?(.+)?/?$`', array('id_theme', 'file_name'))
);
DispatchManager::dispatch($url_controller_mappers);

?>
