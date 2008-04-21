<?php
/*##################################################
 *                                stats.php
 *                            -------------------
 *   begin                : March 28, 2007
 *   copyright          : (C) 2007 Viarre Régis
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
require_once('../forum/forum_tools.php');

$Bread_crumb->Add_link($CONFIG_FORUM['forum_name'], 'index.php' . SID);
$Bread_crumb->Add_link($LANG['stats'], '');
define('TITLE', $LANG['title_forum'] . ' - ' . $LANG['stats']);
require_once('../includes/header.php'); 

$Template->Set_filenames(array(
	'forum_stats'=> 'forum/forum_stats.tpl',
	'forum_top'=> 'forum/forum_top.tpl',
	'forum_bottom'=> 'forum/forum_bottom.tpl'
));

$total_day = number_round((time() - $CONFIG['start'])/(3600*24), 0);
$timestamp_today = @mktime(0, 0, 1, gmdate_format('m'), gmdate_format('d'), gmdate_format('y'));

$sum = $Sql->Query_array("forum_cats", "SUM(nbr_topic) as total_topics", "SUM(nbr_msg) as total_msg", "WHERE level <> 0 AND level < 2 AND aprob = 1", __LINE__, __FILE__);

$total_day = max(1, $total_day);
$nbr_topics_day = number_round($sum['total_topics']/$total_day, 1);
$nbr_msg_day = number_round($sum['total_msg']/$total_day, 1);
$nbr_topics_today = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."forum_topics t
JOIN ".PREFIX."forum_msg m ON m.id = t.first_msg_id
WHERE m.timestamp > '" . $timestamp_today . "'", __LINE__, __FILE__);
$nbr_msg_today = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."forum_msg WHERE timestamp > '" . $timestamp_today . "'", __LINE__, __FILE__);

$Template->Assign_vars(array(
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
	'SID' => SID,
	'MODULE_DATA_PATH' => $Template->Module_data_path('forum'),
	'NBR_TOPICS' => $sum['total_topics'],
	'NBR_MSG' => $sum['total_msg'],
	'NBR_TOPICS_DAY' => $nbr_topics_day,
	'NBR_MSG_DAY' => $nbr_msg_day,
	'NBR_TOPICS_TODAY' => $nbr_topics_today,
	'NBR_MSG_TODAY' => $nbr_msg_today,
	'L_FORUM_INDEX' => $LANG['forum_index'],		
	'L_FORUM' => $LANG['forum'],
	'L_STATS' => $LANG['stats'],
	'L_NBR_TOPICS' => ($sum['total_topics'] > 1) ? $LANG['topic_s'] : $LANG['topic'],
	'L_NBR_MSG' => ($sum['total_msg'] > 1) ? $LANG['message_s'] : $LANG['message'],
	'L_NBR_TOPICS_DAY' => $LANG['nbr_topics_day'],
	'L_NBR_MSG_DAY' => $LANG['nbr_msg_day'],
	'L_NBR_TOPICS_TODAY' => $LANG['nbr_topics_today'],
	'L_NBR_MSG_TODAY' => $LANG['nbr_msg_today'],
	'L_LAST_MSG' => $LANG['forum_last_msg'],
	'L_POPULAR' => $LANG['forum_popular'],				
	'L_ANSWERS' => $LANG['forum_nbr_answers'],
));

$auth_cats = '';
if( is_array($CAT_FORUM) )
{
	foreach($CAT_FORUM as $idcat => $key)
	{
		if( !$Member->Check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM) )
			$auth_cats .= $idcat . ',';
	}
	$auth_cats = !empty($auth_cats) ? " AND c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';
}

//Dernières réponses	
$result = $Sql->Query_while("SELECT t.id, t.title, c.id as cid, c.auth
FROM ".PREFIX."forum_topics t
LEFT JOIN ".PREFIX."forum_cats c ON c.id = t.idcat
WHERE c.level != 0 AND c.aprob = 1 " . $auth_cats . "
ORDER BY t.last_timestamp DESC
" . $Sql->Sql_limit(0, 10), __LINE__, __FILE__);
while($row = $Sql->Sql_fetch_assoc($result))
{
	$Template->Assign_block_vars('last_msg', array(
		'U_TOPIC_ID' => transid('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
		'TITLE' => $row['title']
	));
}
$Sql->Close($result);

//Les plus vus	
$result = $Sql->Query_while("SELECT t.id, t.title, c.id as cid, c.auth
FROM ".PREFIX."forum_topics t
LEFT JOIN ".PREFIX."forum_cats c ON c.id = t.idcat
WHERE c.level != 0 AND c.aprob = 1 " . $auth_cats . "
ORDER BY t.nbr_views DESC
" . $Sql->Sql_limit(0, 10), __LINE__, __FILE__);
while($row = $Sql->Sql_fetch_assoc($result))
{
	$Template->Assign_block_vars('popular', array(
		'U_TOPIC_ID' => transid('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
		'TITLE' => $row['title']
	));
}
$Sql->Close($result);

//Les plus répondus	
$result = $Sql->Query_while("SELECT t.id, t.title, c.id as cid, c.auth
FROM ".PREFIX."forum_topics t
LEFT JOIN ".PREFIX."forum_cats c ON c.id = t.idcat
WHERE c.level != 0 AND c.aprob = 1 " . $auth_cats . "
ORDER BY t.nbr_msg DESC
" . $Sql->Sql_limit(0, 10), __LINE__, __FILE__);
while($row = $Sql->Sql_fetch_assoc($result))
{
	$Template->Assign_block_vars('answers', array(
		'U_TOPIC_ID' => transid('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
		'TITLE' => $row['title']
	));
}	
$Sql->Close($result);
	
//Listes les utilisateurs en lignes.
list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
$result = $Sql->Query_while("SELECT s.user_id, s.level, m.login 
FROM ".PREFIX."sessions s 
LEFT JOIN ".PREFIX."member m ON m.user_id = s.user_id 
WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.session_script LIKE '/forum/%'
ORDER BY s.session_time DESC", __LINE__, __FILE__);
while( $row = $Sql->Sql_fetch_assoc($result) )
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
$Sql->Close($result);

$total_online = $total_admin + $total_modo + $total_member + $total_visit;
$Template->Assign_vars(array(
	'TOTAL_ONLINE' => $total_online,
	'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online'])
));

$Template->Pparse('forum_stats');	

include('../includes/footer.php');

?>