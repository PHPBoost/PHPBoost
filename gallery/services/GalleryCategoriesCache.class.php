<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 05 06
 * @since       PHPBoost 4.1 - 2015 02 10
*/

class GalleryCategoriesCache extends DefaultRichCategoriesCache
{
	protected function get_category_elements_number($id_category)
	{
		$pics_aprob = GalleryService::count('WHERE id_category = :id_category AND aprob = 1', array('id_category' => $id_category));
		$pics_unaprob = GalleryService::count('WHERE id_category = :id_category AND aprob = 0', array('id_category' => $id_category));

		return array(
			'pics_aprob' => $pics_aprob,
			'pics_unaprob' => $pics_unaprob
		);
	}

	protected function get_root_category_description()
	{
		return StringVars::replace_vars(LangLoader::get_message('gallery.seo.description.root', 'common', 'gallery'), array('site' => GeneralConfig::load()->get_site_name()));
	}
}
?>
