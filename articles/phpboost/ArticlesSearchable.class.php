<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2019 02 13
 * @since   	PHPBoost 4.0 - 2013 03 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$now = new Date();
		$authorized_categories = ArticlesService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
			articles.id AS id_content,
			articles.title AS title,
			(2 * FT_SEARCH_RELEVANCE(articles.title, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(articles.contents, '" . $args['search'] . "') +
			FT_SEARCH_RELEVANCE(articles.description, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/articles/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', articles.id, '-', articles.rewrited_title) AS link
			FROM " . ArticlesSetup::$articles_table . " articles
			LEFT JOIN ". ArticlesSetup::$articles_cats_table ." cat ON cat.id = articles.id_category
			LEFT JOIN ". DB_TABLE_KEYWORDS_RELATIONS ." relation ON relation.module_id = 'articles' AND relation.id_in_module = articles.id
			LEFT JOIN ". DB_TABLE_KEYWORDS ." keyword ON keyword.id = relation.id_keyword
			WHERE ( FT_SEARCH(articles.title, '" . $args['search'] . "') OR FT_SEARCH(articles.contents, '" . $args['search'] . "') OR FT_SEARCH_RELEVANCE(articles.description, '" . $args['search'] . "') ) OR keyword.rewrited_name = '" . Url::encode_rewrite($args['search']) . "'
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			AND (published = 1 OR (published = 2 AND publishing_start_date < '" . $now->get_timestamp() . "' AND (publishing_end_date > '" . $now->get_timestamp() . "' OR publishing_end_date = 0)))
			GROUP BY id_content
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
