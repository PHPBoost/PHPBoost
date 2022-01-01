<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 01
 * @since       PHPBoost 1.6 - 2007 10 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
	exit;

//Création de l'arborescence des catégories.
$module_title = LangLoader::get_message('gallery.module.title', 'common', 'gallery');
$Bread_crumb->add($module_title, GalleryUrlBuilder::home());

$id_category = AppContext::get_request()->get_getint('cat', 0);
if (!empty($id_category))
{
	try {
		$category = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_category($id_category);
	} catch (CategoryNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
else
{
	$category = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
}

$parent_categories = array_reverse(CategoriesService::get_categories_manager('gallery')->get_parents($id_category));
foreach ($parent_categories as $cat)
{
	if ($cat->get_id() != Category::ROOT_CATEGORY)
		$Bread_crumb->add($cat->get_name(), url('gallery.php?cat=' . $cat->get_id(), 'gallery-' . $cat->get_id() . '.php'));
}

define('TITLE', $module_title . ($category->get_id() != Category::ROOT_CATEGORY ? ' - ' . $category->get_name() : ''));
if ($category->get_id() != Category::ROOT_CATEGORY)
	$Bread_crumb->add($category->get_name(), url('gallery.php?cat=' . $category->get_id(), 'gallery-' . $category->get_id() . '.php'));
?>
