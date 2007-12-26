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

include_once('../includes/begin.php'); 
include_once('../forum/lang/' . $CONFIG['lang'] . '/forum_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
include_once('../forum/forum_auth.php');

$speed_bar = array(
	$CONFIG_FORUM['forum_name'] => 'index.php' . SID,				
	$LANG['stats'] => ''
);
define('TITLE', $LANG['title_forum'] . ' - ' . $LANG['stats']);
define('ALTERNATIVE_CSS', 'forum');

include_once('../includes/header.php'); 

$page = !empty($_GET['p']) ? numeric($_GET['p']) : 1;

if( !$groups->check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

$template->set_filenames(array(
	'forum_stats' => '../templates/' . $CONFIG['theme'] . '/forum/forum_stats.tpl')
);

$total_day = arrondi((time() - $CONFIG['start'])/(3600*24), 0);
$timestamp_today = @mktime(0, 0, 1, date('m'), date('d'), date('y'));

$nbr_topics = $sql->query("SELECT SUM(nbr_topic) FROM ".PREFIX."forum_cats WHERE level != 0 AND aprob = 1", __LINE__, __FILE__);
$nbr_msg = $sql->query("SELECT SUM(nbr_msg) FROM ".PREFIX."forum_cats WHERE level != 0 AND aprob = 1", __LINE__, __FILE__);

$total_day = max(1, $total_day);
$nbr_topics_day = arrondi($nbr_topics/$total_day, 1);
$nbr_msg_day = arrondi($nbr_msg/$total_day, 1);
$nbr_topics_today = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_topics AS t
JOIN ".PREFIX."forum_msg AS m ON m.id = t.first_msg_id
WHERE m.timestamp > '" . $timestamp_today . "'", __LINE__, __FILE__);
$nbr_msg_today = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_msg WHERE timestamp > '" . $timestamp_today . "'", __LINE__, __FILE__);

$template->assign_vars(array(
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
	'SID' => SID,
	'MODULE_DATA_PATH' => $template->module_data_path('forum'),
	'NBR_TOPICS' => $nbr_topics,
	'NBR_MSG' => $nbr_msg,
	'NBR_TOPICS_DAY' => $nbr_topics_day,
	'NBR_MSG_DAY' => $nbr_msg_day,
	'NBR_TOPICS_TODAY' => $nbr_topics_today,
	'NBR_MSG_TODAY' => $nbr_msg_today,
	'L_FORUM_INDEX' => $LANG['forum_index'],		
	'L_FORUM' => $LANG['forum'],
	'L_STATS' => $LANG['stats'],
	'L_NBR_TOPICS' => ($nbr_topics > 1) ? $LANG['topic_s'] : $LANG['topic'],
	'L_NBR_MSG' => ($nbr_msg > 1) ? $LANG['message_s'] : $LANG['message'],
	'L_NBR_TOPICS_DAY' => $LANG['nbr_topics_day'],
	'L_NBR_MSG_DAY' => $LANG['nbr_msg_day'],
	'L_NBR_TOPICS_TODAY' => $LANG['nbr_topics_today'],
	'L_NBR_MSG_TODAY' => $LANG['nbr_msg_today'],
	'L_LAST_MSG' => $LANG['forum_last_msg'],				
	'L_POPULAR' => $LANG['forum_popular'],				
	'L_ANSWERS' => $LANG['forum_nbr_answers'],
'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
	'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
	'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
	'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
	'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',	
));

$auth_cats = '';
if( is_array($CAT_FORUM) )
{
	foreach($CAT_FORUM as $idcat => $key)
	{
		if( !$groups->check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM) )
			$auth_cats .= $idcat . ',';
	}
	$auth_cats = !empty($auth_cats) ? " AND c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';
}

//Dernières réponses	
$result = $sql->query_while("SELECT t.id, t.title, c.id as cid, c.auth
FROM ".PREFIX."forum_topics AS t
LEFT JOIN ".PREFIX."forum_cats AS c ON c.id = t.idcat
WHERE c.level != 0 AND c.aprob = 1 " . $auth_cats . "
ORDER BY t.last_timestamp DESC
" . $sql->sql_limit(0, 10), __LINE__, __FILE__);
while($row = $sql->sql_fetch_assoc($result))
{
	$template->assign_block_vars('last_msg', array(
		'U_TOPIC_ID' => transid('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
		'TITLE' => $row['title']
	));
}
$sql->close($result);

//Les plus vus	
$result = $sql->query_while("SELECT t.id, t.title, c.id as cid, c.auth
FROM ".PREFIX."forum_topics AS t
LEFT JOIN ".PREFIX."forum_cats AS c ON c.id = t.idcat
WHERE c.level != 0 AND c.aprob = 1 " . $auth_cats . "
ORDER BY t.nbr_views DESC
" . $sql->sql_limit(0, 10), __LINE__, __FILE__);
while($row = $sql->sql_fetch_assoc($result))
{
	$template->assign_block_vars('popular', array(
		'U_TOPIC_ID' => transid('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
		'TITLE' => $row['title']
	));
}
$sql->close($result);

//Les plus répondus	
$result = $sql->query_while("SELECT t.id, t.title, c.id as cid, c.auth
FROM ".PREFIX."forum_topics AS t
LEFT JOIN ".PREFIX."forum_cats AS c ON c.id = t.idcat
WHERE c.level != 0 AND c.aprob = 1 " . $auth_cats . "
ORDER BY t.nbr_msg DESC
" . $sql->sql_limit(0, 10), __LINE__, __FILE__);
while($row = $sql->sql_fetch_assoc($result))
{
	$template->assign_block_vars('answers', array(
		'U_TOPIC_ID' => transid('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
		'TITLE' => $row['title']
	));
}	
$sql->close($result);
	
$total_admin = 0;
$total_modo = 0;
$total_member = 0;
$total_visit = 0;
$user_pseudo = '';

$result = $sql->query_while("SELECT s.user_id, s.level, m.login 
FROM ".PREFIX."sessions AS s 
LEFT JOIN ".PREFIX."member AS m ON m.user_id = s.user_id 
WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.session_script LIKE '" . DIR . "/forum/%'
ORDER BY s.session_time DESC", __LINE__, __FILE__);
while( $row = $sql->sql_fetch_assoc($result) )
{
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

	$coma = !empty($user_pseudo) && $status != 'visiteur' ? ', ' : '';
	$user_pseudo .= ( !empty($row['login']) && $status != 'visiteur' ) ?  $coma . '<a href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $status . '">' . $row['login'] . '</a>' : '';
}

$template->assign_block_vars('online', array(
	'ONLINE' =>  $user_pseudo
));

$sql->close($result);

$l_admin = ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'];
$l_modo = ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'];
$l_member = ($total_member > 1) ? $LANG['member_s'] : $LANG['member'];
$l_visit = ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'];

$total_online = $total_admin + $total_modo + $total_member + $total_visit;
$l_online = ($total_online > 1) ? $LANG['user_s'] : $LANG['user'];

$template->assign_vars(array(
	'L_USER' => $l_online,
	'L_ADMIN' => $l_admin,
	'L_MODO' => $l_modo ,
	'L_MEMBER' => $l_member,
	'L_GUEST' => $l_visit,
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online']),	
	'TOTAL_ONLINE' => $total_online,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'SELECT_CAT' => forum_list_cat($session->data) //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
));

if( ($total_online - $total_visit) == 0  )
{
	$template->assign_block_vars('online', array(
		'ONLINE' =>  '<em>' . $LANG['no_member_online'] . '</em>'
	));
}

$template->pparse('forum_stats');	

include('../includes/footer.php');

?>