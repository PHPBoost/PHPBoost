<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 04 13
 * @since       PHPBoost 1.6 - 2006 10 09
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$config = WikiConfig::load();

$url_controller_mappers = [
    // Configuration
    new UrlControllerMapper('AdminWikiConfigController', '`^/admin(?:/config)?/?$`'),

    //Categories
    new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`'),
    new UrlControllerMapper('WikiCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', ['id_parent']),
    new UrlControllerMapper('WikiCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', ['id']),
    new UrlControllerMapper('WikiDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', ['id']),

    // Items Management
    new UrlControllerMapper('WikiItemsManagerController', '`^/manage/?$`'),
    new UrlControllerMapper('WikiItemFormController', '`^/add/?([0-9]+)?/?$`', ['id_category']),
    new UrlControllerMapper('WikiItemFormController', '`^/([0-9]+)/edit/?$`', ['id']),
    new UrlControllerMapper('WikiItemFormController', '`^/([0-9]+)/duplicate/?$`', ['id']),
    new UrlControllerMapper('WikiItemHistoryController', '`^/([0-9]+)/history/?$`', ['id']),
    new UrlControllerMapper('WikiItemArchiveController', '`^/([0-9]+)/archive/([0-9]+)/?$`', ['id', 'content_id']),
    new UrlControllerMapper('WikiRestoreContentController', '`^/([0-9]+)/restore/([0-9]+)/?$`', ['id', 'content_id']),
    new UrlControllerMapper('WikiDeleteItemController', '`^/([0-9]+)/delete/([0-9]+)/?$`', ['id', 'content_id']),
    new UrlControllerMapper('WikiItemController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)?/?$`', ['id_category', 'rewrited_name_category', 'id', 'rewrited_name']),
    new UrlControllerMapper('WikiReorderItemsController', '`^/reorder/?([0-9]+)?-?([a-z0-9-_]+)?/?$`', ['id_category', 'rewrited_name']),
    new UrlControllerMapper('WikiTrackItemController', '`^/([0-9]+)/track/?$`', ['id']),
    new UrlControllerMapper('WikiUntrackItemController', '`^/([0-9]+)/untrack/?$`', ['id']),

    // Keywords
    new UrlControllerMapper('WikiTagController', '`^/tag/([a-z0-9-_]+)?/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?$`', ['tag', 'page']),

    new UrlControllerMapper('WikiPendingItemsController', '`^/pending(?:/([a-z_]+))?/?([a-z]+)?/?([0-9]+)?/?$`', ['page']),
    new UrlControllerMapper('WikiMemberItemsController', '`^/member/([0-9]+)?/?([0-9]+)?/?$`', ['user_id', 'page']),
    new UrlControllerMapper('WikiTrackedItemsController', '`^/tracked/([0-9]+)?/?([0-9]+)?/?$`', ['user_id', 'page']),

    new UrlControllerMapper('WikiIndexController', $config->get_homepage() == WikiConfig::OVERVIEW ? '`^/?$`' : '`^/overview/?$`'),
    new UrlControllerMapper('WikiExplorerController', $config->get_homepage() == WikiConfig::EXPLORER ? '`^/?$`' : '`^/explorer/?$`'),
    new UrlControllerMapper('WikiCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', ['id_category', 'rewrited_name', 'page', 'subcategories_page']),
];
DispatchManager::dispatch($url_controller_mappers);
?>
