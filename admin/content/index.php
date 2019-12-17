<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 05 30
 * @since       PHPBoost 3.0 - 2011 08 10
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminContentConfigController', '`^/(?:config/?)?$`'),
	new UrlControllerMapper('AdminCommentsConfigController', '`^/comments/config/?$`'),
);
DispatchManager::dispatch($url_controller_mappers);
?>
