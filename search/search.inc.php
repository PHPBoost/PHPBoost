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

if (defined('PHPBOOST') !== true) exit;

require_once ( '../kernel/framework/modules/modules_discovery_service.class.php' );
require_once ( '../kernel/framework/content/search.class.php' );


$Cache->load('search');
global $SEARCH_CONFIG;

define ( 'NB_RESULTS_PER_PAGE', $SEARCH_CONFIG['nb_results_per_page']);

function execute_search($Search, &$searchModules, &$modulesArgs, &$results)
/**
 *  Exécute la recherche
 */
{
    $requests = array();
    
    global $SEARCH_CONFIG;
    
    foreach ($searchModules as $module)
    {
        if (!$Search->is_in_cache($module->get_id()))
        {
            $modulesArgs[$module->get_id()]['weight'] = !empty($SEARCH_CONFIG['modules_weighting'][$module->get_id()]) ? $SEARCH_CONFIG['modules_weighting'][$module->get_id()] : 1;
            // On rajoute l'identifiant de recherche comme parametre pour faciliter la requete
            $modulesArgs[$module->get_id()]['id_search'] = !empty($Search->id_search[$module->get_id()]) ? $Search->id_search[$module->get_id()] : 0;
            $requests[$module->get_id()] = $module->functionnality('get_search_request', $modulesArgs[$module->get_id()]);
        }
    }
    
    $Search->insert_results($requests);
}

function get_search_results($searchTxt, &$searchModules, &$modulesArgs, &$results, &$idsSearch, $justInsert = false)
/**
 *  Exécute la recherche si les résultats ne sont pas dans le cache et
 *  renvoie les résultats.
 */
{
    $modulesIds = array();
    $modulesOptions = array();
    
    // Generation des noms des modules utilisés et de la chaine options
    foreach ($searchModules as $module)
    {
        array_push($modulesIds, $module->get_id());
    }
    
    $Search = new Search($searchTxt, $modulesArgs);
    execute_search($Search, $searchModules, $modulesArgs, $results);
    $idsSearch = $Search->get_ids();
    if ( !$justInsert )
        return $Search->get_results($results, $modulesIds);
    else
        return -1;
}

function get_html_results(&$results, &$htmlResults, &$Modules, &$resultsName)
/**
 *  Renvoie une chaine contenant les resultats
 */
{
    global $Template, $CONFIG;

    $module = $Modules->get_module(strtolower($resultsName));
    
    $Template->set_filenames(array(
        'search_generic_pagination_results' => 'search/search_generic_pagination_results.tpl',
        'search_generic_results' => 'search/search_generic_results.tpl'
    ));

    $Template->assign_vars(Array(
        'RESULTS_NAME' => $resultsName,
        'C_ALL_RESULTS' => ($resultsName == 'all' ? true : false)
    ));

    $nbPages = round(count($results) / NB_RESULTS_PER_PAGE) + 1;
    $nbResults = count($results);
    for ( $numPage = 0; $numPage < $nbPages; $numPage++ )
    {
        $Template->assign_block_vars('page', array(
            'NUM_PAGE' => $numPage,
            'BLOCK_DISPLAY' => ($numPage == 0 ? 'block' : 'none')
        ));

        $j = $numPage * NB_RESULTS_PER_PAGE;
        for ( $i = 0 ; $i < NB_RESULTS_PER_PAGE; $i++ )
        {
            if ( ($j) >= $nbResults )
                break;

            if ( ($resultsName == 'all') || (!$module->has_functionnality('parse_search_results')) )
            {
                $module = $Modules->get_module($results[$j]['module']);
                $Template->assign_vars(array(
                    'L_MODULE_NAME' => ucfirst($module->get_name()),
                    'TITLE' => $results[$j]['title'],
                    'U_LINK' => url($results[$j]['link'])
                ));
                $tempRes = $Template->pparse('search_generic_results', TEMPLATE_STRING_MODE);
            }
            else $tempRes = $module->functionnality('parse_search_results', array('results' => $results));
            
            $Template->assign_block_vars('page.results', array(
                    'result' => $tempRes
                ));
            
            $j++;
        }
    }
    $htmlResults = $Template->pparse('search_generic_pagination_results', TEMPLATE_STRING_MODE);
}

?>