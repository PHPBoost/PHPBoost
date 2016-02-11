<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : October 14, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
	new UrlControllerMapper('ShoutboxHomeController', '`^(?:/([0-9]+))?/?$`', array('page'))
);
DispatchManager::dispatch($url_controller_mappers);
?>
