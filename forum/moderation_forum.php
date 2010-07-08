<?php
/*##################################################
 *                               moderation_forum.php
 *                            -------------------
 *   begin                : August 8, 2006
 *   copyright            : (C) 2006 Sautel Benoît / Viarre Régis
 *   email                : ben.popeye@phpboost.com / crowkait@phpboost.com
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

$action = retrieve(GET, 'action', '');
$id_get = retrieve(GET, 'id', 0);
$new_status = retrieve(GET, 'new_status', '');
$get_del = retrieve(GET, 'del', '');

$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php' . SID);
if ($action == 'alert')
	$Bread_crumb->add($LANG['alert_management'], url('moderation_forum.php?action=alert'));
elseif ($action == 'users')
	$Bread_crumb->add($LANG['warning_management'], url('moderation_forum.php?action=warning'));
$Bread_crumb->add($LANG['moderation_panel'], '../forum/moderation_forum.php' . SID);

define('TITLE', $LANG['title_forum'] . ' - ' . $LANG['moderation_panel']);
require_once('../kernel/header.php');

//Au moins modérateur sur une catégorie du forum, ou modérateur global.
$check_auth_by_group = false;
if (is_array($CAT_FORUM))
{
	foreach ($CAT_FORUM as $idcat => $value)
	{
		if ($User->check_auth($CAT_FORUM[$idcat]['auth'], EDIT_CAT_FORUM))
		{
			$check_auth_by_group = true;
			break;
		}
	}
}

if (!$User->check_level(MODO_LEVEL) && $check_auth_by_group !== true) //Si il n'est pas modérateur (total ou partiel)
	$Errorh->handler('e_auth', E_USER_REDIRECT);

$Template->set_filenames(array(
	'forum_moderation_panel'=> 'forum/forum_moderation_panel.tpl',
	'forum_top'=> 'forum/forum_top.tpl',
	'forum_bottom'=> 'forum/forum_bottom.tpl'
));
$Template->assign_vars(array(
	'SID' => SID,
	'LANG' => get_ulang(),
	'THEME' => get_utheme(),
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
	'L_SEARCH' => $LANG['search'],
	'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
	'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
	'L_USERS_WARNING' => $LANG['warning_management'],
	'L_ALERT_MANAGEMENT' => $LANG['alert_management'],
));

//Redirection changement de catégorie.
$id_topic_get = retrieve(POST, 'change_cat', '');
if (!empty($id_topic_get))
{
	//On va chercher les infos sur le topic
	$topic = !empty($id_topic_get) ? $Sql->query_array(PREFIX . 'forum_topics', 'idcat', 'title', "WHERE id = '" . $id_topic_get . "'", __LINE__, __FILE__) : '';

	//Informations sur la catégorie du topic, en cache $CAT_FORUM variable globale.
	$CAT_FORUM[$topic['idcat']]['secure'] = '2';
	$Cache->load('forum');

	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_cat_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($CAT_FORUM[$topic['idcat']]['name']) : '';
	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($topic['title']) : '';

	AppContext::get_response()->redirect('/forum/forum' . url('.php?id=' . $id_topic_get, '-' . $id_topic_get . $rewrited_cat_title . '.php', '&'));
}

if ($action == 'alert') //Gestion des alertes
{
	//Changement de statut ou suppression
	if ((!empty($id_get) && ($new_status == '0' || $new_status == '1')) || !empty($get_del))
	{
		//Instanciation de la class du forum.
		$Forumfct = new Forum();

		if (!empty($get_del))
		{
			$hist = false;
			foreach ($_POST as $id_alert => $checked)
			{
				if ($checked = 'on' && is_numeric($id_alert))
					$Forumfct->Del_alert_topic($id_alert);
			}
		}
		else
		{
			if ($new_status == '0') //On le passe en non lu
				$Forumfct->Wait_alert_topic($id_get);
			elseif ($new_status == '1') //On le passe en résolu
				$Forumfct->Solve_alert_topic($id_get);
		}

		if (!empty($get_del))
			$get_id = '';
		else
			$get_id = '&id=' . $id_get;

		AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=alert' . $get_id, '', '&'));
	}

	$Template->assign_vars(array(
		'L_MODERATION_PANEL' => $LANG['moderation_panel'],
		'L_MODERATION_FORUM' => $LANG['moderation_forum'],
		'L_FORUM' => $LANG['forum'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_ALERT' => $LANG['alert_management'],
		'U_MODERATION_FORUM_ACTION' => '&raquo; <a href="moderation_forum.php'. url('?action=alert&amp;token=' . $Session->get_token()) . '">' . $LANG['alert_management'] . '</a>',
		'U_ACTION_ALERT' => url('.php?action=alert&amp;del=1&amp;' . $Session->get_token())
	));

	if (empty($id_get)) //On liste les alertes
	{
		$Template->assign_vars(array(
			'C_FORUM_ALERTS' => true,
			'L_TITLE' => $LANG['alert_title'],
			'L_TOPIC' => $LANG['alert_concerned_topic'],
			'L_LOGIN' => $LANG['alert_login'],
			'L_TIME' => $LANG['date'],
			'L_STATUS' => $LANG['status'],
			'L_DELETE' => $LANG['delete'],
			'L_DELETE_MESSAGE' => $LANG['delete_several_alerts']
		));

		//Vérification des autorisations.
		$auth_cats = '';
		foreach ($CAT_FORUM as $idcat => $key)
		{
			if (!$User->check_auth($CAT_FORUM[$idcat]['auth'], EDIT_CAT_FORUM))
				$auth_cats .= $idcat . ',';
		}
		$auth_cats = !empty($auth_cats) ? " WHERE c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';

		$i = 0;
		$result = $Sql->query_while("SELECT ta.id, ta.title, ta.timestamp, ta.status, ta.user_id, ta.idtopic, ta.idmodo, m2.login AS login_modo, m.login, t.title AS topic_title, c.id AS cid
		FROM " . PREFIX . "forum_alerts ta
		LEFT JOIN " . PREFIX . "forum_topics t ON t.id = ta.idtopic
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = ta.user_id
		LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = ta.idmodo
		LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
		" . $auth_cats . "
		ORDER BY ta.status ASC, ta.timestamp DESC", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if ($row['status'] == 0)
				$status = $LANG['alert_not_solved'];
			else
				$status = $LANG['alert_solved'] . '<a href="../member/member' . url('.php?id=' . $row['idmodo'], '-' . $row['idmodo'] . '.php') . '">' . $row['login_modo'] . '</a>';

			$Template->assign_block_vars('alert_list', array(
				'TITLE' => '<a href="moderation_forum' . url('.php?action=alert&amp;id=' . $row['id']) . '">' . $row['title'] . '</a>',
				'EDIT' => '<a href="moderation_forum' . url('.php?action=alert&amp;id=' . $row['id']) . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="" class="valign_middle" /></a>',
				'TOPIC' => '<a href="topic' . url('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . '+' . Url::encode_rewrite($row['topic_title']) . '.php') . '">' . $row['topic_title'] . '</a>',
				'STATUS' => $status,
				'LOGIN' => '<a href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a>',
				'TIME' => gmdate_format('date_format', $row['timestamp']),
				'BACKGROUND_COLOR' => $row['status'] == 1 ? 'background-color:#82c2a7;' : 'background-color:#e59f09;',
				'ID' => $row['id']
			));

			$i++;
		}

		if ($i === 0)
		{
			$Template->assign_vars(array(
				'C_FORUM_NO_ALERT' => true,
				'L_NO_ALERT' => $LANG['no_alert'],
			));
		}
	}
	else //On affiche les informations sur une alerte
	{
		//Vérification des autorisations.
		$auth_cats = '';
		foreach ($CAT_FORUM as $idcat => $key)
		{
			if (!$User->check_auth($CAT_FORUM[$idcat]['auth'], EDIT_CAT_FORUM))
				$auth_cats .= $idcat . ',';
		}
		$auth_cats = !empty($auth_cats) ? " AND c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';

		$result = $Sql->query_while("
		SELECT ta.id, ta.title, ta.timestamp, ta.status, ta.user_id, ta.idtopic, ta.idmodo, m2.login AS login_modo, m.login, t.title AS topic_title, t.idcat, c.id AS cid, ta.contents
		FROM " . PREFIX . "forum_alerts ta
		LEFT JOIN " . PREFIX . "forum_topics t ON t.id = ta.idtopic
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = ta.user_id
		LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = ta.idmodo
		LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
		WHERE ta.id = '" . $id_get . "'" . $auth_cats, __LINE__, __FILE__);
		$row = $Sql->fetch_assoc($result);
		if (!empty($row))
		{
			//Le sujet n'existe plus, on vire l'alerte.
			if (empty($row['idcat']))
			{
				//Instanciation de la class du forum.
				$Forumfct = new Forum();

				$Forumfct->Del_alert_topic($id_get);
				AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=alert', '', '&'));
			}

			if ($row['status'] == 0)
				$status = $LANG['alert_not_solved'];
			else
				$status = $LANG['alert_solved'] . '<a href="../member/member' . url('.php?id=' . $row['idmodo'], '-' . $row['idmodo'] . '.php') . '">' . $row['login_modo'] . '</a>';

			$Template->assign_vars(array(
				'ID' => $id_get,
				'TITLE' => $row['title'],
				'TOPIC' => '<a href="topic' . url('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . '+' . Url::encode_rewrite($row['topic_title']) . '.php') . '">' . $row['topic_title'] . '</a>',
				'CONTENTS' => FormatingHelper::second_parse($row['contents']),
				'STATUS' => $status,
				'LOGIN' => '<a href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a>',
				'TIME' => gmdate_format('date_format', $row['timestamp']),
				'CAT' => '<a href="forum' . url('.php?id=' . $row['idcat'], '-' . $row['idcat'] . '+' . Url::encode_rewrite($CAT_FORUM[$row['idcat']]['name']) . '.php') . '">' . $CAT_FORUM[$row['idcat']]['name'] . '</a>',
				'C_FORUM_ALERT_LIST' => true,
				'U_CHANGE_STATUS' => ($row['status'] == '0') ? 'moderation_forum.php' . url('?action=alert&amp;id=' . $id_get . '&amp;new_status=1&amp;token=' . $Session->get_token()) : 'moderation_forum.php' . url('?action=alert&amp;id=' . $id_get . '&amp;new_status=0&amp;token=' . $Session->get_token()),
				'L_CHANGE_STATUS' => ($row['status'] == '0') ? $LANG['change_status_to_1'] : $LANG['change_status_to_0'],
				'L_TITLE' => $LANG['alert_title'],
				'L_TOPIC' => $LANG['alert_concerned_topic'],
				'L_CONTENTS' => $LANG['alert_msg'],
				'L_LOGIN' => $LANG['alert_login'],
				'L_TIME' => $LANG['date'],
				'L_STATUS' => $LANG['status'],
				'L_STATUS_1' => $LANG['change_status_to_1'],
				'L_CAT' => $LANG['alert_concerned_cat']
			));
		}
		else //Groupe, modérateur partiel qui n'a pas accès à cette alerte car elle ne concerne pas son forum
		{
			$Template->assign_vars(array(
				'C_FORUM_ALERT_NOT_AUTH' => true,
				'L_NO_ALERT' => $LANG['alert_not_auth']
			));
		}
	}
}
elseif ($action == 'punish') //Gestion des utilisateurs
{
	$readonly = retrieve(POST, 'new_info', 0);
	$readonly = $readonly > 0 ? (time() + $readonly) : 0;
	$readonly_contents = retrieve(POST, 'action_contents', '', TSTRING_UNCHANGE);
	if (!empty($id_get) && retrieve(POST, 'valid_user', false)) //On met à  jour le niveau d'avertissement
	{
		$info_mbr = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'level', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);

		//Modérateur ne peux avertir l'admin (logique non?).
		if (!empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || $User->check_level(ADMIN_LEVEL)))
		{
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_readonly = '" . $readonly . "' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);

			//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
			if ($info_mbr['user_id'] != $User->get_attribute('user_id'))
			{
				if (!empty($readonly_contents) && !empty($readonly))
				{


					//Envoi du message.
					PrivateMsg::start_conversation($info_mbr['user_id'], addslashes($LANG['read_only_title']), str_replace('%date', gmdate_format('date_format', $readonly), $readonly_contents), '-1', PrivateMsg::SYSTEM_PM);
				}
			}

			//Insertion de l'action dans l'historique.
			forum_history_collector(H_READONLY_USER, $info_mbr['user_id'], 'moderation_forum.php?action=punish&id=' . $info_mbr['user_id']);
		}

		AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=punish', '', '&'));
	}

	$Template->assign_vars(array(
		'L_FORUM' => $LANG['forum'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_MODERATION_PANEL' => $LANG['moderation_panel'],
		'L_MODERATION_FORUM' => $LANG['moderation_forum'],
		'L_INFO_MANAGEMENT' => $LANG['punishment_management'],
		'U_XMLHTTPREQUEST' => 'punish_moderation_panel',
		'U_MODERATION_FORUM_ACTION' => '&raquo; <a href="moderation_forum.php' . url('?action=punish&amp;token=' . $Session->get_token()) . '">' .$LANG['punishment_management'] . '</a>',
		'U_ACTION' => url('.php?action=punish&amp;token=' . $Session->get_token())
	));

	if (empty($id_get)) //On liste les membres qui ont déjà un avertissement
	{
		if (retrieve(POST, 'search_member', false))
		{
			$login = retrieve(POST, 'login_mbr', '');
			$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
			if (!empty($user_id) && !empty($login))
				AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=punish&id=' . $user_id, '', '&'));
			else
				AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=punish', '', '&'));
		}

		$Template->assign_vars(array(
			'C_FORUM_USER_LIST' => true,
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_punish_until'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_ACTION_USER' => $LANG['punishment_management'],
			'L_PROFILE' => $LANG['profile'],
			'L_SEARCH_USER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));

		$i = 0;
		$result = $Sql->query_while("SELECT user_id, login, user_readonly
		FROM " . PREFIX . "member
		WHERE user_readonly > " . time() . "
		ORDER BY user_readonly", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$Template->assign_block_vars('user_list', array(
				'LOGIN' => '<a href="moderation_forum.php' . url('?action=punish&amp;id=' . $row['user_id']) . '">' . $row['login'] . '</a>',
				'INFO' => gmdate_format('date_format', $row['user_readonly']),
				'U_PROFILE' => '../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
				'U_ACTION_USER' => '<a href="moderation_forum.php' . url('?action=punish&amp;id=' . $row['user_id'] . '&amp;token=' . $Session->get_token()) . '"><img src="../templates/' . get_utheme() . '/images/readonly.png" alt="" /></a>',
				'U_PM' => url('.php?pm='. $row['user_id'], '-' . $row['user_id'] . '.php'),
			));

			$i++;
		}

		if ($i === 0)
		{
			$Template->assign_vars( array(
				'C_FORUM_NO_USER' => true,
				'L_NO_USER' => $LANG['no_punish'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		$member = $Sql->query_array(DB_TABLE_MEMBER, 'login', 'user_readonly', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);

		//Durée de la sanction.
		$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000);
		$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], '2 ' . $LANG['month'], $LANG['life']);

		$diff = ($member['user_readonly'] - time());
		$key_sanction = 0;
		if ($diff > 0)
		{
			//Retourne la sanction la plus proche correspondant au temp de bannissement.
			for ($i = 11; $i >= 0; $i--)
			{
				$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
				if (($diff - $array_time[$i]) > $avg)
				{
					$key_sanction = $i + 1;
					break;
				}
			}
		}

		//On crée le formulaire select
		$select = '';
		foreach ($array_time as $key => $time)
		{
			$selected = ( $key_sanction == $key ) ? 'selected="selected"' : '' ;
			$select .= '<option value="' . $time . '" ' . $selected . '>' . strtolower($array_sanction[$key]) . '</option>';
		}

		array_pop($array_sanction);
		$Template->assign_vars(array(
			'C_FORUM_USER_INFO' => true,
			'KERNEL_EDITOR' => display_editor('action_contents'),
			'ALTERNATIVE_PM' => ($key_sanction > 0) ? str_replace('%date%', $array_sanction[$key_sanction], $LANG['user_readonly_changed']) : str_replace('%date%', '1 ' . $LANG['minute'], $LANG['user_readonly_changed']),
			'LOGIN' => '<a href="../member/member' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $member['login'] . '</a>',
			'INFO' => $array_sanction[$key_sanction],
			'SELECT' => $select,
			'REPLACE_VALUE' => 'replace_value = parseInt(replace_value);'. "\n" .
			'if (replace_value != \'326592000\'){'. "\n" .
			'array_time = new Array(' . (implode(', ', $array_time)) . ');' . "\n" .
			'array_sanction = new Array(\'' . implode('\', \'', array_map('addslashes', $array_sanction)) . '\');'. "\n" .
			'var i;
			for (i = 0; i <= 11; i++)
			{
				if (array_time[i] == replace_value)
				{
					replace_value = array_sanction[i];
					break;
				}
			}' . "\n" .
			'if (replace_value != \'' . addslashes($LANG['no']) . '\')' . "\n" .
			'{' . "\n" .
				'contents = contents.replace(regex, replace_value);' . "\n" .
				'document.getElementById(\'action_contents\').disabled = \'\'' . "\n" .
			'} else' . "\n" .
			'	document.getElementById(\'action_contents\').disabled = \'disabled\';' . "\n" .
			'document.getElementById(\'action_info\').innerHTML = replace_value;}',
			'REGEX'=> '/[0-9]+ [a-zA-Z]+/',
			'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN' => $LANG['user_readonly_explain'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_LOGIN' => $LANG['pseudo'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_CHANGE_INFO' => $LANG['submit'],
			'U_PM' => url('.php?pm='. $id_get, '-' . $id_get . '.php'),
			'U_ACTION_INFO' => url('.php?action=punish&amp;id=' . $id_get . '&amp;token=' . $Session->get_token())
		));
	}
}
elseif ($action == 'warning') //Gestion des utilisateurs
{
	$new_warning_level = retrieve(POST, 'new_info', 0);
	$warning_contents = retrieve(POST, 'action_contents', '', TSTRING_UNCHANGE);
	if ($new_warning_level >= 0 && $new_warning_level <= 100 && !empty($id_get) && retrieve(POST, 'valid_user', false)) //On met à  jour le niveau d'avertissement
	{
		$info_mbr = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'level', 'user_mail', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);

		//Modérateur ne peux avertir l'admin (logique non?).
		if (!empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || $User->check_level(ADMIN_LEVEL)))
		{
			if ($new_warning_level < 100) //Ne peux pas mettre des avertissements supérieurs à 100.
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_warning = '" . $new_warning_level . "' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);

				//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
				if ($info_mbr['user_id'] != $User->get_attribute('user_id'))
				{
					if (!empty($warning_contents))
					{

						$PrivateMsg = new PrivateMsg();

						//Envoi du message.
						PrivateMsg::start_conversation($info_mbr['user_id'], addslashes($LANG['warning_title']), $warning_contents, '-1', PrivateMsg::SYSTEM_PM);
					}
				}

				//Insertion de l'action dans l'historique.
				forum_history_collector(H_SET_WARNING_USER, $info_mbr['user_id'], 'moderation_forum.php?action=warning&id=' . $info_mbr['user_id']);
			}
			elseif ($new_warning_level == 100) //Ban => on supprime sa session et on le banni (pas besoin d'envoyer de pm :p).
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_warning = 100 WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
				$Sql->query_inject("DELETE FROM " . DB_TABLE_SESSIONS . " WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);

				//Insertion de l'action dans l'historique.
				forum_history_collector(H_BAN_USER, $info_mbr['user_id'], 'moderation_forum.php?action=warning&id=' . $info_mbr['user_id']);

				//Envoi du mail

				AppContext::get_mail_service()->send_from_properties($info_mbr['user_mail'], addslashes($LANG['ban_title_mail']), sprintf(addslashes($LANG['ban_mail']), HOST, addslashes(MailServiceConfig::load()->get_mail_signature())));
			}
		}

		AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=warning', '', '&'));
	}

	$Template->assign_vars(array(
		'L_FORUM' => $LANG['forum'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_MODERATION_PANEL' => $LANG['moderation_panel'],
		'L_MODERATION_FORUM' => $LANG['moderation_forum'],
		'L_INFO_MANAGEMENT' => $LANG['warning_management'],
		'U_XMLHTTPREQUEST' => 'warning_moderation_panel',
		'U_MODERATION_FORUM_ACTION' => '&raquo; <a href="moderation_forum.php' . url('?action=warning&amp;token=' . $Session->get_token()) . '">' . $LANG['warning_management'] . '</a>',
		'U_ACTION' => url('.php?action=warning&amp;token=' . $Session->get_token())
	));

	if (empty($id_get)) //On liste les membres qui ont déjà un avertissement
	{
		if (retrieve(POST, 'search_member', false))
		{
			$login = retrieve(POST, 'login_member', '');
			$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
			if (!empty($user_id) && !empty($login))
				AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=warning&id=' . $user_id, '', '&'));
			else
				AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=warning', '', '&'));
		}

		$Template->assign_vars(array(
			'C_FORUM_USER_LIST' => true,
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_warning_level'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_ACTION_USER' => $LANG['change_user_warning'],
			'L_SEARCH_USER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));

		$i = 0;
		$result = $Sql->query_while("SELECT user_id, login, user_warning
		FROM " . PREFIX . "member
		WHERE user_warning > 0
		ORDER BY user_warning", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$Template->assign_block_vars('user_list', array(
				'LOGIN' => $row['login'],
				'INFO' => $row['user_warning'] . '%',
				'U_ACTION_USER' => '<a href="moderation_forum.php' . url('?action=warning&amp;id=' . $row['user_id'] . '&amp;token=' . $Session->get_token()) . '"><img src="../templates/' . get_utheme() . '/images/admin/important.png" alt="" /></a>',
				'U_PROFILE' => '../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
				'U_PM' => url('.php?pm='. $row['user_id'], '-' . $row['user_id'] . '.php'),
			));

			$i++;
		}

		if ($i === 0)
		{
			$Template->assign_vars( array(
				'C_FORUM_NO_USER' => true,
				'L_NO_USER' => $LANG['no_user_warning'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		$member = $Sql->query_array(DB_TABLE_MEMBER, 'login', 'user_warning', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);

		$select = '';
		$j = 0;
		for ($j = 0; $j <= 10; $j++) //On crée le formulaire select
		{
			if ((10 * $j) == $member['user_warning'])
				$select .= '<option value="' . 10 * $j . '" selected="selected">' . 10 * $j . '%</option>';
			else
				$select .= '<option value="' . 10 * $j . '">' . 10 * $j . '%</option>';
		}

		$Template->assign_vars(array(
			'C_FORUM_USER_INFO' => true,
			'KERNEL_EDITOR' => display_editor('action_contents'),
			'ALTERNATIVE_PM' => str_replace('%level%', $member['user_warning'], $LANG['user_warning_level_changed']),
			'LOGIN' => '<a href="../member/member' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $member['login'] . '</a>',
			'INFO' => $LANG['user_warning_level'] . ': ' . $member['user_warning'] . '%',
			'SELECT' => $select,
			'REPLACE_VALUE' => 'contents = contents.replace(regex, \' \' + replace_value + \'%\');' . "\n" . 'document.getElementById(\'action_info\').innerHTML = \'' . addslashes($LANG['user_warning_level']) . ': \' + replace_value + \'%\';',
			'REGEX'=> '/ [0-9]+%/',
			'U_ACTION_INFO' => url('.php?action=warning&amp;id=' . $id_get . '&amp;token=' . $Session->get_token()),
			'U_PM' => url('.php?pm='. $id_get, '-' . $id_get . '.php'),
			'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN' => $LANG['user_warning_explain'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_warning_level'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_CHANGE_INFO' => $LANG['change_user_warning']
		));
	}
}
elseif (retrieve(GET, 'del_h', false) && $User->check_level(ADMIN_LEVEL)) //Suppression de l'historique.
{
	$Sql->query_inject("DELETE FROM " . PREFIX . "forum_history");

	AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php', '', '&'));
}
else //Panneau de modération
{
	$get_more = retrieve(GET, 'more', 0);

	$Template->assign_vars(array(
		'C_FORUM_MODO_MAIN' => true,
		'U_ACTION_HISTORY' => url('.php?del_h=1&amp;token=' . $Session->get_token()),
		'U_MORE_ACTION' => !empty($get_more) ? url('.php?more=' . ($get_more + 100)) : url('.php?more=100')
	));

	//Bouton de suppression de l'historique, visible uniquement pour l'admin.
	if ($User->check_level(ADMIN_LEVEL))
	{
		$Template->assign_vars(array(
			'C_FORUM_ADMIN' => true
		));
	}

	$Template->assign_vars(array(
		'SID' => SID,
		'L_DEL_HISTORY' => $LANG['alert_history'],
		'L_MODERATION_PANEL' => $LANG['moderation_panel'],
		'L_MODERATION_FORUM' => $LANG['moderation_forum'],
		'L_FORUM' => $LANG['forum'],
		'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
		'L_ALERT_MANAGEMENT' => $LANG['alert_management'],
		'L_USERS_MANAGEMENT' => $LANG['warning_management'],
		'L_HISTORY' => $LANG['history'],
		'L_MODO' => $LANG['modo'],
		'L_ACTION' => $LANG['action'],
		'L_USER_CONCERN' => $LANG['history_member_concern'],
		'L_DATE' => $LANG['date'],
		'L_DELETE' => $LANG['delete'],
		'L_MORE_ACTION' => $LANG['more_action']
	));

	$end = !empty($get_more) ? $get_more : 15; //Limit.
	$i = 0;

	$result = $Sql->query_while("SELECT h.action, h.user_id, h.user_id_action, h.url, h.timestamp, m.login, m2.login as member
	FROM " . PREFIX . "forum_history h
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = h.user_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = h.user_id_action
	ORDER BY h.timestamp DESC
	" . $Sql->limit(0, $end), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('action_list', array(
			'LOGIN' => !empty($row['login']) ? $row['login'] : $LANG['guest'],
			'DATE' => gmdate_format('date_format', $row['timestamp']),
			'U_ACTION' => (!empty($row['url']) ? '<a href="../forum/' . $row['url'] . '">' . $LANG[$row['action']] . '</a>' : $LANG[$row['action']]),
			'U_USER_ID' => url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
			'U_USER_CONCERN' => (!empty($row['user_id_action']) ? '<a href="../member/member' . url('.php?id=' . $row['user_id_action'], '-' . $row['user_id_action'] . '.php') . '">' . $row['member'] . '</a>' : '-')
		));

		$i++;
	}
	$Sql->query_close($result);

	if ($i == 0)
	{
		$Template->assign_vars(array(
			'C_FORUM_NO_ACTION' => true,
			'L_NO_ACTION' => $LANG['no_action']
		));
	}
}

//Listes les utilisateurs en lignes.
list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.session_script = '/forum/moderation_forum.php'");

$Template->assign_vars(array(
	'TOTAL_ONLINE' => $total_online,
	'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'SELECT_CAT' => forum_list_cat(0, 0), //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
	'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online']),
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'U_ONCHANGE' => url(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php"),
	'U_ONCHANGE_CAT' => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),
));

$Template->pparse('forum_moderation_panel');

include('../kernel/footer.php');

?>
