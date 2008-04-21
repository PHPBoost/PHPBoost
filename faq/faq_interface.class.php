<?php
/*##################################################
 *                              faq_interface.class.php
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

define('FAQ_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class FaqInterface extends ModuleInterface
{
    ## Public Methods ##
    function FaqInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('faq');
    }
    
    function GetSearchRequest($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
        global $Sql;
        require_once('../faq/faq_cats.class.php');
        $Cats = new FaqCats();
        $auth_cats = array();
        $Cats->Build_children_id_list(0, $list);
        
        $auth_cats = !empty($auth_cats) ? " AND f.idcat IN (" . implode($auth_cats, ',') . ") " : '';
        
        $request = "SELECT " . $args['id_search'] . " AS `id_search`,
            f.id AS `id_content`,
            f.question AS `title`,
            ( 2 * MATCH(f.question) AGAINST('" . $args['search'] . "') + MATCH(f.answer) AGAINST('" . $args['search'] . "') ) / 3 AS `relevance`, "
            . $Sql->Sql_concat("'../faq/faq.php?id='","f.idcat","'&amp;question='","f.id","'#q'","f.id") . " AS `link`
            FROM " . PREFIX . "faq f
            WHERE ( MATCH(f.question) AGAINST('" . $args['search'] . "') OR MATCH(f.answer) AGAINST('" . $args['search'] . "') )" . $auth_cats
            . " ORDER BY `relevance` " . $Sql->Sql_limit(0, FAQ_MAX_SEARCH_RESULTS);
        
        return $request;
    }
}

?>