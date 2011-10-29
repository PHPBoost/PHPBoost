<?php
/*##################################################
 *                        ArticlesCategoriesService.class.php
 *                            -------------------
 *   begin                : April 26, 2011
 *   copyright            : (C) 2011 K�vin MASSY
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
		return $row;
	}
	
	public static function category_exist($id_category)
	{
		return PersistenceContext::get_querier()->count(ArticlesSetup::$articles_categories_table, "WHERE id = '". $id_category ."'");
	}
	
	public static function get_feed_list()
	{
		
	}
}
?>