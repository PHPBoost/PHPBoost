<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 21
 * @since       PHPBoost 3.0 - 2012 12 11
 * @contributor xela <xela@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminGuestbookConfigController', '`^/admin(?:/config)?/?$`'),

	new UrlControllerMapper('GuestbookFormController', '`^/add/?$`'),
	new UrlControllerMapper('GuestbookFormController', '`^/([0-9]+)/edit/?([0-9]+)?/?$`', array('id', 'page')),
	new UrlControllerMapper('GuestbookDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('GuestbookController', '`^(?:/manage)?(?:/([0-9]+))?/?$`', array('page')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
