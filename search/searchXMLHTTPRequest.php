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

require_once('../includes/begin.php');
//------------------------------------------------------------------- Language
load_module_lang('search');

//--------------------------------------------------------------------- Params

$searchTxt = !empty($_POST['search']) ? securit($_POST['search']) : '';

//--------------------------------------------------------------------- Header
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

// Ajout du paramétre search à tous les modules
foreach( $searchModules as $module)
    $modulesArgs[$module->name] = array('search' => $searchTxt);

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

$results = array();
$idsSearch = array();

GetSearchResults($searchTxt, $searchModules, $modulesArgs, $results, $idsSearch, true);

// Propagation des nouveaux id_search
foreach ( $idsSearch as $moduleName => $id_search )
    echo 'idSearch[\''.$moduleName.'\'] = '.$id_search.';';
//--------------------------------------------------------------------- Footer

?>