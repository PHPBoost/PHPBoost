<?php
/*##################################################
 *                                topic.php
 *                            -------------------
 *   begin                : October 26, 2005
 *   copyright            : (C) 2005 Viarre Régis / Sautel Benoît
 *   email                : mickaelhemri@gmail.com / ben.popeye@gmail.com
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

$page = AppContext::get_request()->get_getint('pt', 1);
$id_get = AppContext::get_request()->get_getint('id', 0);
$quote_get = AppContext::get_request()->get_getint('quote', 0);	

//On va chercher les infos sur le topic	
$topic = !empty($id_get) ? $Sql->query_array(PREFIX . 'forum_topics', 'id', 'user_id', 'idcat', 'title', 'subtitle', 'nbr_msg', 'last_msg_id', 'first_msg_id', 'last_timestamp', 'status', 'display_msg', "WHERE id = '" . $id_get . "'", __LINE__, __FILE__) : '';
//Existance du topic.
if (empty($topic['id']))
{
	$controller = PHPBoostErrors::unexisting_category();
    DispatchManager::redirect($controller);
}
//Existance de la catégorie.
if (!isset($CAT_FORUM[$topic['idcat']]) || $CAT_FORUM[$topic['idcat']]['aprob'] == 0 || $CAT_FORUM[$topic['idcat']]['level'] == 0)
{
	$controller = PHPBoostErrors::unexisting_category();
    DispatchManager::redirect($controller);
}

//Récupération de la barre d'arborescence.
$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php' . SID);
foreach ($CAT_FORUM as $idcat => $array_info_cat)
{
	if ($CAT_FORUM[$topic['idcat']]['id_left'] > $array_info_cat['id_left'] && $CAT_FORUM[$topic['idcat']]['id_right'] < $array_info_cat['id_right'] && $array_info_cat['level'] < $CAT_FORUM[$topic['idcat']]['level'])
		$Bread_crumb->add($array_info_cat['name'], ($array_info_cat['level'] == 0) ? url('index.php?id=' . $idcat, 'cat-' . $idcat . '+' . Url::encode_rewrite($array_info_cat['name']) . '.php') : 'forum' . url('.php?id=' . $idcat, '-' . $idcat . '+' . Url::encode_rewrite($array_info_cat['name']) . '.php'));
}
if (!empty($CAT_FORUM[$topic['idcat']]['name'])) //Nom de la catégorie courante.
	$Bread_crumb->add($CAT_FORUM[$topic['idcat']]['name'], 'forum' . url('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '+' . Url::encode_rewrite($CAT_FORUM[$topic['idcat']]['name']) . '.php'));
$Bread_crumb->add($topic['title'], '');

define('TITLE', $LANG['title_topic'] . ' - ' . addslashes($topic['title']));
require_once('../kernel/header.php'); 

$rewrited_cat_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($CAT_FORUM[$topic['idcat']]['name']) : ''; //On encode l'url pour un éventuel rewriting.
$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($topic['title']) : ''; //On encode l'url pour un éventuel rewriting.

//Redirection changement de catégorie.
if (!empty($_POST['change_cat']))
	AppContext::get_response()->redirect('/forum/forum' . url('.php?id=' . $_POST['change_cat'], '-' . $_POST['change_cat'] . $rewrited_cat_title . '.php', '&'));
	
//Autorisation en lecture.
if (!$User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], READ_CAT_FORUM) || !empty($CAT_FORUM[$topic['idcat']]['url']))
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$Template->set_filenames(array(
	'forum_topic'=> 'forum/forum_topic.tpl',
	'forum_top'=> 'forum/forum_top.tpl',
	'forum_bottom'=> 'forum/forum_bottom.tpl'
));

$TmpTemplate = new FileTemplate('forum/forum_generic_results.tpl');
$module_data_path = $TmpTemplate->get_pictures_data_path();

//Si l'utilisateur a le droit de déplacer le topic, ou le verrouiller.	
$check_group_edit_auth = $User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM);
if ($check_group_edit_auth)
{
	$Template->put_all(array(
		'C_FORUM_MODERATOR' => true,
		'C_FORUM_LOCK_TOPIC' => ($topic['status'] == '1') ? true : false,
		'U_TOPIC_LOCK' => url('.php?id=' . $id_get . '&amp;lock=true&amp;token=' . $Session->get_token()),
		'U_TOPIC_UNLOCK' => url('.php?id=' . $id_get . '&amp;lock=false&amp;token=' . $Session->get_token()),
		'U_TOPIC_MOVE' => url('.php?id=' . $id_get),
		'L_TOPIC_LOCK' => ($topic['status'] == '1') ? $LANG['forum_lock'] : $LANG['forum_unlock'],
		'L_TOPIC_MOVE' => $LANG['forum_move'],	
		'L_ALERT_DELETE_TOPIC' => $LANG['alert_delete_topic'],
		'L_ALERT_LOCK_TOPIC' => $LANG['alert_lock_topic'],
		'L_ALERT_UNLOCK_TOPIC' => $LANG['alert_unlock_topic'],
		'L_ALERT_MOVE_TOPIC' => $LANG['alert_move_topic'],
		'L_ALERT_CUT_TOPIC' => $LANG['alert_cut_topic']
	));
}
else
{
	$Template->put_all(array(
		'C_FORUM_MODERATOR' => false
	));
}

//Message(s) dans le topic non lu ( non prise en compte des topics trop vieux (x semaine) ou déjà lus).
mark_topic_as_read($id_get, $topic['last_msg_id'], $topic['last_timestamp']);
	
//Gestion de la page si redirection vers le dernier message lu.
$idm = AppContext::get_request()->get_getint('idm', 0);
if (!empty($idm))
{
	//Calcul de la page sur laquelle se situe le message.
	$nbr_msg_before = $Sql->query("SELECT COUNT(*) as nbr_msg_before FROM " . PREFIX . "forum_msg WHERE idtopic = " . $id_get . " AND id < '" . $idm . "'", __LINE__, __FILE__); //Nombre de message avant le message de destination.
	
	//Dernier message de la page? Redirection vers la page suivante pour prendre en compte la reprise du message précédent.
	if (is_int(($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg'])) 
	{	
		//On redirige vers la page suivante, seulement si ce n'est pas la dernière.
		if ($topic['nbr_msg'] != ($nbr_msg_before + 1))
			$nbr_msg_before++;
	}
	
	$_GET['pt'] = ceil(($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg']); //Modification de la page affichée.
}	
	
//On crée une pagination si le nombre de msg est trop important.
 
$Pagination = new DeprecatedPagination();	

//Affichage de l'arborescence des catégories.
$i = 0;
$forum_cats = '';	
$Bread_crumb->remove_last();
foreach ($Bread_crumb->get_links() as $key => $array)
{
	if ($i == 2)
		$forum_cats .= '<a href="' . $array[1] . '">' . $array[0] . '</a>';
	elseif ($i > 2)		
		$forum_cats .= ' &raquo; <a href="' . $array[1] . '">' . $array[0] . '</a>';
	$i++;
}

$Template->put_all(array(
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
	'SID' => SID,		
	'MODULE_DATA_PATH' => $module_data_path,
	'DESC' => !empty($topic['subtitle']) ? $topic['subtitle'] : '',
	'PAGINATION' => $Pagination->display('topic' . url('.php?id=' . $id_get . '&amp;pt=%d', '-' . $id_get . '-%d' . $rewrited_title . '.php'), $topic['nbr_msg'], 'pt', $CONFIG_FORUM['pagination_msg'], 3),
	'THEME' => get_utheme(),
	'LANG' => get_ulang(),
	'USER_ID' => $topic['user_id'],
	'ID' => $topic['idcat'],
	'IDTOPIC' => $id_get,
	'PAGE' => $page,
	'TITLE_T' => ucfirst($topic['title']),
	'DISPLAY_MSG' => (($CONFIG_FORUM['activ_display_msg'] && $topic['display_msg']) ? $CONFIG_FORUM['display_msg'] . ' ' : '') ,
	'U_MSG_SET_VIEW' => '<a class="small_link" href="../forum/action' . url('.php?read=1&amp;f=' . $topic['idcat'], '') . '" title="' . $LANG['mark_as_read'] . '" onclick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
	'U_CHANGE_CAT'=> 'topic' . url('.php?id=' . $id_get . '&amp;token=' . $Session->get_token(), '-' . $id_get . $rewrited_cat_title . '.php?token=' . $Session->get_token()),
	'U_ONCHANGE' => url(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php"),		
	'U_ONCHANGE_CAT' => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),		
	'U_FORUM_CAT' => !empty($forum_cats) ? $forum_cats . ' &raquo;' : '',
	'U_TITLE_T' => 'topic' . url('.php?id=' . $id_get, '-' . $id_get . $rewrited_title . '.php'),
	'L_REQUIRE_MESSAGE' => $LANG['require_text'],
	'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
	'L_GUEST' => $LANG['guest'],
	'L_DELETE' => $LANG['delete'],
	'L_EDIT' => $LANG['edit'],
	'L_CUT_TOPIC' => $LANG['cut_topic'],
	'L_EDIT_BY' => $LANG['edit_by'],
	'L_PUNISHMENT_MANAGEMENT' => $LANG['punishment_management'],
	'L_WARNING_MANAGEMENT' => $LANG['warning_management'],
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_QUOTE' => $LANG['quote'],
	'L_ON' => $LANG['on'],
	'L_RESPOND' => $LANG['respond'],
	'L_SUBMIT' => $LANG['submit'],
	'L_PREVIEW' => $LANG['preview'],
	'L_RESET' => $LANG['reset']
));

//Création du tableau des rangs.
$array_ranks = array(-1 => $LANG['guest_s'], 0 => $LANG['member_s'], 1 => $LANG['modo_s'], 2 => $LANG['admin_s']);

list($track, $track_pm, $track_mail, $poll_done) = array(false, false, false, false);
$ranks_cache = RanksCache::load()->get_ranks(); //Récupère les rangs en cache.
$page = AppContext::get_request()->get_getint('pt', 0); //Redéfinition de la variable $page pour prendre en compte les redirections.
$quote_last_msg = ($page > 1) ? 1 : 0; //On enlève 1 au limite si on est sur une page > 1, afin de récupérer le dernier msg de la page précédente.
$i = 0;	
$j = 0;	
$result = $Sql->query_while("SELECT msg.id, msg.timestamp, msg.timestamp_edit, msg.user_id_edit, m.user_id, m.user_groups, p.question, p.answers, p.voter_id, p.votes, p.type, m.login, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_readonly, m.user_ban, m2.login as login_edit, s.user_id AS connect, tr.id AS trackid, tr.pm as trackpm, tr.track AS track, tr.mail AS trackmail, msg.contents
FROM " . PREFIX . "forum_msg msg
LEFT JOIN " . PREFIX . "forum_poll p ON p.idtopic = '" . $id_get . "'
LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = msg.user_id
LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = msg.user_id_edit
LEFT JOIN " . PREFIX . "forum_track tr ON tr.idtopic = '" . $id_get . "' AND tr.user_id = '" . $User->get_attribute('user_id') . "'
LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "' AND s.user_id != -1
WHERE msg.idtopic = '" . $id_get . "'	
ORDER BY msg.timestamp 
" . $Sql->limit(($Pagination->get_first_msg($CONFIG_FORUM['pagination_msg'], 'pt') - $quote_last_msg), ($CONFIG_FORUM['pagination_msg'] + $quote_last_msg)), __LINE__, __FILE__);
while ( $row = $Sql->fetch_assoc($result) )
{
	//Invité?
	$is_guest = empty($row['user_id']);
	$first_message = ($row['id'] == $topic['first_msg_id']) ? true : false;

	//Gestion du niveau d'autorisation.
	list($edit, $del, $cut, $moderator) = array(false, false, false, false);
	if ($check_group_edit_auth || ($User->get_attribute('user_id') == $row['user_id'] && !$is_guest && !$first_message))
	{
		list($edit, $del) = array(true, true);
		if ($check_group_edit_auth) //Fonctions réservées à ceux possédants les droits de modérateurs seulement.
		{
			$cut = (!$first_message) ? true : false;
			$moderator = (!$is_guest) ? true : false;
		}
	}
	elseif ($User->get_attribute('user_id') == $row['user_id'] && !$is_guest && $first_message) //Premier msg du topic => suppression du topic non autorisé au membre auteur du message.
		$edit = true;
	
	//Gestion des sondages => executé une seule fois.
	if (!empty($row['question']) && $poll_done === false)
	{
		$Template->put_all(array(				
			'C_POLL_EXIST' => true,
			'QUESTION' => $row['question'],				
			'U_POLL_RESULT' => url('.php?id=' . $id_get . '&amp;r=1&amp;pt=' . $page),
			'U_POLL_ACTION' => url('.php?id=' . $id_get . '&amp;p=' . $page . '&amp;token=' . $Session->get_token()),
			'L_POLL' => $LANG['poll'], 
			'L_VOTE' => $LANG['poll_vote'],
			'L_RESULT' => $LANG['poll_result']
		));
		
		$array_voter = explode('|', $row['voter_id']);			
		if (in_array($User->get_attribute('user_id'), $array_voter) || !empty($_GET['r']) || $User->get_attribute('user_id') === -1) //Déjà voté.
		{
			$array_answer = explode('|', $row['answers']);
			$array_vote = explode('|', $row['votes']);
			
			$sum_vote = array_sum($array_vote);	
			$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Empêche la division par 0.

			foreach ($array_answer as $key => $answer)
			{
				$Template->assign_block_vars('poll_result', array(
					'ANSWERS' => $answer, 
					'NBRVOTE' => $array_vote[$key],
					'WIDTH' => NumberHelper::round(($array_vote[$key] * 100 / $sum_vote), 1) * 4, //x 4 Pour agrandir la barre de vote.					
					'PERCENT' => NumberHelper::round(($array_vote[$key] * 100 / $sum_vote), 1)
				));
			}
		}
		else //Affichage des formulaires (radio/checkbox) pour voter.
		{
			$Template->put_all(array(
				'C_POLL_QUESTION' => true
			));
			
			$z = 0;
			$array_answer = explode('|', $row['answers']);
			if ($row['type'] == 0)
			{
				foreach ($array_answer as $answer)
				{						
					$Template->assign_block_vars('poll_radio', array(
						'NAME' => $z,
						'TYPE' => 'radio',
						'ANSWERS' => $answer
					));
					$z++;
				}
			}	
			elseif ($row['type'] == 1) 
			{
				foreach ($array_answer as $answer)
				{						
					$Template->assign_block_vars('poll_checkbox', array(
						'NAME' => 'forumpoll' . $z,
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
	if ($row['level'] === '2') //Rang spécial (admins).  
	{
		$user_rank = $ranks_cache[-2]['name'];
		$user_group = $user_rank;
		$user_rank_icon = $ranks_cache[-2]['icon'];
	}
	elseif ($row['level'] === '1') //Rang spécial (modos).  
	{
		$user_rank = $ranks_cache[-1]['name'];
		$user_group = $user_rank;
		$user_rank_icon = $ranks_cache[-1]['icon'];
	}
	else
	{
		foreach ($ranks_cache as $msg => $ranks_info)
		{
			if ($msg >= 0 && $msg <= $row['user_msg'])
			{ 
				$user_rank = $ranks_info['name'];
				$user_rank_icon = $ranks_info['icon'];
				break;
			}
		}
	}

	//Image associée au rang.
	$user_assoc_img = !empty($user_rank_icon) ? '<img src="../templates/' . get_utheme() . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';
	
	//Affichage des groupes du membre.		
	if (!empty($row['user_groups'])) 
	{	
		$user_groups = '';
		$array_user_groups = explode('|', $row['user_groups']);
		foreach (GroupsService::get_groups() as $idgroup => $array_group_info)
		{
			if (is_numeric(array_search($idgroup, $array_user_groups)))
				$user_groups .= !empty($array_group_info['img']) ? '<img src="../images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'] . '<br />';
		}
	}
	else
		$user_groups = $LANG['group'] . ': ' . $user_group;

	$user_accounts_config = UserAccountsConfig::load();
	
	//Avatar			
	if (empty($row['user_avatar'])) 
		$user_avatar = ($user_accounts_config->is_default_avatar_enabled() == '1') ? '<img src="../templates/' . get_utheme() . '/images/' .  $user_accounts_config->get_default_avatar_name() . '" alt="" />' : '';
	else
		$user_avatar = '<img src="' . $row['user_avatar'] . '" alt=""	/>';
		
	//Affichage du sexe et du statut (connecté/déconnecté).	
	if ($row['user_sex'] == 1)	
		$user_sex = $LANG['sex'] . ': <img src="../templates/' . get_utheme() . '/images/man.png" alt="" /><br />';	
	elseif ($row['user_sex'] == 2) 
		$user_sex = $LANG['sex'] . ': <img src="../templates/' . get_utheme() . '/images/woman.png" alt="" /><br />';
	else $user_sex = '';
			
	//Localisation.
	if (!empty($row['user_local'])) 
		$user_local = $LANG['place'] . ': ' . (strlen($row['user_local']) > 15 ? TextHelper::substr_html($row['user_local'], 0, 15) . '...<br />' : $row['user_local'] . '<br />');	
	else 
		$user_local = '';

	//Affichage du nombre de message.
	if ($row['user_msg'] >= 1)
		$user_msg = '<a href="../forum/membermsg' . url('.php?id=' . $row['user_id'], '') . '" class="small_link">' . $LANG['message_s'] . '</a>: ' . $row['user_msg'];
	else		
		$user_msg = (!$is_guest) ? '<a href="../forum/membermsg' . url('.php?id=' . $row['user_id'], '') . '" class="small_link">' . $LANG['message'] . '</a>: 0' : $LANG['message'] . ': 0';		
	
	$Template->assign_block_vars('msg', array(
		'ID' => $row['id'],
		'CLASS_COLOR' => ($j%2 == 0) ? '' : 2,
		'FORUM_ONLINE_STATUT_USER' => !empty($row['connect']) ? 'online' : 'offline',
		'FORUM_USER_LOGIN' => TextHelper::wordwrap_html($row['login'], 13),			
		'FORUM_MSG_DATE' => $LANG['on'] . ' ' . gmdate_format('date_format', $row['timestamp']),
		'FORUM_MSG_CONTENTS' => FormatingHelper::second_parse($row['contents']),
		'FORUM_USER_EDITOR_LOGIN' => $row['login_edit'],
		'FORUM_USER_EDITOR_DATE' => gmdate_format('date_format', $row['timestamp_edit']),
		'USER_RANK' => ($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned'],
		'USER_IMG_ASSOC' => $user_assoc_img,
		'USER_AVATAR' => $user_avatar,			
		'USER_GROUP' => $user_groups,
		'USER_DATE' => (!$is_guest) ? $LANG['registered_on'] . ': ' . gmdate_format('date_format_short', $row['registered']) : '',
		'USER_SEX' => $user_sex,
		'USER_MSG' => (!$is_guest) ? $user_msg : '',
		'USER_LOCAL' => $user_local,
		'USER_MAIL' => ( !empty($row['user_mail']) && ($row['user_show_mail'] == '1' ) ) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail']  . '" title="' . $row['user_mail']  . '" /></a>' : '',			
		'USER_MSN' => (!empty($row['user_msn'])) ? '<a href="mailto:' . $row['user_msn'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/msn.png" alt="' . $row['user_msn']  . '" title="' . $row['user_msn']  . '" /></a>' : '',
		'USER_YAHOO' => (!empty($row['user_yahoo'])) ? '<a href="mailto:' . $row['user_yahoo'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/yahoo.png" alt="' . $row['user_yahoo']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
		'USER_SIGN' => (!empty($row['user_sign'])) ? '____________________<br />' . FormatingHelper::second_parse($row['user_sign']) : '',
		'USER_WEB' => (!empty($row['user_web'])) ? '<a href="' . $row['user_web'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="' . $row['user_web']  . '" title="' . $row['user_web']  . '" /></a>' : '',
		'USER_WARNING' => $row['user_warning'],
		'L_FORUM_QUOTE_LAST_MSG' => ($quote_last_msg == 1 && $i == 0) ? $LANG['forum_quote_last_msg'] : '', //Reprise du dernier message de la page précédente.
		'C_FORUM_USER_LOGIN' => !empty($row['login']) ? true : false,
		'C_FORUM_MSG_EDIT' => $edit,
		'C_FORUM_MSG_DEL' => $del,
		'C_FORUM_MSG_DEL_MSG' => (!$first_message) ? true : false,
		'C_FORUM_MSG_CUT' => $cut,
		'C_FORUM_USER_EDITOR' => ($row['timestamp_edit'] > 0 && $CONFIG_FORUM['edit_mark'] == '1'), //Ajout du marqueur d'édition si activé.
		'C_FORUM_USER_EDITOR_LOGIN' => !empty($row['login_edit']) ? true : false,
		'C_FORUM_MODERATOR' => $moderator,
		'U_FORUM_USER_LOGIN' => url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),			
		'U_FORUM_MSG_EDIT' => url('.php?new=msg&amp;idm=' . $row['id'] . '&amp;id=' . $topic['idcat'] . '&amp;idt=' . $id_get),
		'U_FORUM_USER_EDITOR_LOGIN' => url('.php?id=' . $row['user_id_edit'], '-' . $row['user_id_edit'] . '.php'),
		'U_FORUM_MSG_DEL' => url('.php?del=1&amp;idm=' . $row['id'] . '&amp;token=' . $Session->get_token()),
		'U_FORUM_WARNING' => url('.php?action=warning&amp;id=' . $row['user_id']),
		'U_FORUM_PUNISHEMENT' => url('.php?action=punish&amp;id=' . $row['user_id']),
		'U_FORUM_MSG_CUT' => url('.php?idm=' . $row['id']),
		'U_VARS_ANCRE' => url('.php?id=' . $id_get . (!empty($page) ? '&amp;pt=' . $page : ''), '-' . $id_get . (!empty($page) ? '-' . $page : '') . $rewrited_title . '.php'),
		'U_VARS_QUOTE' => url('.php?quote=' . $row['id'] . '&amp;id=' . $id_get . (!empty($page) ? '&amp;pt=' . $page : ''), '-' . $id_get . (!empty($page) ? '-' . $page : '-0') . '-0-' . $row['id'] . $rewrited_title . '.php'),
		'USER_PM' => !$is_guest ? '<a href="../member/pm' . url('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/pm.png" alt="pm" /></a>' : '',
	));
	
	//Marqueur de suivis du sujet.
	if (!empty($row['trackid'])) 
	{	
		$track = ($row['track']) ? true : false;
		$track_pm = ($row['trackpm']) ? true : false;
		$track_mail = ($row['trackmail']) ? true : false;
	}
	$j++;
	$i++;
}
$Sql->query_close($result);

//Listes les utilisateurs en lignes.
list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.session_script = '/forum/topic.php' AND s.session_script_get LIKE '%id=" . $id_get . "%'");

$Template->put_all(array(
	'TOTAL_ONLINE' => $total_online,
	'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'SELECT_CAT' => forum_list_cat($topic['idcat'], $CAT_FORUM[$topic['idcat']]['level']), //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
	'U_SUSCRIBE' => ($track === false) ? url('.php?t=' . $id_get) : url('.php?ut=' . $id_get),
	'U_SUSCRIBE_PM' => url('.php?token=' . $Session->get_token() . '&amp;' . ($track_pm ? 'utp' : 'tp') . '=' . $id_get),
	'U_SUSCRIBE_MAIL' => url('.php?token=' . $Session->get_token() . '&amp;' . ($track_mail ? 'utm' : 'tm') . '=' . $id_get),
	'IS_TRACK' => $track ? 'true' : 'false',
	'IS_TRACK_PM' => $track_pm ? 'true' : 'false',
	'IS_TRACK_MAIL' => $track_mail ? 'true' : 'false',
	'IS_CHANGE' => $topic['display_msg'] ? 'true' : 'false',
	'U_ALERT' => url('.php?id=' . $id_get),
	'L_TRACK_DEFAULT' => ($track === false) ? $LANG['track_topic'] : $LANG['untrack_topic'],
	'L_SUSCRIBE_DEFAULT' => ($track_mail === false) ? $LANG['track_topic_mail'] : $LANG['untrack_topic_mail'],
	'L_SUSCRIBE_PM_DEFAULT' => ($track_pm === false) ? $LANG['track_topic_pm'] : $LANG['untrack_topic_pm'],
	'L_TRACK' => $LANG['track_topic'],
	'L_UNTRACK' => $LANG['untrack_topic'],
	'L_SUSCRIBE_PM' => $LANG['track_topic_pm'],
	'L_UNSUSCRIBE_PM' => $LANG['untrack_topic_pm'],
	'L_SUSCRIBE' => $LANG['track_topic_mail'],
	'L_UNSUSCRIBE' => $LANG['untrack_topic_mail'],
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
if (!empty($quote_get))
{	
	$quote_msg = $Sql->query_array(PREFIX . 'forum_msg', 'user_id', 'contents', "WHERE id = '" . $quote_get . "'", __LINE__, __FILE__);
	$pseudo = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $quote_msg['user_id'] . "'", __LINE__, __FILE__);	
	$contents = '[quote=' . $pseudo . ']' . FormatingHelper::unparse($quote_msg['contents']) . '[/quote]';
}

//Formulaire de réponse, non présent si verrouillé.
if ($topic['status'] == '0' && !$check_group_edit_auth)
{
	$Template->put_all(array(
		'C_ERROR_AUTH_WRITE' => true,
		'L_ERROR_AUTH_WRITE' => $LANG['e_topic_lock_forum']
	));
}	
elseif (!$User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], WRITE_CAT_FORUM)) //On vérifie si l'utilisateur a les droits d'écritures.
{
	$Template->put_all(array(
		'C_ERROR_AUTH_WRITE' => true,
		'L_ERROR_AUTH_WRITE' => $LANG['e_cat_write']
	));
}
else
{
	$img_track_display = $track ? 'untrack_mini.png' : 'track_mini.png';
	$img_track_pm_display = $track_pm ? 'untrack_pm_mini.png' : 'track_pm_mini.png';
	$img_track_mail_display = $track_mail ? 'untrack_mail_mini.png' : 'track_mail_mini.png';
	$Template->put_all(array(
		'C_AUTH_POST' => true,
		'CONTENTS' => $contents,
		'KERNEL_EDITOR' => display_editor(),
		'ICON_TRACK' => '<img src="' . $module_data_path . '/images/' . $img_track_display . '" alt="" class="valign_middle" />',
		'ICON_TRACK2' => '<img src="' . $module_data_path . '/images/' . $img_track_display . '" alt="" class="valign_middle" id="forum_track_img" />',
		'ICON_SUSCRIBE_PM' => '<img src="' . $module_data_path . '/images/' . $img_track_pm_display . '" alt="" class="valign_middle" />',
		'ICON_SUSCRIBE_PM2' => '<img src="' . $module_data_path . '/images/' . $img_track_pm_display . '" alt="" class="valign_middle" id="forum_track_pm_img" />',
		'ICON_SUSCRIBE' => '<img src="' . $module_data_path . '/images/' . $img_track_mail_display . '" alt="" class="valign_middle" />',
		'ICON_SUSCRIBE2' => '<img src="' . $module_data_path . '/images/' . $img_track_mail_display . '" alt="" class="valign_middle" id="forum_track_mail_img" />',
		'U_FORUM_ACTION_POST' => url('.php?idt=' . $id_get . '&amp;id=' . $topic['idcat'] . '&amp;new=n_msg&amp;token=' . $Session->get_token()),
	));

	//Affichage du lien pour changer le display_msg du topic et autorisation d'édition du statut.
	if ($CONFIG_FORUM['activ_display_msg'] == 1 && ($check_group_edit_auth || $User->get_attribute('user_id') == $topic['user_id']))
	{
		$img_msg_display = $topic['display_msg'] ? 'not_processed_mini.png' : 'processed_mini.png';
		$Template->put_all(array(
			'C_DISPLAY_MSG' => true,
			'ICON_DISPLAY_MSG' => $CONFIG_FORUM['icon_activ_display_msg'] ? '<img src="../templates/' . get_utheme() . '/images/' . $img_msg_display . '" alt="" class="valign_middle"  />' : '',
			'ICON_DISPLAY_MSG2' => $CONFIG_FORUM['icon_activ_display_msg'] ? '<img src="../templates/' . get_utheme() . '/images/' . $img_msg_display . '" alt="" class="valign_middle" id="forum_change_img" />' : '',
			'L_DISPLAY_MSG' => $CONFIG_FORUM['display_msg'],
			'L_EXPLAIN_DISPLAY_MSG_DEFAULT' => $topic['display_msg'] ? $CONFIG_FORUM['explain_display_msg_bis'] : $CONFIG_FORUM['explain_display_msg'],
			'L_EXPLAIN_DISPLAY_MSG' => $CONFIG_FORUM['explain_display_msg'],
			'L_EXPLAIN_DISPLAY_MSG_BIS' => $CONFIG_FORUM['explain_display_msg_bis'],
			'U_ACTION_MSG_DISPLAY' => url('.php?msg_d=1&amp;id=' . $id_get . '&amp;token=' . $Session->get_token())
		));
	}
}

$Template->pparse('forum_topic');

include('../kernel/footer.php');

?>
