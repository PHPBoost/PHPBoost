<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 11 23
 * @since   	PHPBoost 4.0 - 2014 08 24
*/

class DownloadSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$now = new Date();
		$authorized_categories = DownloadService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
			d.id AS id_content,
			d.name AS title,
			( 2 * FT_SEARCH_RELEVANCE(d.name, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(d.contents, '" . $args['search'] . "') +
			FT_SEARCH_RELEVANCE(d.short_contents, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/download/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', d.id, '-', d.rewrited_name) AS link
			FROM " . DownloadSetup::$download_table . " d
			LEFT JOIN ". DownloadSetup::$download_cats_table ." cat ON d.id_category = cat.id
			LEFT JOIN ". DB_TABLE_KEYWORDS_RELATIONS ." relation ON relation.module_id = 'download' AND relation.id_in_module = d.id
			LEFT JOIN ". DB_TABLE_KEYWORDS ." keyword ON keyword.id = relation.id_keyword
			WHERE ( FT_SEARCH(d.name, '" . $args['search'] . "') OR FT_SEARCH(d.contents, '" . $args['search'] . "') OR FT_SEARCH_RELEVANCE(d.short_contents, '" . $args['search'] . "') ) OR keyword.rewrited_name = '" . Url::encode_rewrite($args['search']) . "'
			AND id_category IN (" . implode(", ", $authorized_categories) . ")
			AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < '" . $now->get_timestamp() . "' AND (end_date > '" . $now->get_timestamp() . "' OR end_date = 0)))
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
