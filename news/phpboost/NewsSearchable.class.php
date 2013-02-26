<?php
/*##################################################
 *                              NewsSearchable.class.php
 *                            -------------------
 *   begin                : February 22, 2012
 *   copyright            : (C) 2013 Kévin MASSY
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

class NewsSearchable extends AbstractSearchableExtensionPoint
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
		$search_category_children_options->add_authorisations_bits(Category::READ_AUTHORIZATIONS);
		$categories = NewsService::get_categories_manager()->get_childrens(Category::ROOT_CATEGORY, $search_category_children_options);
		$ids_categories = array_keys($categories);
		
		$where = !empty($ids_categories) ? " AND id_category IN(" . implode(", ", $ids_categories) . ")" : " AND id_category = '0'";

		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
            n.id AS id_content,
            n.name AS title,
            n.rewrited_name,
            cat.rewrited_name,
            ( 2 * FT_SEARCH_RELEVANCE(n.name, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(n.contents, '" . $args['search'] . "') +
            FT_SEARCH_RELEVANCE(n.short_contents, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance, "
            . $this->sql_querier->concat("'" . PATH_TO_ROOT . "/news/news.php?id='","n.id") . " AS link
            FROM " . NewsSetup::$news_table . " n
            LEFT JOIN ". NewsSetup::$news_cats_table ." cat ON n.id_category = cat.id
            WHERE ( FT_SEARCH(n.name, '" . $args['search'] . "') OR FT_SEARCH(n.contents, '" . $args['search'] . "') OR
            FT_SEARCH_RELEVANCE(n.short_contents, '" . $args['search'] . "') )
                AND n.approbation_type = 1 OR (n.approbation_type = 2 AND n.start_date < '" . $now->get_timestamp() . "' AND (end_date > '" . $now->get_timestamp() . "' OR end_date = 0))
			" . $where . "
            ORDER BY relevance DESC " . $this->sql_querier->limit(0, 100);
	}
}
?>