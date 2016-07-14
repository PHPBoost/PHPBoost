<?php
/*##################################################
 *                               index.php
 *                            -------------------
 *   begin                : September 2, 2014
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
	new UrlControllerMapper('AdminFaqConfigController', '`^/admin(?:/config)?/?$`'),
	
	//Categories
	new UrlControllerMapper('FaqCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('FaqCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('FaqCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('FaqDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),
	
	//Management
	new UrlControllerMapper('FaqManageController', '`^/manage/?$`'),
	new UrlControllerMapper('FaqFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('FaqFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('FaqDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('FaqAjaxDeleteQuestionController', '`^/ajax_delete/?$`'),
	
	new UrlControllerMapper('FaqDisplayPendingFaqQuestionsController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?$`', array('field', 'sort')),
	
	new UrlControllerMapper('FaqDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'subcategories_page')),
	
);
DispatchManager::dispatch($url_controller_mappers);
?>
