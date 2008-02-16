<?php
/*##################################################
 *                             forum_begin.php
 *                            -------------------
 *   begin                : October 18, 2007
 *   copyright          : (C) 2007 Viarre régis
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

if( defined('PHP_BOOST') !== true)	
	exit;
	
//Accès au module.
if( !$groups->check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 

load_module_lang('forum', $CONFIG['lang']); //Chargement de la langue du module.

//Configuration générale du forum
define('FLOOD_FORUM', 0x01);
define('EDIT_MARK_FORUM', 0x02);
define('TRACK_TOPIC_FORUM', 0x04);

//Configuration sur la catégorie.
define('READ_CAT_FORUM', 0x01);
define('WRITE_CAT_FORUM', 0x02);
define('EDIT_CAT_FORUM', 0x04);

//Historique.
define('H_DELETE_MSG', 'delete_msg'); //Suppression d'un message.
define('H_DELETE_TOPIC', 'delete_topic'); //Suppression d'un sujet.
define('H_LOCK_TOPIC', 'lock_topic'); //Verrouillage d'un sujet.
define('H_UNLOCK_TOPIC', 'unlock_topic'); //Déverrouillage d'un sujet.
define('H_MOVE_TOPIC', 'move_topic'); //Déplacement d'un sujet.
define('H_CUT_TOPIC', 'cut_topic'); //Scindement d'un sujet.
define('H_SET_WARNING_USER', 'set_warning_user'); //Modification pourcentage avertissement.		
define('H_BAN_USER', 'ban_user'); //Modification pourcentage avertissement.		
define('H_EDIT_MSG', 'edit_msg'); //Edition message d'un membre.
define('H_EDIT_TOPIC', 'edit_topic'); //Edition sujet d'un membre.
define('H_SOLVE_ALERT', 'solve_alert'); //Résolution d'une alerte.
define('H_WAIT_ALERT', 'wait_alert'); //Mise en attente d'une alerte.
define('H_DEL_ALERT', 'del_alert'); //Suppression d'une alerte.
define('H_READONLY_USER', 'readonly_user'); //Modification lecture seule d'un membre.

$cache->load_file('forum');

$AUTH_READ_FORUM = array();
if( is_array($CAT_FORUM) )
{
	foreach($CAT_FORUM as $idcat => $key)
	{
		if( $groups->check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM) )
			$AUTH_READ_FORUM[$idcat] = true;
		else
			$AUTH_READ_FORUM[$idcat] = false;
	}
}

//Supprime les menus suivant configuration du site.
if( $CONFIG_FORUM['no_left_column'] == 1 ) 
	define('NO_LEFT_COLUMN', true);
if( $CONFIG_FORUM['no_right_column'] == 1 ) 
	define('NO_RIGHT_COLUMN', true);

//Chargement du css alternatif.
define('ALTERNATIVE_CSS', 'forum');

//Fonction du forum.
require('../forum/forum_functions.php');


############### Header du forum ################
$template->set_filenames(array(
	'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
	'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
));

$is_guest = ($session->data['user_id'] !== -1) ? false : true;
$nbr_msg_not_read = 0;
if( !$is_guest )
{
	//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
	$session->data['last_view_forum'] = isset($session->data['last_view_forum']) ? $session->data['last_view_forum'] : 0;
	$max_time = (time() - $CONFIG_FORUM['view_time']);
	$max_time_msg = ($session->data['last_view_forum'] > $max_time) ? $session->data['last_view_forum'] : $max_time;
	
	//Vérification des autorisations.
	$unauth_cats = '';
	if( is_array($AUTH_READ_FORUM) )
	{
		foreach($AUTH_READ_FORUM as $idcat => $auth)
		{
			if( !$auth )
				$unauth_cats .= $idcat . ',';
		}
		$unauth_cats = !empty($unauth_cats) ? " AND c.id NOT IN (" . trim($unauth_cats, ',') . ")" : '';
	}

	//Si on est sur un topic, on le supprime dans la requête => si ce topic n'était pas lu il ne sera plus dans la liste car désormais lu.
	$clause_topic = '';
	if( strpos(SCRIPT, '/forum/topic.php') !== false )
	{
		$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : '';
		$clause_topic = " AND t.id != '" . $id_get . "'";
	}
	
	//Requête pour compter le nombre de messages non lus.
	$nbr_topics = 0;
	$sql_request = "SELECT t.id AS tid, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, m.user_id, m.login, v.last_view_id 
	FROM ".PREFIX."forum_topics t
	LEFT JOIN ".PREFIX."forum_cats c ON c.id = t.idcat
	LEFT JOIN ".PREFIX."forum_view v ON v.idtopic = t.id AND v.user_id = '" . $session->data['user_id'] . "'
	LEFT JOIN ".PREFIX."member m ON m.user_id = t.last_user_id
	WHERE t.last_timestamp >= '" . $max_time_msg . "' AND (v.last_view_id != t.last_msg_id OR v.last_view_id IS NULL)" . $clause_topic . $unauth_cats;
	$result = $sql->query_while($sql_request, __LINE__, __FILE__);
	$nbr_msg_not_read = $sql->sql_num_rows($result, $sql_request);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		//Si le dernier message lu est présent on redirige vers lui, sinon on redirige vers le dernier posté.
		if( !empty($row['last_view_id']) ) //Calcul de la page du last_view_id réalisé dans topic.php
		{
			$last_msg_id = $row['last_view_id']; 
			$last_page = 'idm=' . $row['last_view_id'] . '&amp;';
			$last_page_rewrite = '-0-' . $row['last_view_id'];
		}
		else
		{
			$last_msg_id = $row['last_msg_id']; 
			$last_page = ceil($row['t_nbr_msg'] / $CONFIG_FORUM['pagination_msg']);
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';					
		}	

		$last_topic_title = (($CONFIG_FORUM['activ_display_msg'] && $row['display_msg']) ? $CONFIG_FORUM['display_msg'] : '') . ' ' . ucfirst($row['title']);			
		$last_topic_title = (strlen(html_entity_decode($last_topic_title)) > 25) ? substr_html($last_topic_title, 0, 25) . '...' : $last_topic_title;			
		$row['login'] = !empty($row['login']) ? $row['login'] : $LANG['guest'];
			
		$template->assign_block_vars('forum_unread_list', array(
			'LOGIN' => ($row['last_user_id'] != '-1' ? '<a href="../member/member' . transid('.php?id=' . $row['last_user_id'], '-' . $row['last_user_id'] . '.php') . '" class="small_link">' . $row['login'] . '</a>' : '<em>' . $LANG['guest'] . '</em>'),
			'DATE' => gmdate_format('date_format', $row['last_timestamp']),
			'U_TOPICS' => '<a href="topic' . transid('.php?' . $last_page .  'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '+' . url_encode_rewrite($row['title'])  . '.php') . '#m' .  $last_msg_id . '"><img src="../templates/' . $CONFIG['theme'] . '/images/ancre.png" alt="" /></a> <a href="topic' . transid('.php?id=' . $row['tid'], '-' . $row['tid'] . '+' . url_encode_rewrite($row['title'])  . '.php') . '" class="small_link">' . $last_topic_title . '</a>'
		));
		$nbr_topics++;
	}
	$sql->close($result);
	
	//Le membre a déjà lu tous les messages.
	if( $nbr_topics == 0 )
	{
		$template->assign_vars(array(
			'C_NO_MSG_NOT_READ' => true,
			'C_MSG_NOT_READ' => false,
			'L_MSG_NOT_READ' => $LANG['no_msg_not_read']
		));		
	}
	else
	{
		$template->assign_vars(array(
			'C_MSG_NOT_READ' => true
		));
	}
}

$max_visible_topics = 10;
$height_visible_topics = ($nbr_msg_not_read < $max_visible_topics) ? (23 * $nbr_msg_not_read) : 23 * $max_visible_topics;
$template->assign_vars(array(	
	'MAX_UNREAD_HEIGHT' => max($height_visible_topics, 65),
	'C_DISPLAY_UNREAD_DETAILS' => ($session->data['user_id'] !== -1 && $nbr_msg_not_read > 0) ? true : false,
	'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a>',
	'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a>',
	'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' .SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . ($session->data['user_id'] !== -1 ? ' (' . $nbr_msg_not_read . ')' : '') . '</a>',
	'U_MSG_SET_VIEW' => '<a class="small_link" href="../forum/action' . transid('.php?read=1', '') . '" title="' . $LANG['mark_as_read'] . '" onClick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
	'L_SEARCH' => $LANG['search'],
	'L_ADVANCED_SEARCH' => $LANG['advanced_search']
));

?>
