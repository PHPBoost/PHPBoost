<?php
/*##################################################
 *                               FaqSearchable.class.php
 *                            -------------------
 *   begin                : September 2, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
