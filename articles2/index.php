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
	new UrlControllerMapper('AdminArticlesConfigController', '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('ArticlesAdminCategoriesManagementController', '`^/admin/categories(?:/management)?/?$`'),
	new UrlControllerMapper('ArticlesAdminAddCategoryController', '`^/admin/categories/add?/?$`'),
	new UrlControllerMapper('ArticlesAdminEditCategoryController', '`^/admin/categories/edit(?:/([a-z0-9-]+))?/?$`', array('rewrited_name')),
	new UrlControllerMapper('ArticlesAdminDeleteCategoryController', '`^/admin/categories/delete(?:/([a-z0-9-]+))?/?$`', array('rewrited_name')),
	
	new UrlControllerMapper('ArticlesExploreCategoryController', '`^/category/([a-z0-9-]+)?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('rewrited_name', 'field', 'sort', 'page')),
	
	new UrlControllerMapper('ArticlesAddArticleController', '`^/article/add?/?$`'),
	new UrlControllerMapper('ArticlesDeleteArticleController', '`^/article/delete/([a-z0-9-]+)?/?$`', array('rewrited_title')),
	new UrlControllerMapper('ArticlesEditArticleController', '`^/article/edit/([a-z0-9-]+)?/?$`', array('rewrited_title')),
	new UrlControllerMapper('ArticlesViewArticleController', '`^/article/([a-z0-9-]+)?/?([a-z0-9-]+)?/?$`', array('rewrited_title', 'rewrited_page_name')),
	new UrlControllerMapper('ArticlesPrintArticleController', '`^/print/([a-z0-9-]+)?/?([A-Za-z0-9-]+)?/?$`', array('rewrited_title', 'rewrited_page_name')),
	
	new UrlControllerMapper('ArticlesPendingController', '`^/pending(?:/([a-z0-9-]+))?/?$`', array('pseudo')),

	new UrlControllerMapper('ArticlesHomePageController', '`^(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page'))
);

DispatchManager::dispatch($url_controller_mappers);

?>