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

$Cache->Load_file('search');

//Si c'est confirmé on execute
if( !empty($_POST['valid']) )
{
    $SEARCH_CONFIG['cache_time'] = !empty($_POST['cache_time']) ? numeric($_POST['cache_time']) : (15 * 60);
    $SEARCH_CONFIG['nb_results_per_page'] = !empty($_POST['nb_results_per_page']) ? numeric($_POST['nb_results_per_page']) : 15;
    $SEARCH_CONFIG['max_use'] = !empty($_POST['max_use']) ? numeric($_POST['max_use']) : 100;
    
    global $Sql;
    
    $cfg = addslashes(serialize($SEARCH_CONFIG));
    $request = "UPDATE ".PREFIX."configs SET value = '".$cfg."' WHERE name = 'search'";
    $Sql->Query_inject($request, __LINE__, __FILE__);
    if ( $Sql->Sql_affected_rows($request, __LINE__, __FILE__) == 0 )
        $Sql->Query_inject("INSERT INTO ".PREFIX."configs (`name`, `value`) VALUES ('search', '".$cfg."')", __LINE__, __FILE__);
    
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
    
    //Gestion erreur.
    $get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
    if( $get_error == 'incomplete' )
        $Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
    
    $SEARCH_CONFIG['cache_time'] = round(!empty($SEARCH_CONFIG['cache_time']) ? numeric($SEARCH_CONFIG['cache_time']) : (15 * 60) / 60);
    $SEARCH_CONFIG['nb_results_per_page'] = !empty($SEARCH_CONFIG['nb_results_per_page']) ? numeric($SEARCH_CONFIG['nb_results_per_page']) : 15;
    $SEARCH_CONFIG['max_use'] = !empty($SEARCH_CONFIG['max_use']) ? numeric($SEARCH_CONFIG['max_use']) : 100;

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
        'CACHE_TIME' => $SEARCH_CONFIG['cache_time'],
        'NB_RESULTS_P' => $SEARCH_CONFIG['nb_results_per_page'],
        'MAX_USE' => $SEARCH_CONFIG['max_use']
    ));

    $Template->Pparse('admin_search');
}

//--------------------------------------------------------------------- Footer
require_once('../includes/admin_footer.php');

?>