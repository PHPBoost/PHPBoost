<?php
/*##################################################
*                         searchXMLHTTPRequest.php
*                            -------------------
*   begin                : January 27, 2008
*   copyright            : (C) 2008 Rouchon Loïc
*   email                : loic.rouchon@phpboost.com
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

$search_txt = retrieve(POST, 'q', '');
$module_id = strtolower(retrieve(POST, 'moduleName', ''));
$id_search = retrieve(POST, 'idSearch', -1);
$selected_modules = retrieve(POST, 'searched_modules', array());
//------------------------------------------------------------- Other includes
import('modules/ModulesDiscoveryService');
require_once(PATH_TO_ROOT . '/search/search.inc.php');


//----------------------------------------------------------------------- Main

$modules = new ModulesDiscoveryService();
$modules_args = array();

if (($id_search >= 0) && ($module_id != ''))
{
    echo 'var syncErr = false;';
    
    $search = new Search();
    if (!$search->is_search_id_in_cache($id_search))
    {   // MAJ DES RESULTATS SI ILS NE SONT PLUS DANS LE CACHE
        // Listes des modules de recherches
        $search_modules = array();
        $all_search_modules = $modules->get_available_modules('get_search_request');
        foreach ($all_search_modules as $search_module)
        {
            if (in_array($search_module->get_id(), $selected_modules))
                $search_modules[] = $search_module;
        }
        
        // Chargement des modules avec formulaires
        $forms_module = $modules->get_available_modules('get_search_form', $search_modules);
        
        // Ajout du paramètre search à tous les modules
        foreach ($search_modules as $module)
            $modules_args[$module->get_id()] = array('search' => $search_txt);
        
        // Ajout de la liste des paramètres de recherches spécifiques à chaque module
        foreach ($forms_module as $form_module)
        {
            if ($form_module->has_functionality('get_search_args'))
            {
                // Récupération de la liste des paramètres
                $form_module_args = $form_module->functionality('get_search_args');
                // Ajout des paramètres optionnels sans les sécuriser.
                // Ils sont sécurisés à l'intérieur de chaque module.
                foreach ($form_module_args as $arg)
                {
                    if ( isset($_POST[$arg]) )
                        $modules_args[$form_module->get_id()][$arg] = $_POST[$arg];
                }
            }
        }
        
        $results = array();
        $ids_search = array();
        
        get_search_results($search_txt, $search_modules, $modules_args, $results, $ids_search, true);
        
        if (empty($ids_search[$module_id]))
        {
            $ids_search[$module_id] = 0;
        }
        
        // Propagation des nouveaux id_search
        foreach ( $ids_search as $module_name => $id_search )
        {
            $search->id_search[$module_name] = $id_search;
            echo 'idSearch[\'' . $module_name . '\'] = ' . $id_search . ';';
        }
    }
    else
    {
        $search->id_search[$module_id] = $id_search;
    }
    echo   'var resultsAJAX = new Array();';
    $nb_results = $search->get_results_by_id($results, $search->id_search[$module_id]);;
    if ($nb_results > 0)
    {
        $module = $modules->get_module($module_id);
        $html_results = '';
        get_html_results($results, $html_results, $module_id);
    
        echo   'nbResults[\'' . $module_id . '\'] = ' . $nb_results . ';
                resultsAJAX[\'nbResults\'] = \'' . $nb_results . ' '.addslashes($nb_results > 1 ? $LANG['nb_results_found'] : $LANG['one_result_found']) . '\';
                resultsAJAX[\'results\'] = \''.str_replace(array("\r", "\n", '\''), array('', ' ', '\\\''), $html_results) . '\';';
    }
    else
    {
        echo   'nbResults[\'' . $module_id . '\'] = 0;
                resultsAJAX[\'nbResults\'] = \''.addslashes($LANG['no_results_found']) . '\';
                resultsAJAX[\'results\'] = \'\';';
    }
}
else
{
    echo 'var syncErr = true;';
}

?>