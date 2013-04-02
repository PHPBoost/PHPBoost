<?php
/*##################################################
 *                        ArticlesService.class.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
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

class ArticlesService
{
	private static $db_querier;
	private static $categories_manager;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function add(Articles $article)
	{
		$result = self::$db_querier->insert(ArticlesSetup::$articles_table, $article->get_properties());
		return $result->get_last_inserted_id();
	}
	
	public static function update(Articles $article)
	{
		self::$db_querier->update(ArticlesSetup::$articles_table, $article->get_properties(), 'WHERE id=:id', array('id', $article->get_id()));
	}
	
	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(ArticlesSetup::$articles_table, $condition, $parameters);
	}
	
	public static function get_article($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row(ArticlesSetup::$articles_table, array('*'), $condition, $parameters);
		$article = new Articles();
		$article->set_properties($row);
		return $article;
	}
	
	public static function get_categories_manager()
	{
		if (self::$categories_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(ArticlesSetup::$articles_table);
			self::$categories_manager = new CategoriesManager(ArticlesCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}
}
?>