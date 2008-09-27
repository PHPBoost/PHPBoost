<?php
/*##################################################
 *                                topic.php
 *                            -------------------
 *   begin                : October 26, 2005
 *   copyright          : (C) 2005 Viarre Régis / Sautel Benoît
 *   email                : mickaelhemri@gmail.com / ben.popeye@gmail.com
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

$page = retrieve(GET, 'pt', 1);
$id_get = retrieve(GET, 'id', 0);
$quote_get = retrieve(GET, 'quote', 0);	

//On va chercher les infos sur le topic	
$topic = !empty($id_get) ? $Sql->Query_array('forum_topics', 'id', 'user_id', 'idcat', 'title', 'subtitle', 'nbr_msg', 'last_msg_id', 'first_msg_id', 'last_timestamp', 'status', 'display_msg', "WHERE id = '" . $id_get . "'", __LINE__, __FILE__) : '';
//Existance du topic.
if( empty($topic['id']) )
	$Errorh->Error_handler('e_unexist_topic_forum', E_USER_REDIRECT);
//Existance de la catégorie.
if( !isset($CAT_FORUM[$topic['idcat']]) || $CAT_FORUM[$topic['idcat']]['aprob'] == 0 || $CAT_FORUM[$topic['idcat']]['level'] == 0 )
	$Errorh->Error_handler('e_unexist_cat', E_USER_REDIRECT);

//Récupération de la barre d'arborescence.
$Bread_crumb->Add_link($CONFIG_FORUM['forum_name'], 'index.php' . SID);
foreach($CAT_FORUM as $idcat => $array_info_cat)
{
	if( $CAT_FORUM[$topic['idcat']]['id_left'] > $array_info_cat['id_left'] && $CAT_FORUM[$topic['idcat']]['id_right'] < $array_info_cat['id_right'] && $array_info_cat['level'] < $CAT_FORUM[$topic['idcat']]['level'] )
		$Bread_crumb->Add_link($array_info_cat['name'], ($array_info_cat['level'] == 0) ? transid('index.php?id=' . $idcat, 'cat-' . $idcat . '+' . url_encode_rewrite($array_info_cat['name']) . '.php') : 'forum' . transid('.php?id=' . $idcat, '-' . $idcat . '+' . url_encode_rewrite($array_info_cat['name']) . '.php'));
}
if( !empty($CAT_FORUM[$topic['idcat']]['name']) ) //Nom de la catégorie courante.
	$Bread_crumb->Add_link($CAT_FORUM[$topic['idcat']]['name'], 'forum' . transid('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '+' . url_encode_rewrite($CAT_FORUM[$topic['idcat']]['name']) . '.php'));
$Bread_crumb->Add_link($topic['title'], '');

define('TITLE', $LANG['title_topic'] . ' - ' . addslashes($topic['title']));
require_once('../kernel/header.php'); 

$rewrited_cat_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($CAT_FORUM[$topic['idcat']]['name']) : ''; //On encode l'url pour un éventuel rewriting.
$rewrited_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($topic['title']) : ''; //On encode l'url pour un éventuel rewriting.

//Redirection changement de catégorie.
if( !empty($_POST['change_cat']) )
	redirect(HOST . DIR . '/forum/forum' . transid('.php?id=' . $_POST['change_cat'], '-' . $_POST['change_cat'] . $rewrited_cat_title . '.php', '&'));
	
//Autorisation en lecture.
if( !$Member->Check_auth($CAT_FORUM[$topic['idcat']]['auth'], READ_CAT_FORUM) || !empty($CAT_FORUM[$topic['idcat']]['url']) )
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT);

$Template->Set_filenames(array(
	'forum_topic'=> 'forum/forum_topic.tpl',
	'forum_top'=> 'forum/forum_top.tpl',
	'forum_bottom'=> 'forum/forum_bottom.tpl'
));

$module_data_path = $Template->Module_data_path('forum');

//Si l'utilisateur a le droit de déplacer le topic, ou le verrouiller.	
$check_group_edit_auth = $Member->Check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM);
if( $check_group_edit_auth )
{
	$lock_status = '';
	if( $topic['status'] == '1' ) //Unlocked, affiche lien pour verrouiller.
		$lock_status = '<a href="action' . transid('.php?id=' . $id_get . '&amp;lock=true') . '" onClick="javascript:return Confirm_lock();" title="' . $LANG['forum_lock']  . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/lock.png" alt="' . $LANG['forum_lock']  . '" title="' . $LANG['forum_lock']  . '" class="valign_middle" /></a>';
	elseif( $topic['status'] == '0' ) //Lock, affiche lien pour déverrouiler.
		$lock_status = '<a href="action' . transid('.php?id=' . $id_get . '&amp;lock=false') . '" onClick="javascript:return Confirm_unlock();" title="' . $LANG['forum_unlock']  . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/unlock.png" alt="' . $LANG['forum_unlock']  . '" title="' . $LANG['forum_unlock']  . '" class="valign_middle" /></a>';
	
	$Template->Assign_vars(array(
		'MOVE' => '<a href="move' . transid('.php?id=' . $id_get) . '" onClick="javascript:return Confirm_move();" title="' . $LANG['forum_move'] . '"><img src="' . $module_data_path . '/images/move.png" alt="' . $LANG['forum_move'] . '" title="' . $LANG['forum_move'] . '" class="valign_middle" /></a>',
		'LOCK' => $lock_status,
		'L_ALERT_DELETE_TOPIC' => $LANG['alert_delete_topic'],
		'L_ALERT_LOCK_TOPIC' => $LANG['alert_lock_topic'],
		'L_ALERT_UNLOCK_TOPIC' => $LANG['alert_unlock_topic'],
		'L_ALERT_MOVE_TOPIC' => $LANG['alert_move_topic'],
		'L_ALERT_CUT_TOPIC' => $LANG['alert_cut_topic']
	));
}

//Message(s) dans le topic non lu ( non prise en compte des topics trop vieux (x semaine) ou déjà lus).
mark_topic_as_read($id_get, $topic['last_msg_id'], $topic['last_timestamp']);
	
//Gestion de la page si redirection vers le dernier message lu.
$idm = retrieve(GET, 'idm', 0);
if( !empty($idm) )
{
	//Calcul de la page sur laquelle se situe le message.
	$nbr_msg_before = $Sql->Query("SELECT COUNT(*) as nbr_msg_before FROM ".PREFIX."forum_msg WHERE idtopic = " . $id_get . " AND id < '" . $idm . "'", __LINE__, __FILE__); //Nombre de message avant le message de destination.
	
	//Dernier message de la page? Redirection vers la page suivante pour prendre en compte la reprise du message précédent.
	if( is_int(($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg']) ) 
	{	
		//On redirige vers la page suivante, seulement si ce n'est pas la dernière.
		if( $topic['nbr_msg'] != ($nbr_msg_before + 1) )
			$nbr_msg_before++;
	}
	
	$_GET['pt'] = ceil(($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg']); //Modification de la page affichée.
}	
	
//On crée une pagination si le nombre de msg est trop important.
include_once('../kernel/framework/util/pagination.class.php'); 
$Pagination = new Pagination();	

//Affichage de l'arborescence des catégories.
$i = 0;
$forum_cats = '';	
$Bread_crumb->Remove_last_link();
foreach($Bread_crumb->array_links as $key => $array)
{
	if( $i == 2 )
		$forum_cats .= '<a href="' . $array[1] . '">' . $array[0] . '</a>';
	elseif( $i > 2 )		
		$forum_cats .= ' &raquo; <a href="' . $array[1] . '">' . $array[0] . '</a>';
	$i++;
}

$Template->Assign_vars(array(
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
	'SID' => SID,		
	'MODULE_DATA_PATH' => $module_data_path,
	'DESC' => !empty($topic['subtitle']) ? $topic['subtitle'] : '',
	'PAGINATION' => $Pagination->Display_pagination('topic' . transid('.php?id=' . $id_get . '&amp;pt=%d', '-' . $id_get . '-%d.php'), $topic['nbr_msg'], 'pt', $CONFIG_FORUM['pagination_msg'], 3),
	'THEME' => $CONFIG['theme'],
	'LANG' => $CONFIG['lang'],
	'USER_ID' => $topic['user_id'],
	'ID' => $topic['idcat'],
	'IDTOPIC' => $id_get,
	'PAGE' => $page,
	'U_MSG_SET_VIEW' => '<a class="small_link" href="../forum/action' . transid('.php?read=1&amp;f=' . $topic['idcat'], '') . '" title="' . $LANG['mark_as_read'] . '" onClick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
	'U_CHANGE_CAT'=> 'topic' . transid('.php?id=' . $id_get, '-' . $id_get . $rewrited_cat_title . '.php'),
	'U_ONCHANGE' => "'forum" . transid(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php") . "'",		
	'U_FORUM_CAT' => !empty($forum_cats) ? $forum_cats . ' &raquo;' : '',
	'U_TITLE_T' => '<a href="topic' . transid('.php?id=' . $id_get, '-' . $id_get . $rewrited_title . '.php') . '">' . (($CONFIG_FORUM['activ_display_msg'] && $topic['display_msg']) ? $CONFIG_FORUM['display_msg'] . ' ' : '') . ucfirst($topic['title']) . '</a>',
	'L_REQUIRE_MESSAGE' => $LANG['require_text'],
	'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_QUOTE' => $LANG['quote'],
	'L_RESPOND' => $LANG['respond'],
	'L_SUBMIT' => $LANG['submit'],
	'L_PREVIEW' => $LANG['preview'],
	'L_RESET' => $LANG['reset']
));

//Création du tableau des rangs.
$array_ranks = array(-1 => $LANG['guest_s'], 0 => $LANG['member_s'], 1 => $LANG['modo_s'], 2 => $LANG['admin_s']);

$track = false;
$poll_done = false; //N'execute qu'une fois les actions propres au sondage.
$Cache->Load_file('ranks'); //Récupère les rangs en cache.
$page = retrieve(GET, 'pt', 0); //Redéfinition de la variable $page pour prendre en compte les redirections.
$quote_last_msg = ($page > 1) ? 1 : 0; //On enlève 1 au limite si on est sur une page > 1, afin de récupérer le dernier msg de la page précédente.
$i = 0;	
$j = 0;	
$result = $Sql->Query_while("SELECT msg.id, msg.user_id, msg.timestamp, msg.timestamp_edit, msg.user_id_edit, m.user_groups, p.question, p.answers, p.voter_id, p.votes, p.type, m.login, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_readonly, m.user_ban, m2.login as login_edit, s.user_id AS connect, tr.id AS track, msg.contents
FROM ".PREFIX."forum_msg msg
LEFT JOIN ".PREFIX."forum_poll p ON p.idtopic = '" . $id_get . "'
LEFT JOIN ".PREFIX."member m ON m.user_id = msg.user_id
LEFT JOIN ".PREFIX."member m2 ON m2.user_id = msg.user_id_edit
LEFT JOIN ".PREFIX."forum_track tr ON tr.idtopic = '" . $id_get . "' AND tr.user_id = '" . $Member->Get_attribute('user_id') . "'
LEFT JOIN ".PREFIX."sessions s ON s.user_id = msg.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.user_id != -1
WHERE msg.idtopic = '" . $id_get . "'	
ORDER BY msg.timestamp 
" . $Sql->Sql_limit(($Pagination->First_msg($CONFIG_FORUM['pagination_msg'], 'pt') - $quote_last_msg), ($CONFIG_FORUM['pagination_msg'] + $quote_last_msg)), __LINE__, __FILE__);
while ( $row = $Sql->Sql_fetch_assoc($result) )
{
	$row['user_id'] = (int)$row['user_id'];
	//Invité?
	$is_guest = ($row['user_id'] === -1);
	if( $is_guest )
		$row['level'] = -1;
		
	list($edit, $del, $cut, $warning, $readonly) = array('', '', '', '', '');
	$first_message = ($row['id'] == $topic['first_msg_id']) ? true : false;
	//Gestion du niveau d'autorisation.
	if( $check_group_edit_auth || ($Member->Get_attribute('user_id') === $row['user_id'] && !$is_guest && !$first_message) )
	{
		$valid = ($first_message) ? 'topic' : 'msg';
		$edit = '&nbsp;&nbsp;<a href="post' . transid('.php?new=msg&amp;idm=' . $row['id'] . '&amp;id=' . $topic['idcat'] . '&amp;idt=' . $id_get) . '" title=""><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="' . $LANG['edit'] . '" title="' . $LANG['edit'] . '" /></a>';
		$del = (!$first_message) ? '&nbsp;&nbsp;<script type="text/javascript"><!-- 
		document.write(\'<img style="cursor: pointer;" onClick="del_msg(\\\'' . $row['id'] . '\\\');" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" id="dimg' . $row['id'] . '" />\'); 
		--></script><noscript><a href="action' . transid('.php?del=1&amp;idm=' . $row['id']) . '" title="" onClick="javascript:return Confirm_' . $valid . '();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" id="dimg' . $row['id'] . '" /></a></noscript>'
		: '&nbsp;&nbsp;<a href="action' . transid('.php?del=1&amp;idm=' . $row['id']) . '" title="" onClick="javascript:return Confirm_' . $valid . '();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" /></a>';
		
		//Fonctions réservées à ceux possédants les droits de modérateurs seulement.
		if( $check_group_edit_auth )
		{
			$cut = (!$first_message) ? '&nbsp;&nbsp;<a href="move' . transid('.php?idm=' . $row['id']) . '" title="' . $LANG['cut_topic'] . '" onClick="javascript:return Confirm_cut();"><img src="' . $module_data_path . '/images/cut.png" alt="' . $LANG['cut_topic'] .  '" /></a>' : '';
			$warning = !$is_guest ? '&nbsp;<a href="moderation_forum' . transid('.php?action=warning&amp;id=' . $row['user_id']) . '" title="' . $LANG['warning_management'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/important.png" alt="' . $LANG['warning_management'] .  '" class="valign_middle" /></a>' : ''; 
			$readonly = !$is_guest ? '<a href="moderation_forum' . transid('.php?action=punish&amp;id=' . $row['user_id']) . '" title="' . $LANG['punishment_management'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/readonly.png" alt="' . $LANG['punishment_management'] .  '" class="valign_middle" /></a>' : ''; 
		}
	}
	elseif( $Member->Get_attribute('user_id') === $row['user_id'] && !$is_guest && $first_message ) //Premier msg du topic => suppression du topic non autorisé au membre auteur du message.
		$edit = '&nbsp;&nbsp;<a href="post' . transid('.php?new=msg&amp;idm=' . $row['id'] . '&amp;id=' . $topic['idcat'] . '&amp;idt=' . $id_get) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="' . $LANG['edit'] . '" title="'. $LANG['edit'] . '" /></a>';
			
	//Gestion des sondages => executé une seule fois.
	if( !empty($row['question']) && $poll_done === false )
	{
		$Template->Assign_vars(array(				
			'C_POLL_EXIST' => true,
			'QUESTION' => $row['question'],				
			'U_POLL_RESULT' => transid('.php?id=' . $id_get . '&amp;r=1'),
			'U_POLL_ACTION' => transid('.php?id=' . $id_get),
			'L_POLL' => $LANG['poll'], 
			'L_VOTE' => $LANG['poll_vote'],
			'L_RESULT' => $LANG['poll_result']
		));
		
		$array_voter = explode('|', $row['voter_id']);			
		if( in_array($Member->Get_attribute('user_id'), $array_voter) || !empty($_GET['r']) || $Member->Get_attribute('user_id') === -1 ) //Déjà voté.
		{
			$array_answer = explode('|', $row['answers']);
			$array_vote = explode('|', $row['votes']);
			
			$sum_vote = array_sum($array_vote);	
			$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Empêche la division par 0.

			$array_poll = array_combine($array_answer, $array_vote);
			foreach($array_poll as $answer => $nbrvote)
			{
				$Template->Assign_block_vars('poll_result', array(
					'ANSWERS' => $answer, 
					'NBRVOTE' => $nbrvote,
					'WIDTH' => number_round(($nbrvote * 100 / $sum_vote), 1) * 4, //x 4 Pour agrandir la barre de vote.					
					'PERCENT' => number_round(($nbrvote * 100 / $sum_vote), 1)
				));
			}
		}
		else //Affichage des formulaires (radio/checkbox)  pour voter.
		{
			$Template->Assign_vars(array(
				'C_POLL_QUESTION' => true
			));
			
			$z = 0;
			$array_answer = explode('|', $row['answers']);
			if( $row['type'] == 0 )
			{
				foreach($array_answer as $answer)
				{						
					$Template->Assign_block_vars('poll_radio', array(
						'NAME' => $z,
						'TYPE' => 'radio',
						'ANSWERS' => $answer
					));
					$z++;
				}
			}	
			elseif( $row['type'] == 1 ) 
			{
				foreach($array_answer as $answer)
				{						
					$Template->Assign_block_vars('poll_checkbox', array(
						'NAME' => $z,
						'TYPE' => 'checkbox',
						'ANSWERS' => $answer
					));
					$z++;	
				}
			}
		}
		$poll_done = true;	
	}
	
	//Rang de l'utilisateur.			
	$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
	$user_group = $user_rank;
	$user_rank_icon = '';
	if( $row['level'] === '2' ) //Rang spécial (admins).  
	{
		$user_rank = $_array_rank[-2][0];
		$user_group = $user_rank;
		$user_rank_icon = $_array_rank[-2][1];
	}
	elseif( $row['level'] === '1' ) //Rang spécial (modos).  
	{
		$user_rank = $_array_rank[-1][0];
		$user_group = $user_rank;
		$user_rank_icon = $_array_rank[-1][1];
	}
	else
	{
		foreach($_array_rank as $msg => $ranks_info)
		{
			if( $msg >= 0 && $msg <= $row['user_msg'] )
			{ 
				$user_rank = $ranks_info[0];
				$user_rank_icon = $ranks_info[1];
				break;
			}
		}
	}

	//Image associée au rang.
	$user_assoc_img = !empty($user_rank_icon) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';
				
	//Affichage des groupes du membre.		
	if( !empty($row['user_groups']) && $_array_groups_auth ) 
	{	
		$user_groups = '';
		$array_user_groups = explode('|', $row['user_groups']);
		foreach($_array_groups_auth as $idgroup => $array_group_info)
		{
			if( is_numeric(array_search($idgroup, $array_user_groups)) )
				$user_groups .= !empty($array_group_info['img']) ? '<img src="../images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'] . '<br />';
		}
	}
	else
		$user_groups = $LANG['group'] . ': ' . $user_group;

	//Avatar			
	if( empty($row['user_avatar']) ) 
		$user_avatar = ($CONFIG_MEMBER['activ_avatar'] == '1' && !empty($CONFIG_MEMBER['avatar_url'])) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/' .  $CONFIG_MEMBER['avatar_url'] . '" alt="" />' : '';
	else
		$user_avatar = '<img src="' . $row['user_avatar'] . '" alt=""	/>';
		
	//Affichage du sexe et du statut (connecté/déconnecté).	
	if( $row['user_sex'] == 1 )	
		$user_sex = $LANG['sex'] . ': <img src="../templates/' . $CONFIG['theme'] . '/images/man.png" alt="" /><br />';	
	elseif( $row['user_sex'] == 2 ) 
		$user_sex = $LANG['sex'] . ': <img src="../templates/' . $CONFIG['theme'] . '/images/woman.png" alt="" /><br />';
	else $user_sex = '';
			
	//Localisation.
	if( !empty($row['user_local']) ) 
		$user_local = $LANG['place'] . ': ' . (strlen($row['user_local']) > 15 ? substr_html($row['user_local'], 0, 15) . '...<br />' : $row['user_local'] . '<br />');	
	else 
		$user_local = '';

	//Reprise du dernier message de la page précédente.
	$row['contents'] = ($quote_last_msg == 1 && $i == 0) ? '<span class="text_strong">' . $LANG['forum_quote_last_msg'] . '</span><br /><br />' . $row['contents'] : $row['contents'];
	$i++;
	
	//Ajout du marqueur d'édition si activé.
	$edit_mark = ($row['timestamp_edit'] > 0 && $CONFIG_FORUM['edit_mark'] == '1') ? '<br /><br /><br /><br /><span style="padding: 10px;font-size:10px;font-style:italic;">' . $LANG['edit_by'] . ' ' . (!empty($row['login_edit']) ? '<a class="small_link" href="../member/member' . transid('.php?id=' . $row['user_id_edit'], '-' . $row['user_id_edit'] . '.php') . '">' . $row['login_edit'] . '</a>' : '<em>' . $LANG['guest'] . '</em>') . ' ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['timestamp_edit']) . '</span>' : '';
	
	//Affichage du nombre de message.
	if( $row['user_msg'] >= 1 )
		$user_msg = '<a href="../forum/membermsg' . transid('.php?id=' . $row['user_id'], '') . '" class="small_link">' . $LANG['message_s'] . '</a>: ' . $row['user_msg'];
	else		
		$user_msg = (!$is_guest) ? '<a href="../forum/membermsg' . transid('.php?id=' . $row['user_id'], '') . '" class="small_link">' . $LANG['message'] . '</a>: 0' : $LANG['message'] . ': 0';		
	
	$Template->Assign_block_vars('msg', array(
		'CONTENTS' => second_parse($row['contents']),
		'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format', $row['timestamp']),
		'ID' => $row['id'],
		'CLASS_COLOR' => ($j%2 == 0) ? '' : 2,
		'USER_ONLINE' => '<img src="../templates/' . $CONFIG['theme'] . '/images/' . ((!empty($row['connect']) && !$is_guest) ? 'online' : 'offline') . '.png" alt="" class="valign_middle" />',
		'USER_PSEUDO' => !empty($row['login']) ? '<a class="msg_link_pseudo" href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . wordwrap_html($row['login'], 13) . '</a>' : '<em>' . $LANG['guest'] . '</em>',			
		'USER_RANK' => ($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned'],
		'USER_IMG_ASSOC' => $user_assoc_img,
		'USER_AVATAR' => $user_avatar,			
		'USER_GROUP' => $user_groups,
		'USER_DATE' => (!$is_guest) ? $LANG['registered_on'] . ': ' . gmdate_format('date_format_short', $row['registered']) : '',
		'USER_SEX' => $user_sex,
		'USER_MSG' => (!$is_guest) ? $user_msg : '',
		'USER_LOCAL' => $user_local,
		'USER_MAIL' => ( !empty($row['user_mail']) && ($row['user_show_mail'] == '1' ) ) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/email.png" alt="' . $row['user_mail']  . '" title="' . $row['user_mail']  . '" /></a>' : '',			
		'USER_MSN' => (!empty($row['user_msn'])) ? '<a href="mailto:' . $row['user_msn'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/msn.png" alt="' . $row['user_msn']  . '" title="' . $row['user_msn']  . '" /></a>' : '',
		'USER_YAHOO' => (!empty($row['user_yahoo'])) ? '<a href="mailto:' . $row['user_yahoo'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/yahoo.png" alt="' . $row['user_yahoo']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
		'USER_EDIT' => $edit_mark,
		'USER_SIGN' => (!empty($row['user_sign'])) ? '____________________<br />' . $row['user_sign'] : '',
		'USER_WEB' => (!empty($row['user_web'])) ? '<a href="' . $row['user_web'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="' . $row['user_web']  . '" title="' . $row['user_web']  . '" /></a>' : '',
		'WARNING' => !empty($warning) ? $row['user_warning'] . '%' . $warning : '',
		'PUNISHMENT' => $readonly,
		'EDIT' => $edit,
		'CUT' => $cut,
		'DEL' => $del,'U_VARS_ANCRE' => transid('.php?id=' . $id_get . (!empty($page) ? '&amp;pt=' . $page : ''), '-' . $id_get . (!empty($page) ? '-' . $page : '') . $rewrited_title . '.php'),
		'U_VARS_QUOTE' => transid('.php?quote=' . $row['id'] . '&amp;id=' . $id_get . (!empty($page) ? '&amp;pt=' . $page : ''), '-' . $id_get . (!empty($page) ? '-' . $page : '-0') . '-0-' . $row['id'] . $rewrited_title . '.php'),
		'USER_PM' => !$is_guest ? '<a href="../member/pm' . transid('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/pm.png" alt="pm" /></a>' : '',
	));
	
	//Marqueur de suivis du sujet.
	if( !empty($row['track']) ) 
		$track = true;
	$j++;
}
$Sql->Close($result);

//Listes les utilisateurs en lignes.
list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
$result = $Sql->Query_while("SELECT s.user_id, s.level, m.login 
FROM ".PREFIX."sessions s 
LEFT JOIN ".PREFIX."member m ON m.user_id = s.user_id 
WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.session_script = '/forum/topic.php' AND s.session_script_get LIKE '%id=" . $id_get . "%'
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
	'SELECT_CAT' => forum_list_cat(), //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
	'U_SUSCRIBE' => ($track === false) ? transid('.php?t=' . $id_get) : transid('.php?ut=' . $id_get),
	'IS_FAVORITE' => $track ? 'true' : 'false',
	'IS_CHANGE' => $topic['display_msg'] ? 'true' : 'false',
	'U_ALERT' => transid('.php?id=' . $id_get),
	'L_SUSCRIBE_DEFAULT' => ($track === false) ? $LANG['track_topic'] : $LANG['untrack_topic'],
	'L_SUSCRIBE' => $LANG['track_topic'],
	'L_UNSUSCRIBE' => $LANG['untrack_topic'],
	'L_ALERT' => $LANG['alert_topic'],
	'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online']),
));	
		
//Récupération du message quoté.
$contents = '';
if( !empty($quote_get) )
{	
	$quote_msg = $Sql->Query_array('forum_msg', 'user_id', 'contents', "WHERE id = '" . $quote_get . "'", __LINE__, __FILE__);
	$pseudo = $Sql->Query("SELECT login FROM ".PREFIX."member WHERE user_id = '" . $quote_msg['user_id'] . "'", __LINE__, __FILE__);	
	$contents = '[quote=' . $pseudo . ']' . unparse($quote_msg['contents']) . '[/quote]';
}

//Formulaire de réponse, non présent si verrouillé.
if( $topic['status'] == '0' && !$check_group_edit_auth )
{
	$Template->Assign_vars(array(
		'C_ERROR_AUTH_WRITE' => true,
		'L_ERROR_AUTH_WRITE' => $LANG['e_topic_lock_forum']
	));
}	
elseif( !$Member->Check_auth($CAT_FORUM[$topic['idcat']]['auth'], WRITE_CAT_FORUM) ) //On vérifie si l'utilisateur a les droits d'écritures.
{
	$Template->Assign_vars(array(
		'C_ERROR_AUTH_WRITE' => true,
		'L_ERROR_AUTH_WRITE' => $LANG['e_cat_write']
	));
}
else
{
	$img_favorite_display = $track ? 'unfavorite.png' : 'favorite.png';
	$Template->Assign_vars(array(
		'C_AUTH_POST' => true,
		'CONTENTS' => $contents,
		'KERNEL_EDITOR' => display_editor(),
		'ICON_FAVORITE' => '<img src="' . $module_data_path . '/images/' . $img_favorite_display . '" alt="" class="valign_middle" />',
		'ICON_FAVORITE2' => '<img src="' . $module_data_path . '/images/' . $img_favorite_display . '" alt="" class="valign_middle" id="forum_favorite_img" />',
		'U_FORUM_ACTION_POST' => transid('.php?idt=' . $id_get . '&amp;id=' . $topic['idcat'] . '&amp;new=n_msg')
	));

	//Affichage du lien pour changer le display_msg du topic et autorisation d'édition du statut.
	if( $CONFIG_FORUM['activ_display_msg'] == 1 && ($check_group_edit_auth || $Member->Get_attribute('user_id') == $topic['user_id']) )
	{
		$img_msg_display = $topic['display_msg'] ? 'msg_display2.png' : 'msg_display.png';
		$Template->Assign_vars(array(
			'C_DISPLAY_MSG' => true,
			'ICON_DISPLAY_MSG' => $CONFIG_FORUM['icon_activ_display_msg'] ? '<img src="' . $module_data_path . '/images/' . $img_msg_display . '" alt="" class="valign_middle"  />' : '',
			'ICON_DISPLAY_MSG2' => $CONFIG_FORUM['icon_activ_display_msg'] ? '<img src="' . $module_data_path . '/images/' . $img_msg_display . '" alt="" class="valign_middle" id="forum_change_img" />' : '',
			'L_EXPLAIN_DISPLAY_MSG_DEFAULT' => $topic['display_msg'] ? $CONFIG_FORUM['explain_display_msg_bis'] : $CONFIG_FORUM['explain_display_msg'],
			'L_EXPLAIN_DISPLAY_MSG' => $CONFIG_FORUM['explain_display_msg'],
			'L_EXPLAIN_DISPLAY_MSG_BIS' => $CONFIG_FORUM['explain_display_msg_bis'],
			'U_ACTION_MSG_DISPLAY' => transid('.php?msg_d=1&amp;id=' . $id_get)
		));
	}
}

$Template->Pparse('forum_topic');

include('../kernel/footer.php');

?>
