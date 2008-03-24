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
$modName = !empty($_GET['module']) ? securit($_GET['module']) : 'All';
$search = !empty($_POST['search']) ? securit($_POST['search']) : '';
$selectedModules = !empty($_POST['searched_modules']) ? $_POST['searched_modules'] : array();
$p = 0;

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
    'L_WARNING_LENGTH_STRING_SEARCH' => $LANG['warning_length_string_searched'],
    'L_FORMS' => $LANG['forms'],
    'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
    'L_SIMPLE_SEARCH' => $LANG['simple_search'],
    'U_FORM_VALID' => transid('../search/search.php#results')
));

//------------------------------------------------------------- Other includes
require_once('../includes/modules.class.php');
require_once('../search/search.inc.php');

//----------------------------------------------------------------------- Main

$Modules = new Modules();
$modulesArgs = array();


// Listes des modules de recherches
$searchModules = $Modules->GetAvailablesModules('GetSearchRequest');

// Chargement des modules avec formulaires
$formsModule = $Modules->GetAvailablesModules('GetSearchForm', $searchModules);

foreach( $SEARCH_CONFIG['authorised_modules'] as $module => $moduleName )
{
    $module = $Modules->GetModule($moduleName);
    $infos = $module->GetInfo();
    if ( ($selectedModules === array()) || in_array($moduleName, $selectedModules) )
        $selected = ' selected="selected"';
    else
        $selected = '';
    
    $Template->Assign_block_vars('searched_modules', array(
        'MODULE' => $module->name,
        'L_MODULE_NAME' => ucfirst($infos['name']),
        'SELECTED' => $selected
    ));
}

if( $search != '' )
{
    $results = array();
    
    // Ajout du paramétre search à tous les modules
    foreach( $searchModules as $module)
        $modulesArgs[$module->name] = array('search' => $search);
    
    // Ajout de la liste des paramètres de recherches spécifiques à chaque module
    foreach( $formsModule as $formModule)
    {
        if( $formModule->HasFunctionnality('GetSearchArgs') )
        {
            // Récupération de la liste des paramètres
            $formModuleArgs = $formModule->Functionnality('GetSearchArgs');
            // Ajout des paramètres optionnels sans les sécuriser.
            // Ils sont sécurisés à l'intérieur de chaque module.
            foreach( $formModuleArgs as $arg)
            {
                if ( isset($_POST[$arg]) )
                    $modulesArgs[$formModule->name][$arg] = $_POST[$arg];
            }
        }
    }
    
    // Génération des formulaires précomplétés et passage aux templates
    $searchForms = GetSearchForms($formsModule, $modulesArgs);
    foreach ( $searchForms as $moduleName => $form )
    {
        $module = $Modules->GetModule($moduleName);
        $infos = $module->GetInfo();
        $Template->Assign_block_vars('forms', array(
            'MODULE_NAME' => ucfirst($moduleName),
            'L_MODULE_NAME' => ucfirst($infos['name']),
            'SEARCH_FORM' => $form
        ));
    }
    
    $idsSearch = array();
    
    if ( $selectedModules !== array() )
    {
        $nbModules = count($searchModules);
        for ( $i = 0; $i < $nbModules; $i++ )
        {
            $module = $searchModules[$i];
            if ( !in_array($module->name, $selectedModules) )
                unset($searchModules[$i]);
        }
    }
    
    // Génération des résultats et passage aux templates
    $nbResults = GetSearchResults($search, $searchModules, $modulesArgs, $results, $idsSearch);
    
    foreach( $searchModules as $module)
    {
        $moduleInfos = $module->GetInfo();
        $Template->Assign_block_vars('results', array(
            'MODULE_NAME' => ucfirst($module->name),
            'L_MODULE_NAME' => ucfirst($moduleInfos['name']),
            'ID_SEARCH' => $idsSearch[$module->name],
        ));
    }
    
    $allhtmlResult = '';
    Get_HTML_Results($results, $allhtmlResult, $Modules, 'All');
    $allhtmlResult .= '<script type="text/javascript">
        <!--
            nbResults[\'All\'] = '.$nbResults.';
            
            if( browserAJAXFriendly() )
                show_div(\'resultsAll_0\');
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
        'ALL_RESULTS' => $allhtmlResult
    ));
    
    // parsage des formulaires de recherches
    $Template->Pparse('search_forms');
    // parsage des résultats de la recherche
    $Template->Pparse('search_results');
}
else
{
    // Génération des formulaires et passage aux templates
    $searchForms = GetSearchForms($formsModule, $modulesArgs);
    foreach( $searchForms as $moduleName => $form )
    {
        $module = $Modules->GetModule($moduleName);
        $infos = $module->GetInfo();
        $Template->Assign_block_vars('forms', array(
            'MODULE_NAME' => $moduleName,
            'L_MODULE_NAME' => ucfirst($infos['name']),
            'SEARCH_FORM' => $form
        ));
    }
    
    // parsage de la page
    $Template->Pparse('search_forms');
}

//--------------------------------------------------------------------- Footer
require_once('../includes/footer.php');

?>