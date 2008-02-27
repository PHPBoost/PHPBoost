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
class WikiInterface extends ModuleInterface
{
    ## Public Methods ##
    function WikiInterface() //Constructeur de la classe WikiInterface
    {
        parent::ModuleInterface('wiki');
    }
    
    // Recherche
    function GetSearchForm($args=null)
    /**
     *  Renvoie le formulaire de recherche du wiki
     */
    {
        $form  = '
        <dl>
            <dt><label for="where">Ou?</label></dt>
            <dd>
                <label><input name="where" value="title" type="radio" checked="checked" />Titre</label>
                <label><input name="where" value="contents" type="radio"  />Contenu</label>
            </dd>
        </dl>';
        
        return $form;
    }
    
    function GetSearchArgs()
    /**
     *  Renvoie la liste des arguments de la méthode <GetSearchRequest>
     */
    {
        return Array('where');
    }
    
    function GetSearchRequest($args)
    /**
     *  Renvoie la requête de recherche dans le wiki
     */
    {
        return "SELECT ".
                    $args['id_search']." AS id_search,
                    `id` AS `id_content`,
                    `title` AS `title`,
                    MATCH(`title`) AGAINST('".$args['search']."') AS `relevance`,
                    CONCAT('../wiki/wiki.php?page=',encoded_title) AS link
                FROM ".
                    PREFIX."wiki_articles
                WHERE
                    MATCH(`title`) AGAINST('".$args['search']."')
                ";
    }
}

?>