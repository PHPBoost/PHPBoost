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
require_once('../includes/framework/modules/module_interface.class.php');
 
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
        global $CONFIG, $LANG, $Template;
        
        if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
            $args['WikiWhere'] = 'title';

        $Template->Set_filenames(array(
            'wiki_search_form' => 'wiki/wiki_search_form.tpl'
        ));

        $Template->Assign_vars(Array(
            'L_WHERE' => $LANG['wiki_search_where'],
            'IS_TITLE_SELECTED' => $args['WikiWhere'] == 'title'? ' selected="selected"': '',
            'IS_CONTENTS_SELECTED' => $args['WikiWhere'] == 'contents'? ' selected="selected"': '',
            'IS_ALL_SELECTED' => $args['WikiWhere'] == 'all'? ' selected="selected"': '',
            'L_TITLE' => $LANG['wiki_search_where_title'],
            'L_CONTENTS' => $LANG['wiki_search_where_contents']
        ));
        
        return $Template->Pparse('wiki_search_form', TEMPLATE_STRING_MODE);
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