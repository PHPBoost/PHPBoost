<?php
/*##################################################
 *                               admin_forum_groups.php
 *                            -------------------
 *   begin                : October 30, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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

include_once('../includes/admin_begin.php');
include_once('../forum/lang/' . $CONFIG['lang'] . '/forum_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

$class = ( !empty($_GET['id'])) ? numeric($_GET['id']) : 0;
$top = ( !empty($_GET['top'])) ? securit($_GET['top']) : '' ;
$bottom = ( !empty($_GET['bot'])) ? securit($_GET['bot']) : '' ;

$cache->load_file('forum');

//Si c'est confirmé on execute
if( !empty($_POST['valid']) )
{
	$auth_flood = isset($_POST['groups_auth1']) ? $_POST['groups_auth1'] : ''; 
	$auth_edit_mark = isset($_POST['groups_auth2']) ? $_POST['groups_auth2'] : ''; 
	$auth_topic_track = isset($_POST['groups_auth3']) ? $_POST['groups_auth3'] : '';
	
	//Génération du tableau des droits.
	$array_auth_all = $groups->return_array_auth($auth_flood, $auth_edit_mark, $auth_topic_track, false);
		
	$CONFIG_FORUM['auth'] = serialize($array_auth_all);
	$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG_FORUM)) . "' WHERE name = 'forum'", __LINE__, __FILE__);

	###### Regénération du cache des catégories (liste déroulante dans le forum) #######
	$cache->generate_module_file('forum');

	header('location:' . HOST . SCRIPT);
	exit;
}
else	
{		
	$template->set_filenames(array(
		'admin_forum_groups' => '../templates/' . $CONFIG['theme'] . '/forum/admin_forum_groups.tpl'
	));
	
	$array_groups = array();
	//Création du tableau des groupes.
	foreach($_array_groups_auth as $idgroup => $array_group_info)
		$array_groups[$idgroup] = $array_group_info[0];
		
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'MODULE_DATA_PATH' => $template->module_data_path('forum'),
		'NBR_GROUP' => count($array_groups),
		'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
		'L_CAT_MANAGEMENT' => $LANG['cat_management'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_FORUM_CONFIG' => $LANG['forum_config'],
		'L_FORUM_GROUPS' => $LANG['forum_groups_config'],
		'EXPLAIN_FORUM_GROUPS' => $LANG['explain_forum_groups'],
		'L_FLOOD' => $LANG['flood_auth'],
		'L_EDIT_MARK' => $LANG['edit_mark_auth'],
		'L_TRACK_TOPIC' => $LANG['track_topic_auth'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none']
	));
	
	//Création du tableau des rangs.
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	
	//Génération d'une liste à sélection multiple des rangs et groupes
	function generate_select_groups($array_auth, $auth_id, $auth_level)
	{
		global $array_groups, $array_ranks, $LANG, $CONFIG_FORUM;
		
		$j = 0;
		//Liste des rangs
		$select_groups = '<select id="groups_auth' . $auth_id . '" name="groups_auth' . $auth_id . '[]" size="8" multiple="multiple"><optgroup label="' . $LANG['ranks'] . '">';
		foreach($array_ranks as $idgroup => $group_name)
		{
			$selected = '';	
			if( isset($CONFIG_FORUM['auth']['r' . $idgroup]) && ((int)$CONFIG_FORUM['auth']['r' . $idgroup] & (int)$auth_level) !== 0 )
				$selected = 'selected="selected"';
							
			$select_groups .=  '<option value="r' . $idgroup . '" id="' . $auth_id . 'r' . $j . '" ' . $selected . ' onclick="check_select_multiple_ranks(\'' . $auth_id . 'r\', ' . $j . ')">' . $group_name . '</option>';
			$j++;
		}
		$select_groups .=  '</optgroup>';
		
		//Liste des groupes.
		$j = 0;
		$select_groups .= '<optgroup label="' . $LANG['groups'] . '">';
		foreach($array_groups as $idgroup => $group_name)
		{
			$selected = '';		
			if( isset($CONFIG_FORUM['auth'][$idgroup]) && ((int)$CONFIG_FORUM['auth'][$idgroup] & (int)$auth_level) !== 0 )
				$selected = 'selected="selected"';

			$select_groups .= '<option value="' . $idgroup . '" id="' . $auth_id . 'g' . $j . '" ' . $selected . '>' . $group_name . '</option>';
			$j++;
		}
		$select_groups .= '</optgroup></select>';
		
		return $select_groups;
	}
	
	//Récupération des tableaux des autorisations et des groupes.
	$array_auth = isset($CONFIG_FORUM['auth']) ? $CONFIG_FORUM['auth'] : array();
	
	//On assigne les variables pour le POST en précisant l'idurl.	
	$template->assign_vars(array(
		'FLOOD_AUTH' => generate_select_groups($array_auth, 1, 1),
		'EDIT_MARK_AUTH' => generate_select_groups($array_auth, 2, 2),
		'TRACK_TOPIC_AUTH' => generate_select_groups($array_auth, 3, 4)
	));

	$template->pparse('admin_forum_groups'); // traitement du modele	
}

include_once('../includes/admin_footer.php');

?>