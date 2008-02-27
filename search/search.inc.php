<?php
/*##################################################
*                               search.inc.php
*                            -------------------
*   begin                : february 5, 2008
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

require_once ( '../includes/modules.class.php' );
require_once ( '../includes/search.class.php' );

function GetSearchForms(&$modules, &$args)
/**
 *  Affiche les formulaires de recherches pour tous les modules.
 */
{
    $searchForms = array();
    foreach( $modules as $module )
    {
        if( isset($args[$module->name]) )
            $searchForms[$module->name] = $module->Functionnality('GetSearchForm', $args[$module->name]);
        else
            $searchForms[$module->name] = $module->Functionnality('GetSearchForm', array('search' => ''));
    }
    
    return $searchForms;
}

function GetSearchResults($searchTxt, &$searchModules, &$modulesArgs, &$results, $offset = 0, $nbResults = 10)
/**
 *  Exécute la recherche si les résultats ne sont pas dans le cache et
 *  renvoie les résultats.
 */
{
    $requests = array();
    $modulesNames = array();
    $modulesOptions = array();
    
    // Génération des noms des modules utilisés et de la chaine options
    foreach($searchModules as $module)
    {
        array_push($modulesNames, $module->name);
        // enlève la chaine search de la chaine options et la tronque à 255 caractères
        $options = $modulesArgs[$module->name];
        unset($options['search']);
        $modulesOptions[$module->name] = substr(implode('|', $options), 0, 255);
    }
    
    $Search = new Search($searchTxt, $modulesOptions);
    
    foreach($searchModules as $module)
    {
        // On rajoute l'identifiant de recherche comme paramètre pour faciliter la requête
        $modulesArgs[$module->name]['id_search'] = $Search->id_search[$module->name];
        $requests[$module->name] = $module->Functionnality('GetSearchRequest', $modulesArgs[$module->name]);
    }
    
    $Search->InsertResults($requests);
    
    return $Search->GetResults($results, $modulesNames, $offset, $nbResults);
}

?>