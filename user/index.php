<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
	new UrlControllerMapper('UserLoginController', '`^/login/?$`'),
	new UrlControllerMapper('UserExploreGroupsController', '`^/groups(?:/([0-9]+))?/?$`', array('id')),
	new UrlControllerMapper('UserRegistrationController', '`^/registration/?$`'),
	new UrlControllerMapper('UserConfirmRegistrationController', '`^/registration/confirm(?:/([a-z0-9]+))?/?$`', array('registration_pass')),
	new UrlControllerMapper('UserHomeProfileController', '`^/profile/home/?$`'),
	new UrlControllerMapper('UserViewProfileController', '`^/profile(?:/([0-9]+))?/?$`', array('user_id')),
	new UrlControllerMapper('UserEditProfileController', '`^/profile(?:/([0-9]+))/edit/?$`', array('user_id')),
	new UrlControllerMapper('UserMessagesController', '`^/messages(?:/([0-9]+))?/?$`', array('user_id')),
	new UrlControllerMapper('UserCommentsController', '`^/messages(?:/([0-9]+))?/?comments?/?([A-Za-z]+)?/?([0-9]+)?/?$`', array('user_id', 'module_id', 'page')),
	new UrlControllerMapper('UserLostPasswordController', '`^/password/lost/?$`'),
	new UrlControllerMapper('UserChangeLostPasswordController', '`^/password/change(?:/([a-z0-9]+))?/?$`', array('key')),
	new UrlControllerMapper('UserError403Controller', '`^/error/403/?$`'),
	new UrlControllerMapper('UserError404Controller', '`^/error/404/?$`'),
	new UrlControllerMapper('UserUsersListController', '`^(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),
);

DispatchManager::dispatch($url_controller_mappers);
?>