<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 13
 * @since       PHPBoost 3.0 - 2010 05 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MediaSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, 'media');
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		$request = "SELECT " . $args['id_search'] . " AS id_search,
			f.id AS id_content,
			f.title AS title,
			( 2 * FT_SEARCH_RELEVANCE(f.title, '" . $args['search'] . "' IN BOOLEAN MODE) + FT_SEARCH_RELEVANCE(f.content, '" . $args['search'] . "' IN BOOLEAN MODE) ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/media/media.php?id=', f.id, '&amp;cat=', f.id_category) AS link
			FROM " . PREFIX . "media f
			WHERE ( FT_SEARCH(f.title, '" . $args['search'] . "*' IN BOOLEAN MODE) OR FT_SEARCH(f.content, '" . $args['search'] . "*' IN BOOLEAN MODE) )
			AND id_category IN (" . implode(", ", $authorized_categories) . ")
			ORDER BY relevance DESC
			LIMIT " . MEDIA_MAX_SEARCH_RESULTS . " OFFSET 0";

		return $request;
	}
}
?>
