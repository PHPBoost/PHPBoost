<?php
/*##################################################
 *                               admin_forum_groups.php
 *                            -------------------
 *   begin                : October 30, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

require_once('../admin/admin_begin.php');
load_module_lang('forum'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);

require_once('../forum/forum_begin.php');
require_once('../admin/admin_header.php');

$class = retrieve(GET, 'id', 0);
$top = retrieve(GET, 'top', '');
$bottom = retrieve(GET, 'bot', '');

//Si c'est confirmé on execute
if (!empty($_POST['valid']))
{
	//Génération du tableau des droits.
	$array_auth_all = Authorizations::build_auth_array_from_form(FLOOD_FORUM, EDIT_MARK_FORUM, TRACK_TOPIC_FORUM, ADMIN_NOAUTH_DEFAULT);
		
	$CONFIG_FORUM['auth'] = serialize($array_auth_all);
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_FORUM)) . "' WHERE name = 'forum'", __LINE__, __FILE__);

	###### Regénération du cache des catégories (liste déroulante dans le forum) #######
	$Cache->Generate_module_file('forum');

	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
else	
{		
	$Template->set_filenames(array(
		'admin_forum_groups'=> 'forum/admin_forum_groups.tpl'
	));
	
	$array_auth = isset($CONFIG_FORUM['auth']) ? $CONFIG_FORUM['auth'] : array(); //Récupération des tableaux des autorisations et des groupes.
	
	$Template->put_all(array(
		'FLOOD_AUTH' => Authorizations::generate_select(FLOOD_FORUM, $array_auth),
		'EDIT_MARK_AUTH' => Authorizations::generate_select(EDIT_MARK_FORUM, $array_auth),
		'TRACK_TOPIC_AUTH' => Authorizations::generate_select(TRACK_TOPIC_FORUM, $array_auth),
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
		'L_RESET' => $LANG['reset']
	));

	$Template->pparse('admin_forum_groups'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>