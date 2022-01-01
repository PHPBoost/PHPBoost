<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 11
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

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
	new UrlControllerMapper('UserPublicationsController', '`^/publications(?:/([0-9]+))?/?$`', array('user_id')),
	new UrlControllerMapper('UserCommentsController', '`^/publications(?:/([0-9]+))?/?comments?/?([A-Za-z]+)?/?([0-9]+)?/?$`', array('user_id', 'module_id', 'page')),
	new UrlControllerMapper('UserLostPasswordController', '`^/password/lost/?$`'),
	new UrlControllerMapper('UserChangeLostPasswordController', '`^/password/change(?:/([a-z0-9]+))?/?$`', array('key')),
	new UrlControllerMapper('UserError403Controller', '`^/error/403/?$`'),
	new UrlControllerMapper('UserError404Controller', '`^/error/404/?$`'),
	new UrlControllerMapper('UserAboutCookieController', '`^/aboutcookie/?$`'),
	new UrlControllerMapper('UserUsersListController', '`^(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),

);

DispatchManager::dispatch($url_controller_mappers);
?>
