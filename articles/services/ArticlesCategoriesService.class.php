<?php
/*##################################################
 *                        ArticlesCategoriesService.class.php
 *                            -------------------
 *   begin                : April 26, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class ArticlesCategoriesService
{
    public static function add(ArticlesCategory $category)
	{
		
	}
	
	public static function update(ArticlesCategory $category)
	{
		
	}

	public static function delete($id_category)
	{
		
	}
	
	public static function change_position($id_category, $position)
	{
	
	}
	
	public static function move($current_category_id, $moved_category_id)
	{
	
	}
	
	public static function change_publishing_state($id_category, $published)
	{
	
	}
	
	public static function get_categories()
	{
		return array();
	}
	
	public static function get_category($id_category)
	{
		$columns = array('*');
		$condition = "WHERE id = '". $id_category ."'";
		$row = PersistenceContext::get_querier()->select_single_row(ArticlesSetup::$articles_categories_table, $columns, $condition);
		
		$category = new ArticlesCategory();
		
		$category->set_name($row['name']);
		$category->set_id_parent($row['id_parent']);
		$category->set_order($row['c_order']);
		$category->set_description($row['description']);
		$category->set_picture_path($row['picture_path']);
		$category->set_disable_comments_system($row['comments_disabled']);
		$category->set_disable_comments_system($row['comments_disabled']);
		$category->set_publishing_state($row['published']);
		$category->set_authorizations($row['authorizations']);
		
		return $category;
	}
	
	public static function number_articles_contained($id_category)
	{
		return PersistenceContext::get_querier()->count(ArticlesSetup::$articles_table, "WHERE id_cat = :id_category", array('id_category' => $id_category));
	}
	public static function exists($id_category)
	{
		return PersistenceContext::get_querier()->count(ArticlesSetup::$articles_categories_table, "WHERE id = '". $id_category ."'");
	}
	
	public static function get_feed_list()
	{
		
	}
}
?>