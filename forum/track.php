<?php
/*##################################################
 *                                track.php
 *                            -------------------
 *   begin                : October 26, 2005
 *   copyright          : (C) 2005 Viarre Régis
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/begin.php'); 
require_once('../forum/forum_begin.php');
speed_bar_generate($SPEED_BAR, $CONFIG_FORUM['forum_name'], 'index.php' . SID, $LANG['show_topic_track'], '');
define('TITLE', $LANG['title_forum'] . ' - ' . $LANG['show_topic_track']);
require_once('../includes/header.php'); 

$page = !empty($_GET['p']) ? numeric($_GET['p']) : 1;

//Redirection changement de catégorie.
if( !empty($_POST['change_cat']) )
{
	header('location:' . HOST . DIR . '/forum/forum' . transid('.php?id=' . $_POST['change_cat'], '-' . $_POST['change_cat'] . $rewrited_title . '.php', '&'));
	exit;
}
if( !$session->check_auth($session->data, 0) ) //Réservé aux membres.
{
	header('location: ' . HOST . DIR . '/member/error.php'); 
	exit;
}
	
if( !empty($_POST['valid']) )
{
	include_once('../includes/pagination.class.php'); 
	$pagination = new Pagination();
	
	$result = $sql->query_while("SELECT t.id, tr.pm, tr.mail
	FROM ".PREFIX."forum_topics t
	LEFT JOIN ".PREFIX."forum_track tr ON tr.idtopic = t.id
	WHERE tr.user_id = '" . $session->data['user_id'] . "'
	" . $sql->sql_limit($pagination->first_msg($CONFIG_FORUM['pagination_topic'], 'p'), $CONFIG_FORUM['pagination_topic']), __LINE__, __FILE__);
	while ($row = $sql->sql_fetch_assoc($result))
	{
		$pm = (isset($_POST['p' . $row['id']]) && $_POST['p' . $row['id']] == 'on') ? 1 : 0;
		if( $row['pm'] != $pm )
			$sql->query_inject("UPDATE ".PREFIX."forum_track SET pm = '" . $pm . "' WHERE idtopic = '" . $row['id'] . "'", __LINE__, __FILE__);
		$mail = (isset($_POST['m' . $row['id']]) && $_POST['m' . $row['id']] == 'on') ? 1 : 0;
		if( $row['mail'] != $mail )
			$sql->query_inject("UPDATE ".PREFIX."forum_track SET mail = '" . $mail . "' WHERE idtopic = '" . $row['id'] . "'", __LINE__, __FILE__);	
		$del = (isset($_POST['d' . $row['id']]) && $_POST['d' . $row['id']] == 'on') ? true : false;
		if( $del )
			$sql->query_inject("DELETE FROM ".PREFIX."forum_track WHERE idtopic = '" . $row['id'] . "'", __LINE__, __FILE__);	
	}
	$sql->close($result);
	
	header('location: ' . HOST . DIR . '/forum/track.php' . SID2);
	exit;
}
elseif( $session->check_auth($session->data, 0) ) //Affichage des message()s non lu(s) du membre.
{
	$template->set_filenames(array(
		'forum_track' => '../templates/' . $CONFIG['theme'] . '/forum/forum_track.tpl',
		'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
		'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
	));

	//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
	$max_time_config = (time() - $CONFIG_FORUM['view_time']);
	
	include_once('../includes/pagination.class.php'); 
	$pagination = new Pagination();

	$nbr_topics_compt = 0;
	$result = $sql->query_while("SELECT m1.login AS login , m2.login AS last_login , t.id , t.title , t.subtitle , t.user_id , t.nbr_msg , t.nbr_views , t.last_user_id , t.last_msg_id , t.last_timestamp , t.type , t.status, t.display_msg, v.last_view_id, p.question, me.last_view_forum, tr.pm, tr.mail, me.last_view_forum
	FROM ".PREFIX."forum_topics t
	LEFT JOIN ".PREFIX."forum_view v ON v.user_id = '" . $session->data['user_id'] . "' AND v.idtopic = t.id
	LEFT JOIN ".PREFIX."forum_track tr ON tr.idtopic = t.id
	LEFT JOIN ".PREFIX."forum_poll p ON p.idtopic = t.id
	LEFT JOIN ".PREFIX."member m1 ON m1.user_id = t.user_id
	LEFT JOIN ".PREFIX."member m2 ON m2.user_id = t.last_user_id
	LEFT JOIN ".PREFIX."member_extend me ON me.user_id = '" . $session->data['user_id'] . "'
	WHERE tr.user_id = '" . $session->data['user_id'] . "'
	ORDER BY t.type DESC , t.last_timestamp DESC
	" . $sql->sql_limit($pagination->first_msg($CONFIG_FORUM['pagination_topic'], 'p'), $CONFIG_FORUM['pagination_topic']), __LINE__, __FILE__);
	while ($row = $sql->sql_fetch_assoc($result))
	{
		//On définit un array pour l'appellation correspondant au type de champ
		$type = array('2' => $LANG['forum_announce'] . ':', '1' => $LANG['forum_postit'] . ':', '0' => '');
		
		//Vérifications des topics Lu/non Lus.
		$img_announce = 'announce';		
		$max_time = ($row['last_view_forum'] > $max_time_config) ? $row['last_view_forum'] : $max_time_config;
		$new_msg = false;
		if( $row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time ) //Nouveau message (non lu).
		{		
			$img_announce =  'new_' . $img_announce; 
			$new_msg = true;
		}
		if( $row['type'] == '0' && $row['status'] != '0' ) //Topic non vérrouillé de type normal avec plus de pagination_msg réponses.
			$img_announce .= ($row['nbr_msg'] > $CONFIG_FORUM['pagination_msg']) ? '_hot' : '';			
		$img_announce .= ($row['type'] == '1') ? '_post' : '';
		$img_announce .= ($row['type'] == '2') ? '_top' : '';
		$img_announce .= ($row['status'] == '0' && $row['type'] == '0') ? '_lock' : '';

		//Si le dernier message lu est présent on redirige vers lui, sinon on redirige vers le dernier posté.
		//Puis calcul de la page du last_msg_id ou du last_view_id.
		if( !empty($row['last_view_id']) ) 
		{
			$last_msg_id = $row['last_view_id']; 
			$last_page = 'idm=' . $row['last_view_id'] . '&amp;';
			$last_page_rewrite = '-0-' . $row['last_view_id'];
		}
		else
		{
			$last_msg_id = $row['last_msg_id']; 
			$last_page = ceil( $row['nbr_msg'] / $CONFIG_FORUM['pagination_msg'] );
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
		}
		
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($row['title']) : '';
		
		//Affichage du dernier message posté.
		$last_msg = '<a href="topic' . transid('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title . '.php') . '#m' . $last_msg_id . '" title=""><img src="../templates/' . $CONFIG['theme'] . '/images/ancre.png" alt="" /></a>' . ' ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br /> ' . $LANG['by'] . ' ' . (!empty($row['last_login']) ? '<a class="small_link" href="../member/member' . transid('.php?id=' . $row['last_user_id'], '-' . $row['last_user_id'] . '.php') . '">' . wordwrap_html($row['last_login'], 13) . '</a>' : '<em>' . $LANG['guest'] . '</em>');
		
		//Ancre ajoutée aux messages non lus.	
		$new_ancre = ($new_msg === true && $session->data['user_id'] !== -1) ? '<a href="topic' . transid('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title . '.php') . '#m' . $last_msg_id . '" title=""><img src="../templates/' . $CONFIG['theme'] . '/images/ancre.png" alt="" /></a>' : '';
		
		$template->assign_block_vars('topics', array(
			'ID' => $row['id'],
			'INCR' => $nbr_topics_compt,
			'CHECKED_PM' => ($row['pm'] == 1) ? 'checked="checked"' : '',
			'CHECKED_MAIL' => ($row['mail'] == 1) ? 'checked="checked"' : '',
			'ANNOUNCE' => $img_announce,
			'ANCRE' => $new_ancre,
			'POLL' => !empty($row['question']) ? '<img src="' . $template->module_data_path('forum') . '/images/poll_mini.png" class="valign_middle" alt="" />' : '',
			'TRACK' => '<img src="' . $template->module_data_path('forum') . '/images/favorite_mini.png" class="valign_middle" alt="" />',
			'DISPLAY_MSG' => ($CONFIG_FORUM['activ_display_msg'] && $CONFIG_FORUM['icon_activ_display_msg'] && $row['display_msg']) ? '<img src="' . $template->module_data_path('forum') . '/images/msg_display_mini.png" alt="" style="vertical-align:middle;" />' : '',
			'TYPE' => $type[$row['type']],
			'TITLE' => ucfirst($row['title']),			
			'AUTHOR' => !empty($row['login']) ? '<a href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="small_link">' . $row['login'] . '</a>' : '<em>' . $LANG['guest'] . '</em>',
			'DESC' => $row['subtitle'],
			'PAGINATION_TOPICS' => $pagination->show_pagin('topic' . transid('.php?id=' . $row['id'] . '&amp;pt=%d', '-' . $row['id'] . '-%d.php'), $row['nbr_msg'], 'pt', $CONFIG_FORUM['pagination_msg'], 2, 10, false),
			'MSG' => ($row['nbr_msg'] - 1),
			'VUS' => $row['nbr_views'],
			'U_TOPIC_VARS' => transid('.php?id=' . $row['id'], '-' . $row['id'] . $rewrited_title . '.php'),
			'U_LAST_MSG' => $last_msg,
			'L_DISPLAY_MSG' => ($CONFIG_FORUM['activ_display_msg'] && $row['display_msg']) ? $CONFIG_FORUM['display_msg'] : '',
		));
		$nbr_topics_compt++;
	}
	$sql->close($result);
	
	$nbr_topics = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_topics t
	LEFT JOIN ".PREFIX."forum_track tr ON tr.idtopic = t.id
	WHERE tr.user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__);
	
	//Le membre a déjà lu tous les messages.
	if( $nbr_topics == 0 )
	{
		$template->assign_block_vars('msg_read', array(
			'L_MSG_NOT_READ' => $LANG['show_topic_track']
		));
	}

	$l_topic = ($nbr_topics > 1) ? $LANG['topic_s'] : $LANG['topic'];
	
	$template->assign_vars(array(
		'NBR_TOPICS' => $nbr_topics,
		'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
		'SID' => SID,
		'MODULE_DATA_PATH' => $template->module_data_path('forum'),
		'PAGINATION' => $pagination->show_pagin('track' . transid('.php?p=%d'), $nbr_topics, 'p', $CONFIG_FORUM['pagination_topic'], 3),
		'LANG' => $CONFIG['lang'],
		'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
		'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' . SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
		'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
		'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
		'U_CHANGE_CAT'=> 'track.php' . SID,
		'U_ONCHANGE' => "'forum" . transid(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php") . "'",
		'U_FORUM_CAT' => '<a href="../forum/track.php' . SID . '">' . $LANG['show_topic_track'] . '</a>',
		'U_POST_NEW_SUBJECT' => '',		
		'U_TRACK_ACTION' => transid('.php?p=' . $page),
		'L_FORUM_INDEX' => $LANG['forum_index'],
		'L_AUTHOR' => $LANG['author'],
		'L_FORUM' => $LANG['forum'],	
		'L_DELETE' => $LANG['delete'],	
		'L_MAIL' => $LANG['mail'],	
		'L_PM' => $LANG['pm'],	
		'L_EXPLAIN_TRACK' => $LANG['explain_track'],
		'L_TOPIC' => $l_topic,
		'L_MESSAGE' => $LANG['replies'],
		'L_VIEW' => $LANG['views'],
		'L_LAST_MESSAGE' => $LANG['last_message'],
		'L_SUBMIT' => $LANG['submit']	
	));	
	
	//Listes les utilisateurs en lignes.
	list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
	$result = $sql->query_while("SELECT s.user_id, s.level, m.login 
	FROM ".PREFIX."sessions s 
	LEFT JOIN ".PREFIX."member m ON m.user_id = s.user_id 
	WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.session_script = '/forum/track.php'
	ORDER BY s.session_time DESC", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		switch( $row['level'] ) //Coloration du membre suivant son level d'autorisation. 
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
		$users_list .= (!empty($row['login']) && $row['level'] != -1) ?  $coma . '<a href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $status . '">' . $row['login'] . '</a>' : '';
	}
	$sql->close($result);

	$total_online = $total_admin + $total_modo + $total_member + $total_visit;
	$template->assign_vars(array(
		'TOTAL_ONLINE' => $total_online,
		'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
		'ADMIN' => $total_admin,
		'MODO' => $total_modo,
		'MEMBER' => $total_member,
		'GUEST' => $total_visit,
		'SELECT_CAT' => forum_list_cat($session->data), //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
		'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
		'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
		'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
		'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
		'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
		'L_AND' => $LANG['and'],
		'L_ONLINE' => strtolower($LANG['online'])
	));
	
	$template->pparse('forum_track');
}
else
{
	header('Location:' . HOST . DIR . '/forum/index.php' . SID2);
	exit;
}

include('../includes/footer.php');

?>