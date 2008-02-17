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

require_once('../includes/begin.php');
require_once('../forum/forum_begin.php');

$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : '';
$cat_name = !empty($CAT_FORUM[$id_get]['name']) ? $CAT_FORUM[$id_get]['name'] : '';
$speed_bar->Add_link($CONFIG_FORUM['forum_name'], 'index.php' . SID);
$speed_bar->Add_link($cat_name, '');

if( !empty($id_get) )
	define('TITLE', $LANG['title_forum'] . ' - ' . addslashes($CAT_FORUM[$id_get]['name']));
else
	define('TITLE', $LANG['title_forum']);
require_once('../includes/header.php'); 

$template->set_filenames(array(
	'forum_index' => '../templates/' . $CONFIG['theme'] . '/forum/forum_index.tpl'
));

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

//Vérification des autorisations.
$unauth_cats = '';
if( is_array($AUTH_READ_FORUM) )
{
	foreach($AUTH_READ_FORUM as $idcat => $auth)
	{
		if( $auth === false )
			$unauth_cats .= $idcat . ',';
	}
	$unauth_cats = !empty($unauth_cats) ? " AND c.id NOT IN (" . trim($unauth_cats, ',') . ")" : '';
}

//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
$session->data['last_view_forum'] = isset($session->data['last_view_forum']) ? $session->data['last_view_forum'] : 0;
$max_time = (time() - $CONFIG_FORUM['view_time']);
$max_time_msg = ($session->data['last_view_forum'] > $max_time) ? $session->data['last_view_forum'] : $max_time;

$is_guest = ($session->data['user_id'] !== -1) ? false : true;
$total_topic = 0;
$total_msg = 0;	
$cat_left = 0;
$cat_right = 0;
//On liste les catégories et sous-catégories.
$result = $sql->query_while("SELECT c.id AS cid, c.level, c.name, c.subname, c.nbr_msg, c.nbr_topic, c.status, c.last_topic_id, t.id AS tid, 
t.idcat, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, m.user_id, m.login, v.last_view_id 
FROM ".PREFIX."forum_cats c
LEFT JOIN ".PREFIX."forum_topics t ON t.id = c.last_topic_id
LEFT JOIN ".PREFIX."forum_view v ON v.user_id = '" . $session->data['user_id'] . "' AND v.idtopic = t.id
LEFT JOIN ".PREFIX."member m ON m.user_id = t.last_user_id
WHERE c.aprob = 1 " . $display_sub_cat . " " . $unauth_cats . "
ORDER BY c.id_left", __LINE__, __FILE__);
while ($row = $sql->sql_fetch_assoc($result))
{	
	$template->assign_block_vars('forums_list', array(
	));	
	
	if( $row['level'] === '0' ) //Si c'est une catégorie
	{
		$template->assign_block_vars('forums_list.cats', array(
			'IDCAT' => $row['cid'],
			'NAME' => $row['name'],
			'U_FORUM_VARS' => transid('index.php?id=' . $row['cid'], 'cat-' . $row['cid'] . '+' . url_encode_rewrite($row['name']) . '.php')
		));
		$cat_left = $CAT_FORUM[$row['cid']]['id_left'];
		$cat_right = $CAT_FORUM[$row['cid']]['id_right'];			
	}
	else //On liste les sous-catégories
	{
		$subforums = '';
		if( !empty($id_get) )
		{
			$template->assign_block_vars('forums_list.cats', array(
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
			$template->assign_vars(array(
				'C_FORUM_ROOT_CAT' => false,
				'C_FORUM_CHILD_CAT' => true,
				'C_END_S_CATS' => false
			));			
			if( $CAT_FORUM[$row['cid']]['id_right'] - $CAT_FORUM[$row['cid']]['id_left'] > 1 )
			{		
				foreach($CAT_FORUM as $idcat => $key) //Listage des sous forums.
				{
					if( $CAT_FORUM[$idcat]['id_left'] > $CAT_FORUM[$row['cid']]['id_left'] && $CAT_FORUM[$idcat]['id_right'] < $CAT_FORUM[$row['cid']]['id_right'] )
					{
						if( $CAT_FORUM[$idcat]['level'] == ($CAT_FORUM[$row['cid']]['level'] + 1) ) //Sous forum distant d'un niveau au plus.
						{
							if( $AUTH_READ_FORUM[$row['cid']] ) //Autorisation en lecture.
							{
								$link = '<a href="forum' . transid('.php?id=' . $idcat, '-' . $idcat . '+' . url_encode_rewrite($CAT_FORUM[$idcat]['name']) . '.php') . '" class="small_link">';
								$subforums .= !empty($subforums) ? ', ' . $link . $CAT_FORUM[$idcat]['name'] . '</a>' : $link . $CAT_FORUM[$idcat]['name'] . '</a>';				
							}	
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

			$last_topic_title = (($CONFIG_FORUM['activ_display_msg'] && $row['display_msg']) ? $CONFIG_FORUM['display_msg'] : '') . ' ' . ucfirst($row['title']);			
			$last_topic_title = (strlen(html_entity_decode($last_topic_title)) > 20) ? substr_html($last_topic_title, 0, 20) . '...' : $last_topic_title;			
			$row['login'] = !empty($row['login']) ? $row['login'] : $LANG['guest'];
			
			$last = '<a href="topic' . transid('.php?id=' . $row['tid'], '-' . $row['tid'] . '+' . url_encode_rewrite($row['title'])  . '.php') . '" class="small_link">' . $last_topic_title . '</a><br />
			<a href="topic' . transid('.php?' . $last_page .  'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '+' . url_encode_rewrite($row['title'])  . '.php') . '#m' .  $last_msg_id . '"><img src="../templates/' . $CONFIG['theme'] . '/images/ancre.png" alt="" /></a> ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br />' . $LANG['by'] . ' ' . ($row['last_user_id'] != '-1' ? '<a href="../member/member' . transid('.php?id=' . $row['last_user_id'], '-' . $row['last_user_id'] . '.php') . '" class="small_link">' . $row['login'] . '</a>' : '<em>' . $LANG['guest'] . '</em>');
		}
		else
		{
			$row['last_timestamp'] = '';
			$last = '<br />' . $LANG['no_message'] . '<br /><br />';
		}

		//Vérifications des topics Lu/non Lus.
		$img_announce = 'announce';		
		if( !$is_guest )
		{
			if( $row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg ) //Nouveau message (non lu).
				$img_announce =  'new_' . $img_announce; //Image affiché aux visiteurs.
		}
		$img_announce .= ($row['status'] == '0') ? '_lock' : '';
		
		$total_topic += $row['nbr_topic'];
		$total_msg += $row['nbr_msg'];
		$template->assign_block_vars('forums_list.subcats', array(
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
	
	if( $cat_right - $cat_left == 1 || ($CAT_FORUM[$row['cid']]['id_right'] + 1) == $cat_right ) //Fermeture de la catégorie racine.
	{
		$template->assign_block_vars('forums_list.endcats', array(
		));	
	}
}
$sql->close($result);

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
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
	'NBR_MSG' => $total_msg,
	'NBR_TOPIC' => $total_topic,
	'TOTAL_ONLINE' => $total_online,	
	'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'SID' => SID,
	'MODULE_DATA_PATH' => $module_data_path,
	'C_TOTAL_POST' => true,
	'L_SEARCH' => $LANG['search'],
	'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_FORUM' => $LANG['forum'],

	'L_TOPIC' => ($total_topic > 1) ? $LANG['topic_s'] : $LANG['topic'],
	'L_MESSAGE' => ($total_msg > 1) ? $LANG['message_s'] : $LANG['message'],
	'L_LAST_MESSAGE' => $LANG['last_message'],
	'L_STATS' => $LANG['stats'],
	'L_CONFIRM_READ_TOPICS' => $LANG['confirm_mark_as_read'],
	'L_TOTAL_POST' => $LANG['nbr_message'],
	'L_DISTRIBUTED' => strtolower($LANG['distributed']),
	'L_AND' => $LANG['and'],	
	'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online'])
));

$template->pparse('forum_index');	

include('../includes/footer.php');

?>