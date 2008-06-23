<?php
/*##################################################
*                         searchXMLHTTPRequest.php
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

define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/begin.php');
//------------------------------------------------------------------- Language
load_module_lang('search');

//--------------------------------------------------------------------- Params

$searchTxt = retrieve(POST, 'search', '');
$MODULE_NAME = strtolower(retrieve(POST, 'moduleName', ''));
$idSearch = retrieve(POST, 'idSearch', -1);

//--------------------------------------------------------------------- Header
//------------------------------------------------------------- Other includes
require_once('../kernel/framework/modules/modules.class.php');
require_once('../search/search.inc.php');

//----------------------------------------------------------------------- Main

$Modules = new Modules();
$modulesArgs = array();

if( ($idSearch >= 0) && ($MODULE_NAME != '') )
{
    echo 'var syncErr = false;';
    
    $Search = new Search();
    if( !$Search->is_search_id_in_cache($idSearch) )
    {
        // MAJ DES RESULTATS SI ILS NE SONT PLUS DANS LE CACHE
        // Listes des modules de recherches
        $searchModules = $Modules->get_available_modules('GetSearchRequest');
        
        // Chargement des modules avec formulaires
        $formsModule = $Modules->get_available_modules('GetSearchForm', $searchModules);
        
        // Ajout du paramétre search à tous les modules
        foreach( $searchModules as $module)
            $modulesArgs[$module->get_id()] = array('search' => $searchTxt);
        
        // Ajout de la liste des paramètres de recherches spécifiques à chaque module
        foreach( $formsModule as $formModule)
        {
            if( $formModule->has_functionnality('GetSearchArgs') )
            {
                // Récupération de la liste des paramètres
                $formModuleArgs = $formModule->functionnality('GetSearchArgs');
                // Ajout des paramètres optionnels sans les sécuriser.
                // Ils sont sécurisés à l'intérieur de chaque module.
                foreach( $formModuleArgs as $arg)
                {
                    if ( isset($_POST[$arg]) )
                        $modulesArgs[$formModule->get_id()][$arg] = $_POST[$arg];
                }
            }
        }
        
        $results = array();
        $idsSearch = array();
        
        get_search_results($searchTxt, $searchModules, $modulesArgs, $results, $idsSearch, true);
        
        // Propagation des nouveaux id_search
        foreach ( $idsSearch as $moduleName => $id_search )
        {
            $Search->id_search[$moduleName] = $id_search;
            echo 'idSearch[\''.$moduleName.'\'] = '.$id_search.';';
        }
    }
    else $Search->id_search[$MODULE_NAME] = $idSearch;
    
    echo   'var resultsAJAX = new Array();';
    
    $nbResults = $Search->get_results_by_id($results, $Search->id_search[$MODULE_NAME]);
    if( $nbResults > 0 )
    {
        $module = $Modules->get_module($MODULE_NAME);
        $htmlResults = '';
        get_html_results($results, $htmlResults, $Modules, $MODULE_NAME);
    
        echo   'nbResults[\''.$MODULE_NAME.'\'] = '.$nbResults.';
                resultsAJAX[\'nbResults\'] = \''.$nbResults.' '.addslashes($nbResults > 1 ? $LANG['nb_results_found']:$LANG['one_result_found']).'\';
                resultsAJAX[\'results\'] = \''.str_replace(array("\n", '\''), array("\\\n", '\\\''), $htmlResults).'\';';
    }
    else
    {
        echo   'nbResults[\''.$MODULE_NAME.'\'] = 0;
                resultsAJAX[\'nbResults\'] = \''.addslashes($LANG['no_results_found']).'\';
                resultsAJAX[\'results\'] = \'\';';
    }
}
else echo 'var syncErr = true;';
//--------------------------------------------------------------------- Footer

?>