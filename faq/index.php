<?php
/*##################################################
 *                               index.php
 *                            -------------------
 *   begin                : September 2, 2014
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
	new UrlControllerMapper('AdminFaqConfigController', '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('AdminFaqManageController', '`^/admin/manage(?:/([a-z_-]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),
	
	//Categories
	new UrlControllerMapper('FaqCategoriesManageController', '`^/admin/categories/?$`'),
	new UrlControllerMapper('FaqCategoriesFormController', '`^/admin/categories/add/?$`'),
	new UrlControllerMapper('FaqCategoriesFormController', '`^/admin/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('FaqDeleteCategoryController', '`^/admin/categories/([0-9]+)/delete/?$`', array('id')),
	
	//Management
	new UrlControllerMapper('FaqFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('FaqFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('FaqDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	
	new UrlControllerMapper('FaqDisplayPendingFaqQuestionsController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?$`', array('field', 'sort')),
	
	new UrlControllerMapper('FaqDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?$`', array('id_category', 'rewrited_name')),
	
);
DispatchManager::dispatch($url_controller_mappers);
?>
