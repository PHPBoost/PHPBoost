<?php
/*##################################################
 *                              BugtrackerSearchable.class.php
 *                            -------------------
 *   begin                : April 27, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
	private $sql_querier;

	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_search_request($args)
    /**
     *  Renvoie la requete de recherche
     */
    {
        global $Cache;
		$Cache->load('bugtracker');
		
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        
        $request = "SELECT " . $args['id_search'] . " AS id_search,
		id AS `id_content`,	title, contents,
		( 2 * FT_SEARCH_RELEVANCE(title, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(contents, '" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance, "
		. $this->sql_querier->concat("'../bugtracker/bugtracker.php?view=true&amp;id='","id") . " AS `link`
		FROM " . PREFIX . "bugtracker
		WHERE ( FT_SEARCH(title, '" . $args['search'] . "') OR FT_SEARCH(contents, '" . $args['search'] . "') )"
		. " ORDER BY relevance DESC " . $this->sql_querier->limit(0, BUGTRACKER_MAX_SEARCH_RESULTS);
        
        return $request;
    }
}
?>
