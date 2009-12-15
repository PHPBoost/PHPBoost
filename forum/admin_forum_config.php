<?php
/*##################################################
 *                               admin_forum_config.php
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
require_once('../admin/admin_header.php');

$get_id = retrieve(GET, 'id', 0);	
$id_post = retrieve(POST, 'idc', 0);  
$update_cached = !empty($_GET['upd']) ? true : false;	

$Cache->load('forum');

//Si c'est confirmé on execute
if (!empty($_POST['valid']))
{
	$CONFIG_FORUM['forum_name'] = stripslashes(retrieve(POST, 'forum_name', $CONFIG['site_name'] . ' forum'));  
	$CONFIG_FORUM['pagination_topic'] = retrieve(POST, 'pagination_topic', 20);  
	$CONFIG_FORUM['pagination_msg'] = retrieve(POST, 'pagination_msg', 15);
	$CONFIG_FORUM['view_time'] = retrieve(POST, 'view_time', 30) * 3600 * 24;
	$CONFIG_FORUM['topic_track'] = retrieve(POST, 'topic_track', 40);
	$CONFIG_FORUM['edit_mark'] = retrieve(POST, 'edit_mark', 0);
	$CONFIG_FORUM['display_connexion'] = retrieve(POST, 'display_connexion', 0);
	$CONFIG_FORUM['no_left_column'] = retrieve(POST, 'no_left_column', 0);
	$CONFIG_FORUM['no_right_column'] = retrieve(POST, 'no_right_column', 0);
	$CONFIG_FORUM['activ_display_msg']  = retrieve(POST, 'activ_display_msg', 0);
	$CONFIG_FORUM['display_msg'] = stripslashes(retrieve(POST, 'display_msg', ''));
	$CONFIG_FORUM['explain_display_msg'] = stripslashes(retrieve(POST, 'explain_display_msg', ''));	
	$CONFIG_FORUM['explain_display_msg_bis'] = stripslashes(retrieve(POST, 'explain_display_msg_bis', ''));
	$CONFIG_FORUM['icon_activ_display_msg'] = retrieve(POST, 'icon_activ_display_msg', 0);
	$CONFIG_FORUM['auth'] = serialize($CONFIG_FORUM['auth']);
		
	if (!empty($CONFIG_FORUM['forum_name']) && !empty($CONFIG_FORUM['pagination_topic']) && !empty($CONFIG_FORUM['pagination_msg']) && !empty($CONFIG_FORUM['view_time']))
	{
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_FORUM)) . "' WHERE name = 'forum'", __LINE__, __FILE__);
			
		###### Régénération du cache du forum ###### 
		$Cache->Generate_module_file('forum');
				
		redirect(HOST . SCRIPT);	
	}
	else
		redirect('/forum/admin_forum_config.php?error=incomplete#errorh');
}
elseif ($update_cached) //Mise à jour des données stockées en cache dans la bdd.
{
	$result = $Sql->query_while("SELECT id, id_left, id_right
	FROM " . PREFIX . "forum_cats
	WHERE level > 0", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{	
		$cat_list = $row['id'];
		if (($row['id_right'] - $row['id_left']) > 1)
		{
			$result2 = $Sql->query_while("SELECT id
			FROM " . PREFIX . "forum_cats
			WHERE id_left >= '" . $row['id_left'] . "' AND id_right <= '" . $row['id_right'] ."'", __LINE__, __FILE__);
			
			while ($row2 = $Sql->fetch_assoc($result2))
				$cat_list .=  ', ' . $row2['id'];
		}
		
		$info_cat = $Sql->query_array(PREFIX . "forum_topics", "COUNT(*) as nbr_topic", "SUM(nbr_msg) as nbr_msg", "WHERE idcat IN (" . $cat_list . ")", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_topic = '" . $info_cat['nbr_topic'] . "', nbr_msg = '" . $info_cat['nbr_msg'] . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	$Sql->query_close($result);
	
	redirect(HOST . SCRIPT);	
}
else	
{	
	$Template->set_filenames(array(
		'admin_forum_config'=> 'forum/admin_forum_config.tpl'
	));

	$Cache->load('forum');
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$CONFIG_FORUM['edit_mark'] = isset($CONFIG_FORUM['edit_mark']) ? $CONFIG_FORUM['edit_mark'] : 0;
	$CONFIG_FORUM['display_connexion'] = isset($CONFIG_FORUM['display_connexion']) ? $CONFIG_FORUM['display_connexion'] : 0;
	$CONFIG_FORUM['no_left_column'] = isset($CONFIG_FORUM['no_left_column']) ? $CONFIG_FORUM['no_left_column'] : 0;
	$CONFIG_FORUM['no_right_column'] = isset($CONFIG_FORUM['no_right_column']) ? $CONFIG_FORUM['no_right_column'] : 0;
	$CONFIG_FORUM['activ_display_msg'] = isset($CONFIG_FORUM['activ_display_msg']) ? $CONFIG_FORUM['activ_display_msg'] : 0;
	$CONFIG_FORUM['icon_display_msg'] = isset($CONFIG_FORUM['icon_display_msg']) ? $CONFIG_FORUM['icon_display_msg'] : 1;

	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'FORUM_NAME' => !empty($CONFIG_FORUM['forum_name']) ? $CONFIG_FORUM['forum_name'] : '',
		'PAGINATION_TOPIC' => !empty($CONFIG_FORUM['pagination_topic']) ? $CONFIG_FORUM['pagination_topic'] : '20',
		'PAGINATION_MSG' => !empty($CONFIG_FORUM['pagination_msg']) ? $CONFIG_FORUM['pagination_msg'] : '15',
		'VIEW_TIME' => !empty($CONFIG_FORUM['view_time']) ? $CONFIG_FORUM['view_time']/(3600*24) : '30',
		'TOPIC_TRACK_MAX' => !empty($CONFIG_FORUM['topic_track']) ? $CONFIG_FORUM['topic_track'] : '40',	
		'EDIT_MARK_ENABLED' => ($CONFIG_FORUM['edit_mark'] == 1) ? 'checked="checked"' : '',
		'EDIT_MARK_DISABLED' => ($CONFIG_FORUM['edit_mark'] == 0) ? 'checked="checked"' : '',
		'DISPLAY_CONNEXION_ENABLED' => ($CONFIG_FORUM['display_connexion'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_CONNEXION_DISABLED' => ($CONFIG_FORUM['display_connexion'] == 0) ? 'checked="checked"' : '',
		'NO_LEFT_COLUMN_ENABLED' => ($CONFIG_FORUM['no_left_column'] == 1) ? 'checked="checked"' : '',
		'NO_LEFT_COLUMN_DISABLED' => ($CONFIG_FORUM['no_left_column'] == 0) ? 'checked="checked"' : '',
		'NO_RIGHT_COLUMN_ENABLED' => ($CONFIG_FORUM['no_right_column'] == 1) ? 'checked="checked"' : '',
		'NO_RIGHT_COLUMN_DISABLED' => ($CONFIG_FORUM['no_right_column'] == 0) ? 'checked="checked"' : '',
		'DISPLAY_MSG_ENABLED' => ($CONFIG_FORUM['activ_display_msg'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_MSG_DISABLED' => ($CONFIG_FORUM['activ_display_msg'] == 0) ? 'checked="checked"' : '',
		'DISPLAY_MSG' => !empty($CONFIG_FORUM['display_msg']) ? $CONFIG_FORUM['display_msg'] : '',
		'EXPLAIN_DISPLAY_MSG' => !empty($CONFIG_FORUM['explain_display_msg']) ? $CONFIG_FORUM['explain_display_msg'] : '',
		'EXPLAIN_DISPLAY_MSG_BIS' => !empty($CONFIG_FORUM['explain_display_msg_bis']) ? $CONFIG_FORUM['explain_display_msg_bis'] : '',
		'ICON_DISPLAY_MSG_ENABLED' => ($CONFIG_FORUM['icon_activ_display_msg'] == 1) ? 'checked="checked"' : '',
		'ICON_DISPLAY_MSG_DISABLED' => ($CONFIG_FORUM['icon_activ_display_msg'] == 0) ? 'checked="checked"' : '',
		'L_REQUIRE_NAME' => $LANG['require_name'],
		'L_REQUIRE_TOPIC_P' => $LANG['require_topic_p'],
		'L_REQUIRE_NBR_MSG_P' => $LANG['require_nbr_msg_p'],
		'L_REQUIRE_TIME_NEW_MSG' => $LANG['require_time_new_msg'],
		'L_REQUIRE_TOPIC_TRACK_MAX' => $LANG['require_topic_track_max'],
		'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
		'L_CAT_MANAGEMENT' => $LANG['cat_management'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_FORUM_CONFIG' => $LANG['forum_config'],
		'L_FORUM_GROUPS' => $LANG['forum_groups_config'],
		'L_FORUM_NAME' => $LANG['forum_name'],
		'L_NBR_TOPIC_P' => $LANG['nbr_topic_p'],
		'L_NBR_TOPIC_P_EXPLAIN' => $LANG['nbr_topic_p_explain'],
		'L_NBR_MSG_P' => $LANG['nbr_msg_p'],
		'L_NBR_MSG_P_EXPLAIN' => $LANG['nbr_msg_p_explain'],
		'L_TIME_NEW_MSG' => $LANG['time_new_msg'],
		'L_TIME_NEW_MSG_EXPLAIN' => $LANG['time_new_msg_explain'],
		'L_TOPIC_TRACK_MAX' => $LANG['topic_track_max'],
		'L_TOPIC_TRACK_MAX_EXPLAIN' => $LANG['topic_track_max_explain'],
		'L_EDIT_MARK' => $LANG['edit_mark'],
		'L_DISPLAY_CONNEXION' => $LANG['forum_display_connexion'],
		'L_NO_LEFT_COLUMN' => $LANG['no_left_column'],
		'L_NO_RIGHT_COLUMN' => $LANG['no_right_column'],
		'L_ACTIV_DISPLAY_MSG' => $LANG['activ_display_msg'],
		'L_DISPLAY_MSG' => $LANG['display_msg'],
		'L_EXPLAIN_DISPLAY_MSG' => $LANG['explain_display_msg'],
		'L_EXPLAIN_DISPLAY_MSG_EXPLAIN' => $LANG['explain_display_msg_explain'],
		'L_EXPLAIN_DISPLAY_MSG_BIS' => $LANG['explain_display_msg'],		
		'L_EXPLAIN_DISPLAY_MSG_BIS_EXPLAIN' => $LANG['explain_display_msg_bis_explain'],		
		'L_ICON_DISPLAY_MSG' => $LANG['icon_display_msg'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIVE' => $LANG['unactiv'],
		'L_DAYS' => $LANG['day_s'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_DELETE' => $LANG['delete'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_UPDATE_DATA_CACHED' => $LANG['update_data_cached']
	));

	$Template->pparse('admin_forum_config');
}

require_once('../admin/admin_footer.php');

?>