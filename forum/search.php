<?php
/*##################################################
 *                                search.php
 *                            -------------------
 *   begin                : May 18 2006
 *   copyright          : (C) 2006 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

include_once('../includes/begin.php'); 
include_once('../forum/lang/' . $CONFIG['lang'] . '/forum_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
include_once('../forum/forum_auth.php');

$speed_bar = array(
	$CONFIG_FORUM['forum_name'] => 'index.php' . SID,				
	$LANG['title_search'] => ''
);
define('TITLE', $LANG['title_search']);
define('ALTERNATIVE_CSS', 'forum');

include_once('../includes/header.php');

if( !$groups->check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

$search = !empty($_POST['search']) ? securit($_POST['search']) : '';
$idcat = !empty($_POST['idcat']) ? numeric($_POST['idcat']) : -1;
$time = !empty($_POST['time']) ? numeric($_POST['time']) * 3600 * 24 : 0; //X jour en secondes.
$where = !empty($_POST['where']) ? securit($_POST['where']) : '';
$valid_search = !empty($_POST['valid_search']) ? securit($_POST['valid_search']) : '';

$template->set_filenames(array(
	'search' => '../templates/' . $CONFIG['theme'] . '/forum/forum_search.tpl'
));

$template->assign_vars(array(
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
	'LANG' => $CONFIG['lang'],
	'SID' => SID,
	'SEARCH' => $search,
	'SELECT_CAT' => forum_list_cat($session->data), //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
	'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
	'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
	'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' .SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
	'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
	'U_FORUM_CAT' => '<a href="search.php' . SID . '">' . $LANG['search'] . '</a>',
	'U_ONCHANGE' => "'forum" . transid(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php") . "'",	
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_REQUIRE_TEXT' => $LANG['require_text'],
	'L_SEARCH_FORUM' => $LANG['search_forum'],
	'L_KEYWORDS' => $LANG['keywords'],
	'L_OPTIONS' => $LANG['option'],
	'L_DATE' => $LANG['date'],
	'L_DAY' => $LANG['day'],
	'L_DAYS' => $LANG['day_s'],
	'L_ALL' => $LANG['all'],
	'L_MONTH' => $LANG['month'],
	'L_TOPIC' => $LANG['topic'],
	'L_YEAR' => $LANG['year'],
	'L_CATEGORY' => $LANG['category'],
	'L_TITLE' => $LANG['title'],
	'L_CONTENTS' => $LANG['contents'],
	'L_RELEVANCE' => $LANG['relevance'],
	'L_SEARCH' => $LANG['search'],
	'L_ON' => $LANG['on']	
));

$auth_cats = '';
if( is_array($CAT_FORUM) )
{	
	foreach($CAT_FORUM as $id => $key)
	{
		if( !$groups->check_auth($CAT_FORUM[$id]['auth'], READ_CAT_FORUM) )
			$auth_cats .= $id . ',';
	}
}
$auth_cats_select = !empty($auth_cats) ? " AND id NOT IN (" . trim($auth_cats, ',') . ")" : '';

$selected = ($idcat == '-1') ? ' selected="selected"' : '';	
$template->assign_block_vars('cat', array(
	'CAT' => '<option value="-1"' . $selected . '>' . $LANG['all'] . '</option>'
));	
$result = $sql->query_while("SELECT id, name, level
FROM ".PREFIX."forum_cats 
WHERE aprob = 1 " . $auth_cats_select . "
ORDER BY id_left", __LINE__, __FILE__);
while( $row = $sql->sql_fetch_assoc($result) )
{	
	$margin = ($row['level'] > 0) ? str_repeat('----------', $row['level']) : '----';
	$selected = ($row['id'] == $idcat) ? ' selected="selected"' : '';	
	$template->assign_block_vars('cat', array(
		'CAT' => '<option value="' . $row['id'] . '"' . $selected . '>' . $margin . ' ' . $row['name'] . '</option>'
	));	
}
$sql->close($result);

if( !empty($valid_search) && !empty($search) )
{
	if( $idcat == '-1' )
		$idcat = 0;
			
	if( strlen($search) >= 4 )
	{
		$auth_cats = !empty($auth_cats) ? " AND c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';
		
		$req_msg = "SELECT msg.id as msgid, msg.user_id, msg.idtopic, msg.timestamp, t.title, c.id, c.auth, m.login, s.user_id AS connect, msg.contents, MATCH(msg.contents) AGAINST('" . $search . "') AS relevance
		FROM ".PREFIX."forum_msg AS msg
		LEFT JOIN ".PREFIX."sessions AS s ON s.user_id = msg.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.user_id != -1
		LEFT JOIN ".PREFIX."member AS m ON m.user_id = msg.user_id
		JOIN ".PREFIX."forum_topics AS t ON t.id = msg.idtopic
		JOIN ".PREFIX."forum_cats AS c1 ON c1.id = t.idcat
		JOIN ".PREFIX."forum_cats AS c ON c.level != 0 AND c.aprob = 1
		WHERE MATCH(msg.contents) AGAINST('" . $search . "') AND msg.timestamp > '" . (time() - $time) . "'
		" . (!empty($idcat) ? " AND t.idcat = '" . $idcat . "'" : '') . $auth_cats . "
		GROUP BY msg.id
		ORDER BY relevance DESC
		" . $sql->sql_limit(0, 24);

		$req_topic = "SELECT msg.id as msgid, msg.user_id, msg.idtopic, msg.timestamp, t.title, c.id, c.auth, m.login, s.user_id AS connect, msg.contents, MATCH(t.title) AGAINST('" . $search . "') AS relevance
		FROM ".PREFIX."forum_msg AS msg
		LEFT JOIN ".PREFIX."sessions AS s ON s.user_id = msg.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.user_id != -1
		LEFT JOIN ".PREFIX."member AS m ON m.user_id = msg.user_id
		JOIN ".PREFIX."forum_topics AS t ON t.id = msg.idtopic
		JOIN ".PREFIX."forum_cats AS c1 ON c1.id = t.idcat
		JOIN ".PREFIX."forum_cats AS c ON c.level != 0 AND c.aprob = 1
		WHERE MATCH(t.title) AGAINST('" . $search . "') AND msg.timestamp > '" . (time() - $time) . "'
		" . (!empty($idcat) ? " AND t.idcat = '" . $idcat . "'" : '') . $auth_cats . "
		GROUP BY t.id
		ORDER BY relevance DESC
		" . $sql->sql_limit(0, 24);
		
		$req = ($where === 'title') ? $req_topic : $req_msg;

		$max_relevance = 4.5;		
		$check_result = false;
		$result = $sql->query_while($req, __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) ) //On execute la requête dans une boucle pour afficher tout les résultats.
		{ 
			$title = '';
			if( !empty($row['title']) )
				$title = (strlen(html_entity_decode($row['title'])) > 45 ) ? substr_html($row['title'], 0, 45) . '...' : $row['title'];
			
			//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
			$rewrited_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($row['title']) : '';
			
			$template->assign_block_vars('list', array(
				'USER_ONLINE' => '<img src="../templates/' . $CONFIG['theme'] . '/images/' . ((!empty($row['connect']) && $row['user_id'] !== -1) ? 'online' : 'offline') . '.png" alt="" class="valign_middle" />',
				'USER_PSEUDO' => !empty($row['login']) ? '<a class="msg_link_pseudo" href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . wordwrap_html($row['login'], 13) . '</a>' : '<em>' . $LANG['guest'] . '</em>',			
				'CONTENTS' => second_parse($row['contents']),
				'RELEVANCE' => ($row['relevance'] > $max_relevance ) ? '100' : arrondi(($row['relevance'] * 100) / $max_relevance, 2),
				'DATE' => date('d/m/y', $row['timestamp']),
				'U_TITLE'  => '<a class="small_link" href="../forum/topic' . transid('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . $rewrited_title . '.php') . '#m' . $row['msgid'] . '" title="' . $title . '">' . $title . '</a>'				
			));
			
			$check_result = true;
		}	
		$sql->close($result);
		
		if( $check_result !== true )
			$errorh->error_handler($LANG['no_result'], E_USER_NOTICE);
	}
	else //Gestion erreur.
		$errorh->error_handler($LANG['invalid_req'], E_USER_NOTICE);
}
elseif( !empty($valid_search) )
	$errorh->error_handler($LANG['incomplete'], E_USER_WARNING);
	
$total_admin = 0;
$total_modo = 0;
$total_member = 0;
$total_visit = 0;
$user_pseudo = '';

$result = $sql->query_while("SELECT s.user_id, s.level, m.login 
FROM ".PREFIX."sessions AS s 
LEFT JOIN ".PREFIX."member AS m ON m.user_id = s.user_id 
WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.session_script = '" . DIR . "/forum/search.php'
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
	
	$virgule = !empty($user_pseudo) && $status != 'visiteur' ? ', ' : '';
	$user_pseudo .= ( !empty($row['login']) && $status != 'visiteur' ) ?  $virgule . '<a href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $status . '">' . $row['login'] . '</a>' : '';	
}
$sql->close($result);

$template->assign_block_vars('online', array(
	'ONLINE' =>  $user_pseudo
));
	
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
	'SELECT_CAT' => forum_list_cat($session->data) //Retourne la list des catégories, avec les vérifications d'accès qui s'imposent.
));

if( ($total_online - $total_visit) == 0  )
{
	$template->assign_block_vars('online', array(
		'ONLINE' =>  '<em>' . $LANG['no_member_online'] . '</em>'
	));
}

$template->pparse('search');

include('../includes/footer.php');

?>