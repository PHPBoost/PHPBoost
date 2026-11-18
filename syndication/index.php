<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2011 07 16
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
	new UrlControllerMapper('DisplayRssSyndicationController', '`^/rss(?:/([A-Za-z0-9]+))?/?([0-9]+)?/?([A-Za-z0-9]+)?/?$`', ['module_id', 'module_category_id', 'feed_name']),
	new UrlControllerMapper('DisplayAtomSyndicationController', '`^/atom(?:/([A-Za-z0-9]+))?/?([0-9]+)?/?([A-Za-z0-9]+)?/?$`', ['module_id', 'module_category_id', 'feed_name']),
];

AppContext::get_response()->set_header('content-type', 'application/xml');
DispatchManager::dispatch($url_controller_mappers);
?>
