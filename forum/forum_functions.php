<?php
/*##################################################
 *                              forum_functions.php
 *                            -------------------
 *   begin                : December 11, 2007
 *   copyright          : (C) 2007 Viarre R�gis
 *   email                : crowkait@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

//Liste des cat�gories du forum.
function forum_list_cat($userdata)
{
	global $groups, $CAT_FORUM;
	
	$select = '';
	foreach($CAT_FORUM as $idcat => $array_cat)
	{
		$margin = ($array_cat['level'] > 0) ? str_repeat('--------', $array_cat['level']) : '--';
		$select .= ($groups->check_auth($CAT_FORUM[$idcat]['auth'], 1)) ? '<option value="' . $idcat . '">' . $margin . ' ' . str_replace('\'', '\\\'', $array_cat['name']) . '</option>' : '';
	}
	
	return $select;
}

//Marque un topic comme lu.
function mark_topic_as_read($idtopic, $last_msg_id, $last_timestamp)
{
	global $sql, $session, $CONFIG_FORUM;
	
	//Message(s) dans le topic non lu ( non prise en compte des topics trop vieux (x semaine) ou d�j� lus).
	$last_view_forum = $sql->query("SELECT last_view_forum FROM ".PREFIX."member_extend WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__);
	$max_time_config = (time() - $CONFIG_FORUM['view_time']);
	$max_time = $last_view_forum > $max_time_config ? $last_view_forum : $max_time_config;
	if( $session->data['user_id'] !== -1 && $last_timestamp >= $max_time )
	{
		$check_view_id = $sql->query("SELECT last_view_id FROM ".PREFIX."forum_view WHERE user_id = '" . $session->data['user_id'] . "' AND idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
		if( !empty($check_view_id) && $check_view_id != $last_msg_id ) 
		{
			$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
			$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_view SET last_view_id = '" . $last_msg_id . "', timestamp = '" . time() . "' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__);
		}
		elseif( empty($check_view_id) )
		{			
			$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
			$sql->query_inject("INSERT ".LOW_PRIORITY." INTO ".PREFIX."forum_view (idtopic, last_view_id, user_id, timestamp) VALUES('" . $idtopic . "', '" . $last_msg_id . "', '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);			
		}
		else
			$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
	}
	else
		$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
}
	
//Gestion de l'historique des actions sur le forum.
function forum_history_collector($type, $user_id_action = '', $url_action = '')
{
	global $sql, $session;
	
	$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action, user_id, user_id_action, url, timestamp) VALUES('" . securit($type) . "', '" . $session->data['user_id'] . "', '" . numeric($user_id_action) . "', '" . securit($url_action) . "', '" . time() . "')", __LINE__, __FILE__);
}

//Gestion du rss du forum.
function forum_generate_rss()
{		
	include_once('../includes/rss.class.php');
	$rss = new Rss('forum/rss.php');
	$rss->cache_path('../cache/');
	$rss->generate_file('javascript', 'rss_forum');
	$rss->generate_file('php', 'rss2_forum');
}
	
?>