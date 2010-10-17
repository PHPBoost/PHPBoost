<?php
/*##################################################
 *                              NewsSearchable.class.php
 *                            -------------------
 *   begin                : May, 29 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class NewsSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);

		$news_cat = new NewsCats();

		// Build array with the children categories.
		$array_cat = array();
		$news_cat->build_children_id_list(0, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, AUTH_NEWS_READ);
		$where = !empty($array_cat) ? " AND idcat IN(" . implode(", ", $array_cat) . ")" : " AND idcat = '0'";

		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		$request = "SELECT " . $args['id_search'] . " AS id_search,
            n.id AS id_content,
            n.title AS title,
            ( 2 * FT_SEARCH_RELEVANCE(n.title, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(n.contents, '" . $args['search'] . "') +
            FT_SEARCH_RELEVANCE(n.extend_contents, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance, "
            . $this->sql_querier->concat("'" . PATH_TO_ROOT . "/news/news.php?id='","n.id") . " AS link
            FROM " . DB_TABLE_NEWS . " n
            WHERE ( FT_SEARCH(n.title, '" . $args['search'] . "') OR FT_SEARCH(n.contents, '" . $args['search'] . "') OR
            FT_SEARCH_RELEVANCE(n.extend_contents, '" . $args['search'] . "') )
                AND n.start <= '" . $now->get_timestamp() . "' AND n.visible = 1" . $where . "
            ORDER BY relevance DESC " . $this->sql_querier->limit(0, NEWS_MAX_SEARCH_RESULTS);

            return $request;
	}
}

?>