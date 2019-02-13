<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 02 13
 * @since   	PHPBoost 4.0 - 2013 02 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
			LEFT JOIN ". DB_TABLE_KEYWORDS_RELATIONS ." relation ON relation.module_id = 'news' AND relation.id_in_module = n.id
			LEFT JOIN ". DB_TABLE_KEYWORDS ." keyword ON keyword.id = relation.id_keyword
			WHERE ( FT_SEARCH(n.name, '" . $args['search'] . "') OR FT_SEARCH(n.contents, '" . $args['search'] . "') OR FT_SEARCH_RELEVANCE(n.short_contents, '" . $args['search'] . "') ) OR keyword.rewrited_name = '" . Url::encode_rewrite($args['search']) . "'
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < '" . $now->get_timestamp() . "' AND (end_date > '" . $now->get_timestamp() . "' OR end_date = 0)))
			GROUP BY id_content
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
