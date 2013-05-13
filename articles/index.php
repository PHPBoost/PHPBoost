<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : March 04, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

/**
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminArticlesConfigController', '`^/admin(?:/config)?/?$`'),

	//Manage categories
	new UrlControllerMapper('ArticlesCategoriesManageController', '`^/admin/categories/?$`'),
	new UrlControllerMapper('ArticlesCategoriesFormController', '`^/admin/categories/add/?$`'),
	new UrlControllerMapper('ArticlesCategoriesFormController', '`^/admin/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('ArticlesDeleteCategoryController', '`^/admin/categories/([0-9]+)/delete/?$`', array('id')),

	//Manage articles
	new UrlControllerMapper('ArticlesFormController', '`^/add/?$`'),
	new UrlControllerMapper('ArticlesFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('ArticlesDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),

	//new UrlControllerMapper('ArticlesViewArticleController', '`^/article/([a-z0-9-]+)?/?([a-z0-9-]+)?/?$`', array('rewrited_title', 'rewrited_page_name')),

	//new UrlControllerMapper('ArticlesPrintArticleController', '`^/print/([a-z0-9-]+)?/?([A-Za-z0-9-]+)?/?$`', array('rewrited_title', 'rewrited_page_name')),
	//Display articles and categories
	new UrlControllerMapper('ArticlesDisplayPendingArticlesController', '`^/pending/?$`'),
	new UrlControllerMapper('ArticlesDisplayArticlesController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_title')),
	new UrlControllerMapper('ArticlesDisplayCategoryController', '`^/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id', 'rewrited_name')),
	new UrlControllerMapper('ArticlesAjaxTagsAutoCompleteController','`^/ajax/tag/?$`'),
    
	//Home page
	new UrlControllerMapper('ArticlesHomeController', '`^(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page'))
);

DispatchManager::dispatch($url_controller_mappers);

?>