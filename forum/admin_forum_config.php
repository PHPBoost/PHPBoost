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

$update_cached = !empty($_GET['upd']);
$config = ForumConfig::load();

//Si c'est confirmé on execute
if (!empty($_POST['valid']))
{
	$config->set_forum_name(retrieve(POST, 'forum_name', ''));
	$config->set_number_topics_per_page(retrieve(POST, 'number_topics_per_page', 20));
	$config->set_number_messages_per_page(retrieve(POST, 'number_messages_per_page', 15));
	$config->set_read_messages_storage_duration(retrieve(POST, 'read_messages_storage_duration', 30));
	$config->set_max_topic_number_in_favorite(retrieve(POST, 'max_topic_number_in_favorite', 40));
	
	if (retrieve(POST, 'edit_mark_enabled', ''))
		$config->enable_edit_mark();
	else
		$config->disable_edit_mark();
	
	if (retrieve(POST, 'connexion_form_displayed', ''))
		$config->display_connexion_form();
	else
		$config->hide_connexion_form();
	
	if (retrieve(POST, 'left_column_disabled', ''))
		$config->disable_left_column();
	else
		$config->enable_left_column();
	
	if (retrieve(POST, 'right_column_disabled', ''))
		$config->disable_right_column();
	else
		$config->enable_right_column();
	
	if (retrieve(POST, 'message_before_topic_title_displayed', ''))
		$config->display_message_before_topic_title();
	else
		$config->hide_message_before_topic_title();
	
	$config->set_message_before_topic_title(retrieve(POST, 'message_before_topic_title', ''));
	$config->set_message_when_topic_is_unsolved(retrieve(POST, 'message_when_topic_is_unsolved', ''));
	$config->set_message_when_topic_is_solved(retrieve(POST, 'message_when_topic_is_solved', ''));
	
	if (retrieve(POST, 'message_before_topic_title_icon_displayed', ''))
		$config->display_message_before_topic_title_icon();
	else
		$config->hide_message_before_topic_title_icon();
	
	ForumConfig::save();
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
elseif ($update_cached) //Mise à jour des données stockées en cache dans la bdd.
{
	$result = $Sql->query_while("SELECT id, id_left, id_right
	FROM " . PREFIX . "forum_cats
	WHERE level > 0");
	while ($row = $Sql->fetch_assoc($result))
	{
		$cat_list = $row['id'];
		if (($row['id_right'] - $row['id_left']) > 1)
		{
			$result2 = $Sql->query_while("SELECT id
			FROM " . PREFIX . "forum_cats
			WHERE id_left >= '" . $row['id_left'] . "' AND id_right <= '" . $row['id_right'] ."'");
			
			while ($row2 = $Sql->fetch_assoc($result2))
				$cat_list .=  ', ' . $row2['id'];
		}
		
		$info_cat = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array("COUNT(*) as nbr_topic", "SUM(nbr_msg) as nbr_msg"), "WHERE idcat IN (" . $cat_list . ")");
		PersistenceContext::get_querier()->update(PREFIX . 'forum_cats', array('nbr_topic' => $info_cat['nbr_topic'], 'nbr_msg' => $info_cat['nbr_msg']), 'WHERE id=:id', array('id' => $row['id']));
	}
	$result->dispose();
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
else
{
	$tpl = new FileTemplate('forum/admin_forum_config.tpl');
	
	$tpl->put_all(array(
		'FORUM_NAME' => $config->get_forum_name(),
		'NUMBER_TOPICS_PER_PAGE' => $config->get_number_topics_per_page(),
		'NUMBER_MESSAGES_PER_PAGE' => $config->get_number_messages_per_page(),
		'READ_MESSAGES_STORAGE_DURATION' => $config->get_read_messages_storage_duration(),
		'MAX_TOPIC_NUMBER_IN_FAVORITE' => $config->get_max_topic_number_in_favorite(),
		'C_EDIT_MARK_ENABLED' => $config->is_edit_mark_enabled(),
		'C_CONNEXION_FORM_DISPLAYED' => $config->is_connexion_form_displayed(),
		'C_LEFT_COLUMN_DISABLED' => $config->is_left_column_disabled(),
		'C_RIGHT_COLUMN_DISABLED' => $config->is_right_column_disabled(),
		'C_MESSAGE_BEFORE_TOPIC_TITLE_DISPLAYED' => $config->is_message_before_topic_title_displayed(),
		'MESSAGE_BEFORE_TOPIC_TITLE' => $config->get_message_before_topic_title(),
		'MESSAGE_WHEN_TOPIC_IS_UNSOLVED' => $config->get_message_when_topic_is_unsolved(),
		'MESSAGE_WHEN_TOPIC_IS_SOLVED' => $config->get_message_when_topic_is_solved(),
		'C_MESSAGE_BEFORE_TOPIC_TITLE_ICON_DISPLAYED' => $config->is_message_before_topic_title_icon_displayed(),
		'L_REQUIRE' => $LANG['require'],
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
		'L_FORUM_RANKS_MANAGEMENT' => $LANG['rank_management'],
		'L_FORUM_ADD_RANKS' => $LANG['rank_add'],
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
		'L_DAYS' => LangLoader::get_message('days', 'date-common'),
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_UPDATE_DATA_CACHED' => $LANG['update_data_cached']
	));

	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>