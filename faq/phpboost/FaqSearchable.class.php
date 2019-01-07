<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 11
 * @since   	PHPBoost 4.0 - 2014 09 02
*/

class FaqSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$authorized_categories = FaqService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
			f.id AS id_content,
			f.question AS title,
			( 2 * FT_SEARCH_RELEVANCE(f.question, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(f.answer, '" . $args['search'] . "' )) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/faq/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/#question', f.id) AS link
			FROM " . FaqSetup::$faq_table . " f
			LEFT JOIN ". FaqSetup::$faq_cats_table ." cat ON f.id_category = cat.id
			WHERE ( FT_SEARCH(f.question, '" . $args['search'] . "') OR FT_SEARCH(f.answer, '" . $args['search'] . "') )
			AND approved = 1
			AND id_category IN (" . implode(", ", $authorized_categories) . ")
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
