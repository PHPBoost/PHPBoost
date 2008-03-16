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

if( $idSearch >= 0 )
{
    $Search = new Search();
    if( $Search->IsSearchIdInCache($idSearch) )
    {
        $nbResults = $Search->GetResultsById($results, $idSearch);
        if( $nbResults > 0 )
        {
            $module = $Modules->GetModule($results[0]['module']);
            $htmlResults = '';
            Get_HTML_Results($results, $htmlResults, $Modules, $module->name);
        }
        
        $showPagin = '';
        // Création de la pagination si le nombre de commentaires est trop important.
        $return = ' var resultsAJAX = new Array();
                    resultsAJAX[\'nbResults\'] = \''.$nbResults.' '.addslashes($nbResults > 1 ? $LANG['nb_results_found']:$LANG['one_result_found']).'\';
                    resultsAJAX[\'results\'] = \''.addslashes($htmlResults).'\';
                    resultsAJAX[\'pagination\'] = \''.addslashes($showPagin).'\';';
        echo $return;
    }
    else echo 'NO RESULTS IN CACHE';
}

//--------------------------------------------------------------------- Footer

?>