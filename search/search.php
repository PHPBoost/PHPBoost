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
$p = 0;

//--------------------------------------------------------------------- Header

if( !empty($search) )
    define('TITLE', $LANG['title_search'].' : '.addslashes($search));
else
    define('TITLE', $LANG['title_search']);
require_once('../includes/header.php');

$Template->Assign_vars(Array(
    'TITLE_SEARCH' => TITLE,
    'SEARCH' => $LANG['title_search'],
    'TEXT_SEARCHED' => $search,
    'SEARCH_MIN_LENGTH' => $LANG['search_min_length'],
    'WARNING_LENGTH_STRING_SEARCH' => $LANG['warning_length_string_searched'],
    'FORMS' => $LANG['forms'],
    'ADVANCED_SEARCH' => $LANG['advanced_search'],
    'SIMPLE_SEARCH' => $LANG['simple_search']
));

//------------------------------------------------------------- Other includes
require_once('../includes/modules.class.php');
require_once('../search/search.inc.php');

//----------------------------------------------------------------------- Main

$Modules = new Modules();
$modulesArgs = array();

if( $search != '' )
{
    $Template->Assign_vars(Array(
        'TITLE_ALL_RESULTS' => $LANG['title_all_results'],
        'RESULTS' => $LANG['results'],
        'RESULTS_CHOICE' => $LANG['results_choice'],
        'PRINT' => $LANG['print']
    ));
    
    $results = array();
    
    // Listes des modules de recherches
    $searchModules = $Modules->GetAvailablesModules('GetSearchRequest');
    
    // Ajout du paramétre search à tous les modules
    foreach( $searchModules as $module)
    {
        $modulesArgs[$module->name] = array('search' => $search);
    }
    
    // Chargement des modules avec formulaires
    $formsModule = $Modules->GetAvailablesModules('GetSearchForm', $searchModules);
    
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
        $Template->Assign_block_vars('forms', array(
            'MODULE_NAME' => ucfirst($moduleName),
            'SEARCH_FORM' => $form
        ));
    }
    
    $idsSearch = array();
    // Génération des résultats et passage aux templates
    $nbResults = GetSearchResults($search, $searchModules, $modulesArgs, $results, $idsSearch);
    
    foreach( $searchModules as $module)
    {
        $Template->Assign_block_vars('results', array(
            'MODULE_NAME' => ucfirst($module->name),
            'ID_SEARCH' => $idsSearch[$module->name],
        ));
    }
    
    $allhtmlResult = '';
    Get_HTML_Results($results, $allhtmlResult, $Modules, 'All');
    $allhtmlResult .= '<script type="text/javascript">
        <!--
            if( browserAJAXFriendly() )
                show_div(\'resultsAll_0\');
        -->
        </script>';
    
    $Template->Assign_vars(Array(
        'NB_RESULTS_FOUND' => $nbResults > 1 ? $LANG['nb_results_found'] : $LANG['one_result_found'],
        'SEARCH_RESULTS' => $LANG['search_results'],
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
    // Listes des modules de recherches
    $searchModules = $Modules->GetAvailablesModules('GetSearchRequest');
    // Chargement des modules avec formulaires
    $formsModule = $Modules->GetAvailablesModules('GetSearchForm', $searchModules);
    
    $modulesArgs = array();
    
    // Génération des formulaires et passage aux templates
    $searchForms = GetSearchForms($formsModule, $modulesArgs);
    foreach( $searchForms as $moduleName => $form )
    {
        $Template->Assign_block_vars('forms', array(
            'MODULE_NAME' => $moduleName,
            'SEARCH_FORM' => $form
        ));
    }
    
    // parsage de la page
    $Template->Pparse('search_forms');
}

//--------------------------------------------------------------------- Footer
require_once('../includes/footer.php');

?>