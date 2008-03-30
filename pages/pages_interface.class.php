<?php
/*##################################################
 *                              wiki_interface.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright            : (C) 2007 ROUCHON Loïc
 *   email                : horn@phpboost.com
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/
 
// Inclusion du fichier contenant la classe ModuleInterface
require_once('../includes/module_interface.class.php');
define('PAGES_MAX_SEARCH_RESULTS', 100);

// Classe WikiInterface qui hérite de la classe ModuleInterface
class PagesInterface extends ModuleInterface
{
    ## Public Methods ##
    function PagesInterface() //Constructeur de la classe WikiInterface
    {
        parent::ModuleInterface('pages');
    }
    
    // Recherche
    function GetSearchRequest($args)
    /**
     *  Renvoie la requête de recherche dans le wiki
     */
    {
        $search = $args['search'];
        
        global $_PAGES_CATS, $CONFIG_PAGES, $Member, $Cache, $Sql;
        require_once('../pages/pages_defines.php');
        $Cache->Load_file('pages');
        
        $auth_cats = '';
        if( is_array($_PAGES_CATS) )
        {
            if( isset($_PAGES_CATS['auth']) && !$Member->Check_auth($_PAGES_CATS['auth'], READ_PAGE) )
                $auth_cats .= '0,';
            foreach($_PAGES_CATS as $id => $key)
            {
                if( !$Member->Check_auth($_PAGES_CATS[$id]['auth'], READ_PAGE) )
                    $auth_cats .= $id.',';
            }
        }
        $auth_cats = !empty($auth_cats) ? " AND p.id_cat NOT IN (" . trim($auth_cats, ',') . ")" : '';
        
        $results = array();
        $request = "SELECT ".
            $args['id_search']." AS `id_search`,
            p.id AS `id_content`,
            p.title AS `title`,
            ( 4 * MATCH(p.title) AGAINST('".$args['search']."') + MATCH(p.contents) AGAINST('".$args['search']."') ) / 5 AS `relevance`,
            CONCAT('../pages/pages.php?title=',p.encoded_title) AS `link`,
            p.auth AS `auth`
            FROM ".PREFIX."pages p
            WHERE ( MATCH(title) AGAINST('".$args['search']."') OR MATCH(contents) AGAINST('".$args['search']."') )".$auth_cats
            .$Sql->Sql_limit(0, PAGES_MAX_SEARCH_RESULTS);

        $result = $Sql->Query_while($request, __LINE__, __FILE__);
        while( $row = $Sql->sql_fetch_assoc($result) )
        {
            if ( !empty($row['auth']) )
            {
                $auth = unserialize($row['auth']);
                if ( !$Member->Check_auth($auth, READ_PAGE) )
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
        $Sql->Close($result);
        
        return $results;
    }
}

?>