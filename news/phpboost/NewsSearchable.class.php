<?php
/*##################################################
 *                              NewsSearchable.class.php
 *                            -------------------
 *   begin                : February 22, 2012
 *   copyright            : (C) 2013 K�vin MASSY
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
class NewsSearchable extends AbstractSearchableExtensionPoint
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
			n.id AS id_content,
			n.name AS title,
			n.rewrited_name,
			cat.rewrited_name,
			( 2 * FT_SEARCH_RELEVANCE(n.name, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(n.contents, '" . $args['search'] . "') +
			FT_SEARCH_RELEVANCE(n.short_contents, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/news/index.php?url=/', id_category, '-', cat.rewrited_name, '/', n.id, '-', n.rewrited_name) AS link
			FROM " . NewsSetup::$news_table . " n
			LEFT JOIN ". NewsSetup::$news_cats_table ." cat ON n.id_category = cat.id
			WHERE ( FT_SEARCH(n.name, '" . $args['search'] . "') OR FT_SEARCH(n.contents, '" . $args['search'] . "') OR
			FT_SEARCH_RELEVANCE(n.short_contents, '" . $args['search'] . "') )
			AND n.approbation_type = 1 OR (n.approbation_type = 2 AND n.start_date < '" . $timestamp . "' AND (end_date > '" . $timestamp . "' OR end_date = 0))
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			ORDER BY relevance DESC " . $this->sql_querier->limit(0, 100);
	}
}
?>