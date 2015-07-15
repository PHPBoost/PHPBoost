<?php
/*##################################################
 *                              gallery_auth.php
 *                            -------------------
 *   begin                : October 18, 2007
 *   copyright            : (C) 2007 Viarre régis
 *   email                : crowkait@phpboost.com
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

if (defined('PHPBOOST') !== true)
	exit;

load_module_lang('gallery'); //Chargement de la langue du module.

//Création de l'arborescence des catégories.
$module_title = LangLoader::get_message('module_title', 'common', 'gallery');
$Bread_crumb->add($module_title, GalleryUrlBuilder::home());

$id_category = AppContext::get_request()->get_getint('cat', 0);
if (!empty($id_category))
{
	try {
		$category = GalleryService::get_categories_manager()->get_categories_cache()->get_category($id_category);
	} catch (CategoryNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
else
{
	$category = GalleryService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
}

$parent_categories = array_reverse(GalleryService::get_categories_manager()->get_parents($id_category));
foreach ($parent_categories as $cat)
{
	if ($cat->get_id() != Category::ROOT_CATEGORY)
		$Bread_crumb->add($cat->get_name(), url('gallery.php?cat=' . $cat->get_id(), 'gallery-' . $cat->get_id() . '.php'));
}

define('TITLE', $module_title . ($category->get_id() != Category::ROOT_CATEGORY ? ' - ' . $category->get_name() : ''));
if ($category->get_id() != Category::ROOT_CATEGORY)
	$Bread_crumb->add($category->get_name(), url('gallery.php?cat=' . $category->get_id(), 'gallery-' . $category->get_id() . '.php'));
?>
