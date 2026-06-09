<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 06 06
 * @since       PHPBoost 5.2 - 2020 06 15
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$config  = PagesConfig::load();
$columns = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();

if ($config->get_left_column_disabled()) {
    $columns->set_disable_left_columns(true);
}

if ($config->get_right_column_disabled()) {
    $columns->set_disable_right_columns(true);
}

$url_controller_mappers = [
    // -------------------------------------------------------------------------
    // Administration / config
    // -------------------------------------------------------------------------
    new UrlControllerMapper('AdminPagesConfigController', '`^/admin(?:/config)?/?$`'),

    // -------------------------------------------------------------------------
    // Items management (admin)
    // -------------------------------------------------------------------------
    new UrlControllerMapper('PagesReorderItemsController', '`^/reorder/?([0-9]+)?-?([a-z0-9-_]+)?/?$`', ['id_category', 'rewrited_name']),

    // -------------------------------------------------------------------------
    // Frontend item display — PagesDisplayItemController so that current_url
    // and breadcrumbs produce root-based URLs (no /pages/ prefix).
    // Must be declared BEFORE ModuleDispatchManager adds DefaultDisplayItemController.
    // -------------------------------------------------------------------------
    new UrlControllerMapper('PagesDisplayItemController', '`^(?:/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+))/?$`', ['id_category', 'rewrited_name_category', 'id', 'rewrited_title'], 'pages'),

    // -------------------------------------------------------------------------
    // Frontend category display — PagesHomeController so that category URLs
    // and breadcrumbs produce root-based URLs.
    // Must be declared BEFORE ModuleDispatchManager adds DefaultSeveralItemsController.
    // -------------------------------------------------------------------------
    new UrlControllerMapper('PagesHomeController', '`^/([0-9]+)-([a-z0-9-_]+)/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', ['id_category', 'rewrited_name', 'field', 'sort', 'page', 'subcategories_page'], 'pages'),

    // -------------------------------------------------------------------------
    // Frontend root / home
    // -------------------------------------------------------------------------
    new UrlControllerMapper('PagesHomeController', '`^/?$`', [], 'pages'),
];

ModuleDispatchManager::dispatch($url_controller_mappers, 'pages');
