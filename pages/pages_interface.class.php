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
 
// Classe WikiInterface qui hérite de la classe ModuleInterface
class PagesInterface extends ModuleInterface
{
    ## Public Methods ##
    function PagesInterface() //Constructeur de la classe WikiInterface
    {
        parent::ModuleInterface('Pages');
    }
    
    // Recherche
    
    function GetSearchRequest($args)
    /**
     *  Renvoie la requête de recherche dans le wiki
     */
    {
		return "SELECT ".
			$args['id_search']." AS `id_search`,
			id AS `id_content`,
			title AS `title`,
			( 4 * MATCH(title) AGAINST('".$args['search']."') + MATCH(contents) AGAINST('".$args['search']."') ) / 5 AS `relevance`,
			CONCAT('../pages/pages.php?title=',encoded_title) AS `link`
			FROM ".PREFIX."pages
			WHERE ( MATCH(title) AGAINST('".$args['search']."') OR MATCH(contents) AGAINST('".$args['search']."') )";
    }
}

?>