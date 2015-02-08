<?php

/*##################################################
 *                              MediaSearchable.class.php
 *                            -------------------
 *   begin                : May 29, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class MediaSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$authorized_categories = MediaService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		
		$request = "SELECT " . $args['id_search'] . " AS id_search,
			f.id AS id_content,
			f.name AS title,
			( 2 * FT_SEARCH_RELEVANCE(f.name, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(f.contents, '" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/media/media.php?id=', f.id, '&amp;cat=', f.idcat) AS link
			FROM " . PREFIX . "media f
			WHERE ( FT_SEARCH(f.name, '" . $args['search'] . "') OR FT_SEARCH(f.contents, '" . $args['search'] . "') )
			AND idcat IN (" . implode(", ", $authorized_categories) . ")
			ORDER BY relevance DESC
			LIMIT " . MEDIA_MAX_SEARCH_RESULTS . " OFFSET 0";
		
		return $request;
	}
}
?>