<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 05
 * @since       PHPBoost 5.2 - 2020 06 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$config = PagesConfig::load();
$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
if ($config->get_left_column_disabled())
	$columns_disabled->set_disable_left_columns(true);
if ($config->get_right_column_disabled())
	$columns_disabled->set_disable_right_columns(true);

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminPagesConfigController', '`^/admin(?:/config)?/?$`'),

	//Items management
	new UrlControllerMapper('PagesReorderItemsController', '`^/reorder/?([0-9]+)?-?([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name')),

	//Items list
	new UrlControllerMapper('PagesHomeController', '`^/?$`')
);

ModuleDispatchManager::dispatch($url_controller_mappers, 'pages');

?>
