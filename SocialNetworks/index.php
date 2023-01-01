<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 01 05
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminSocialNetworksConfigController', '`^(?:/admin)?(?:/config)?/?$`'),
	new UrlControllerMapper('SocialNetworksAjaxChangeSharingContentDisplayController', '`^/config/change_display/?$`'),
);

DispatchManager::dispatch($url_controller_mappers);
?>
