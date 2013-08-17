<?php
/*##################################################
 *		                         NewsKeywordsService.class.php
 *                            -------------------
 *   begin                : February 26, 2013
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
class NewsKeywordsService
{
	private static $db_querier;
	
	private static $categories_manager;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function add($name)
	{
		$result = self::$db_querier->insert(NewsSetup::$news_keywords_table, array('name' => $name, 'rewrited_name' => Url::encode_rewrite($name)));

		return $result->get_last_inserted_id();
	}

	public static function update($id, $name)
	{
		self::$db_querier->update(NewsSetup::$news_keywords_table, array('name' => $name, 'rewrited_name' => Url::encode_rewrite($name), 'WHERE id=:id', array('id' => $id)));
	}

	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(NewsSetup::$news_keywords_table, $condition, $parameters);
	}
	
	public static function delete_relations($id_news)
	{
		self::$db_querier->delete(NewsSetup::$news_keywords_relation_table, 'WHERE id_news=:id_news', array('id_news' => $id_news));
	}
	
	public static function exists($name)
	{
		return self::$db_querier->row_exists(NewsSetup::$news_keywords_table, 'WHERE rewrited_name=:rewrited_name', array('rewrited_name' => Url::encode_rewrite($name)));
	}

	public static function get_id_keyword($condition, array $parameters)
	{
		return self::$db_querier->get_column_value(NewsSetup::$news_keywords_table, 'id', $condition, $parameters);
	}
	
	public static function get_keywords($id_news)
	{
		$keywords = array();
		$result = PersistenceContext::get_querier()->select('SELECT relation.id_news, relation.id_keyword, keyword.*
			FROM '. NewsSetup::$news_keywords_relation_table .' relation
			LEFT JOIN '. NewsSetup::$news_keywords_table .' keyword ON keyword.id = relation.id_keyword
			WHERE relation.id_news = :id_news', array(
				'id_news' => $id_news
		));
		while ($row = $result->fetch())
		{
			$keyword = new NewsKeyword();
			$keyword->set_properties($row);
			$keywords[] = $keyword;
		}
		return $keywords;
	}
	
	public static function get_keywords_name($id_news)
	{
		$keywords = array();
		$result = PersistenceContext::get_querier()->select('SELECT relation.id_news, relation.id_keyword, keyword.name
			FROM '. NewsSetup::$news_keywords_relation_table .' relation
			LEFT JOIN '. NewsSetup::$news_keywords_table .' keyword ON keyword.id = relation.id_keyword
			WHERE relation.id_news = :id_news', array(
				'id_news' => $id_news
		));
		while ($row = $result->fetch())
		{
			$keywords[] = $row['name'];
		}
		return $keywords;
	}

	public static function put_relation($id_news, $id_keyword)
	{
		$result = self::$db_querier->insert(NewsSetup::$news_keywords_relation_table, array('id_news' => $id_news, 'id_keyword' => $id_keyword));

		return $result->get_last_inserted_id();
	}
}
?>