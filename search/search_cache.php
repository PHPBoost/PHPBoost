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
    $config = unserialize($Sql->Query($request, __LINE__, __FILE__));
    
    // Si la configuration n'existe pas, on en crée une par défaut
    if ( $Sql->Sql_affected_rows($request, __LINE__, __FILE__) == 0 )
    {
        include_once('../includes/modules.class.php');
        
        $SEARCH_CONFIG['cache_time'] = 15 * 60;
        $SEARCH_CONFIG['nb_results_per_page'] = 15;
        $SEARCH_CONFIG['max_use'] = 100;
        
        $Modules = new Modules();
        $searchModules = $Modules->GetAvailablesModules('GetSearchRequest');
        $SEARCH_CONFIG['authorised_modules'] = array();
        
        foreach ( $searchModules as $module )
        {
            $moduleInfos = $module->GetInfo();
            $SEARCH_CONFIG['authorised_modules'][$module->name] = $moduleInfos['name'];
        }
        
        $config = addslashes(serialize($SEARCH_CONFIG));
        
        $Sql->Query_inject("INSERT INTO ".PREFIX."configs (`name`, `value`) VALUES ('search', '".$config."')", __LINE__, __FILE__);
    }
    
    return 'global $SEARCH_CONFIG;
            $SEARCH_CONFIG = ' . var_export($config, true) . ';';
}

function Load_search_cache()
/**
 *  Charge le fichier cache de la recherche
 */
{
    global $Cache;
    
    $Cache->Load_file('search');
    global $SEARCH_CONFIG;
    
    $SEARCH_CONFIG['cache_time'] = round(!empty($SEARCH_CONFIG['cache_time']) ? numeric($SEARCH_CONFIG['cache_time']) : (15 * 60) / 60);
    $SEARCH_CONFIG['nb_results_per_page'] = !empty($SEARCH_CONFIG['nb_results_per_page']) ? numeric($SEARCH_CONFIG['nb_results_per_page']) : 15;
    $SEARCH_CONFIG['max_use'] = !empty($SEARCH_CONFIG['max_use']) ? numeric($SEARCH_CONFIG['max_use']) : 100;
	if ( empty($SEARCH_CONFIG['authorised_modules']) || !is_array($SEARCH_CONFIG['authorised_modules']) )
		$SEARCH_CONFIG['authorised_modules'] = new array();
    
    return $SEARCH_CONFIG;
}

?>