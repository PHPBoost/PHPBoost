<?php
/*##################################################
 *                              forum_functions.php
 *                            -------------------
 *   begin                : December 11, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

//Listes les utilisateurs en lignes.
function forum_list_user_online($sql_condition)
{
	global $Sql;
	
	list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
	$result = $Sql->query_while("SELECT s.user_id, m.level, m.display_name, m.groups
	FROM " . DB_TABLE_SESSIONS . " s 
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id 
	WHERE s.timestamp > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "' ". $sql_condition."
	ORDER BY s.timestamp DESC");
	while ($row = $Sql->fetch_assoc($result))
	{
		$group_color = User::get_group_color($row['groups'], $row['level']);
		switch ($row['level']) //Coloration du membre suivant son level d'autorisation. 
		{ 		
			case -1:
			$status = 'visiteur';
			$total_visit++;
			break;			
			case 0:
			$status = 'member';
			$total_member++;
			break;			
			case 1: 
			$status = 'modo';
			$total_modo++;
			break;			
			case 2: 
			$status = 'admin';
			$total_admin++;
			break;
		} 
		$coma = !empty($users_list) && $row['level'] != -1 ? ', ' : '';
		$users_list .= (!empty($row['display_name']) && $row['level'] != -1) ?  $coma . '<a href="'. UserUrlBuilder::profile($row['user_id'])->rel() .'" class="' . $status . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['display_name'] . '</a>' : '';
	}
	$result->dispose();
	
	return array($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_admin + $total_modo + $total_member + $total_visit);
}

//Liste des catégories du forum.
function forum_list_cat($id_select, $level)
{
	global $CAT_FORUM, $AUTH_READ_FORUM;
	
	$select = '';
	foreach ($CAT_FORUM as $idcat => $array_cat)
	{
		$selected = '';
		if ($id_select == $idcat && $array_cat['level'] == $level)
			$selected = ' selected="selected"';
		
		$margin = ($array_cat['level'] > 0) ? str_repeat('------', $array_cat['level']) : '';
		$select .= $AUTH_READ_FORUM[$idcat] && empty($CAT_FORUM[$idcat]['url']) ? '<option value="' . $idcat . '"' . $selected . '>' . $margin . ' ' . $array_cat['name'] . '</option>' : '';
	}
	
	return $select;
}

//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
function forum_limit_time_msg()
{
	$last_view_forum = AppContext::get_session()->get_cached_data('last_view_forum');
	$max_time = (time() - (ForumConfig::load()->get_read_messages_storage_duration() * 3600 * 24));
	$max_time_msg = ($last_view_forum > $max_time) ? $last_view_forum : $max_time;
	
	return $max_time_msg;
}

//Marque un topic comme lu.
function mark_topic_as_read($idtopic, $last_msg_id, $last_timestamp)
{
	global $Sql;
	
	//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
	$last_view_forum = AppContext::get_session()->get_cached_data('last_view_forum', 0);
	$max_time = (time() - (ForumConfig::load()->get_read_messages_storage_duration() * 3600 * 24));
	$max_time_msg = ($last_view_forum > $max_time) ? $last_view_forum : $max_time;
	if (AppContext::get_current_user()->get_id() !== -1 && $last_timestamp >= $max_time_msg)
	{
		$check_view_id = $Sql->query("SELECT last_view_id FROM " . PREFIX . "forum_view WHERE user_id = '" . AppContext::get_current_user()->get_id() . "' AND idtopic = '" . $idtopic . "'");
		if (!empty($check_view_id) && $check_view_id != $last_msg_id) 
		{
			$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
			$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . PREFIX . "forum_view SET last_view_id = '" . $last_msg_id . "', timestamp = '" . time() . "' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . AppContext::get_current_user()->get_id() . "'");
		}
		elseif (empty($check_view_id))
		{			
			$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
			$Sql->query_inject("INSERT ".LOW_PRIORITY." INTO " . PREFIX . "forum_view (idtopic, last_view_id, user_id, timestamp) VALUES('" . $idtopic . "', '" . $last_msg_id . "', '" . AppContext::get_current_user()->get_id() . "', '" . time() . "')");			
		}
		else
			$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
	}
	else
		$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
}
	
//Gestion de l'historique des actions sur le forum.
function forum_history_collector($type, $user_id_action = '', $url_action = '')
{
	global $Sql;
	
	$Sql->query_inject("INSERT INTO " . PREFIX . "forum_history (action, user_id, user_id_action, url, timestamp) VALUES('" . TextHelper::strprotect($type) . "', '" . AppContext::get_current_user()->get_id() . "', '" . NumberHelper::numeric($user_id_action) . "', '" . TextHelper::strprotect($url_action) . "', '" . time() . "')");
}

//Gestion du rss du forum.
function forum_generate_feeds()
{
    Feed::clear_cache('forum');
}

//Coloration de l'item recherché en dehors des balises html.
function token_colorate($matches)
{
    static $open_tag = 0;
    static $close_tag = 0;
    
    $open_tag += substr_count($matches[1], '<');
    $close_tag += substr_count($matches[1], '>');
    
    if ($open_tag == $close_tag)
        return $matches[1] . '<span class="forum-search-word">' . $matches[2] . '</span>' . $matches[3];
    else
        return $matches[0];
}
?>