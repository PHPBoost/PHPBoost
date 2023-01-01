<?php
/**
 * This service handles all the needed operations that deals with the site map data.
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 24
 * @since       PHPBoost 3.0 - 2009 12 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('ViewXMLSitemapController', '`^/view/xml?$`'),
	new UrlControllerMapper('ViewSitemapController', '`^(?:/view)?/?$`'),
	new UrlControllerMapper('AdminSitemapController', '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('GenerateXMLSitemapController', '`^/admin/generate?$`')
);

DispatchManager::dispatch($url_controller_mappers);
?>
