<?php
/*##################################################
 *                                 index.php
 *                            -------------------
 *   begin                : May 2, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
	//Fields
	new UrlControllerMapper('AdminContactFieldsListController', '`^/admin/fields(?:/list)?/?$`'),
	new UrlControllerMapper('AdminContactFieldFormController', '`^/admin/fields/add/?$`'),
	new UrlControllerMapper('AdminContactFieldFormController', '`^/admin/fields/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('ContactAjaxDeleteFieldController', '`^/admin/fields/delete/?$`'),
	new UrlControllerMapper('ContactAjaxChangeFieldDisplayController', '`^/admin/fields/change_display/?$`'),
	new UrlControllerMapper('ContactAjaxCheckFieldNameController', '`^/admin/fields/check_name/?$`'),
	
	//Config
	new UrlControllerMapper('AdminContactConfigController', '`^/admin(?:/config)?/?([a-z]+)?/?$`', array('message')),
	
	//Contact form
	new UrlControllerMapper('ContactController')
);

DispatchManager::dispatch($url_controller_mappers);
?>
