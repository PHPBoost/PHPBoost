<?php
/*##################################################
 *                               GalleryCategoriesCache.class.php
 *                            -------------------
 *   begin                : February 10, 2015
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

class GalleryCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return GallerySetup::$gallery_cats_table;
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
		$pics_aprob = GalleryService::count('WHERE idcat = :id_category AND aprob = 1', array('id_category' => $id_category));
		$pics_unaprob = GalleryService::count('WHERE idcat = :id_category AND aprob = 0', array('id_category' => $id_category));
		
		return array(
			'pics_aprob' => $pics_aprob,
			'pics_unaprob' => $pics_unaprob
		);
	}
	
	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(GalleryConfig::load()->get_authorizations());
		return $root;
	}
}
?>
