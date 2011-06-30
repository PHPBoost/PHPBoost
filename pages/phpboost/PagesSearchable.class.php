<?php

/*##################################################
 *                              PagesSearchable.class.php
 *                            -------------------
 *   begin                : May 29, 2010
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

class PagesSearchable extends AbstractSearchable
{
	function get_search_request($args = null)
    /**
     *  Renvoie la requête de recherche
     */
    {
        $search = $args['search'];
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        
        global $_PAGES_CATS, $CONFIG_PAGES, $User, $Cache;
        require_once(PATH_TO_ROOT . '/pages/pages_defines.php');
        $Cache->load('pages');
        
        $auth_cats = '';
        if (is_array($_PAGES_CATS))
        {
            if (isset($_PAGES_CATS['auth']) && !$User->check_auth($_PAGES_CATS['auth'], READ_PAGE))
                $auth_cats .= '0,';
            foreach ($_PAGES_CATS as $id => $key)
            {
                if (!$User->check_auth($_PAGES_CATS[$id]['auth'], READ_PAGE))
                    $auth_cats .= $id.',';
            }
        }
        $auth_cats = !empty($auth_cats) ? " AND p.id_cat NOT IN (" . trim($auth_cats, ',') . ")" : '';
        
        $results = array();
        $request = "SELECT ".
            $args['id_search']." AS `id_search`,
            p.id AS `id_content`,
            p.title AS `title`,
            ( 2 * FT_SEARCH_RELEVANCE(p.title, '".$args['search']."') + FT_SEARCH_RELEVANCE(p.contents, '".$args['search']."') ) / 3 * " . $weight . " AS `relevance`,
            CONCAT('" . PATH_TO_ROOT . "/pages/pages.php?title=',p.encoded_title) AS `link`,
            p.auth AS `auth`
            FROM " . PREFIX . "pages p
            WHERE ( FT_SEARCH(title, '".$args['search']."') OR FT_SEARCH(contents, '".$args['search']."') )".$auth_cats
            .$this->sql_querier->limit(0, PAGES_MAX_SEARCH_RESULTS);

        $result = $this->sql_querier->query_while ($request, __LINE__, __FILE__);
        while ($row = $this->sql_querier->fetch_assoc($result))
        {
            if ( !empty($row['auth']) )
            {
                $auth = unserialize($row['auth']);
                if ( !$User->check_auth($auth, READ_PAGE) )
                {
                    unset($row['auth']);
                    array_push($results, $row);
                }
            }
            else
            {
                unset($row['auth']);
                array_push($results, $row);
            }
        }
        $this->sql_querier->query_close($result);
        
        return $results;
    }
}

?>