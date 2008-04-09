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
        require_once('../includes/begin.php');
        load_module_lang('wiki');
        global $LANG;
        
        if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
            $args['WikiWhere'] = 'title';
        
        return '
        <dl>
            <dt><label for="WikiWhere">'.$LANG['wiki_search_where'].'</label></dt>
            <dd>
                <select id="WikiWhere" name="WikiWhere" class="search_field">
                    <option value="title"'.($args['WikiWhere'] == 'title'? ' selected="selected"': '').'>'.$LANG['wiki_search_where_title'].'</option>
                    <option value="contents"'.($args['WikiWhere'] == 'contents'? ' selected="selected"': '').'>'.$LANG['wiki_search_where_contents'].'</option>
                    <option value="all"'.($args['WikiWhere'] == 'all'? ' selected="selected"': '').'>'.$LANG['wiki_search_where_all'].'</option>
                </select>
            </dd>
        </dl>';
    }
    
    function GetSearchArgs()
    /**
     *  Renvoie la liste des arguments de la méthode <GetSearchRequest>
     */
    {
        return Array('WikiWhere');
    }
    
    function GetSearchRequest($args)
    /**
     *  Renvoie la requête de recherche dans le wiki
     */
    {
        if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
            $args['WikiWhere'] = 'title';
        
        if ( $args['WikiWhere'] == 'all' )
            return "SELECT ".
                $args['id_search']." AS `id_search`,
                a.id AS `id_content`,
                a.title AS `title`,
                ( 4 * MATCH(a.title) AGAINST('".$args['search']."') + MATCH(c.content) AGAINST('".$args['search']."') ) / 5 AS `relevance`,
                CONCAT('../wiki/wiki.php?title=',a.encoded_title) AS `link`
                FROM ".PREFIX."wiki_articles a
                LEFT JOIN ".PREFIX."wiki_contents c ON c.id_contents = a.id
                WHERE ( MATCH(a.title) AGAINST('".$args['search']."') OR MATCH(c.content) AGAINST('".$args['search']."') )";
        if ( $args['WikiWhere'] == 'contents' )
            return "SELECT ".
                $args['id_search']." AS `id_search`,
                a.id AS `id_content`,
                a.title AS `title`,
                MATCH(c.content) AGAINST('".$args['search']."') AS `relevance`,
                CONCAT('../wiki/wiki.php?title=',a.encoded_title) AS `link`
                FROM ".PREFIX."wiki_articles a
                LEFT JOIN ".PREFIX."wiki_contents c ON c.id_contents = a.id
                WHERE MATCH(c.content) AGAINST('".$args['search']."')";
        else
            return "SELECT ".
                $args['id_search']." AS `id_search`,
                `id` AS `id_content`,
                `title` AS `title`,
                MATCH(title) AGAINST('".$args['search']."') AS `relevance`,
                CONCAT('../wiki/wiki.php?title=',encoded_title) AS `link`
                FROM ".PREFIX."wiki_articles
                WHERE MATCH(title) AGAINST('".$args['search']."')";
    }
}

?>