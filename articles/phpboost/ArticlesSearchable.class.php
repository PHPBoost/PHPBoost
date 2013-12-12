<?php
/*##################################################
 *                      ArticlesSearchable.class.php
 *                            -------------------
 *   begin                : March 27, 2013
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
class ArticlesSearchable extends AbstractSearchableExtensionPoint
{
	private $sql_querier;
	
	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_search_request($args)
	{
		$now = new Date();
		$timestamp = $now->get_timestamp();
		$authorized_categories = NewsService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		
		return "SELECT " . $args['id_search'] . " AS id_search,
			articles.id AS id_content,
			articles.title AS title,
			articles.rewrited_title,
			cat.rewrited_name,
			(2 * FT_SEARCH_RELEVANCE(articles.title, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(articles.contents, '" . $args['search'] . "') +
			FT_SEARCH_RELEVANCE(articles.description, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/articles/index.php?url=/', id_category, '-', cat.rewrited_name, '/', articles.id, '-', articles.rewrited_title) AS link
			FROM " . ArticlesSetup::$articles_table . " articles
			LEFT JOIN ". ArticlesSetup::$articles_cats_table ." cat ON articles.id_category = cat.id
			WHERE (FT_SEARCH(articles.title, '" . $args['search'] . "') OR FT_SEARCH(articles.contents, '" . $args['search'] . "') OR
			FT_SEARCH_RELEVANCE(articles.description, '" . $args['search'] . "') )
			AND articles.published = 1 OR (articles.published = 2 AND publishing_start_date < '" . $timestamp . 
			"' AND (publishing_end_date > '" . $timestamp . "' OR publishing_end_date = 0))
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			 ORDER BY relevance DESC " . $this->sql_querier->limit(0, 100);
	}
}
?>