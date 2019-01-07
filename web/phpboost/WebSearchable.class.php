<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 11 23
 * @since   	PHPBoost 4.1 - 2014 08 21
*/

class WebSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$now = new Date();
		$authorized_categories = WebService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
			w.id AS id_content,
			w.name AS title,
			( 2 * FT_SEARCH_RELEVANCE(w.name, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(w.contents, '" . $args['search'] . "') +
			FT_SEARCH_RELEVANCE(w.short_contents, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/web/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', w.id, '-', w.rewrited_name) AS link
			FROM " . WebSetup::$web_table . " w
			LEFT JOIN ". WebSetup::$web_cats_table ." cat ON w.id_category = cat.id
			LEFT JOIN ". DB_TABLE_KEYWORDS_RELATIONS ." relation ON relation.module_id = 'web' AND relation.id_in_module = w.id
			LEFT JOIN ". DB_TABLE_KEYWORDS ." keyword ON keyword.id = relation.id_keyword
			WHERE ( FT_SEARCH(w.name, '" . $args['search'] . "') OR FT_SEARCH(w.contents, '" . $args['search'] . "') OR FT_SEARCH_RELEVANCE(w.short_contents, '" . $args['search'] . "') ) OR keyword.rewrited_name = '" . Url::encode_rewrite($args['search']) . "'
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < '" . $now->get_timestamp() . "' AND (end_date > '" . $now->get_timestamp() . "' OR end_date = 0)))
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
