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

//------------------------------------------------------- Headers and Language
require_once ( '../includes/begin.php' );
load_module_lang ( 'search', $CONFIG['lang'] );
// define('ALTERNATIVE_CSS', 'search');

//------------------------------------------------------------- Other includes
require_once ( '../includes/modules.class.php' );
require_once ( '../search/search.inc.php' );


//--------------------------------------------------------------------- Params
define ( 'NB_RESULTS_PER_PAGE', 10 );

// A protéger impérativement;
$pageNum = !empty ( $_GET['pageNum'] ) ? numeric ($_GET['pageNum'] ) : 1;
$module = !empty ( $_GET['module'] ) ? securit ( $_GET['module'] ) : '';
$search = !empty ( $_GET['search'] ) ? securit ( $_GET['search'] ) : '';

//----------------------------------------------------------------------- Main

if ( $search != '')
{
    $results = Array ( );
    $modulesArgs = Array ( );
    $modules = new Modules ( );
    
    foreach ( array_keys ( $searchForms ) as $moduleName )
    {
        $module = $modules->GetModule ( $moduleName );
        if ( ( $module->GetErrors == 0 ) and ( $module->HasFunctionnalitie ( 'GetSearchArgs' ) )
        {
            $args = Array ( );
            $moduleArgs = $module->Functionnalitie ( 'GetSearchArgs' );
            
            foreach ( $moduleArgs as $arg )
            {
                array_push ( $args, $_POST[$arg] );
            }
            $modulesArgs[$module] = $args;
        }
    }
    
    // Affiche les formulaires prérempli
    $searchForms = GetSearchForms ( $search, $modulesArgs );
    
    $nbResults = GetSearchResults ( $search, $modulesArgs, $results, ($p - 1), ($p - 1 + NB_RESULTS_PER_PAGE ) );
}
else
{
    // Affiche les formulaires
    $searchForms = GetSearchForms ( );
}

//--------------------------------------------------------------------- Footer
require_once( '../includes/footer.php' );

?>