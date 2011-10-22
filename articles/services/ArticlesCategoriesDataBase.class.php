<?php
/*##################################################
 *                        ArticlesCategoriesDataBase.class.php
 *                            -------------------
 *   begin                : October 21m 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
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

class ArticlesCategoriesDataBase
{
	public static $db_querier;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function add(ArticlesCategory $category)
	{
		self::$db_querier->insert(ArticlesSetup::$articles_categories_table, array(
			'id_parent' => $category->get_id_parent(),
			'c_order' => $category->get_order(),
			'name' => $category->get_name(),
			'description' => $category->get_description(),
			'picture_path' => $category->get_picture_path(),	
			'notation_disable' => $category->notation_system_is_disabled(),
			'comments_disable' => $category->comments_system_is_disabled(),
			'published' => $category->get_publishing_state(),
			'authorizations' => serialize($category->get_authorizations())
		));
	}
	
	public static function update(ArticlesCategory $category)
	{
		$columns = array(
			'id_parent' => $category->get_id_parent(),
			'c_order' => $category->get_order(),
			'name' => $category->get_name(),
			'description' => $category->get_description(),
			'picture_path' => $category->get_picture_path(),	
			'notation_disable' => $category->notation_system_is_disabled(),
			'comments_disable' => $category->comments_system_is_disabled(),
			'published' => $category->get_publishing_state(),
			'authorizations' => serialize($category->get_authorizations())
		);
		self::$db_querier->update(ArticlesSetup::$articles_categories_table, $columns, 'WHERE id = :id', array('id' => $category->get_id()));
	}
	
	public static function delete($id_category)
	{
		self::$db_querier->delete(ArticlesSetup::$articles_categories_table, 'WHERE id = id', array('id' => $id_category));
	}
}

?>