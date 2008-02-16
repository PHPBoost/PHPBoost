<?php
/*##################################################
 *                                alert.php
 *                            -------------------
 *   begin                : August 7, 2006
 *   copyright          : (C) 2006 Viarre Régis / Sautel Benoît
 *   email                : crowkait@phpboost.com / ben.popeye@phpboost.com
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

$alert = !empty($_GET['id']) ? numeric($_GET['id']) : '';	
$alert_post = !empty($_POST['id']) ? numeric($_POST['id']) : '';	
$topic_id = (!empty($alert)) ? $alert : $alert_post;
$topic = $sql->query_array('forum_topics', 'idcat', 'title', 'subtitle', "WHERE id = '" . $topic_id . "'", __LINE__, __FILE__);

$cat_name = !empty($CAT_FORUM[$topic['idcat']]['name']) ? $CAT_FORUM[$topic['idcat']]['name'] : '';
$topic_name = !empty($topic['title']) ? $topic['title'] : '';
speed_bar_generate($SPEED_BAR, $CONFIG_FORUM['forum_name'], 'index.php' . SID,
$cat_name, 'forum' . transid('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '+' . url_encode_rewrite($cat_name) . '.php'),
$topic['title'], 'topic' . transid('.php?id=' . $alert, '-' . $alert . '-' . url_encode_rewrite($topic_name) . '.php'),
$LANG['alert_topic'], '');
define('TITLE', $LANG['title_forum'] . ' - ' . $LANG['alert_topic']);
require_once('../includes/header.php');

if( empty($alert) && empty($alert_post) || empty($topic['idcat']) ) 
	redirect(HOST . DIR . '/forum/index' . transid('.php'));  

if( !$session->check_auth($session->data, 0) ) //Si c'est un invité
    $errorh->error_handler('e_auth', E_USER_REDIRECT); 
	
$template->set_filenames(array(
	'forum_alert' => '../templates/' . $CONFIG['theme'] . '/forum/forum_alert.tpl',
	'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
	'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
));
	
//On fait un formulaire d'alerte
if( !empty($alert) && empty($alert_post) )
{
	//On vérifie qu'une alerte sur le même sujet n'ait pas été postée
	$nbr_alert = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_alerts WHERE idtopic = '" . $alert ."'", __LINE__, __FILE__);
	if( empty($nbr_alert) ) //On affiche le formulaire
	{
		$template->assign_vars(array(
			'L_ALERT' => $LANG['alert_topic'],
			'L_ALERT_EXPLAIN' => $LANG['alert_modo_explain'],
			'L_ALERT_TITLE' => $LANG['alert_title'],
			'L_ALERT_CONTENTS' => $LANG['alert_contents'],
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_REQUIRE_TITLE' => $LANG['require_title']
		));
			
		include_once('../includes/bbcode.php');	
		
		$template->assign_block_vars('alert_form', array(
			'TITLE' => $topic_name,
			'U_TOPIC' => 'topic' . transid('.php?id=' . $alert, '-' . $alert . '-' . url_encode_rewrite($topic_name) . '.php'),
			'ID_ALERT' => $alert,
		));	
	}
	else //Une alerte a déjà été postée
	{
		$template->assign_vars(array(
			'L_ALERT' => $LANG['alert_topic'],
			'L_BACK_TOPIC' => $LANG['alert_back'],
			'URL_TOPIC' => 'topic' . transid('.php?id=' . $alert, '-' . $alert . '-' . url_encode_rewrite($topic_name) . '.php')
		));	
		
		$template->assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}
}

//Si on enregistre une alerte
if( !empty($alert_post) )
{
	$template->assign_vars(array(
		'L_ALERT' => $LANG['alert_topic'],
		'L_BACK_TOPIC' => $LANG['alert_back'],
		'URL_TOPIC' => 'topic' . transid('.php?id=' . $alert_post, '-' . $alert_post . '-' . url_encode_rewrite($topic_name) . '.php')
	));
	
	//On vérifie qu'une alerte sur le même sujet n'ait pas été postée
	$nbr_alert = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_alerts WHERE idtopic = '" . $alert_post ."'", __LINE__, __FILE__);
	if( empty($nbr_alert) ) //On enregistre
	{
		$alert_title = !empty($_POST['title']) ? securit($_POST['title']) : '';
		$alert_contents = !empty($_POST['contents']) ? parse($_POST['contents']) : '';
		
		//Instanciation de la class du forum.
		include_once('../forum/forum.class.php');
		$forumfct = new Forum;

		$forumfct->alert_topic($alert_post, $alert_title, $alert_contents);
		
		$template->assign_block_vars('alert_confirm', array(
			'MSG' => str_replace('%title', $topic_name, $LANG['alert_success'])
		));
	}
	else //Une alerte a déjà été postée
	{
		$template->assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}
	
}

//Listes les utilisateurs en lignes.
list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
$result = $sql->query_while("SELECT s.user_id, s.level, m.login 
FROM ".PREFIX."sessions s 
LEFT JOIN ".PREFIX."member m ON m.user_id = s.user_id 
WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.session_script LIKE '/forum/%'
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
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'] . ' : ' . $LANG['alert_topic'],
	'SID' => SID,
	'MODULE_DATA_PATH' => $template->module_data_path('forum'),
	'DESC' => $topic['subtitle'],
	'TOTAL_ONLINE' => $total_online,
	'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a>',
	'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
	'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a>',
	'U_MSG_SET_VIEW' => '<a class="small_link" href="../forum/action' . transid('.php?read=1', '') . '" title="' . $LANG['mark_as_read'] . '" onClick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
	'U_FORUM_CAT' => '<a href="forum' . transid('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '.php') . '">' . $CAT_FORUM[$topic['idcat']]['name'] . '</a>',
	'U_TITLE_T' => '<a href="topic' . transid('.php?id=' . $topic_id, '-' . $topic_id . '.php') . '">' . $topic['title'] . '</a>',
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_CONFIRM_READ_TOPICS' => $LANG['confirm_mark_as_read'],
	'L_SEARCH' => $LANG['search'],
	'L_PREVIEW' => $LANG['preview'],
	'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online'])
));

$template->pparse('forum_alert');	

include('../includes/footer.php');

?>