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

require_once('../kernel/admin_begin.php');

//------------------------------------------------------------------- Language
load_module_lang('search'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);

//--------------------------------------------------------------------- Header
require_once('../kernel/admin_header.php');

//--------------------------------------------------------------------- Params
$id_post = retrieve(POST, 'idc', 0);
$clearOutCache = !empty($_GET['clear']) ? true : false;

//Si c'est confirmé on execute
if( !empty($_POST['valid']) )
{
    // Configuration de la classe search.class.php
    $CONFIG['search_cache_time'] = retrieve(POST, 'cache_time', 15);
    $CONFIG['search_max_use'] = retrieve(POST, 'max_use', 200);
    
    // Configuration du module 'Search'
    $SEARCH_CONFIG = array();
    $SEARCH_CONFIG['nb_results_per_page'] = retrieve(POST, 'nb_results_p', 15);
    $SEARCH_CONFIG['authorised_modules'] = retrieve(POST, 'authorised_modules', array());
    
    // Enregistrement des modifications de la config
    $config_string = addslashes(serialize($CONFIG));
    $request = "UPDATE ".PREFIX."configs SET value = '".$config_string."' WHERE name = 'config'";
    $Sql->Query_inject($request, __LINE__, __FILE__);
    
    // Enregistrement des modifications de la config du module 'Search'
    $search_cfg = addslashes(serialize($SEARCH_CONFIG));
    $request = "UPDATE ".PREFIX."configs SET value = '".$search_cfg."' WHERE name = 'search'";
    $Sql->Query_inject($request, __LINE__, __FILE__);
    
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
    require_once('../kernel/framework/template.class.php');
    $Tpl = new Template('search/admin_search.tpl');
    
    $Cache->Load_file('search');
    
    require_once('../kernel/framework/modules/modules.class.php');
    
	$SEARCH_CONFIG['nb_results_per_page'] = isset($SEARCH_CONFIG['nb_results_per_page']) ? $SEARCH_CONFIG['nb_results_per_page'] : 15;
	$SEARCH_CONFIG['search_cache_time'] = isset($SEARCH_CONFIG['search_cache_time']) ? $SEARCH_CONFIG['search_cache_time'] : 15;
    $SEARCH_CONFIG['search_max_use'] = isset($SEARCH_CONFIG['search_max_use']) ? $SEARCH_CONFIG['search_max_use'] : 200;
	$SEARCH_CONFIG['authorised_modules'] = isset($SEARCH_CONFIG['authorised_modules']) && is_array($SEARCH_CONFIG['authorised_modules']) ? $SEARCH_CONFIG['authorised_modules'] : array();

	$Modules = new Modules();
//     $searchModules = $Modules->get_available_modules('get_search_request');
    $searchModules = $Modules->get_available_modules('get_search_request');
    
    foreach( $searchModules as $module )
    {
        if ( in_array($module->get_id(), $SEARCH_CONFIG['authorised_modules']) )
            $selected = ' selected="selected"';
        else
            $selected = '';

        $Tpl->Assign_block_vars('authorised_modules', array(
            'MODULE' => $module->get_id(),
            'SELECTED' => $selected,
            'L_MODULE_NAME' => ucfirst($module->get_name())
        ));
    }
    
    $Tpl->Assign_vars(array(
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
        'L_SEARCH_CACHE' => $LANG['search_cache'],
        'CACHE_TIME' => $SEARCH_CONFIG['search_cache_time'],
        'MAX_USE' => $SEARCH_CONFIG['search_max_use'],
        'NB_RESULTS_P' => $SEARCH_CONFIG['nb_results_per_page']
    ));

    $Tpl->parse();
}

//--------------------------------------------------------------------- Footer
require_once('../kernel/admin_footer.php');

?>