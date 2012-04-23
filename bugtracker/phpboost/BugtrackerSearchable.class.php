<?php
/*##################################################
 *                              BugtrackerSearchable.class.php
 *                            -------------------
 *   begin                : April 16, 2012
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
	
	function get_search_request($args = null)
    /**
     *  Renvoie la requête de recherche
     */
    {
        $search = $args['search'];
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        
        global $User, $Cache;

        $Cache->load('bugtracker');
        
        $results = array();
        $request = "SELECT ".
            $args['id_search']." AS `id_search`,
            id AS `id_content`,
            title AS `title`,
            ( 2 * FT_SEARCH_RELEVANCE(title, '".$args['search']."') + FT_SEARCH_RELEVANCE(contents, '".$args['search']."') ) / 3 * " . $weight . " AS `relevance`,
            CONCAT('" . PATH_TO_ROOT . "/bugtracker/bugtracker.php?view=true&amp;id=',id) AS `link`,
            FROM " . PREFIX . "bugtracker
            WHERE ( FT_SEARCH(title, '".$args['search']."') OR FT_SEARCH(contents, '".$args['search']."') )".$auth_cats
            .$this->sql_querier->limit(0, BUGTRACKER_MAX_SEARCH_RESULTS);

        $result = $this->sql_querier->query_while ($request, __LINE__, __FILE__);
        $this->sql_querier->query_close($result);
        
        return $results;
    }
}
?>