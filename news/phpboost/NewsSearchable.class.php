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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class NewsSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$now = new Date();
		$authorized_categories = NewsService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		
		return "SELECT " . $args['id_search'] . " AS id_search,
			n.id AS id_content,
			n.name AS title,
			( 2 * FT_SEARCH_RELEVANCE(n.name, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(n.contents, '" . $args['search'] . "') +
			FT_SEARCH_RELEVANCE(n.short_contents, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/news/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', n.id, '-', n.rewrited_name) AS link
			FROM " . NewsSetup::$news_table . " n
			LEFT JOIN ". NewsSetup::$news_cats_table ." cat ON n.id_category = cat.id
			WHERE ( FT_SEARCH(n.name, '" . $args['search'] . "') OR FT_SEARCH(n.contents, '" . $args['search'] . "') OR FT_SEARCH_RELEVANCE(n.short_contents, '" . $args['search'] . "') )
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < '" . $now->get_timestamp() . "' AND (end_date > '" . $now->get_timestamp() . "' OR end_date = 0)))
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
