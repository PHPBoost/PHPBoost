<?php
/*##################################################
 *                                index.php
 *                            -------------------
 *   begin                : October 25, 2005
 *   copyright          : (C) 2005 Viarre Régis / Sautel Benoît
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

include_once('../includes/begin.php');
include_once('../forum/lang/' . $CONFIG['lang'] . '/forum_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
include_once('../forum/forum_auth.php');

$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : '';
$cat_name = !empty($CAT_FORUM[$id_get]['name']) ? $CAT_FORUM[$id_get]['name'] : '';
$speed_bar = array(
	$CONFIG_FORUM['forum_name'] => 'index.php' . SID, $cat_name => ''
);
if( !empty($id_get) )
	define('TITLE', $LANG['title_forum'] . ' - ' . addslashes($CAT_FORUM[$id_get]['name']));
else
	define('TITLE', $LANG['title_forum']);
define('ALTERNATIVE_CSS', 'forum');
	
include_once('../includes/header.php'); 

//Accès au module.
if( !$groups->check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

$template->set_filenames(array(
	'forum_index' => '../templates/' . $CONFIG['theme'] . '/forum/forum_index.tpl')
);

//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
$max_time_config = (time() - $CONFIG_FORUM['view_time']);
$extend_field = '';
$extend_field_s = '';
if( $session->data['user_id'] != -1 ) //Jointure pour les membres pour des raisons d'optimisation SQL
{
	$extend_field = "LEFT JOIN ".PREFIX."member_extend AS me ON me.user_id = '" . $session->data['user_id'] . "'";
	$extend_field_s = ', me.last_view_forum';
}

//Affichage des sous-catégories de la catégorie.
$display_sub_cat = ' AND c.level BETWEEN 0 AND 1';
if( !empty($id_get) )
{
	$intervall = $sql->query_array("forum_cats", "id_left", "id_right", "level", "WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
	$display_sub_cat = ' AND c.id_left > \'' . $intervall['id_left'] . '\'
   AND c.id_right < \'' . $intervall['id_right'] . '\'
   AND c.level = \'' . $intervall['level'] . '\' + 1';
}

$module_data_path = $template->module_data_path('forum');

$total_topic = 0;
$total_msg = 0;	
$cat_left = 0;
$cat_right = 0;
$is_guest = ($session->data['user_id'] !== -1) ? false : true;
//On liste les catégories et sous-catégories.
$result = $sql->query_while("SELECT c.id AS cid, c.level, c.name, c.subname, c.nbr_msg, c.nbr_topic, c.status, c.last_topic_id, t.id AS tid, 
t.idcat, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, m.user_id, m.login, v.last_view_id 
" . $extend_field_s . "
FROM ".PREFIX."forum_cats AS c
LEFT JOIN ".PREFIX."forum_topics AS t ON t.id = c.last_topic_id
LEFT JOIN ".PREFIX."forum_view AS v ON v.user_id = '" . $session->data['user_id'] . "' AND v.idtopic = t.id
LEFT JOIN ".PREFIX."member AS m ON m.user_id = t.last_user_id
" . $extend_field . "
WHERE c.aprob = 1 " . $display_sub_cat . "
ORDER BY c.id_left", __LINE__, __FILE__);
while ($row = $sql->sql_fetch_assoc($result))
{	
	if( $groups->check_auth($CAT_FORUM[$row['cid']]['auth'], READ_CAT_FORUM) )
	{
		$template->assign_block_vars('all', array(
		));
			
		//Si c'est une catégorie
		if( $row['level'] === '0' )
		{
			$template->assign_block_vars('all.cats', array(
				'IDCAT' => $row['cid'],
				'NAME' => $row['name'],
				'U_FORUM_VARS' => transid('index.php?id=' . $row['cid'], 'cat-' . $row['cid'] . '+' . url_encode_rewrite($row['name']) . '.php')
			));
			$cat_left = $CAT_FORUM[$row['cid']]['id_left'];
			$cat_right = $CAT_FORUM[$row['cid']]['id_right'];			
		}
		//On liste les sous-catégories
		else
		{
			$subforums = '';
			if( !empty($id_get) )
			{
				$template->assign_block_vars('all.cats', array(
					'IDCAT' => $id_get,
					'NAME' => $CAT_FORUM[$id_get]['name'],
					'U_FORUM_VARS' => transid('index.php?id=' . $id_get, 'cat-' . $id_get . '+' . url_encode_rewrite($CAT_FORUM[$id_get]['name']) . '.php')
				));
				$cat_left = $CAT_FORUM[$id_get]['id_left'];
				$cat_right = $CAT_FORUM[$id_get]['id_right'];
				$id_get = '';
			}
			else //Vérirication de l'existance de sous forums.
			{
				if( $CAT_FORUM[$row['cid']]['id_right'] - $CAT_FORUM[$row['cid']]['id_left'] > 1 )
				{		
					foreach($CAT_FORUM as $idcat => $key) //Listage des sous forums.
					{
						if( $CAT_FORUM[$idcat]['id_left'] > $CAT_FORUM[$row['cid']]['id_left'] && $CAT_FORUM[$idcat]['id_right'] < $CAT_FORUM[$row['cid']]['id_right'] )
						{
							if( $CAT_FORUM[$idcat]['level'] == ($CAT_FORUM[$row['cid']]['level'] + 1) )
							{
								$link = '<a href="forum' . transid('.php?id=' . $idcat, '-' . $idcat . '+' . url_encode_rewrite($CAT_FORUM[$idcat]['name']) . '.php') . '" class="small_link">';
								$subforums .= !empty($subforums) ? ', ' . $link . $CAT_FORUM[$idcat]['name'] . '</a>' : $link . $CAT_FORUM[$idcat]['name'] . '</a>';					
							}
						}
					}	
					$subforums = '<strong>' . $LANG['subforum_s'] . '</strong>: ' . $subforums;
				}
			}
			
			if( !empty($row['last_topic_id']) )
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
				
				$last_topic_title = (($CONFIG_FORUM['activ_display_msg'] && $row['display_msg']) ? $CONFIG_FORUM['display_msg'] : '') . ' ' . $row['title'];			
				$last_topic_title = (strlen(html_entity_decode($last_topic_title)) > 23) ? substr_html($row['title'], 0, 23). '...' : $last_topic_title;			
				$row['login'] = !empty($row['login']) ? $row['login'] : $LANG['guest'];
				
				$last = '<a href="topic' . transid('.php?id=' . $row['tid'], '-' . $row['tid'] . '+' . url_encode_rewrite($row['title'])  . '.php') . '" class="small_link">' . ucfirst($last_topic_title) . '</a><br />
				<a href="topic' . transid('.php?' . $last_page .  'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '+' . url_encode_rewrite($row['title'])  . '.php') . '#m' .  $last_msg_id . '"><img src="../templates/' . $CONFIG['theme'] . '/images/ancre.png" alt="" /></a> ' . $LANG['on'] . ' ' . date($LANG['date_format'] . ' ' . $LANG['at'] . ' H\hi', $row['last_timestamp']) . '<br />' . $LANG['by'] . ' ' . ($row['last_user_id'] != '-1' ? '<a href="../member/member' . transid('.php?id=' . $row['last_user_id'], '-' . $row['last_user_id'] . '.php') . '" class="small_link">' . $row['login'] . '</a>' : '<em>' . $LANG['guest'] . '</em>');
				
			}
			else
			{
				$row['last_timestamp'] = '';
				$last = '<br />' . $lang['no_message'] . '<br /><br />';
			}

			//Vérifications des topics Lu/non Lus.
			$img_announce = 'announce';		
			if( !$is_guest )
			{
				$max_time = ($row['last_view_forum'] > $max_time_config) ? $row['last_view_forum'] : $max_time_config;
				if( $row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time ) //Nouveau message (non lu).
					$img_announce =  'new_' . $img_announce; //Image affiché aux visiteurs.
			}
			$img_announce .= ($row['status'] == '0') ? '_lock' : '';
			
			$total_topic += $row['nbr_topic'];
			$total_msg += $row['nbr_msg'];
			
			$template->assign_block_vars('all.s_cats', array(
				'ANNOUNCE' => '<img src="' . $module_data_path . '/images/' . $img_announce . '.gif" alt="" />',
				'NAME' => $row['name'],
				'DESC' => $row['subname'],
				'SUBFORUMS' => !empty($subforums) && !empty($row['subname']) ? '<br />' . $subforums : $subforums,
				'NBR_TOPIC' => $row['nbr_topic'],
				'NBR_MSG' => $row['nbr_msg'],
				'U_FORUM_VARS' => transid('.php?id=' . $row['cid'], '-' . $row['cid'] . '+' . url_encode_rewrite($row['name']) . '.php'),
				'U_LAST_TOPIC' => $last				
			));
		}
		
		if( $cat_right - $cat_left == 1 || ($CAT_FORUM[$row['cid']]['id_right'] + 1) == $cat_right )
		{
			$template->assign_block_vars('all.end_s_cats', array(
			));	
		}
	}
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

$l_msg = ($total_msg > 1) ? $LANG['message_s'] : $LANG['message'];
$l_topic = ($total_topic > 1) ? $LANG['topic_s'] : $LANG['topic'];
$l_admin = ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'];
$l_modo = ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'];
$l_member = ($total_member > 1) ? $LANG['member_s'] : $LANG['member'];
$l_visit = ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'];

$total_online = $total_admin + $total_modo + $total_member + $total_visit;
$l_online = ($total_online > 1) ? $LANG['user_s'] : $LANG['user'];

$template->assign_vars(array(
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
	'NBR_MSG' => $total_msg,
	'NBR_TOPIC' => $total_topic,
	'TOTAL_ONLINE' => $total_online,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'SID' => SID,
	'MODULE_DATA_PATH' => $module_data_path,
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_FORUM' => $LANG['forum'],
	'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
	'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
	'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' .SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a> &bull;',
	'U_MSG_SET_VIEW' => '<a class="small_link" href="../forum/action' . transid('.php?read=1', '') . '" title="">' . $LANG['mark_as_read'] . '</a>',
	'L_TOPIC' => $l_topic,
	'L_MESSAGE' => $l_msg,
	'L_LAST_MESSAGE' => $LANG['last_message'],
	'L_STATS' => $LANG['stats'],
	'L_TOTAL_POST' => $LANG['nbr_message'],
	'L_DISTRIBUTED' => strtolower($LANG['distributed']),
	'L_USER' => $l_online,
	'L_ADMIN' => $l_admin,
	'L_MODO' => $l_modo ,
	'L_MEMBER' => $l_member,
	'L_GUEST' => $l_visit,
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online'])	
));

if( ($total_online - $total_visit) == 0  )
{
	$template->assign_block_vars('online', array(
		'ONLINE' =>  '<em>' . $LANG['no_member_online'] . '</em>'
	));
}

$template->pparse('forum_index');	

include('../includes/footer.php');

?>