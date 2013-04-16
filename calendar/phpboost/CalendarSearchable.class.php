<?php
/*##################################################
 *		             CalendarSearchable.class.php
 *                            -------------------
 *   begin                : February 25, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
	private $sql_querier;

	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_search_request($args)
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);

		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$categories = CalendarService::get_categories_manager()->get_childrens(Category::ROOT_CATEGORY, $search_category_children_options);
		$ids_categories = array_keys($categories);
		
		$where = !empty($ids_categories) ? " AND id_category IN(" . implode(", ", $ids_categories) . ")" : " AND id_category = '0'";

		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
		c.id AS id_content,
		c.title,
		cat.rewrited_name,
		( 2 * FT_SEARCH_RELEVANCE(c.title, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(c.contents, '" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance, "
		. $this->sql_querier->concat("'" . PATH_TO_ROOT . "/calendar/index.php?url=/event/'","c.id") . " AS link
		FROM " . CalendarSetup::$calendar_table . " c
		LEFT JOIN ". CalendarSetup::$calendar_cats_table ." cat ON c.id_category = cat.id
		WHERE ( FT_SEARCH(c.title, '" . $args['search'] . "') OR FT_SEARCH(c.contents, '" . $args['search'] . "') )
		AND c.start_date < '" . $now->get_timestamp() . "' AND c.end_date > '" . $now->get_timestamp() . "'
		" . $where . "
		ORDER BY relevance DESC " . $this->sql_querier->limit(0, 100);
	}
}
?>