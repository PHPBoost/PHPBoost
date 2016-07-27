<?php
/*##################################################
 *                              BugtrackerSearchable.class.php
 *                            -------------------
 *   begin                : April 27, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
			( 2 * FT_SEARCH_RELEVANCE(title, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(contents, '" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/bugtracker/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "detail/',id) AS link
			FROM " . BugtrackerSetup::$bugtracker_table . "
			WHERE ( FT_SEARCH(title, '" . $args['search'] . "') OR FT_SEARCH(contents, '" . $args['search'] . "') ) " . ($search_id ? "OR id = '" . $search_id . "'" : "") . "
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
