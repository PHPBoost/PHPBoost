<?php
/*##################################################
*                         search_cache.php
*                            -------------------
*   begin                : March 22, 2008
*   copyright            : (C) 2008 Rouchon Loïc
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


//------------------------------------------------------------------- Includes
require_once('../includes/begin.php');

//----------------------------------------------------------------------- Main
if( defined('PHP_BOOST') !== true ) exit;


function generate_module_file_search()
/**
 *  Génère le fichier cache de la recherche
 */
{
    global $Sql;
    
    $request = "SELECT value FROM ".PREFIX."configs WHERE name = 'search'";
    
    //Configuration
    $search_config = unserialize($Sql->Query($request, __LINE__, __FILE__));
    
    return 'global $SEARCH_CONFIG;
            $SEARCH_CONFIG = '.var_export($search_config, true).';';
}

?>