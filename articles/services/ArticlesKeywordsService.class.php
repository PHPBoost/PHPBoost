<?php
/*##################################################
 *                        ArticlesKeywordsService.class.php
 *                            -------------------
 *   begin                : March 05, 2013
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

/**
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class ArticlesKeywordsService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	public static function add(ArticlesKeywords $keyword, $id_article)
	{
		$result = self::$db_querier->insert(ArticlesSetup::$articles_keywords_table, $keyword->get_properties());
		self::add_relation($result->get_last_inserted_id(), $id_article);
	}

	public static function add_relation($id_keyword, $id_article)
	{
		self::$db_querier->insert(ArticlesSetup::$articles_keywords_relation_table, array(
			'id_article' => $id_article,
			'id_keyword' => $id_keyword
		));
	}

	public static function delete_all_keywords_relation($id_article)
	{
		self::$db_querier->delete(ArticlesSetup::$articles_keywords_relation_table, 'WHERE id_article=:id_article', array(
			'id_article' => $id_article
		));
	}
	
	public static function delete_single_keyword_relation($id_article, $id_keyword)
	{
		self::$db_querier->delete(ArticlesSetup::$articles_keywords_relation_table, 'WHERE id_article=:id_article AND id_keyword=:id_keyword', array(
			'id_article' => $id_article,
			'id_keyword' => $id_keyword
		));
	}

	public static function get_keywords()
	{
		$keywords = array();
		$result = self::$db_querier->select_rows(ArticlesSetup::$articles_keywords_table, array('*'));
		
		while ($rows = $result->fetch())
		{
			$keywords['id'] = $rows['id'];
			$keywords['name'] = $rows['name'];
		}
		
		return $keywords;
	}
	
	public static function exists($name)
	{
		return self::$db_querier->row_exists(ArticlesSetup::$articles_keywords_table, 'WHERE rewrited_name=:rewrited_name', array('rewrited_name' => Url::encode_rewrite($name)));
	}

	public static function get_id_keyword($condition, array $parameters)
	{
		$id = self::$db_querier->get_column_value(ArticlesSetup::$articles_keywords_table, 'id', $condition, $parameters);
		return $id;
	}
	
	public static function get_article_keywords($id_article)
	{	
		$result = self::$db_querier->select('SELECT keywords.id, keywords.name, keywords.rewrited_name FROM ' 
			. ArticlesSetup::$articles_keywords_table . ' keywords LEFT JOIN '. ArticlesSetup::$articles_keywords_relation_table . 
			' relation ON keywords.id = relation.id_keyword WHERE relation.id_article=:id_article', 
			array(
				'id_article' => $id_article
			), SelectQueryResult::FETCH_ASSOC
		);
		
                return $result;
	}
	
	public static function get_article_keywords_name($id_article)
	{
		$result = self::get_article_keywords($id_article);
		
		$article_keywords = array();
		while ($rows = $result->fetch())	
		{		
			$article_keywords['name'] = $rows['name'];		
		}		
		return $article_keywords;
	}
}
?>