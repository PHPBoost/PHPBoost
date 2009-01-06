<?php
/*##################################################
 *                         pages_interface.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
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
import('modules/module_interface');
define('PAGES_MAX_SEARCH_RESULTS', 100);

// Classe WikiInterface qui hérite de la classe ModuleInterface
class PagesInterface extends ModuleInterface
{
    ## Public Methods ##
    function PagesInterface() //Constructeur de la classe WikiInterface
    {
        parent::ModuleInterface('pages');
    }
	
	//Récupération du cache.
	function get_cache()
	{
		global $Sql;
		
		//Catégories des pages
		$config = 'global $_PAGES_CATS;' . "\n";
		$config .= '$_PAGES_CATS = array();' . "\n";
		$result = $Sql->query_while("SELECT c.id, c.id_parent, c.id_page, p.title, p.auth
		FROM " . PREFIX . "pages_cats c
		LEFT JOIN " . PREFIX . "pages p ON p.id = c.id_page
		ORDER BY p.title", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$config .= '$_PAGES_CATS[\'' . $row['id'] . '\'] = ' . var_export(array(
				'id_parent' => !empty($row['id_parent']) ? $row['id_parent'] : '0',
				'name' => $row['title'],
				'auth' => unserialize($row['auth'])
			), true) . ';' . "\n";
		}

		//Configuration du module de pages
		$code = 'global $_PAGES_CONFIG;' . "\n";
		$CONFIG_PAGES = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'pages'", __LINE__, __FILE__));
								
		if (is_array($CONFIG_PAGES))
			$CONFIG_PAGES['auth'] = unserialize($CONFIG_PAGES['auth']);
		else
			$CONFIG_PAGES = array(
			'count_hits' => 1,
			'activ_com' => 1,
			'auth' => array (
				'r-1' => 5,
				'r0' => 5,
				'r1' => 7,
				'r2' => 7,
			));
		
		$code .=  '$_PAGES_CONFIG = ' . var_export($CONFIG_PAGES, true) . ';' . "\n";
		
		return $config . "\n\r" . $code;	
	}
    
    //Actions journalière.
	/*
	function on_changeday()
	{
	}
	*/
	
	// Recherche
    function get_search_request($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
        $search = $args['search'];
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        
        global $_PAGES_CATS, $CONFIG_PAGES, $User, $Cache, $Sql;
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
            ( 2 * MATCH(p.title) AGAINST('".$args['search']."') + MATCH(p.contents) AGAINST('".$args['search']."') ) / 3 * " . $weight . " AS `relevance`,
            CONCAT('" . PATH_TO_ROOT . "/pages/pages.php?title=',p.encoded_title) AS `link`,
            p.auth AS `auth`
            FROM " . PREFIX . "pages p
            WHERE ( MATCH(title) AGAINST('".$args['search']."') OR MATCH(contents) AGAINST('".$args['search']."') )".$auth_cats
            .$Sql->limit(0, PAGES_MAX_SEARCH_RESULTS);

        $result = $Sql->query_while ($request, __LINE__, __FILE__);
        while ($row = $Sql->fetch_assoc($result))
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
        $Sql->query_close($result);
        
        return $results;
    }
}

?>
