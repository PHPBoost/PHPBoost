<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 07
 * @since       PHPBoost 3.0 - 2010 05 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class MediaSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, 'media', 'idcat');
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		$request = "SELECT " . $args['id_search'] . " AS id_search,
			f.id AS id_content,
			f.name AS title,
			( 2 * FT_SEARCH_RELEVANCE(f.name, '" . $args['search'] . "' IN BOOLEAN MODE) + FT_SEARCH_RELEVANCE(f.contents, '" . $args['search'] . "' IN BOOLEAN MODE) ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/media/media.php?id=', f.id, '&amp;cat=', f.idcat) AS link
			FROM " . PREFIX . "media f
			WHERE ( FT_SEARCH(f.name, '" . $args['search'] . "*' IN BOOLEAN MODE) OR FT_SEARCH(f.contents, '" . $args['search'] . "*' IN BOOLEAN MODE) )
			AND idcat IN (" . implode(", ", $authorized_categories) . ")
			ORDER BY relevance DESC
			LIMIT " . MEDIA_MAX_SEARCH_RESULTS . " OFFSET 0";

		return $request;
	}
}
?>
