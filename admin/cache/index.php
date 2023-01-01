<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 05 30
 * @since       PHPBoost 3.0 - 2010 08 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminCacheController', '`^/(?:data/?)?$`'),
	new UrlControllerMapper('AdminSyndicationCacheController', '`^/syndication/?$`'),
	new UrlControllerMapper('AdminCacheConfigController', '`^/config/?$`'),
	new UrlControllerMapper('AdminCSSCacheController', '`^/css/?$`')
);
DispatchManager::dispatch($url_controller_mappers);
?>
