<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 11
 * @since   	PHPBoost 4.0 - 2013 02 25
*/

class CalendarSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$authorized_categories = CalendarService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
			id_event AS id_content,
			title,
			( 2 * FT_SEARCH_RELEVANCE(title, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(contents, '" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/calendar/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', id_event, '-', event_content.rewrited_title) AS link
			FROM " . CalendarSetup::$calendar_events_table . " event
			LEFT JOIN " . CalendarSetup::$calendar_events_content_table . " event_content ON event_content.id = event.content_id
			LEFT JOIN ". CalendarSetup::$calendar_cats_table ." cat ON id_category = cat.id
			WHERE ( FT_SEARCH(title, '" . $args['search'] . "') OR FT_SEARCH(contents, '" . $args['search'] . "') )
			AND event_content.approved = 1
			AND id_category IN (" . implode(", ", $authorized_categories) . ")
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
