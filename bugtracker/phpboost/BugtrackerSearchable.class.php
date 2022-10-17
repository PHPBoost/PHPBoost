<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 24
 * @since       PHPBoost 3.0 - 2012 04 27
*/

class BugtrackerSearchable extends AbstractSearchableExtensionPoint
{
	/**
	 * @method Return the search request.
	 * @param string[] $args Search arguments
	 */
	public function get_search_request($args)
	{
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		$search_id = '';
		$search_words = explode(' ', $args['search']);
		foreach ($search_words as $word)
		{
			$word = str_replace('#', '', $word);
			if (is_numeric($word))
				$search_id = $word;
		}

		return "SELECT " . $args['id_search'] . " AS id_search,
			id AS id_content,
			title,
			( 2 * FT_SEARCH_RELEVANCE(title, '" . $args['search'] . "' IN BOOLEAN MODE) + FT_SEARCH_RELEVANCE(content, '" . $args['search'] . "' IN BOOLEAN MODE) ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/bugtracker/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "detail/',id) AS link
			FROM " . BugtrackerSetup::$bugtracker_table . "
			WHERE ( FT_SEARCH(title, '" . $args['search'] . "*' IN BOOLEAN MODE) OR FT_SEARCH(content, '" . $args['search'] . "*' IN BOOLEAN MODE) ) " . ($search_id ? "OR id = '" . $search_id . "'" : "") . "
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
