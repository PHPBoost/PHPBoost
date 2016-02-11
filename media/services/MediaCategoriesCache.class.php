<?php
/*##################################################
 *                               MediaCategoriesCache.class.php
 *                            -------------------
 *   begin                : February 4, 2015
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

class MediaCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return MediaSetup::$media_cats_table;
	}
	
	public function get_category_class()
	{
		return 'MediaCategory';
	}
	
	public function get_module_identifier()
	{
		return 'media';
	}
	
	protected function get_category_elements_number($id_category)
	{
		require_once(PATH_TO_ROOT . '/media/media_constant.php');
		
		return MediaService::count('WHERE idcat = :id_category AND infos = :status',
			array(
				'id_category' => $id_category,
				'status' => MEDIA_STATUS_APROBED
			)
		);
	}
	
	public function get_root_category()
	{
		$config = MediaConfig::load();
		
		$root = new MediaCategory();
		$root->set_id(Category::ROOT_CATEGORY);
		$root->set_id_parent(Category::ROOT_CATEGORY);
		$root->set_name(LangLoader::get_message('root', 'main'));
		$root->set_rewrited_name('root');
		$root->set_order(0);
		$root->set_authorizations($config->get_authorizations());
		$root->set_description($config->get_root_category_description());
		$root->set_content_type($config->get_root_category_content_type());
		return $root;
	}
}
?>
