<?php
/*##################################################
 *                                forum.php
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

require_once('../kernel/begin.php'); 
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php' . SID);
$Bread_crumb->add($LANG['show_not_reads'], '');
define('TITLE', $LANG['title_forum'] . ' - ' . $LANG['show_not_reads']);
require_once('../kernel/header.php'); 

//Redirection changement de catégorie.
if (!empty($_POST['change_cat']))
	redirect('/forum/forum' . url('.php?id=' . $_POST['change_cat'], '-' . $_POST['change_cat'] . $rewrited_title . '.php', '&'));
if (!$User->check_level(MEMBER_LEVEL)) //Réservé aux membres.
{	
	redirect('/member/error.php'); 
}
	
$Template->set_filenames(array(
	'forum_forum'=> 'forum/forum_forum.tpl',
	'forum_top'=> 'forum/forum_top.tpl',
	'forum_bottom'=> 'forum/forum_bottom.tpl'
));

import('util/pagination'); 
$Pagination = new Pagination();

//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
$max_time_msg = forum_limit_time_msg();

//Vérification des autorisations.
$auth_cats = '';
if (is_array($CAT_FORUM))
{
	foreach ($CAT_FORUM as $idcat => $key)
	{
		if (!$User->check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM) || !$CAT_FORUM[$idcat]['aprob'])
			$auth_cats .= $idcat . ',';
	}
	$auth_cats = !empty($auth_cats) ? " AND c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';
}

//Catégorie pour laquelle il faut afficher les messages non lus.
$idcat_unread = retrieve(GET, 'cat', 0);
$clause_cat = !empty($idcat_unread) ? "(c.id_left >= '" . $CAT_FORUM[$idcat_unread]['id_left'] . "' AND c.id_right <= '" . $CAT_FORUM[$idcat_unread]['id_right'] . "') AND " : '';

$result = $Sql->query_while("SELECT c.id as cid, m1.login AS login, m2.login AS last_login, t.id, t.title, t.subtitle, t.user_id, t.nbr_msg, t.nbr_views, t.last_user_id, t.last_msg_id, t.last_timestamp, t.type, t.status, t.display_msg, v.last_view_id, p.question, tr.id AS idtrack
FROM " . PREFIX . "forum_topics t
LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = t.id	AND v.user_id = '" . $User->get_attribute('user_id') . "'
LEFT JOIN " . PREFIX . "forum_poll p ON p.idtopic = t.id
LEFT JOIN " . PREFIX . "forum_track tr ON tr.idtopic = t.id AND tr.user_id = '" . $User->get_attribute('user_id') . "'
LEFT JOIN " . DB_TABLE_MEMBER . " m1 ON m1.user_id = t.user_id
LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = t.last_user_id
WHERE " . $clause_cat . "t.last_timestamp >= '" . $max_time_msg . "' AND (v.last_view_id != t.last_msg_id OR v.last_view_id IS NULL) " . $auth_cats . "
ORDER BY t.last_timestamp DESC 
" . $Sql->limit($Pagination->get_first_msg($CONFIG_FORUM['pagination_topic'], 'p'), $CONFIG_FORUM['pagination_topic']), __LINE__, __FILE__);
while ($row = $Sql->fetch_assoc($result))
{
	//On définit un array pour l'appelation correspondant au type de champ
	$type = array('2' => $LANG['forum_announce'] . ':', '1' => $LANG['forum_postit'] . ':', '0' => '');
		
	$img_announce = 'new_announce'; //Forcement non lu.
	$img_announce .= ($row['type'] == '1') ? '_post' : '';
	$img_announce .= ($row['type'] == '2') ? '_top' : '';
	$img_announce .= ($row['status'] == '0' && $row['type'] == '0') ? '_lock' : '';
	
	//Si le dernier message lu est présent on redirige vers lui, sinon on redirige vers le dernier posté.		
	if (!empty($row['last_view_id'])) //Calcul de la page du last_view_id réalisé dans topic.php
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
	$last_msg = '<a href="topic' . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite .  $rewrited_title . '.php') . '#m' . $last_msg_id . '" title=""><img src="../templates/' . get_utheme() . '/images/ancre.png" alt="" /></a>' . ' ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br /> ' . $LANG['by'] . ' ' . (!empty($row['last_login']) ? '<a class="small_link" href="../member/member' . url('.php?id=' . $row['last_user_id'], '-' . $row['last_user_id'] . '.php') . '">' . wordwrap_html($row['last_login'], 13) . '</a>' : '<em>' . $LANG['guest'] . '</em>');
	
	//Ancre ajoutée aux messages non lus.	
	$new_ancre = '<a href="topic' . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title . '.php') . '#m' . $last_msg_id . '" title=""><img src="../templates/' . get_utheme() . '/images/ancre.png" alt="" /></a>';
	
	$Template->assign_block_vars('topics', array(
		'C_IMG_POLL' => !empty($row['question']),
		'C_IMG_TRACK' => !empty($row['idtrack']),
		'C_DISPLAY_MSG' => ($CONFIG_FORUM['activ_display_msg'] && $CONFIG_FORUM['icon_activ_display_msg'] && $row['display_msg']),
		'C_HOT_TOPIC' => ($row['type'] == '0' && $row['status'] != '0' && ($row['nbr_msg'] > $CONFIG_FORUM['pagination_msg'])),
		'IMG_ANNOUNCE' => $img_announce,
		'ANCRE' => $new_ancre,
		'TYPE' => $type[$row['type']],
		'TITLE' => ucfirst($row['title']),			
		'AUTHOR' => !empty($row['login']) ? '<a href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="small_link">' . $row['login'] . '</a>' : '<em>' . $LANG['guest'] . '</em>',
		'DESC' => $row['subtitle'],
		'PAGINATION_TOPICS' => $Pagination->display('topic' . url('.php?id=' . $row['id'] . '&amp;pt=%d', '-' . $row['id'] . '-%d.php'), $row['nbr_msg'], 'pt', $CONFIG_FORUM['pagination_msg'], 2, 10, false),
		'MSG' => ($row['nbr_msg'] - 1),
		'VUS' => $row['nbr_views'],
		'U_TOPIC_VARS' => url('.php?id=' . $row['id'], '-' . $row['id'] . $rewrited_title . '.php'),
		'U_LAST_MSG' => $last_msg,
		'L_DISPLAY_MSG' => ($CONFIG_FORUM['activ_display_msg'] && $row['display_msg']) ? $CONFIG_FORUM['display_msg'] : '',
	));	
}
$Sql->query_close($result);

$nbr_topics = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_topics t
LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = t.id	AND v.user_id = '" . $User->get_attribute('user_id') . "'
WHERE " . $clause_cat . "t.last_timestamp >= '" . $max_time_msg . "' AND (v.last_view_id != t.last_msg_id OR v.last_view_id IS NULL) " . $auth_cats, __LINE__, __FILE__);

//Le membre a déjà lu tous les messages.
if ($nbr_topics == 0)
{
	$Template->assign_vars(array(
		'C_NO_MSG_NOT_READ' => true,
		'L_MSG_NOT_READ' => $LANG['no_msg_not_read']
	));		
}

$l_topic = ($nbr_topics > 1) ? $LANG['topic_s'] : $LANG['topic'];

$cat_filter = !empty($idcat_unread) ? '&amp;cat=' . $idcat_unread : '';
$Template->assign_vars(array(
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
	'SID' => SID,
	'MODULE_DATA_PATH' => $Template->get_module_data_path('forum'),
	'PAGINATION' => $Pagination->display('unread' . url('.php?p=%d' . $cat_filter), $nbr_topics, 'p', $CONFIG_FORUM['pagination_topic'], 3),
	'LANG' => get_ulang(),
	'U_CHANGE_CAT'=> 'unread.php' . SID . '&amp;token=' . $Session->get_token(),
	'U_ONCHANGE' => url(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php"),
	'U_ONCHANGE_CAT' => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),		
	'U_FORUM_CAT' => '<a href="../forum/unread.php' . SID . '">' . $LANG['show_not_reads'] . '</a>',
	'U_POST_NEW_SUBJECT' => '',		
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_FORUM' => $LANG['forum'],	
	'L_AUTHOR' => $LANG['author'],
	'L_TOPIC' => $l_topic,
	'L_MESSAGE' => $LANG['replies'],
	'L_ANSWERS' => $LANG['answers'],
	'L_VIEW' => $LANG['views'],
	'L_LAST_MESSAGE' => $LANG['last_message']
));	

//Listes les utilisateurs en lignes.
list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.session_script = '/forum/unread.php'");

$Template->assign_vars(array(
	'TOTAL_ONLINE' => $total_online,
	'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'USER' => $total_member,
	'GUEST' => $total_visit,
	'SELECT_CAT' => forum_list_cat(0, 0), //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
	'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_USER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online'])
));

$Template->pparse('forum_forum');

require_once('../kernel/footer.php');

?>
