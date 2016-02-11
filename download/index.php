<?php
/*##################################################
 *                               index.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminDownloadConfigController', '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('AdminDownloadManageController', '`^/admin/manage/?$`'),
	
	//Categories
	new UrlControllerMapper('DownloadCategoriesManageController', '`^/admin/categories/?$`'),
	new UrlControllerMapper('DownloadCategoriesFormController', '`^/admin/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('DownloadCategoriesFormController', '`^/admin/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DownloadDeleteCategoryController', '`^/admin/categories/([0-9]+)/delete/?$`', array('id')),
	
	//Management
	new UrlControllerMapper('DownloadFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('DownloadFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DownloadDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('DownloadDisplayDownloadFileController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),
	
	//Keywords
	new UrlControllerMapper('DownloadDisplayDownloadFileTagController', '`^/tag/([a-z0-9-_]+)?/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('tag', 'field', 'sort', 'page')),
	
	new UrlControllerMapper('DownloadDisplayPendingDownloadFilesController', '`^/pending(?:/([a-z_]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),
	
	new UrlControllerMapper('DownloadFileController', '`^/download/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('DownloadDeadLinkController', '`^/dead_link/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('DownloadDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'field', 'sort', 'page', 'subcategories_page'))
);
DispatchManager::dispatch($url_controller_mappers);
?>
