<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 3.0 - 2012 11 20
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Admin
	new UrlControllerMapper('AdminCalendarConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('DefaultCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('CalendarCategoriesFormController', '`^/categories/add/?$`'),
	new UrlControllerMapper('CalendarCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('CalendarDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Display events
	new UrlControllerMapper('CalendarDisplayEventController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	//Manage events
	new UrlControllerMapper('CalendarManageEventsController', '`^/manage/?$`'),
	new UrlControllerMapper('CalendarFormController', '`^/add/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('year', 'month', 'day', 'id_category')),
	new UrlControllerMapper('CalendarFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('CalendarDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('CalendarSuscribeController', '`^/([0-9]+)/suscribe/?$`', array('event_id')),
	new UrlControllerMapper('CalendarUnsuscribeController', '`^/([0-9]+)/unsuscribe/?$`', array('event_id')),

	new UrlControllerMapper('CalendarDisplayPendingEventsController', '`^/pending/?$`'),
	new UrlControllerMapper('CalendarEventsListController', '`^/events_list/?$`'),

	//Display calendar
	new UrlControllerMapper('CalendarAjaxCalendarController', '`^/ajax_month_calendar/([0-9]+)?/?([0-9]+)?/?(\d{0,1})?/?$`', array('calendar_ajax_year', 'calendar_ajax_month', 'calendar_mini')),
	new UrlControllerMapper('CalendarAjaxEventsController', '`^/ajax_month_events/([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('calendar_ajax_year', 'calendar_ajax_month', 'calendar_ajax_day')),
	new UrlControllerMapper('CalendarDisplayCategoryController', '`^/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('year', 'month', 'day')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
