<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : October 14, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('ArticlesAdminConfigController', '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('ArticlesAdminCategoriesManagementController', '`^/admin/categories(?:/management)?/?$`'),
	new UrlControllerMapper('ArticlesAdminAddCategoryController', '`^/admin/categories/add?/?$`'),
	new UrlControllerMapper('ArticlesAdminEditCategoryController', '`^/admin/categories/edit(?:/([A-Za-z0-9-]+))?/?$`', array('rewrited_name')),
	new UrlControllerMapper('ArticlesAdminDeleteCategoryController', '`^/admin/categories/delete(?:/([A-Za-z0-9-]+))?/?$`', array('rewrited_name')),
	
	new UrlControllerMapper('ArticlesExploreCategoryController', '`^/([A-Za-z0-9-]+)?/?$`', array('rewrited_name')),
	
	new UrlControllerMapper('ArticlesAddArticleController', '`^/article/add?/?$`'),
	new UrlControllerMapper('ArticlesDeleteArticleController', '`^/article/delete/([A-Za-z0-9-]+)?/?$`', array('rewrited_title')),
	new UrlControllerMapper('ArticlesEditArticleController', '`^/article/edit/([A-Za-z0-9-]+)?/?$`', array('rewrited_title')),
	new UrlControllerMapper('ArticlesViewArticleController', '`^/article/([A-Za-z0-9-]+)?/?$`', array('rewrited_title')),
	new UrlControllerMapper('ArticlesPrintArticleController', '`^/print/([A-Za-z0-9-]+)?/?$`', array('rewrited_title')),
	
	new UrlControllerMapper('ArticlesPendingController', '`^/pending(?:/([A-Za-z0-9-]+))?/?$`', array('pseudo')),

	new UrlControllerMapper('ArticlesHomePageController', '`^/?$`'),
);

DispatchManager::dispatch($url_controller_mappers);

?>