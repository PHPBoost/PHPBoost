<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : November 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Categories
	new UrlControllerMapper('CalendarCategoriesManageController', '`^/admin/categories/?$`'),
	new UrlControllerMapper('CalendarCategoriesFormController', '`^/admin/categories/add/?$`'),
	new UrlControllerMapper('CalendarCategoriesFormController', '`^/admin/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('CalendarDeleteCategoryController', '`^/admin/categories/([0-9]+)/delete/?$`', array('id')),
	
	//Admin
	new UrlControllerMapper('AdminManageCalendarEventsController', '`^/admin/manage(?:/([a-z_-]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),
	new UrlControllerMapper('AdminCalendarConfigController', '`^/admin(?:/config)?/?([a-z]+)?/?$`', array('message')),
	
	//Display events
	new UrlControllerMapper('CalendarDisplayCategoryController', '`^/category/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name')),
	new UrlControllerMapper('CalendarDisplayEventController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),
	
	//Manage events
	new UrlControllerMapper('CalendarFormController', '`^/add/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('year', 'month', 'day', 'id_category')),
	new UrlControllerMapper('CalendarFormController', '`^/edit/([0-9]+)?/?$`', array('id')),
	new UrlControllerMapper('CalendarDeleteController', '`^/delete/([0-9]+)?/?(?:/([a-z]+))?/?(?:/([a-z_-]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('id', 'return', 'field', 'sort', 'page')),
	new UrlControllerMapper('CalendarAjaxSubscribeUnsubscribeController', '`^/ajax_change_participation/?$`'),
	
	//Display calendar
	new UrlControllerMapper('CalendarAjaxCalendarController', '`^/ajax_month_calendar/([0-9]+)?/?([0-9]+)?/?$`', array('year', 'month')),
	new UrlControllerMapper('CalendarDisplayCategoryController', '`^/error/([a-z_-]+)?/?$`', array('error')),
	new UrlControllerMapper('CalendarDisplayCategoryController', '`^/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?(?:/([0-9]+)-([a-z0-9-_]+))?/?$`', array('year', 'month', 'day', 'id_category', 'rewrited_name')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
