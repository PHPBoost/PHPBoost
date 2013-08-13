<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : November 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
	//Config
	new UrlControllerMapper('AdminCalendarConfigController', '`^/admin(?:/config)?/?$`'),
	
	//Categories
	new UrlControllerMapper('CalendarCategoriesManageController', '`^/admin/categories/?$`'),
	new UrlControllerMapper('CalendarCategoriesFormController', '`^/admin/categories/add/?$`'),
	new UrlControllerMapper('CalendarCategoriesFormController', '`^/admin/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('CalendarDeleteCategoryController', '`^/admin/categories/([0-9]+)/delete/?$`', array('id')),
	
	new UrlControllerMapper('CalendarDisplayCategoryController', '`^/category/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id', 'rewrited_name')),
	
	//Manage Events
	new UrlControllerMapper('CalendarFormController', '`^/add/?$`'),
	new UrlControllerMapper('CalendarFormController', '`^/edit/([0-9]+)?/?$`', array('id')),
	new UrlControllerMapper('CalendarDeleteController', '`^/delete/([0-9]+)?/?$`', array('id')),
	
	new UrlControllerMapper('AjaxMiniCalendarController', '`^/mini/([0-9]+)?/?([0-9]+)?/?$`', array('year', 'month')),
	new UrlControllerMapper('CalendarController', '`^/error/([a-z_-]+)?/?$`', array('error')),
	new UrlControllerMapper('CalendarController', '`^/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('year', 'month', 'day', 'event')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
