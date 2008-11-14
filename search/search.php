<?php
/*##################################################
*                               search.php
*                            -------------------
*   begin                : January 27, 2008
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

//------------------------------------------------------------------- Language
require_once('../kernel/begin.php');
load_module_lang('search');
define('ALTERNATIVE_CSS', 'search');

$Template->set_filenames(array(
    'search_mini_form' => 'search/search_mini_form.tpl',
    'search_forms' => 'search/search_forms.tpl',
    'search_results' => 'search/search_results.tpl'
));

//--------------------------------------------------------------------- Params
// A protéger impérativement;
$search = retrieve(REQUEST, 'q', '');
$unsecure_search = htmlentities(retrieve(REQUEST, 'q', '', TSTRING_UNCHANGE));
$selectedModules = retrieve(POST, 'searched_modules', array());
$searchIn = retrieve(POST, 'search_in', 'all');
$simpleMode = ($searchIn == 'all') ? true : false;

//--------------------------------------------------------------------- Header

define('TITLE', $LANG['title_search']);

require_once('../kernel/header.php');
$Template->assign_vars(Array(
    'L_TITLE_SEARCH' => TITLE,
    'L_SEARCH' => $LANG['title_search'],
    'TEXT_SEARCHED' => !empty($unsecure_search) ? $unsecure_search : $LANG['search'] . '...',
    'L_SEARCH_ALL' => $LANG['search_all'],
    'L_SEARCH_KEYWORDS' => $LANG['search_keywords'],
    'L_SEARCH_MIN_LENGTH' => $LANG['search_min_length'],
    'L_SEARCH_IN_MODULES' => $LANG['search_in_modules'],
    'L_SEARCH_IN_MODULES_EXPLAIN' => $LANG['search_in_modules_explain'],
    'L_SEARCH_SPECIALIZED_FORM' => $LANG['search_specialized_form'],
    'L_SEARCH_SPECIALIZED_FORM_EXPLAIN' => $LANG['search_specialized_form_explain'],
    'L_WARNING_LENGTH_STRING_SEARCH' => $LANG['warning_length_string_searched'],
    'L_FORMS' => $LANG['forms'],
    'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
    'L_SIMPLE_SEARCH' => $LANG['simple_search'],
    'U_FORM_VALID' => url('../search/search.php#results'),
    'C_SIMPLE_SEARCH' => $searchIn == 'all' ? true : false,
    'SEARCH_MODE_MODULE' => $searchIn
));

//------------------------------------------------------------- Other includes
require_once('../kernel/framework/modules/modules_discovery_service.class.php');
require_once('../search/search.inc.php');

//----------------------------------------------------------------------- Main

$Modules = new ModulesDiscoveryService();
$modulesArgs = array();
$usedModules = array();

// Chargement des modules avec formulaires
$searchModule = $Modules->get_available_modules('get_search_request');

// Génération des formulaires précomplétés et passage aux templates
foreach( $searchModule as $module)
{
	if( in_array($module->get_id(), $SEARCH_CONFIG['authorized_modules']))
	{
	    // Ajout du paramètre search à tous les modules
	    $modulesArgs[$module->get_id()]['search'] = $search;
	    if( $module->has_functionnality('get_search_args') )
	    {
	        // Récupération de la liste des paramètres
	        $formModuleArgs = $module->functionnality('get_search_args');
	        // Ajout des paramètres optionnels sans les sécuriser.
	        // Ils sont sécurisés à l'intérieur de chaque module.
	        if ( $searchIn )
	        {
	            foreach( $formModuleArgs as $arg)
	            {
	                if ( $arg == 'search' ) // 'search' non sécurisé
	                    $modulesArgs[$module->get_id()]['search'] = $search;
	                elseif ( isset($_POST[$arg]) )  // Argument non sécurisé (sécurisé par le module en question)
	                    $modulesArgs[$module->get_id()][$arg] = $_POST[$arg];
	            }
	        }
	        
	        $Template->assign_block_vars('forms', array(
	            'MODULE_NAME' => $module->get_id(),
	            'L_MODULE_NAME' => ucfirst($module->get_name()),
	            'C_SEARCH_FORM' => true,
	            'SEARCH_FORM' => $module->functionnality('get_search_form', $modulesArgs[$module->get_id()])
	        ));
	    }
	    else
	    {
	        $Template->assign_block_vars('forms', array(
	            'MODULE_NAME' => $module->get_id(),
	            'L_MODULE_NAME' => ucfirst($module->get_name()),
	            'C_SEARCH_FORM' => false,
	            'SEARCH_FORM' => $LANG['search_no_options']
	        ));
	    }
	    
	    // Récupération de la liste des modules à traiter
	    if ( ($selectedModules === array()) || ($searchIn === $module->get_id()) ||
	    	(($searchIn === 'all') && (in_array($module->get_id(), $selectedModules))) )
	    {
	        $selected = ' selected="selected"';
	        $usedModules[$module->get_id()] = $module; // Ajout du module à traiter
	    }
	    else $selected = '';
	    
	    $Template->assign_block_vars('searched_modules', array(
	        'MODULE' => $module->get_id(),
	        'L_MODULE_NAME' => ucfirst($module->get_name()),
	        'SELECTED' => $selected
	    ));
	}
	else unset($module);
}


//foreach( $search_config['authorized_modules'] as $moduleid )
//{
//    $module = $modules->get_module($moduleid);
//    if ( ($selectedmodules === array()) || in_array($moduleid, $selectedmodules) || ($searchin === $moduleid) )
//    {
//        $selected = ' selected="selected"';
//        $usedmodules[$moduleid] = $module; // ajout du module à traiter
//    }
//    else
//        $selected = '';
//    
//    $template->assign_block_vars('searched_modules', array(
//        'module' => $moduleid,
//        'l_module_name' => ucfirst($module->get_name()),
//        'selected' => $selected
//    ));
//}

// parsage des formulaires de recherches
$Template->pparse('search_forms');

if( !empty($search) )
{
    $results = array();
    $idsSearch = array();
    
    if ( $searchIn != 'all' ) // If we are searching in only onde module
    {
        $usedModules = array($searchIn => $usedModules[$searchIn]);
        $modulesArgs = array($searchIn => $modulesArgs[$searchIn]);
    }
    
    // Génération des résultats et passage aux templates
    $nbResults = get_search_results($search, $usedModules, $modulesArgs, $results, $idsSearch);
    
    foreach( $usedModules as $module)
    {
        $Template->assign_block_vars('results', array(
            'MODULE_NAME' => $module->get_id(),
            'L_MODULE_NAME' => ucfirst($module->get_name()),
            'ID_SEARCH' => $idsSearch[$module->get_id()]
        ));
    }
    
    $allhtmlResult = '';
    if ( $nbResults > 0 )
        get_html_results($results, $allhtmlResult, $Modules, $searchIn);
    
    $Template->assign_vars(Array(
        'NB_RESULTS_PER_PAGE' => NB_RESULTS_PER_PAGE,
        'L_TITLE_ALL_RESULTS' => $LANG['title_all_results'],
        'L_RESULTS' => $LANG['results'],
        'L_RESULTS_CHOICE' => $LANG['results_choice'],
        'L_PRINT' => $LANG['print'],
        'L_NB_RESULTS_FOUND' => $nbResults > 1 ? $LANG['nb_results_found'] : ($nbResults == 0 ? $LANG['no_results_found'] : $LANG['one_result_found']),
        'L_SEARCH_RESULTS' => $LANG['search_results'],
        'NB_RESULTS' => $nbResults,
        'ALL_RESULTS' => $allhtmlResult,
        'SEARCH_IN' => $searchIn,
        'C_SIMPLE_SEARCH' => ($searchIn == 'all') ? true : false
    ));
    
    // parsage des résultats de la recherche
    $Template->pparse('search_results');
}

//--------------------------------------------------------------------- Footer
require_once('../kernel/footer.php');

?>