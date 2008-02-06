<?php
/*##################################################
 *                               search.inc.php
 *                            -------------------
 *   begin                : february 5, 2008
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

require_once ( '../includes/modules.class.php' );
require_once ( '../includes/search.class.php' );

function GetSearchForms ( &$modules, &$args )
/**
 *  Affiche les formulaires de recherches pour tous les modules.
 */
{
    $searchForms = Array ( );
    foreach ( $modules as $module )
    {
        $searchForms[$module->name] = $module->Functionnalitie ( 'GetSearchForm', $args );
    }
    
    return $searchForms;
}

function GetSearchResults ( &$search, &$modules, &$results, $offset = 0, $nbResults = 10 )
/**
 *  Exécute la recherche si les résultats ne sont pas dans le cache et
 *  renvoie les résultats.
 */
{
    $requests = Array ( );
    
    foreach ( $modules as $module => $args )
    {
        $requests[$module->name] = $module->Functionnalitie ( 'GetSearchRequest', $search, $args );
    }
    
    $search = new Search ( $search, $modules )
    
    $search->InsertResults ( $request );
    
    return $search->GetResults ( &$results, &$id_modules, $offset = 0, $nbLines = NB_LINES);
}

function GetSearchFormsAvailablesModules ( &$searchModules = Array ( ) )
/**
 *  Renvoie la liste des modules disposants d'un formulaires de recherche
 *  spécialisé
 */
{
    return $modules->GetAvailablesModules ( 'GetSearchForm', $searchModules );
}

function GetSearchAvailablesModules ( )
/**
 *  Renvoie la liste des modules disposants de la fonctionnalité de recherche
 */
{
    return $modules->GetAvailablesModules ( 'GetSearchRequest' );
}

?>