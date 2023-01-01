<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 21
 * @since       PHPBoost 4.1 - 2014 10 14
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Admin
	new UrlControllerMapper('AdminShoutboxConfigController', '`^/admin(?:/config)?/?$`'),

	//Mini menu
	new UrlControllerMapper('ShoutboxAjaxAddMessageController', '`^/ajax_add/?$`'),
	new UrlControllerMapper('ShoutboxAjaxDeleteMessageController', '`^/ajax_delete/?$`'),
	new UrlControllerMapper('ShoutboxAjaxRefreshMessagesController', '`^/ajax_refresh/?$`'),

	//Archives
	new UrlControllerMapper('ShoutboxFormController', '`^/add/?$`'),
	new UrlControllerMapper('ShoutboxFormController', '`^/([0-9]+)/edit/?([0-9]+)?/?$`', array('id', 'page')),
	new UrlControllerMapper('ShoutboxDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('ShoutboxHomeController', '`^(?:/manage)?(?:/([0-9]+))?/?$`', array('page'))
);
DispatchManager::dispatch($url_controller_mappers);
?>
