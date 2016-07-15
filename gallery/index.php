<?php
/*##################################################
 *                               index.php
 *                            -------------------
 *   begin                : February 12, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
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
	//new UrlControllerMapper('AdminGalleryConfigController', '`^/admin(?:/config)?/?$`'),
	
	//Categories
	new UrlControllerMapper('GalleryCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('GalleryCategoriesFormController', '`^/categories/add/?$`'),
	new UrlControllerMapper('GalleryCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('GalleryDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),
	
	new UrlControllerMapper('GalleryDisplayCategoryController', '`^/?$`'),
);
DispatchManager::dispatch($url_controller_mappers);
?>
