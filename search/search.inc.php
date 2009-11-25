<?php
/*##################################################
*                               search.inc.php
*                            -------------------
*   begin                : february 5, 2008
*   copyright            : (C) 2008 Rouchon Loïc
*   email                : loic.rouchon@phpboost.com
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





$Cache->load('search');
global $SEARCH_CONFIG;

define ( 'NB_RESULTS_PER_PAGE', $SEARCH_CONFIG['nb_results_per_page']);

/**
 *  Exécute la recherche
 */
function execute_search($search, $search_modules, &$modules_args)
{
    $requests = array();
    
    global $SEARCH_CONFIG;
    
    foreach ($search_modules as $module)
    {
        if (!$search->is_in_cache($module->get_id()))
        {
            $modules_args[$module->get_id()]['weight'] = !empty($SEARCH_CONFIG['modules_weighting'][$module->get_id()]) ? $SEARCH_CONFIG['modules_weighting'][$module->get_id()] : 1;
            // On rajoute l'identifiant de recherche comme parametre pour faciliter la requete
            $modules_args[$module->get_id()]['id_search'] = !empty($search->id_search[$module->get_id()]) ? $search->id_search[$module->get_id()] : 0;
            $requests[$module->get_id()] = $module->functionality('get_search_request', $modules_args[$module->get_id()]);
        }
    }
    
    $search->insert_results($requests);
}

/**
 *  Exécute la recherche si les résultats ne sont pas dans le cache et
 *  renvoie les résultats.
 */
function get_search_results($search_string, $search_modules, &$modules_args, &$results, &$ids_search, $just_insert = false)
{
    $modules_options = array();
    
    $search = new Search($search_string, $modules_args);
    execute_search($search, $search_modules, $modules_args, $results);
    
    $ids_search = $search->get_ids();
    
    if (!$just_insert)
    {
        $modules_ids = array();
        foreach ($search_modules as $module)
        {
            $modules_ids[] = $module->get_id();
        }
        return $search->get_results($results, $modules_ids);
    }
    else
        return -1;
}

/**
 *  Renvoie une chaine contenant les resultats
 */
function get_html_results($results, $html_results, $results_name)
{
    global $CONFIG;
    $modules = new ModulesDiscoveryService();
    $display_all_results = ($results_name == 'all' ? true : false);
    
    $tpl_results = new Template('search/search_generic_pagination_results.tpl');
    $tpl_results->assign_vars(Array(
        'RESULTS_NAME' => $results_name,
        'C_ALL_RESULTS' => $display_all_results
    ));
    
    $nb_pages = round(count($results) / NB_RESULTS_PER_PAGE) + 1;
    $nb_results = count($results);
    
    if (!$display_all_results)
    {
        $module = $modules->get_module(strtolower($results_name));
        
        $results_data = array();
        $personnal_parse_results = $module->has_functionality('compute_search_results') && $module->has_functionality('parse_search_result');
        if ($personnal_parse_results && $results_name != 'all')
        {
            $results_data = $module->functionality('compute_search_results', array('results' => $results));
            $nb_results = min($nb_results, count($results_data));
        }
    }
    
    for ($num_page = 0; $num_page < $nb_pages; $num_page++)
    {
        $tpl_results->assign_block_vars('page', array(
            'NUM_PAGE' => $num_page,
            'BLOCK_DISPLAY' => ($num_page == 0 ? 'block' : 'none')
        ));

        for ($i = 0 ; $i < NB_RESULTS_PER_PAGE; $i++)
        {
            $num_item = $num_page * NB_RESULTS_PER_PAGE + $i;
            if (($num_item) >= $nb_results)
                break;
            
            if ($display_all_results || !$personnal_parse_results)
            {
                $tpl_result = new Template('search/search_generic_results.tpl');
                if ($display_all_results)
                {
                    $module = $modules->get_module($results[$num_item]['module']);
                    $tpl_result->assign_vars(array(
                        'C_ALL_RESULTS' => true,
                        'L_MODULE_NAME' => $module->get_name()
                    ));
                }
                else
                {
                    $tpl_result->assign_vars(array(
                        'C_ALL_RESULTS' => false,
                        'L_MODULE_NAME' => $module->get_name(),
                    ));
                }
                $tpl_result->assign_vars(array(
                    'TITLE' => $results[$num_item]['title'],
                    'U_LINK' => url($results[$num_item]['link'])
                ));
                
                $tpl_results->assign_block_vars('page.results', array(
                    'result' => $tpl_result->parse(Template::TEMPLATE_PARSER_STRING)
                ));
            }
            else
            {
                $tpl_results->assign_block_vars('page.results', array(
                    'result' => $module->functionality('parse_search_result', $results_data[$num_item])
                ));
            }
        }
    }
    $html_results = $tpl_results->parse(Template::TEMPLATE_PARSER_STRING);
}

?>