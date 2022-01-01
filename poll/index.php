<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 24
 * @since       PHPBoost 6.0 - 2020 05 14
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once '../kernel/init.php';

$url_controller_mappers = array(
	// Configuration
	new UrlControllerMapper('AdminPollConfigController', '`^/admin(?:/config)?/?$`'),

 	// Form
	new UrlControllerMapper('PollItemFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('PollItemFormController', '`^/([0-9]+)/edit/?$`', array('id')),

	// Item
	new UrlControllerMapper('PollItemController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	// Items manage
	new UrlControllerMapper('PollItemsManagementController', '`^/manage/?$`'),
	
  	// Mini
	new UrlControllerMapper('AjaxPollMiniController', '`^/ajax_send/$`')
);

ModuleDispatchManager::dispatch($url_controller_mappers);
?>
