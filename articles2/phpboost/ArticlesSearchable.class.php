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

class ArticlesSearchable extends AbstractSearchableExtensionPoint
{
	private $sql_querier;

        public function __construct()
        {
            $this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_search_request($args)
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);

		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$categories = ArticlesService::get_categories_manager()->get_childrens(Category::ROOT_CATEGORY, $search_category_children_options);
		$ids_categories = array_keys($categories);
		
		$where = !empty($ids_categories) ? " AND id_category IN(" . implode(", ", $ids_categories) . ")" : " AND id_category = '0'";

		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
                        articles.id AS id_content,
                        articles.title AS title,
                        articles.rewrited_title,
                        cat.rewrited_name,
                        (2 * FT_SEARCH_RELEVANCE(articles.title, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(articles.contents, '" . $args['search'] . "') +
                        FT_SEARCH_RELEVANCE(articles.description, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance, "
                        . $this->sql_querier->concat("'" . PATH_TO_ROOT . "/articles/articles.php?id='","articles.id") . " AS link
                        FROM " . ArticlesSetup::$articles_table . " articles
                        LEFT JOIN ". ArticlesSetup::$articles_cats_table ." cat ON articles.id_category = cat.id
                        WHERE (FT_SEARCH(articles.title, '" . $args['search'] . "') OR FT_SEARCH(articles.contents, '" . $args['search'] . "') OR
                        FT_SEARCH_RELEVANCE(articles.description, '" . $args['search'] . "') )
                        AND articles.publishing_state = 1 OR (articles.publishing_state = 2 AND n.publishing_start_date < '" . $now->get_timestamp() . 
                        "' AND (publishing_end_date > '" . $now->get_timestamp() . "' OR publishing_end_date = 0))
                        " . $where . " ORDER BY relevance DESC " . $this->sql_querier->limit(0, 100);
	}
}
?>