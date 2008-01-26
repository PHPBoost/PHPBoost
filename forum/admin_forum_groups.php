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

require_once('../includes/admin_begin.php');
load_module_lang('forum', $CONFIG['lang']); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

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
	$array_auth_all = $groups->return_array_auth($auth_flood, $auth_edit_mark, $auth_topic_track, ADMIN_NOAUTH_DEFAULT);
		
	$CONFIG_FORUM['auth'] = serialize($array_auth_all);
	$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG_FORUM)) . "' WHERE name = 'forum'", __LINE__, __FILE__);

	###### Regénération du cache des catégories (liste déroulante dans le forum) #######
	$cache->generate_module_file('forum');

	redirect(HOST . SCRIPT);
}
else	
{		
	$template->set_filenames(array(
		'admin_forum_groups' => '../templates/' . $CONFIG['theme'] . '/forum/admin_forum_groups.tpl'
	));
	
	$array_groups = $groups->create_groups_array(); //Création du tableau des groupes.
	$array_auth = isset($CONFIG_FORUM['auth']) ? $CONFIG_FORUM['auth'] : array(); //Récupération des tableaux des autorisations et des groupes.
	
	$template->assign_vars(array(
		'NBR_GROUP' => count($array_groups),
		'FLOOD_AUTH' => $groups->generate_select_groups(1, $array_auth, 0x01),
		'EDIT_MARK_AUTH' => $groups->generate_select_groups(2, $array_auth, 0x02),
		'TRACK_TOPIC_AUTH' => $groups->generate_select_groups(3, $array_auth, 0x04),
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

	$template->pparse('admin_forum_groups'); // traitement du modele	
}

require_once('../includes/admin_footer.php');

?>