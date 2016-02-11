<?php
/*##################################################
 *		             CalendarSearchable.class.php
 *                            -------------------
 *   begin                : February 25, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
