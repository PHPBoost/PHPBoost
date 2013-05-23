<?php
/*##################################################
 *		                         NewsService.class.php
 *                            -------------------
 *   begin                : February 13, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class NewsService
{
	private static $db_querier;
	
	private static $categories_manager;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function add(News $news)
	{
		$result = self::$db_querier->insert(NewsSetup::$news_table, $news->get_properties());

		return $result->get_last_inserted_id();
	}

	public static function update(News $news)
	{
		self::$db_querier->update(NewsSetup::$news_table, $news->get_properties(), 'WHERE id=:id', array('id' => $news->get_id()));
	}

	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(NewsSetup::$news_table, $condition, $parameters);
	}

	public static function get_news($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT news.*, member.*
		FROM ' . NewsSetup::$news_table . ' news 
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = news.author_user_id
		' . $condition, $parameters);
		$news = new News();
		$news->set_properties($row);
		return $news;
	}
	
	public static function get_categories_manager()
	{
		if (self::$categories_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(NewsSetup::$news_table);
			self::$categories_manager = new CategoriesManager(NewsCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}
}
?>