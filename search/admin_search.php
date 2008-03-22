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
    $SEARCH_CONFIG['forum_name'] = !empty($_POST['forum_name']) ? stripslashes(securit($_POST['forum_name'])) : $CONFIG['site_name'] . ' forum';
    $SEARCH_CONFIG['pagination_topic'] = !empty($_POST['pagination_topic']) ? numeric($_POST['pagination_topic']) : '20';
    $SEARCH_CONFIG['pagination_msg'] = !empty($_POST['pagination_msg']) ? numeric($_POST['pagination_msg']) : '15';
    $SEARCH_CONFIG['view_time'] = !empty($_POST['view_time']) ? (numeric($_POST['view_time']) * 3600 * 24) : (30 * 3600 * 24);
    $SEARCH_CONFIG['topic_track'] = !empty($_POST['topic_track']) ? numeric($_POST['topic_track']) : '40';
    $SEARCH_CONFIG['edit_mark'] = !empty($_POST['edit_mark']) ? numeric($_POST['edit_mark']) : 0;
    $SEARCH_CONFIG['explain_display_msg'] = !empty($_POST['explain_display_msg']) ? stripslashes(securit($_POST['explain_display_msg'])) : '';
    
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

    $Cache->Load_file('forum');
    
    //Gestion erreur.
    $get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
    if( $get_error == 'incomplete' )
        $Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
    
    $CONFIG_FORUM['edit_mark'] = isset($CONFIG_FORUM['edit_mark']) ? $CONFIG_FORUM['edit_mark'] : 0;
    $CONFIG_FORUM['display_connexion'] = isset($CONFIG_FORUM['display_connexion']) ? $CONFIG_FORUM['display_connexion'] : 0;
    $CONFIG_FORUM['no_left_column'] = isset($CONFIG_FORUM['no_left_column']) ? $CONFIG_FORUM['no_left_column'] : 0;
    $CONFIG_FORUM['no_right_column'] = isset($CONFIG_FORUM['no_right_column']) ? $CONFIG_FORUM['no_right_column'] : 0;
    $CONFIG_FORUM['activ_display_msg'] = isset($CONFIG_FORUM['activ_display_msg']) ? $CONFIG_FORUM['activ_display_msg'] : 0;
    $CONFIG_FORUM['icon_display_msg'] = isset($CONFIG_FORUM['icon_display_msg']) ? $CONFIG_FORUM['icon_display_msg'] : 1;

    $Template->Assign_vars(array(
        'THEME' => $CONFIG['theme'],
        'L_SEARCH_MANAGEMENT' => $LANG['search_management'],
        'L_SEARCH_CONFIG' => $LANG['search_config'],
        'L_CACHE_TIME' => $LANG['search_config'],
        'L_NB_RESULTS_P' => $LANG['cache_time'],
        'L_MAX_USE' => $LANG['max_use'],
        'L_MAX_USE_EXPLAIN' => $LANG['max_use_explain'],
        'L_CLEAR_OUT_CACHE' => $LANG['clear_out_cache'],
        'L_UPDATE' => $LANG['update'],
        'L_RESET' => $LANG['reset']
    ));

    $Template->Pparse('admin_search');
}

//--------------------------------------------------------------------- Footer
require_once('../includes/admin_footer.php');

?>