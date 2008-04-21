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

if( defined('PHPBOOST') !== true ) exit;

require_once ( '../includes/framework/modules/modules.class.php' );
require_once ( '../includes/framework/search.class.php' );


$Cache->Load_file('search');
global $SEARCH_CONFIG;

define ( 'NB_RESULTS_PER_PAGE', $SEARCH_CONFIG['nb_results_per_page']);

function ExecuteSearch($Search, &$searchModules, &$modulesArgs, &$results)
/**
 *  Exécute la recherche
 */
{
    $requests = array();
    
    foreach($searchModules as $module)
    {
        if( !$Search->IsInCache($module->GetId()) )
        {
            // On rajoute l'identifiant de recherche comme parametre pour faciliter la requete
            $modulesArgs[$module->GetId()]['id_search'] = $Search->id_search[$module->GetId()];
            $requests[$module->GetId()] = $module->Functionnality('GetSearchRequest', $modulesArgs[$module->GetId()]);
        }
    }
    
    $Search->InsertResults($requests);
}

function GetSearchResults($searchTxt, &$searchModules, &$modulesArgs, &$results, &$idsSearch, $justInsert = false)
/**
 *  Exécute la recherche si les résultats ne sont pas dans le cache et
 *  renvoie les résultats.
 */
{
    $modulesIds = array();
    $modulesOptions = array();
    
    // Generation des noms des modules utilisés et de la chaine options
    foreach($searchModules as $module)
    {
        array_push($modulesIds, $module->GetId());
        // enleve la chaine search de la chaine options et la tronque a 255 caracteres
        $modulesOptions[$module->GetId()] = md5(implode('|', $modulesArgs[$module->GetId()]));
    }
    
    $Search = new Search($searchTxt, $modulesOptions);
    ExecuteSearch($Search, $searchModules, $modulesArgs, $results);
    $idsSearch = $Search->id_search;
    
    if ( !$justInsert )
        return $Search->GetResults($results, $modulesIds);
    else
        return -1;
}

function Get_HTML_Results(&$results, &$htmlResults, &$Modules, &$resultsName)
/**
 *  Renvoie une chaine contenant les resultats
 */
{
    global $Template, $CONFIG;

    $module = $Modules->GetModule(strtolower($resultsName));
    
    $Template->Set_filenames(array(
        'search_generic_pagination_results' => 'search/search_generic_pagination_results.tpl',
        'search_generic_results' => 'search/search_generic_results.tpl'
    ));

    $Template->Assign_vars(Array(
        'RESULTS_NAME' => $resultsName,
        'C_ALL_RESULTS' => ($resultsName == 'all' ? true : false)
    ));

    $nbPages = round(count($results) / NB_RESULTS_PER_PAGE) + 1;
    $nbResults = count($results);
    for ( $numPage = 0; $numPage < $nbPages; $numPage++ )
    {
        $Template->Assign_block_vars('page', array(
            'NUM_PAGE' => $numPage,
            'BLOCK_DISPLAY' => ($numPage == 0 ? 'block' : 'none')
        ));

        $j = $numPage * NB_RESULTS_PER_PAGE;
        for ( $i = 0 ; $i < NB_RESULTS_PER_PAGE; $i++ )
        {
            if ( ($j) >= $nbResults )
                break;

            if ( ($resultsName == 'all') || (!$module->HasFunctionnality('ParseSearchResults')) )
            {
                $module = $Modules->GetModule($results[$j]['module']);
                $Template->Assign_vars(array(
                    'L_MODULE_NAME' => ucfirst($module->GetName()),
                    'TITLE' => $results[$j]['title'],
                    'U_LINK' => transid($results[$j]['link'])
                ));
                $tempRes = $Template->Pparse('search_generic_results', TEMPLATE_STRING_MODE);
            }
            else $tempRes = $module->Functionnality('ParseSearchResults', array('results' => $results));
            
            $Template->Assign_block_vars('page.results', array(
                    'result' => $tempRes
                ));
            
            $j++;
        }
    }
    $htmlResults = $Template->Pparse('search_generic_pagination_results', TEMPLATE_STRING_MODE);
}

?>