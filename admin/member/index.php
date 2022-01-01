<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 05 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminExtendedFieldsMemberListController', '`^/extended-fields(?:/list)?/?$`'),
	new UrlControllerMapper('AdminExtendedFieldMemberAddController', '`^/extended-fields/add/?$`'),
	new UrlControllerMapper('AdminExtendedFieldMemberEditController', '`^/extended-fields/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('AdminExtendedFieldMemberDeleteController', '`^/extended-fields/delete/?$`'),
	new UrlControllerMapper('AdminExtendedFieldChangeFieldDisplayController', '`^/extended-fields/change_display/?$`'),

	new UrlControllerMapper('AdminMemberAddController', '`^/add/?$`'),
	new UrlControllerMapper('AdminMemberConfigController', '`^/config/?$`'),
	new UrlControllerMapper('AdminMemberDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('AdminViewAllMembersController', '`^/(?:management/?)?$`')
);
DispatchManager::dispatch($url_controller_mappers);
?>
