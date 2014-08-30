<?php
/*##################################################
 *                               index.php
 *                            -------------------
 *   begin                : August 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminWebConfigController', '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('AdminWebManageController', '`^/admin/manage(?:/([a-z_-]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),
	
	//Categories
	new UrlControllerMapper('WebCategoriesManageController', '`^/admin/categories/?$`'),
	new UrlControllerMapper('WebCategoriesFormController', '`^/admin/categories/add/?$`'),
	new UrlControllerMapper('WebCategoriesFormController', '`^/admin/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('WebDeleteCategoryController', '`^/admin/categories/([0-9]+)/delete/?$`', array('id')),
	
	//Management
	new UrlControllerMapper('WebFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('WebFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('WebDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('WebDisplayWebLinkController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_title')),
	
	//Keywords
	new UrlControllerMapper('WebDisplayWebLinkTagController', '`^/tag/([a-z0-9-_]+)?/?([0-9]+)?/?$`', array('tag', 'page')),
	
	new UrlControllerMapper('WebDisplayPendingWebLinksController', '`^/pending/([0-9]+)?/?$`', array('page')),
	
	new UrlControllerMapper('WebVisitWebLinkController', '`^/visit/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('WebDeadLinkController', '`^/dead_link/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('WebDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'page')),
	
);
DispatchManager::dispatch($url_controller_mappers);
?>
