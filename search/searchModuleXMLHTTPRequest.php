<?php
/*##################################################
*                         searchXMLHTTPRequest.php
*                            -------------------
*   begin                : January 27, 2008
*   copyright            : (C) 2008 Rouchon Lo�c
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

define ( 'NB_RESULTS_PER_PAGE', 10);

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
    if ( $Search->IsSearchIdInCache($idSearch) )
    {
        $nbResults = $Search->GetResultsById($results, $idSearch, ($pageNum - 1) *  NB_RESULTS_PER_PAGE, NB_LINES);
        if ( $nbResults > 0 )
        {
            $module = $Modules->GetModule($results[0]['module']);
            $htmlResults = '<ul class="search_results">';
            foreach ( $results as $result )
            {
                if( $module->HasFunctionnality('ParseSearchResult') )
                {
                    $htmlResult = $module->Functionnality('ParseSearchResult', array($result));
                }
                else
                {
                    $htmlResult  = '<div class="result">';
                    $htmlResult .= '<span><i>'.$result['relevance'].'</i></span> - ';
                    $htmlResult .= '<span><b>'.ucfirst($result['module']).'</b></span> - ';
                    $htmlResult .= '<a href="'.$result['link'].'">'.$result['title'].'</a>';
                    $htmlResult .= '</div>';
                }
                $htmlResults .= '<li>'.$htmlResult.'</li>';
            }
            $htmlResults .= '</ul>';
        }
        // Création de la pagination si le nombre de commentaires est trop important.
        include_once('../includes/pagination.class.php');
        $Pagination = new Pagination();
        $showPagin = $Pagination->Display_pagination(
            transid('search.php?module='.$module->name.'&amp;p=%d'),
            $nbResults,
            'p',
            NB_RESULTS_PER_PAGE,
            3
        );
        $showPagin = $nbResults > NB_RESULTS_PER_PAGE  ?  $showPagin : '';
        
        $return = ' var resultsAJAX = new Array();
                    resultsAJAX[\'nbResults\'] = \''.$nbResults.' '.
                    addslashes($nbResults > 1 ? $LANG['nb_results_found']:$LANG['one_result_found']).'\';
                    resultsAJAX[\'results\'] = \''.$htmlResults.'\';
                    resultsAJAX[\'pagination\'] = \''.$showPagin.'\';';
        echo $return;
    }
    else echo 'NO RESULTS IN CACHE';
}

//--------------------------------------------------------------------- Footer

?>