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
require_once('../includes/begin.php');
load_module_lang('search');
define('ALTERNATIVE_CSS', 'search');

$Template->Set_filenames(array(
    'search_mini_form' => '../templates/'.$CONFIG['theme'].'/search/search_mini_form.tpl',
    'search_forms' => '../templates/'.$CONFIG['theme'].'/search/search_forms.tpl',
    'search_results' => '../templates/'.$CONFIG['theme'].'/search/search_results.tpl'
));

//--------------------------------------------------------------------- Params
// A protéger impérativement;
$pageNum = !empty($_GET['p']) ? numeric($_GET['p']) : 1;
$modName = !empty($_GET['module']) ? securit($_GET['module']) : 'all';
$search = !empty($_POST['search']) ? securit($_POST['search']) : '';
$selectedModules = !empty($_POST['searched_modules']) ? $_POST['searched_modules'] : array();
$searchIn = !empty($_POST['search_in']) ? $_POST['search_in'] : 'all';

//--------------------------------------------------------------------- Header

if( !empty($search) )
    define('TITLE', $LANG['title_search'].' : '.addslashes($search));
else
    define('TITLE', $LANG['title_search']);
require_once('../includes/header.php');

$Template->Assign_vars(Array(
    'L_TITLE_SEARCH' => TITLE,
    'L_SEARCH' => $LANG['title_search'],
    'TEXT_SEARCHED' => $search,
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
    'U_FORM_VALID' => transid('../search/search.php#results'),
    'C_SIMPLE_SEARCH' => $searchIn == 'all' ? true : false,
    'SEARCH_MODE_MODULE' => $searchIn
));

//------------------------------------------------------------- Other includes
require_once('../includes/modules.class.php');
require_once('../search/search.inc.php');

//----------------------------------------------------------------------- Main

$Modules = new Modules();
$modulesArgs = array();
$usedModules = array();

// Chargement des modules avec formulaires
$searchModule = $Modules->GetAvailablesModules('GetSearchRequest');

// Génération des formulaires précomplétés et passage aux templates
foreach( $searchModule as $module)
{
    // Ajout du paramètre search à tous les modules
    $modulesArgs[$module->GetId()]['search'] = $search;
    if( $module->HasFunctionnality('GetSearchArgs') )
    {
        // Récupération de la liste des paramètres
        $formModuleArgs = $module->Functionnality('GetSearchArgs');
        // Ajout des paramètres optionnels sans les sécuriser.
        // Ils sont sécurisés à l'intérieur de chaque module.
        if ( $searchIn )
        {
            foreach( $formModuleArgs as $arg)
            {
                if ( isset($_POST[$arg]) )
                    $modulesArgs[$module->GetId()][$arg] = $_POST[$arg];
            }
        }
        
        $Template->Assign_block_vars('forms', array(
            'MODULE_NAME' => $module->GetId(),
            'L_MODULE_NAME' => ucfirst($module->GetName()),
            'SEARCH_FORM' => $module->Functionnality('GetSearchForm', $modulesArgs[$module->GetId()])
        ));
    }
}

// Récupération de la liste des modules à traiter
foreach( $SEARCH_CONFIG['authorised_modules'] as $moduleId )
{
    $module = $Modules->GetModule($moduleId);
    if ( ($selectedModules === array()) || in_array($moduleId, $selectedModules) )
    {
        $selected = ' selected="selected"';
        $usedModules[$moduleId] = $module; // Ajout du module à traiter
    }
    else
        $selected = '';
    
    $Template->Assign_block_vars('searched_modules', array(
        'MODULE' => $moduleId,
        'L_MODULE_NAME' => ucfirst($module->GetName()),
        'SELECTED' => $selected
    ));
}

// parsage des formulaires de recherches
$Template->Pparse('search_forms');

if( $search != '' )
{
    $results = array();
    $idsSearch = array();
    
    if ( $searchIn != 'all' ) // If we are searching in only onde module
    {
        $usedModules = array($searchIn => $usedModules[$searchIn]);
        $modulesArgs = array($searchIn => $modulesArgs[$searchIn]);
    }
    
    // Génération des résultats et passage aux templates
    $nbResults = GetSearchResults($search, $usedModules, $modulesArgs, $results, $idsSearch);
    
    foreach( $usedModules as $module)
    {
        $Template->Assign_block_vars('results', array(
            'MODULE_NAME' => $module->GetId(),
            'L_MODULE_NAME' => ucfirst($module->GetName()),
            'ID_SEARCH' => $idsSearch[$module->GetId()]
        ));
    }
    
    $allhtmlResult = '';
    Get_HTML_Results($results, $allhtmlResult, $Modules, 'all');
    $allhtmlResult .= '<script type="text/javascript">
        <!--
            nbResults[\'all\'] = '.$nbResults.';
            
            if( browserAJAXFriendly() )
                show_div(\'results_all_0\');
        -->
        </script>';
    
    $Template->Assign_vars(Array(
        'NB_RESULTS_PER_PAGE' => NB_RESULTS_PER_PAGE,
        'L_TITLE_ALL_RESULTS' => $LANG['title_all_results'],
        'L_RESULTS' => $LANG['results'],
        'L_RESULTS_CHOICE' => $LANG['results_choice'],
        'L_PRINT' => $LANG['print'],
        'L_NB_RESULTS_FOUND' => $nbResults > 1 ? $LANG['nb_results_found'] : ($nbResults == 0 ? $LANG['no_results_found'] : $LANG['one_result_found']),
        'L_SEARCH_RESULTS' => $LANG['search_results'],
        'NB_RESULTS' => $nbResults,
        'ALL_RESULTS' => $allhtmlResult,
        'C_SIMPLE_SEARCH' => $searchIn == 'all' ? true : false
    ));
    
    // parsage des résultats de la recherche
    $Template->Pparse('search_results');
}

//--------------------------------------------------------------------- Footer
require_once('../includes/footer.php');

?>