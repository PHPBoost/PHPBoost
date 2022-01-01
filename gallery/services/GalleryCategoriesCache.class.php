<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 29
 * @since       PHPBoost 4.1 - 2015 02 10
*/

class GalleryCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return GallerySetup::$gallery_cats_table;
	}

	public function get_table_name_containing_items()
	{
		return GallerySetup::$gallery_table;
	}

	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_module_identifier()
	{
		return 'gallery';
	}

	protected function get_category_elements_number($id_category)
	{
		$pics_aprob = GalleryService::count('WHERE id_category = :id_category AND aprob = 1', array('id_category' => $id_category));
		$pics_unaprob = GalleryService::count('WHERE id_category = :id_category AND aprob = 0', array('id_category' => $id_category));

		return array(
			'pics_aprob' => $pics_aprob,
			'pics_unaprob' => $pics_unaprob
		);
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(GalleryConfig::load()->get_authorizations());
		$root->set_description(
			StringVars::replace_vars(LangLoader::get_message('gallery.seo.description.root', 'common', 'gallery'),
			array('site' => GeneralConfig::load()->get_site_name()
		)));
		return $root;
	}
}
?>
