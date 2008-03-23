<?php
/*##################################################
 *                               admin_forum_config.php
 *                            -------------------
 *   begin                : March 22, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : horn@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/admin_begin.php');

//------------------------------------------------------------------- Language
load_module_lang('search'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);

//--------------------------------------------------------------------- Header
require_once('../includes/admin_header.php');

//--------------------------------------------------------------------- Params
$id_post = !empty($_POST['idc']) ? numeric($_POST['idc']) : '';
$clearOutCache = !empty($_GET['clear']) ? true : false;

//Si c'est confirmé on execute
if( !empty($_POST['valid']) )
{
    global $CONFIG;
    global $Sql;
    
    // Configuration de la classe search.class.php
    $CONFIG['search_cache_time'] = !empty($_POST['cache_time']) ? numeric($_POST['cache_time']) : (15 * 60);
    $CONFIG['search_max_use'] = !empty($_POST['max_use']) ? numeric($_POST['max_use']) : 100;
    
    // Configuration du module 'Search'
    $SEARCH_CONFIG = array();
    $SEARCH_CONFIG['nb_results_per_page'] = !empty($_POST['nb_results_p']) ? numeric($_POST['nb_results_p']) : 15;
    $SEARCH_CONFIG['authorised_modules'] = !empty($_POST['authorised_modules']) ? $_POST['authorised_modules'] : array();
    
    // Enregistrement des modifications de la config
    $config_string = addslashes(serialize($CONFIG));
    $request = "UPDATE ".PREFIX."configs SET value = '".$config_string."' WHERE name = 'config'";
    $Sql->Query_inject($request, __LINE__, __FILE__);
//     if ( $Sql->Sql_affected_rows($request, __LINE__, __FILE__) != 1 )
//         $Sql->Query_inject("INSERT INTO ".PREFIX."configs (`name`, `value`) VALUES ('search', '".$config_string."')", __LINE__, __FILE__);
    
    // Enregistrement des modifications de la config du module 'Search'
    $search_cfg = addslashes(serialize($SEARCH_CONFIG));
    $request = "UPDATE ".PREFIX."configs SET value = '".$search_cfg."' WHERE name = 'search'";
    $Sql->Query_inject($request, __LINE__, __FILE__);
//     if ( $Sql->Sql_affected_rows($request, __LINE__, __FILE__) != 1 )
//         $Sql->Query_inject("INSERT INTO ".PREFIX."configs (`name`, `value`) VALUES ('search', '".$cfg."')", __LINE__, __FILE__);
    
    // Génération des nouveaux fichiers de cache
    $Cache->Generate_file('config');
    $Cache->Generate_module_file('search');
    
    redirect(HOST.SCRIPT);
}
elseif( $clearOutCache ) // On vide le contenu du cache de la recherche
{
    $Sql->Query_inject("TRUNCATE ".PREFIX."search_results", __LINE__, __FILE__);
    $Sql->Query_inject("TRUNCATE ".PREFIX."search_index", __LINE__, __FILE__);
    redirect(HOST.SCRIPT);
}
else
{
    $Template->Set_filenames(array(
        'admin_search' => '../templates/' . $CONFIG['theme'] . '/search/admin_search.tpl'
    ));
    
    $Cache->Load_file('search');
    global $SEARCH_CONFIG;
    
    require_once('../includes/modules.class.php');
    
    $Modules = new Modules();
    $searchModules = $Modules->GetAvailablesModules('GetSearchRequest');
    
    foreach( $searchModules as $module )
    {
        if ( in_array($module->name, $SEARCH_CONFIG['authorised_modules']) )
            $selected = ' selected="selected"';
        else
            $selected = '';
        
        $moduleInfos = $module->GetInfo();
        $Template->Assign_block_vars('authorised_modules', array(
            'MODULE' => $module->name,
            'SELECTED' => $selected,
            'L_MODULE_NAME' => ucfirst($moduleInfos['name'])
        ));
    }
    
    $Template->Assign_vars(array(
        'THEME' => $CONFIG['theme'],
        'L_SEARCH_MANAGEMENT' => $LANG['search_management'],
        'L_SEARCH_CONFIG' => $LANG['search_config'],
        'L_CACHE_TIME' => $LANG['cache_time'],
        'L_CACHE_TIME_EXPLAIN' => $LANG['cache_time_explain'],
        'L_NB_RESULTS_P' => $LANG['nb_results_per_page'],
        'L_MAX_USE' => $LANG['max_use'],
        'L_MAX_USE_EXPLAIN' => $LANG['max_use_explain'],
        'L_CLEAR_OUT_CACHE' => $LANG['clear_out_cache'],
        'L_AUTHORISED_MODULES' => $LANG['authorised_modules'],
        'L_AUTHORISED_MODULES_EXPLAIN' => $LANG['authorised_modules_explain'],
        'L_UPDATE' => $LANG['update'],
        'L_RESET' => $LANG['reset'],
        'CACHE_TIME' => $CONFIG['search_cache_time'],
        'MAX_USE' => $CONFIG['search_max_use'],
        'NB_RESULTS_P' => $SEARCH_CONFIG['nb_results_per_page']
    ));

    $Template->Pparse('admin_search');
}

//--------------------------------------------------------------------- Footer
require_once('../includes/admin_footer.php');

?>