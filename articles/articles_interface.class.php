<?php
/*##################################################
 *                              articles_interface.class.php
 *                            -------------------
 *   begin                : April 9, 2008
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
require_once('../includes/module_interface.class.php');

define('ARTICLES_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class ArticlesInterface extends ModuleInterface
{
    ## Public Methods ##
    function ArticlesInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('articles');
    }
    
    // Recherche
//     function GetSearchForm($args=null)
//     /**
//      *  Renvoie le formulaire de recherche
//      */
//     {
//         return '';
//     }
//    
//     function GetSearchArgs()
//     /**
//      *  Renvoie la liste des arguments de la méthode <GetSearchRequest>
//      */
//     {
//         return Array();
//     }
    
    function GetSearchRequest($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
		/*global $Sql;
        require_once('../articles/articles_cats.class.php');
        $Cats = new ArticlesCats();
        $auth_cats = array();
        $Cats->Build_children_id_list(0, $list);
        
        $auth_cats = !empty($auth_cats) ? " AND a.idcat IN (" . implode($auth_cats, ',') . ") " : '';
        
        $request = "SELECT " . $args['id_search'] . " AS `id_search`,
            a.id AS `id_content`,
            a.title AS `title`,
            ( 2 * MATCH(a.title) AGAINST('" . $args['search'] . "') + MATCH(a.contents) AGAINST('" . $args['search'] . "') ) / 3 AS `relevance`, "
            . $Sql->Sql_concat("'../articles/articles.php?id='","a.id","'&amp;cat='","a.idcat") . " AS `link`
            FROM " . PREFIX . "articles a
            WHERE ( MATCH(a.title) AGAINST('" . $args['search'] . "') OR MATCH(a.contents) AGAINST('" . $args['search'] . "') )" . $auth_cats . "
				AND visible = 1 AND ('" . time() . "' > start AND ( end = 0 OR '" . time() . "' < end ) )
            ORDER BY `relevance` " . $Sql->Sql_limit(0, FAQ_MAX_SEARCH_RESULTS);
        
        return $request;*/
        return array();
    }
}

?>