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
$idSearch = !empty($_GET['idSearch']) ? numeric($_GET['idSearch']) : -1;
$pageNum = !empty($_GET['pageNum']) ? numeric($_GET['pageNum']) : 1;

//--------------------------------------------------------------------- Header
//------------------------------------------------------------- Other includes
require_once('../includes/modules.class.php');
require_once('../search/search.inc.php');

//----------------------------------------------------------------------- Main

$Modules = new Modules();
$modulesArgs = array();

$results = array();

$searchInCache = false;

if ( !$searchInCache )
{
	echo 'DEBUT MAJ RESULTATS';
    // Listes des modules de recherches
    $searchModules = $Modules->GetAvailablesModules('GetSearchRequest');
    
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
    
    $Search = new Search($searchTxt, $modulesOptions);
    
    $requests = array();
    foreach($searchModules as $module)
    {
        if( !$Search->IsInCache($module->name) )
        {
            // On rajoute l'identifiant de recherche comme paramètre pour faciliter la requête
            $modulesArgs[$module->name]['id_search'] = $Search->id_search[$module->name];
            $requests[$module->name] = $module->Functionnality('GetSearchRequest', $modulesArgs[$module->name]);
        }
    }
    
    $Search->InsertResults($requests);
	
	echo 'var idSearch = new Array();';
	
	// Propagation des nouveaux id_search
	foreach ( $Search->id_search as $moduleName => $id_search )
		echo 'idSearch[\''.$moduleName.'\'] = '.$id_search;
}
//--------------------------------------------------------------------- Footer

?>