<?php
/*##################################################
 *                              news_interface.class.php
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
require_once('../includes/framework/modules/module_interface.class.php');

define('NEWS_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class NewsInterface extends ModuleInterface
{
    ## Public Methods ##
    function NewsInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('news');
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
		global $Sql;
		
		$request = "SELECT " . $args['id_search'] . " AS `id_search`,
            n.id AS `id_content`,
            n.title AS `title`,
            ( 2 * MATCH(n.title) AGAINST('" . $args['search'] . "') + (MATCH(n.contents) AGAINST('" . $args['search'] . "') + MATCH(n.extend_contents) AGAINST('" . $args['search'] . "')) / 2 ) / 3 AS `relevance`, "
            . $Sql->Sql_concat("'../news/news.php?id='","n.id") . " AS `link`
            FROM " . PREFIX . "news n
            WHERE ( MATCH(n.title) AGAINST('" . $args['search'] . "') OR MATCH(n.contents) AGAINST('" . $args['search'] . "') OR MATCH(n.extend_contents) AGAINST('" . $args['search'] . "') )
				AND visible = 1 AND ('" . time() . "' > start AND ( end = 0 OR '" . time() . "' < end ) )
            ORDER BY `relevance` " . $Sql->Sql_limit(0, NEWS_MAX_SEARCH_RESULTS);
        
        return $request;
    }
}

?>