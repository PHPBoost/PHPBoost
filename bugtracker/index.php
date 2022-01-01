<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 3.0 - 2012 10 20
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Admin
	new UrlControllerMapper('AdminBugtrackerConfigController', '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('AdminBugtrackerAuthorizationsController', '`^/admin/authorizations/?$`'),
	new UrlControllerMapper('AdminBugtrackerDeleteParameterController', '`^/admin/delete/([a-z]+)/([0-9]+)/?$`', array('parameter', 'id')),
	new UrlControllerMapper('AdminBugtrackerDeleteDefaultParameterController', '`^/admin/delete/default/([a-z]+)/?$`', array('parameter')),

	//Lists
	new UrlControllerMapper('BugtrackerUnsolvedListController', '`^/unsolved/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9_-]+)?/?$`', array('field', 'sort', 'page', 'filter', 'filter_id')),
	new UrlControllerMapper('BugtrackerSolvedListController', '`^/solved/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9_-]+)?/?$`', array('field', 'sort', 'page', 'filter', 'filter_id')),
	new UrlControllerMapper('BugtrackerRoadmapListController', '`^/roadmap/?([0-9]+)?-?([a-z0-9_-]+)?/?([a-z_-]+)?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('id_version', 'version', 'status', 'field', 'sort', 'page')),

	//Stats
	new UrlControllerMapper('BugtrackerStatsListController', '`^/stats/?$`'),

	//Bug history
	new UrlControllerMapper('BugtrackerHistoryListController', '`^/history/([0-9]+)/?([0-9]+)?/?$`', array('id', 'page')),

	//Bug detail
	new UrlControllerMapper('BugtrackerDetailController', '`^/detail/([0-9]+)-?([0-9A-Za-z_-]+)?/?$`', array('id', 'rewrited_title')),

	//New bug
	new UrlControllerMapper('BugtrackerFormController', '`^/add/?$`'),

	//Bug edition
	new UrlControllerMapper('BugtrackerFormController', '`^/([0-9]+)/edit/?$`', array('id')),

	//Actions
	new UrlControllerMapper('BugtrackerDeleteBugController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('BugtrackerChangeBugStatusController', '`^/change_status/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('BugtrackerAjaxCheckStatusChangedController', '`^/check_status_changed/?$`'),

	//Filters
	new UrlControllerMapper('BugtrackerAddFilterController', '`^/add_filter/([a-z]+)/([a-z_-]+)/([a-z0-9-]+)/?$`', array('page', 'filter', 'filter_id')),
	new UrlControllerMapper('BugtrackerAjaxDeleteFilterController', '`^/delete_filter/?$`'),

	new UrlControllerMapper('BugtrackerUnsolvedListController', '`^/?$`'),
);
DispatchManager::dispatch($url_controller_mappers);
?>
