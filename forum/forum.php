<?php
/*##################################################
 *                                forum.php
 *                            -------------------
 *   begin                : October 26, 2005
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
//Récupération de la barre d'arborescence.
speed_bar_generate($SPEED_BAR, $CONFIG_FORUM['forum_name'], 'index.php' . SID);
foreach($CAT_FORUM as $idcat => $array_info_cat)
{
	if( $CAT_FORUM[$id_get]['id_left'] > $array_info_cat['id_left'] && $CAT_FORUM[$id_get]['id_right'] < $array_info_cat['id_right'] && $array_info_cat['level'] < $CAT_FORUM[$id_get]['level'] )
		speed_bar_generate($SPEED_BAR, $array_info_cat['name'], ($array_info_cat['level'] == 0) ? transid('index.php?id=' . $idcat, 'cat-' . $idcat . '+' . url_encode_rewrite($array_info_cat['name']) . '.php') : 'forum' . transid('.php?id=' . $idcat, '-' . $idcat . '+' . url_encode_rewrite($array_info_cat['name']) . '.php'));
}
if( !empty($CAT_FORUM[$id_get]['name']) ) //Nom de la catégorie courante.
	speed_bar_generate($SPEED_BAR, $CAT_FORUM[$id_get]['name'], '');
if( !empty($id_get) )
	define('TITLE', $LANG['title_forum'] . ' - ' . addslashes($CAT_FORUM[$id_get]['name']));
else
	define('TITLE', $LANG['title_forum']);	
require_once('../includes/header.php'); 

//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
$rewrited_title = ($CONFIG['rewrite'] == 1 && !empty($CAT_FORUM[$id_get]['name'])) ? '+' . url_encode_rewrite($CAT_FORUM[$id_get]['name']) : '';

//Redirection changement de catégorie.
if( !empty($_POST['change_cat']) )
	redirect(HOST . DIR . '/forum/forum' . transid('.php?id=' . $_POST['change_cat'], '-' . $_POST['change_cat'] . $rewrited_title . '.php', '&'));
	
if( !empty($id_get) )
{
	//Vérification de l'existance de la catégorie.
	if( !isset($CAT_FORUM[$id_get]) || $CAT_FORUM[$id_get]['aprob'] == 0 || $CAT_FORUM[$id_get]['level'] == 0 )
		$errorh->error_handler('e_unexist_cat_forum', E_USER_REDIRECT);
		
	//Vérification des autorisations d'accès.
	if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], READ_CAT_FORUM) )
		$errorh->error_handler('e_auth', E_USER_REDIRECT);
	
	$template->set_filenames(array(
		'forum_forum' => '../templates/' . $CONFIG['theme'] . '/forum/forum_forum.tpl',
		'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
		'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
	));
	
	//Invité?	
	$is_guest = ($session->data['user_id'] !== -1) ? false : true;

	$module_data_path = $template->module_data_path('forum');
	
	//Affichage des sous forums s'il y en a.
	if( ($CAT_FORUM[$id_get]['id_right'] - $CAT_FORUM[$id_get]['id_left']) > 1 ) //Intervalle > 1 => sous forum présent.
	{
		//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
		$max_time_config = (time() - $CONFIG_FORUM['view_time']);
		$extend_field = '';
		$extend_field_s = '';
		if( !$is_guest ) //Jointure pour les membres pour des raisons d'optimisation SQL
		{
			$extend_field = "LEFT JOIN ".PREFIX."member_extend me ON me.user_id = '" . $session->data['user_id'] . "'";
			$extend_field_s = ', me.last_view_forum';
		}
	
		$template->assign_block_vars('cat', array(
			'L_NAME' => $LANG['sub_forums']
		));
		
		//On liste les sous-catégories.
		$result = $sql->query_while("SELECT c.id AS cid, c.name, c.subname, c.nbr_topic, c.nbr_msg, c.status, t.id AS tid, 
		t.idcat, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, m.user_id, m.login, v.last_view_id 
		" . $extend_field_s . "
		FROM ".PREFIX."forum_cats c
		LEFT JOIN ".PREFIX."forum_topics t ON t.id = c.last_topic_id
		LEFT JOIN ".PREFIX."forum_view v ON v.user_id = '" . $session->data['user_id'] . "' AND v.idtopic = t.id
		LEFT JOIN ".PREFIX."member m ON m.user_id = t.last_user_id
		" . $extend_field . "
		WHERE c.aprob = 1 
		AND c.id_left > '" . $CAT_FORUM[$id_get]['id_left'] . "' AND c.id_right < '" . $CAT_FORUM[$id_get]['id_right'] . "' AND c.level = '" . $CAT_FORUM[$id_get]['level'] . "' + 1
		ORDER BY c.id_left ASC", __LINE__, __FILE__);
		while ($row = $sql->sql_fetch_assoc($result))
		{	
			if( $groups->check_auth($CAT_FORUM[$row['cid']]['auth'], READ_CAT_FORUM) )
			{
				if( $row['nbr_msg'] !== '0' )
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

					$last = '<a href="topic' . transid('.php?id=' . $row['tid'], '-' . $row['tid'] . '+' . url_encode_rewrite($row['title'])  . '.php') . '" class="small_link">' . ucfirst($last_topic_title) . '</a><br />
					<a href="topic' . transid('.php?' . $last_page .  'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '+' . url_encode_rewrite($row['title'])  . '.php') . '#m' .  $last_msg_id . '" title=""><img src="../templates/' . $CONFIG['theme'] . '/images/ancre.png" alt="" /></a> ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br />
					' . $LANG['by'] . (!empty($row['login']) ? ' <a href="../member/member' . transid('.php?id=' . $row['last_user_id'], '-' . $row['last_user_id'] . '.php') . '" class="small_link">' . wordwrap_html($row['login'], 13) . '</a>' : ' ' . $LANG['guest']);
				}
				else
				{
					$row['last_timestamp'] = '';
					$last = '<br />' . $LANG['no_message'] . '<br /><br />';
				}

				//Vérirication de l'existance de sous forums.
				$subforums = '';
				if( $CAT_FORUM[$row['cid']]['id_right'] - $CAT_FORUM[$row['cid']]['id_left'] > 1 )
				{		
					foreach($CAT_FORUM as $idcat => $key) //Listage des sous forums.
					{
						if( $CAT_FORUM[$idcat]['id_left'] > $CAT_FORUM[$row['cid']]['id_left'] && $CAT_FORUM[$idcat]['id_right'] < $CAT_FORUM[$row['cid']]['id_right'] )
						{
							if( $CAT_FORUM[$idcat]['level'] == ($CAT_FORUM[$row['cid']]['level'] + 1) )
							{
								if( $groups->check_auth($CAT_FORUM[$row['cid']]['auth'], READ_CAT_FORUM) )
								{
									$link = '<a href="forum' . transid('.php?id=' . $idcat, '-' . $idcat . '+' . url_encode_rewrite($CAT_FORUM[$idcat]['name']) . '.php') . '" class="small_link">';
									$subforums .= !empty($subforums) ? ', ' . $link . $CAT_FORUM[$idcat]['name'] . '</a>' : $link . $CAT_FORUM[$idcat]['name'] . '</a>';		
								}
							}	
						}
					}	
					$subforums = '<strong>' . $LANG['subforum_s'] . '</strong>: ' . $subforums;
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
				
				$template->assign_block_vars('s_cats', array(					
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
		}
		$sql->close($result);		
		
		$template->assign_block_vars('end_cat', array(
		));
	}
		
	//On vérifie si l'utilisateur a les droits d'écritures.
	$check_group_write_auth = $groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM);
	$locked_cat = ($CAT_FORUM[$id_get]['status'] == 1 || $session->data['level'] == 2) ? false : true;
	if( !$check_group_write_auth )
	{
		$template->assign_block_vars('error_auth_write', array(
			'L_ERROR_AUTH_WRITE' => $LANG['e_cat_write']
		));
	}
	//Catégorie verrouillée?
	elseif( $locked_cat )
	{
		$template->assign_block_vars('error_auth_write', array(
			'L_ERROR_AUTH_WRITE' => $LANG['e_cat_lock_forum']
		));
	}
	
	//On crée une pagination (si activé) si le nombre de forum est trop important.
	include_once('../includes/pagination.class.php'); 
	$pagination = new Pagination();

	//Affichage de l'arborescence des catégories.
	$i = 0;
	$forum_cats = '';	
	foreach($SPEED_BAR as $key => $array)
	{
		if( $i == 2 )
			$forum_cats .= '<a href="' . $array[1] . '">' . $array[0] . '</a>';
		elseif( $i > 2 )		
			$forum_cats .= ' &raquo; <a href="' . $array[1] . '">' . $array[0] . '</a>';
		$i++;
	}
	
	//Si l'utilisateur a les droits d'édition.	
	$check_group_edit_auth = $groups->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);

	$nbr_topic = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_topics WHERE idcat = '" . $id_get . "'", __LINE__, __FILE__);
	$template->assign_vars(array(
		'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
		'SID' => SID,		
		'MODULE_DATA_PATH' => $module_data_path,
		'PAGINATION' => $pagination->show_pagin('forum' . transid('.php?id=' . $id_get . '&amp;p=%d', '-' . $id_get . '-%d.php'), $nbr_topic, 'p', $CONFIG_FORUM['pagination_topic'], 3),
		'IDCAT' => $id_get,
		'C_MASS_MODO_CHECK' => $check_group_edit_auth ? true : false,
		'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
		'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
		'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
		'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
		'U_CHANGE_CAT'=> 'forum' . transid('.php?id=' . $id_get, '-' . $id_get . $rewrited_title . '.php'),
		'U_ONCHANGE' => "'forum" . transid(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php") . "'",
		'U_FORUM_CAT' => $forum_cats,		
		'U_POST_NEW_SUBJECT' => ($check_group_write_auth && !$locked_cat) ? '&raquo; <a href="post' . transid('.php?new=topic&amp;id=' . $id_get, '') . '" title="' . $LANG['post_new_subject'] . '"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/post.png" alt="' . $LANG['post_new_subject'] . '" title="' . $LANG['post_new_subject'] . '" /></a>' : '',
		'L_FORUM_INDEX' => $LANG['forum_index'],		
		'L_FORUM' => $LANG['forum'],		
		'L_AUTHOR' => $LANG['author'],
		'L_TOPIC' => $LANG['topic_s'],
		'L_ANSWERS' => $LANG['replies'],
		'L_MESSAGE' => $LANG['message_s'],
		'L_VIEW' => $LANG['views'],
		'L_LAST_MESSAGE' => $LANG['last_messages'],
		'L_FOR_SELECTION' => $LANG['for_selection'],
		'L_CHANGE_STATUT_TO' => sprintf($LANG['change_status_to'], $CONFIG_FORUM['display_msg']),
		'L_CHANGE_STATUT_TO_DEFAULT' => $LANG['change_status_to_default'],
		'L_MOVE_TO' => $LANG['move_to'],
		'L_DELETE' => $LANG['delete'],
		'L_LOCK' => $LANG['forum_lock'],
		'L_UNLOCK' => $LANG['forum_unlock'],
		'L_GO' => $LANG['go']
	));		

	//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
	$max_time_config = (time() - $CONFIG_FORUM['view_time']);
	$extend_field = '';
	$extend_field_s = '';
	if( $session->data['user_id'] != -1 ) //Jointure pour les membres pour des raisons d'optimisation SQL
	{
		$extend_field = "LEFT JOIN ".PREFIX."member_extend me ON me.user_id = '" . $session->data['user_id'] . "'";
		$extend_field_s = ', me.last_view_forum';
	}

	$nbr_topics_display = 0;
	$result = $sql->query_while("SELECT m1.login AS login, m2.login AS last_login, t.id, t.title, t.subtitle, t.user_id, t.nbr_msg, t.nbr_views, t.last_user_id , t.last_msg_id, t.last_timestamp, t.type, t.status, t.display_msg, v.last_view_id, p.question, tr.id AS idtrack
	" . $extend_field_s . "
	FROM ".PREFIX."forum_topics t
	LEFT JOIN ".PREFIX."forum_view v ON v.user_id = '" . $session->data['user_id'] . "' AND v.idtopic = t.id
	LEFT JOIN ".PREFIX."member m1 ON m1.user_id = t.user_id
	LEFT JOIN ".PREFIX."member m2 ON m2.user_id = t.last_user_id
	LEFT JOIN ".PREFIX."forum_poll p ON p.idtopic = t.id
	LEFT JOIN ".PREFIX."forum_track tr ON tr.idtopic = t.id AND tr.user_id = '" . $session->data['user_id'] . "'
	" . $extend_field . "
	WHERE t.idcat = '" . $id_get . "'
	ORDER BY t.type DESC , t.last_timestamp DESC
	" . $sql->sql_limit($pagination->first_msg($CONFIG_FORUM['pagination_topic'], 'p'), $CONFIG_FORUM['pagination_topic']), __LINE__, __FILE__);	
	while ( $row = $sql->sql_fetch_assoc($result) )
	{
		//On définit un array pour l'appellation correspondant au type de champ
		$type = array('2' => $LANG['forum_announce'] . ':', '1' => $LANG['forum_postit'] . ':', '0' => '');
		
		//Vérifications des topics Lu/non Lus.
		$img_announce = 'announce';		
		$new_msg = false;
		if( !$is_guest ) //Non visible aux invités.
		{
			$new_msg = false;
			$max_time = ($row['last_view_forum'] > $max_time_config) ? $row['last_view_forum'] : $max_time_config;
			if( $row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time ) //Nouveau message (non lu).
			{	
				$img_announce =  'new_' . $img_announce; //Image affiché aux visiteurs.
				$new_msg = true;
			}
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
		$new_ancre = ($new_msg === true && !$is_guest) ? '<a href="topic' . transid('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title . '.php') . '#m' . $last_msg_id . '" title=""><img src="../templates/' . $CONFIG['theme'] . '/images/ancre.png" alt="" /></a>' : '';
		
		$template->assign_block_vars('topics', array(
			'ANNOUNCE' => $img_announce,
			'ANCRE' => $new_ancre,
			'POLL' => !empty($row['question']) ? '<img src="' . $module_data_path . '/images/poll_mini.png" class="valign_middle" alt="" />' : '',
			'TRACK' => !empty($row['idtrack']) ? '<img src="' . $module_data_path . '/images/favorite_mini.png" class="valign_middle" alt="" />' : '',
			'DISPLAY_MSG' => ($CONFIG_FORUM['activ_display_msg'] && $CONFIG_FORUM['icon_activ_display_msg'] && $row['display_msg']) ? '<img src="' . $module_data_path . '/images/msg_display_mini.png" alt="" class="valign_middle" />' : '',
			'TYPE' => $type[$row['type']],
			'TITLE' => ucfirst($row['title']),			
			'AUTHOR' => !empty($row['login']) ? '<a href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="small_link">' . $row['login'] . '</a>' : '<em>' . $LANG['guest'] . '</em>',
			'DESC' => $row['subtitle'],
			'PAGINATION_TOPICS' => $pagination->show_pagin('topic' . transid('.php?id=' . $row['id'] . '&amp;pt=%d', '-' . $row['id'] . '-%d.php'), $row['nbr_msg'], 'pt', $CONFIG_FORUM['pagination_msg'], 2, 10, NO_PREVIOUS_NEXT_LINKS, LINK_START_PAGE),
			'MSG' => ($row['nbr_msg'] - 1),
			'VUS' => $row['nbr_views'],
			'L_DISPLAY_MSG' => ($CONFIG_FORUM['activ_display_msg'] && $row['display_msg']) ? $CONFIG_FORUM['display_msg'] : '', 
			'U_TOPIC_VARS' => transid('.php?id=' . $row['id'], '-' . $row['id'] . $rewrited_title . '.php'),
			'U_LAST_MSG' => $last_msg
		));
		$nbr_topics_display++;
	}
	$sql->close($result);
		
	//Affichage message aucun topics.
	if( $nbr_topics_display == 0 )
	{
		$template->assign_block_vars('no_topics', array(
			'L_NO_TOPICS' => $LANG['no_topics']
		));
	}
		
	//Listes les utilisateurs en lignes.
	list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
	$result = $sql->query_while("SELECT s.user_id, s.level, m.login 
	FROM ".PREFIX."sessions s 
	LEFT JOIN ".PREFIX."member m ON m.user_id = s.user_id 
	WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.session_script = '/forum/forum.php' AND s.session_script_get LIKE '%id=" . $id_get . "%'
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
	
	$template->pparse('forum_forum');
}
else
	redirect(HOST . DIR . '/forum/index.php' . SID2);

include('../includes/footer.php');

?>