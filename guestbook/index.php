<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : December 11, 2012
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
	new UrlControllerMapper('AdminGuestbookConfigController', '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('AdminGuestbookConfigController', '`^/admin/config/error/([a-z_-]+)?/?$`', array('error')),
	new UrlControllerMapper('AdminGuestbookConfigController', '`^/admin/config/success/([a-z_-]+)?/?$`', array('success')),
	
	new UrlControllerMapper('GuestbookDeleteController', '`^/delete/([0-9]+)?/?([0-9]+)?/?$`', array('id', 'page')),
	new UrlControllerMapper('GuestbookController', '`^(?:/([0-9]+))?/?([0-9]+)?/?$`', array('page', 'id')),
	new UrlControllerMapper('UserError404Controller', '`^/([\w/_-]*)?/?$`'),
);
DispatchManager::dispatch($url_controller_mappers);
?>
