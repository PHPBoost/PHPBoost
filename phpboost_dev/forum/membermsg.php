<?php
/*##################################################
 *                                membermsg.php
 *                            -------------------
 *   begin                : April 19, 2007
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

require_once('../kernel/begin.php'); 
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php' . SID);
define('TITLE', $LANG['title_forum']);
require_once('../kernel/header.php'); 

$view_msg = retrieve(GET, 'id', 0);
if (!empty($view_msg)) //Affichage de tous les messages du membre
{
	$Template->set_filenames(array(
		'membermsg'=> 'forum/forum_membermsg.tpl',
		'forum_top'=> 'forum/forum_top.tpl',
		'forum_bottom'=> 'forum/forum_bottom.tpl'
	));
	
	
	$Pagination = new Pagination;
	
	$auth_cats = '';
	foreach ($CAT_FORUM as $idcat => $key)
	{
		if (!$User->check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM))
			$auth_cats .= $idcat . ',';
	}
	$auth_cats = !empty($auth_cats) ? " AND c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';

	$nbr_msg = $Sql->query("SELECT COUNT(*)
	FROM " . PREFIX . "forum_msg msg
	LEFT JOIN " . PREFIX . "forum_topics t ON msg.idtopic = t.id
	JOIN " . PREFIX . "forum_cats c ON t.idcat = c.id AND c.aprob = 1" . $auth_cats . "
	WHERE msg.user_id = '" . $view_msg . "'", __LINE__, __FILE__);

	$Template->assign_vars(array(
		'SID' => SID,
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'FORUM_NAME' => $CONFIG_FORUM['forum_name'] . ' : ' . $LANG['show_member_msg'],
		'PAGINATION' => $Pagination->display('membermsg' . url('.php?id=' . $view_msg . '&amp;p=%d'), $nbr_msg, 'p', 10, 3),
		'L_BACK' => $LANG['back'],
		'L_VIEW_MSG_USER' => $LANG['show_member_msg'],
		'L_FORUM_INDEX' => $LANG['forum_index'],
		'U_FORUM_VIEW_MSG' => url('.php?id=' . $view_msg)
	));
	
	$result = $Sql->query_while("SELECT msg.id, msg.user_id, msg.idtopic, msg.timestamp, msg.timestamp_edit, m.user_groups, t.title, t.status, t.idcat, c.name, m.login, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_ban, s.user_id AS connect, msg.contents
	FROM " . PREFIX . "forum_msg msg
	LEFT JOIN " . PREFIX . "forum_topics t ON msg.idtopic = t.id
	JOIN " . PREFIX . "forum_cats c ON t.idcat = c.id AND c.aprob = 1
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = '" . $view_msg . "'
	LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
	WHERE msg.user_id = '" . $view_msg . "'" . $auth_cats . "
	ORDER BY msg.id DESC
	" . $Sql->limit($Pagination->get_first_msg(10, 'p'), 10), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//Membre en ligne?
		$user_online = !empty($row['connect']) ? 'online' : 'offline';
	
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_cat_title = ($CONFIG['rewrite'] == 1) ? '+' . Url::encode_rewrite($row['name']) : '';
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ( $CONFIG['rewrite'] == 1 ) ? '+' . Url::encode_rewrite($row['title']) : '';
		
		//Ajout du marqueur d'édition si activé.
	$edit_mark = ($row['timestamp_edit'] > 0 && $CONFIG_FORUM['edit_mark'] == '0') ? '<br /><br /><br /><span style="padding: 10px;font-size:10px;font-style:italic;">' . $LANG['edit_by'] . ' <a class="edit_pseudo" href="../member/member' . url('.php?id=' . $row['user_id_edit'], '-' . $row['user_id_edit'] . '.php') . '">' . $row['login_edit'] . '</a> ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['timestamp_edit']) . '</span><br />' : '';
		
		$Template->assign_block_vars('list', array(
			'CONTENTS' => FormatingHelper::second_parse($row['contents']),
			'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format', $row['timestamp']),
			'ID' => $row['id'],
			'USER_ONLINE' => '<img src="../templates/' . get_utheme() . '/images/' . (!empty($row['connect']) ? 'online' : 'offline') . '.png" alt="" class="valign_middle" />',
			'USER_PSEUDO' => !empty($row['login']) ? wordwrap(html_entity_decode($row['login']), 13, '<br />', 1) : $LANG['guest'],			
			'U_USER_ID' => url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
			'U_USER_ID' => url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
			'U_VARS_ANCRE' => url('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . $rewrited_title . '.php'),
			'U_FORUM_CAT' => '<a class="forum_mbrmsg_links" href="../forum/forum' . url('.php?id=' . $row['idcat'], '-' . $row['idcat'] . $rewrited_cat_title . '.php') . '">' . $row['name'] . '</a>',
			'U_TITLE_T' => '<a class="forum_mbrmsg_links" href="../forum/topic' . url('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . $rewrited_title . '.php') . '">' . ucfirst($row['title']) . '</a>'
		));
	}
	$Sql->query_close($result);
	
	//Listes les utilisateurs en lignes.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.session_script LIKE '" . DIR . "/forum/%'");

	$Template->assign_vars(array(
		'TOTAL_ONLINE' => $total_online,
		'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
		'ADMIN' => $total_admin,
		'MODO' => $total_modo,
		'USER' => $total_member,
		'GUEST' => $total_visit,
		'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
		'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
		'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
		'L_USER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
		'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
		'L_AND' => $LANG['and'],
		'L_ONLINE' => strtolower($LANG['online'])
	));
		
	$Template->pparse('membermsg');
}
else
	AppContext::get_response()->redirect('/forum/index.php');

require_once('../kernel/footer.php');

?>
