<?php
/*##################################################
 *                                pm.php
 *                            -------------------
 *   begin                : July 12, 2006
 *   copyright            : (C) 2006 Viarre Régis
 *   email                : crowkait@phpboost.com
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
define('TITLE', $LANG['title_pm']);
$Bread_crumb->add($LANG['member_area'], url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1'));
$Bread_crumb->add($LANG['title_pm'], url('pm.php'));
require_once('../kernel/header.php');

//Interdit aux non membres.
if (!$User->check_level(MEMBER_LEVEL))
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}



$pm_get = retrieve(GET, 'pm', 0);
$pm_id_get = retrieve(GET, 'id', 0);
$pm_del_convers = retrieve(GET, 'del_convers', false);
$quote_get = retrieve(GET, 'quote', 0);
$page = retrieve(GET, 'p', 0);
$post = retrieve(GET, 'post', false);
$pm_edit = retrieve(GET, 'edit', 0);
$pm_del = retrieve(GET, 'del', 0);
$read = retrieve(GET, 'read', false);

//Marque les messages privés comme lus
if ($read)
{
	$nbr_pm = PrivateMsg::count_conversations($User->get_attribute('user_id'));
	$max_pm_number = UserAccountsConfig::load()->get_max_private_messages_number();
	$limit_group = $User->check_max_value(PM_GROUP_LIMIT, $max_pm_number);
	$unlimited_pm = $User->check_level(MODO_LEVEL) || ($limit_group === -1);

	$nbr_waiting_pm = 0;
	if (!$unlimited_pm && $nbr_pm > $limit_group)
		$nbr_waiting_pm = $nbr_pm - $limit_group; //Nombre de messages privés non visibles.
	
	$j = 0;
	$result = $Sql->query_while("SELECT pm.last_msg_id, pm.user_view_pm
	FROM " . DB_TABLE_PM_TOPIC . "  pm
	LEFT JOIN " . DB_TABLE_PM_MSG . " msg ON msg.idconvers = pm.id AND msg.id = pm.last_msg_id
	WHERE " . $User->get_attribute('user_id') . " IN (pm.user_id, pm.user_id_dest) AND pm.last_user_id <> '" . $User->get_attribute('user_id') . "' AND msg.view_status = 0
	ORDER BY pm.last_timestamp DESC ", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On saute l'itération si la limite est dépassé.
		$j++;
		if (!$unlimited_pm && ($nbr_waiting_pm - $j) >= 0)
			continue;
		$Sql->query_inject("UPDATE " . DB_TABLE_PM_MSG . " SET view_status = 1 WHERE id = '" . $row['last_msg_id'] . "'", __LINE__, __FILE__);
	}
	$Sql->query_close($result);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_pm = '" . $nbr_waiting_pm . "' WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	
	AppContext::get_response()->redirect(HOST . DIR . url('/member/pm.php', '', '&'));
}

$convers = retrieve(POST, 'convers', false);
if ($convers && empty($pm_edit) && empty($pm_del)) //Envoi de conversation.
{
	$title = retrieve(POST, 'title', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
	$login = retrieve(POST, 'login', '');
	
	$limit_group = $User->check_max_value(PM_GROUP_LIMIT, UserAccountsConfig::load()->get_max_private_messages_number());
	//Vérification de la boite de l'expéditeur.
	if (PrivateMsg::count_conversations($User->get_attribute('user_id')) >= $limit_group && (!$User->check_level(MODO_LEVEL) && !($limit_group === -1))) //Boîte de l'expéditeur pleine.
		AppContext::get_response()->redirect('/member/pm' . url('.php?post=1&error=e_pm_full_post', '', '&') . '#errorh');
		
	if (!empty($title) && !empty($contents) && !empty($login))
	{
		//On essaye de récupérer le user_id, si le membre n'a pas cliqué une fois la recherche AJAX terminée.
		$user_id_dest = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "'", __LINE__, __FILE__);
		if (!empty($user_id_dest) && $user_id_dest != $User->get_attribute('user_id'))
		{
			//Envoi de la conversation, vérification de la boite si pleine => erreur
			list($pm_convers_id, $pm_msg_id) = PrivateMsg::start_conversation($user_id_dest, $title, $contents, $User->get_attribute('user_id'));
			//Succès redirection vers la conversation.
			AppContext::get_response()->redirect('/member/pm' . url('.php?id=' . $pm_convers_id, '-0-' . $pm_convers_id . '.php', '&') . '#m' . $pm_msg_id);
		}
		else //Destinataire non trouvé.
			AppContext::get_response()->redirect('/member/pm' . url('.php?post=1&error=e_unexist_user', '', '&') . '#errorh');
	}
	else //Champs manquants.
		AppContext::get_response()->redirect('/member/pm' . url('.php?post=1&error=e_incomplete', '', '&') . '#errorh');
}
elseif (!empty($post) || (!empty($pm_get) && $pm_get != $User->get_attribute('user_id')) && $pm_get > '0') //Interface pour poster la conversation.
{
	$Template->set_filenames(array(
		'pm'=> 'member/pm.tpl'
	));

	$Template->put_all(array(
		'LANG' => get_ulang(),
		'THEME' => get_utheme(),
		'KERNEL_EDITOR' => display_editor(),
		'L_REQUIRE_RECIPIENT' => $LANG['require_recipient'],
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE' => $LANG['require'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'L_POST_NEW_CONVERS' => $LANG['post_new_convers'],
		'L_RECIPIENT' => $LANG['recipient'],
		'L_SEARCH' => $LANG['search'],
		'L_TITLE' => $LANG['title'],
		'L_MESSAGE' => $LANG['message'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));
	
	$login = !empty($pm_get) ? $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $pm_get . "'", __LINE__, __FILE__) : '';
	
	$Template->assign_block_vars('post_convers', array(
		'U_ACTION_CONVERS' => url('.php?token=' . $Session->get_token()),
		'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
		'U_USER_VIEW' => '<a href="' . url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1') . '">' . $LANG['member_area'] . '</a>',
		'LOGIN' => $login
	));
	
	$limit_group = $User->check_max_value(PM_GROUP_LIMIT, UserAccountsConfig::load()->get_max_private_messages_number());
	$nbr_pm = PrivateMsg::count_conversations($User->get_attribute('user_id'));
	if (!$User->check_level(MODO_LEVEL) && !($limit_group === -1) && $nbr_pm >= $limit_group)
		$Errorh->handler($LANG['e_pm_full_post'], E_USER_WARNING);
	else
	{
		//Gestion des erreurs
		$get_error = retrieve(GET, 'error', '');
		switch ($get_error)
		{
			case 'e_unexist_user':
				$errstr = $LANG['e_unexist_user'];
				$type = E_USER_WARNING;
				break;
			case 'e_pm_full_post':
				$errstr = $LANG['e_pm_full_post'];
				$type = E_USER_WARNING;
				break;
			case 'e_incomplete':
				$errstr = $LANG['e_incomplete'];
				$type = E_USER_NOTICE;
			break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$Errorh->handler($errstr, $type);
	}
	
	$Template->assign_block_vars('post_convers.user_id_dest', array(
	));
	
	$Template->pparse('pm');
}
elseif (!empty($_POST['prw_convers']) && empty($mp_edit)) //Prévisualisation de la conversation.
{
	$Template->set_filenames(array(
		'pm'=> 'member/pm.tpl'
	));
	
	$Template->put_all(array(
		'LANG' => get_ulang(),
		'THEME' => get_utheme(),
		'KERNEL_EDITOR' => display_editor(),
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE' => $LANG['require'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'L_POST_NEW_CONVERS' => $LANG['post_new_convers'],
		'L_RECIPIENT' => $LANG['recipient'],
		'L_SEARCH' => $LANG['search'],
		'L_TITLE' => $LANG['title'],
		'L_MESSAGE' => $LANG['message'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));
	
	$Template->assign_block_vars('post_convers', array(
		'U_ACTION_CONVERS' => url('.php?token=' . $Session->get_token()),
		'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
		'U_USER_VIEW' => '<a href="' . url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1') . '">' . $LANG['member_area'] . '</a>',
		'LOGIN' => !empty($_POST['login']) ? stripslashes($_POST['login']) : '',
		'TITLE' => !empty($_POST['title']) ? stripslashes($_POST['title']) : '',
		'CONTENTS' => !empty($_POST['contents']) ? stripslashes($_POST['contents']) : ''
	));
	
	$Template->assign_block_vars('post_convers.show_convers', array(
		'DATE' => gmdate_format('date_format'),
		'CONTENTS' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($_POST['contents'])))
	));
	
	$Template->assign_block_vars('post_convers.user_id_dest', array(
	));
	
	$Template->pparse('pm');
}
elseif (!empty($_POST['prw']) && empty($pm_edit) && empty($pm_del)) //Prévisualisation du message.
{
	//On récupère les info de la conversation.
	$convers_title = $Sql->query("SELECT title FROM " . DB_TABLE_PM_TOPIC . "  WHERE id = '" . $pm_id_get . "'", __LINE__, __FILE__);
	
	$Template->set_filenames(array(
		'pm'=> 'member/pm.tpl'
	));

	$Template->put_all(array(
		'LANG' => get_ulang(),
		'KERNEL_EDITOR' => display_editor(),
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));
	
	$Template->assign_block_vars('show_pm', array(
		'DATE' => gmdate_format('date_format'),
		'CONTENTS' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($_POST['contents']))),
		'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
		'U_TITLE_CONVERS' => '<a href="pm' . url('.php?id=' . $pm_id_get, '-0-' . $pm_id_get .'.php') . '">' . $convers_title . '</a>',
		'U_USER_VIEW' => '<a href="' . url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1') . '">' . $LANG['member_area'] . '</a>',
	));
	
	$Template->assign_block_vars('post_pm', array(
		'CONTENTS' => !empty($_POST['contents']) ? stripslashes($_POST['contents']) : '',
		'U_PM_ACTION_POST' => url('.php?id=' . $pm_id_get . '&amp;token=' . $Session->get_token())
	));
	
	$Template->pparse('pm');
}
elseif (!empty($_POST['pm']) && !empty($pm_id_get) && empty($pm_edit) && empty($pm_del)) //Envoi de messages.
{
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
	if (!empty($contents))
	{
		//user_view_pm => nombre de messages non lu par l'un des 2 participants.
		
		//On récupère les info de la conversation.
		$convers = $Sql->query_array(DB_TABLE_PM_TOPIC, 'user_id', 'user_id_dest', 'user_convers_status', 'nbr_msg', 'user_view_pm', 'last_user_id', "WHERE id = '" . $pm_id_get . "'", __LINE__, __FILE__);
		
		//Récupération de l'id du destinataire.
		$user_id_dest = ($convers['user_id_dest'] == $User->get_attribute('user_id')) ? $convers['user_id'] : $convers['user_id_dest'];
		
		if ($convers['user_convers_status'] == '0' && $user_id_dest > '0') //On vérifie que la conversation n'a pas été supprimée chez le destinataire, et que ce n'est pas un mp automatique du site.
		{
			//Vu par exp et pas par dest  => 1
			//Vu par dest et pas par exp  => 2
			if ($convers['user_id'] == $User->get_attribute('user_id')) //Le membre est le créateur de la conversation.
				$status = 1;
			elseif ($convers['user_id_dest'] == $User->get_attribute('user_id')) //Le membre est le destinataire de la conversation.
				$status = 2;
			
			//Envoi du message privé.
			$pm_msg_id = PrivateMsg::send($user_id_dest, $pm_id_get, $contents, $User->get_attribute('user_id'), $status);

			//Calcul de la page vers laquelle on redirige.
			$last_page = ceil( ($convers['nbr_msg'] + 1) / 25);
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? '&p=' . $last_page : '';
			
			AppContext::get_response()->redirect('/member/pm' . url('.php?id=' . $pm_id_get . $last_page, '-0-' . $pm_id_get . $last_page_rewrite . '.php', '&') . '#m' . $pm_msg_id);
		}
		else //Le destinataire a supprimé la conversation.
			AppContext::get_response()->redirect('/member/pm' . url('.php?id=' . $pm_id_get . '&error=e_pm_del', '-0-' . $pm_id_get . '-0.php?error=e_pm_del', '&') . '#errorh');
	}
	else //Champs manquants.
		AppContext::get_response()->redirect('/member/pm' . url('.php?id=' . $pm_id_get . '&error=e_incomplete', '-0-' . $pm_id_get . '-0-e_incomplete.php', '&') . '#errorh');
}
elseif ($pm_del_convers) //Suppression de conversation.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	
	$Pagination = new DeprecatedPagination();
	$pagination_pm = 25;

	//Conversation présente chez les deux membres: user_convers_status => 0.
	//Conversation supprimée chez l'expediteur: user_convers_status => 1.
	//Conversation supprimée chez le destinataire: user_convers_status => 2.
	$result = $Sql->query_while("SELECT id, user_id, user_id_dest, user_convers_status, last_msg_id
	FROM " . DB_TABLE_PM_TOPIC . "
	WHERE
	(
		" . $User->get_attribute('user_id') . " IN (user_id, user_id_dest)
	)
	AND
	(
		user_convers_status = 0
		OR
		(
			(user_id_dest = '" . $User->get_attribute('user_id') . "' AND user_convers_status = 1)
			OR
			(user_id = '" . $User->get_attribute('user_id') . "' AND user_convers_status = 2)
		)
	)
	ORDER BY last_timestamp DESC
	" . $Sql->limit($Pagination->get_first_msg($pagination_pm, 'p'), $pagination_pm), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$del_convers = isset($_POST[$row['id']]) ? trim($_POST[$row['id']]) : '';
		if ($del_convers == 'on')
		{
			$del_convers = false;
			if ($row['user_id'] == $User->get_attribute('user_id')) //Expediteur.
			{
				$expd = true;
				if ($row['user_convers_status'] == 2)
					$del_convers = true;
			}
			elseif ($row['user_id_dest'] == $User->get_attribute('user_id')) //Destinataire
			{
				$expd = false;
				if ($row['user_convers_status'] == 1)
					$del_convers = true;
			}
			
			$view_status = $Sql->query("SELECT view_status FROM " . DB_TABLE_PM_MSG . " WHERE id = '" . $row['last_msg_id'] . "'", __LINE__, __FILE__);
			$update_nbr_pm = ($view_status == '0') ? true : false;
			PrivateMsg::delete_conversation($User->get_attribute('user_id'), $row['id'], $expd, $del_convers, $update_nbr_pm);
		}
	}
	
	AppContext::get_response()->redirect('/member/pm' . url('.php?pm=' . $User->get_attribute('user_id'), '-' . $User->get_attribute('user_id') . '.php', '&'));
}
elseif (!empty($pm_del)) //Suppression du message privé, si le destinataire ne la pas encore lu.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$pm = $Sql->query_array(DB_TABLE_PM_MSG, 'idconvers', 'contents', 'view_status', "WHERE id = '" . $pm_del . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	
	if (!empty($pm['idconvers'])) //Permet de vérifier si le message appartient bien au membre.
	{
		//On récupère les info de la conversation.
		$convers = $Sql->query_array(DB_TABLE_PM_TOPIC, 'title', 'user_id', 'user_id_dest', 'last_msg_id', "WHERE id = '" . $pm['idconvers'] . "'", __LINE__, __FILE__);
		if ($pm_del ==  $convers['last_msg_id']) //On édite uniquement le dernier message.
		{
			if ($convers['user_id'] == $User->get_attribute('user_id')) //Expediteur.
			{
				$expd = true;
				$pm_to = $convers['user_id_dest'];
			}
			elseif ($convers['user_id_dest'] == $User->get_attribute('user_id')) //Destinataire
			{
				$expd = false;
				$pm_to = $convers['user_id'];
			}

			$view = false;
			if ($pm['view_status'] == '1') //Le membre a déjà lu le message => échec.
				$view = true;
				
			//Le destinataire n'a pas lu le message => on peut éditer.
			if ($view === false)
			{
				$id_first = $Sql->query("SELECT MIN(id) FROM " . DB_TABLE_PM_MSG . " WHERE idconvers = '" . $pm['idconvers'] . "'", __LINE__, __FILE__);
				if ($pm_del > $id_first) //Suppression du message.
				{
					$pm_last_msg = PrivateMsg::delete($pm_to, $pm_del, $pm['idconvers']);
					AppContext::get_response()->redirect('/member/pm' . url('.php?id=' . $pm['idconvers'], '-0-' . $pm['idconvers'] . '.php', '&') . '#m' . $pm_last_msg);
				}
				elseif ($pm_del == $id_first) //Suppression de la conversation.
				{
					PrivateMsg::delete_conversation($pm_to, $pm['idconvers'], $expd, PrivateMsg::DEL_PM_CONVERS, PrivateMsg::UPDATE_MBR_PM);
					AppContext::get_response()->redirect('/member/pm.php' . SID2);
				}
			}
			else //Le membre a déjà lu le message on ne peux plus le supprimer.
			{
				$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                    $LANG['e_pm_nodel']);
                DispatchManager::redirect($controller);
			}
		}
		else //Echec.
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	else //Echec.
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif (!empty($pm_edit)) //Edition du message privé, si le destinataire ne la pas encore lu.
{
	$pm = $Sql->query_array(DB_TABLE_PM_MSG, 'idconvers', 'contents', 'view_status', "WHERE id = '" . $pm_edit . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	
	if (!empty($pm['idconvers'])) //Permet de vérifier si le message appartient bien au membre.
	{
		//On récupère les info de la conversation.
		$convers = $Sql->query_array(DB_TABLE_PM_TOPIC, 'title', 'user_id', 'user_id_dest', "WHERE id = '" . $pm['idconvers'] . "'", __LINE__, __FILE__);
		
		$view = false;
		if ($pm['view_status'] == '1') //Le membre a déjà lu le message => échec.
				$view = true;
			
		//Le destinataire n'a pas lu le message => on peut éditer.
		if ($view === false)
		{
			$id_first = $Sql->query("SELECT MIN(id) as id FROM " . DB_TABLE_PM_MSG . " WHERE idconvers = '" . $pm['idconvers'] . "'", __LINE__, __FILE__);
			if (!empty($_POST['convers']) XOR !empty($_POST['edit_pm']))
			{
				$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
				$title = retrieve(POST, 'title', '');
				
				if (!empty($_POST['edit_pm']) && !empty($contents))
				{
					if ($pm_edit > $id_first) //Maj du message.
						$Sql->query_inject("UPDATE " . DB_TABLE_PM_MSG . " SET contents = '" . $contents . "', timestamp = '" . time() . "' WHERE id = '" . $pm_edit . "'", __LINE__, __FILE__);
					else //Echec.
					{
						$error_controller = PHPBoostErrors::unexisting_page();
						DispatchManager::redirect($error_controller);
					}
				}
				elseif (!empty($_POST['convers']) && !empty($title)) //Maj de la conversation, si il s'agit du premier message.
				{
					if ($pm_edit == $id_first)
					{
						$Sql->query_inject("UPDATE " . DB_TABLE_PM_TOPIC . "  SET title = '" . $title . "', last_timestamp = '" . time() . "' WHERE id = '" . $pm['idconvers'] . "' AND last_msg_id = '" . $pm_edit . "'", __LINE__, __FILE__);
						$Sql->query_inject("UPDATE " . DB_TABLE_PM_MSG . " SET contents = '" . $contents . "', timestamp = '" . time() . "' WHERE id = '" . $pm_edit . "'", __LINE__, __FILE__);
					}
					else //Echec.
					{
						$error_controller = PHPBoostErrors::unexisting_page();
						DispatchManager::redirect($error_controller);
					}
				}
				else //Champs manquants.
				{
					$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                        $LANG['e_incomplete']);
                    DispatchManager::redirect($controller);
				}
				
				//Succès redirection vers la conversation.
				AppContext::get_response()->redirect('/member/pm' . url('.php?id=' . $pm['idconvers'], '-0-' . $pm['idconvers'] . '.php', '&') . '#m' . $pm_edit);
			}
			else //Interface d'édition
			{
				$Template->set_filenames(array(
					'pm'=> 'member/pm.tpl'
				));
				
				$Template->put_all(array(
					'LANG' => get_ulang(),
					'THEME' => get_utheme(),
					'KERNEL_EDITOR' => display_editor(),
					'L_REQUIRE_MESSAGE' => $LANG['require_text'],
					'L_REQUIRE' => $LANG['require'],
					'L_EDIT' => $LANG['edit'],
					'L_PRIVATE_MESSAGE' => $LANG['private_message'],
					'L_MESSAGE' => $LANG['message'],
					'L_SUBMIT' => $LANG['update'],
					'L_PREVIEW' => $LANG['preview'],
					'L_RESET' => $LANG['reset']
				));
				
				$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
				$title = retrieve(POST, 'title', '', TSTRING_UNCHANGE);
				
				$Template->assign_block_vars('edit_pm', array(
					'CONTENTS' => (!empty($_POST['prw_convers']) XOR !empty($_POST['prw'])) ? $contents : FormatingHelper::unparse($pm['contents']),
					'U_ACTION_EDIT' => url('.php?edit=' . $pm_edit . '&amp;token=' . $Session->get_token()),
					'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
					'U_USER_VIEW' => '<a href="' . url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1') . '">' . $LANG['member_area'] . '</a>'
				));
				
				if (!empty($_POST['prw_convers']) XOR !empty($_POST['prw']))
				{
					$Template->assign_block_vars('edit_pm.show_pm', array(
						'DATE' => gmdate_format('date_format'),
						'CONTENTS' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($_POST['contents']))),
					));
				}

				if ($id_first == $pm_edit) //Premier message de la convers => Edition de celle-ci
				{
					$Template->put_all(array(
						'SUBMIT_NAME' => 'convers',
						'L_TITLE' => $LANG['title'],
					));
					
					$Template->assign_block_vars('edit_pm.title', array(
						'TITLE' => (!empty($_POST['prw_convers']) XOR !empty($_POST['prw']) ) ? $title : $convers['title']
					));
				}
				else
					$Template->put_all(array(
						'SUBMIT_NAME' => 'edit_pm',
					));
					
				$Template->pparse('pm');
			}
		}
		else //Le membre a déjà lu le message on ne peux plus éditer.
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                $LANG['e_pm_noedit']);
            DispatchManager::redirect($controller);
		}
	}
	else //Echec.
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif (!empty($pm_id_get)) //Messages associés à la conversation.
{
	$Template->set_filenames(array(
		'pm'=> 'member/pm.tpl'
	));
	
	//On crée une pagination si le nombre de MP est trop important.
	
	$Pagination = new DeprecatedPagination();

	//On récupère les info de la conversation.
	$convers = $Sql->query_array(DB_TABLE_PM_TOPIC, 'id', 'title', 'user_id', 'user_id_dest', 'nbr_msg', 'last_msg_id', 'last_user_id', 'user_view_pm', "WHERE id = '" . $pm_id_get . "' AND '" . $User->get_attribute('user_id') . "' IN (user_id, user_id_dest)", __LINE__, __FILE__);

	//Vérification des autorisations.
	if (empty($convers['id']) || ($convers['user_id'] != $User->get_attribute('user_id') && $convers['user_id_dest'] != $User->get_attribute('user_id')))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	if ($convers['user_view_pm'] > 0 && $convers['last_user_id'] != $User->get_attribute('user_id')) //Membre n'ayant pas encore lu la conversation.
	{
		$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . DB_TABLE_MEMBER . " SET user_pm = user_pm - " . (int)$convers['user_view_pm'] . " WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . DB_TABLE_PM_TOPIC . " SET user_view_pm = 0 WHERE id = '" . $pm_id_get . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . DB_TABLE_PM_MSG . " SET view_status = 1 WHERE idconvers = '" . $convers['id'] . "' AND user_id <> '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	}
	
	$pagination_msg = 25;
	$Template->assign_block_vars('pm', array(
		'PAGINATION' => $Pagination->display('pm' . url('.php?id=' . $pm_id_get . '&amp;p=%d', '-0-' . $pm_id_get . '-%d.php'), $convers['nbr_msg'], 'p', $pagination_msg, 3),
		'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
		'U_TITLE_CONVERS' => '<a href="pm' . url('.php?id=' . $pm_id_get, '-0-' . $pm_id_get .'.php') . '">' . $convers['title'] . '</a>',
		'U_USER_VIEW' => '<a href="' . url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1') . '">' . $LANG['member_area'] . '</a>'
	));

	$Template->put_all(array(
		'THEME' => get_utheme(),
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'L_RESPOND' => $LANG['respond'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_EDIT' => $LANG['edit'],
		'L_DELETE' => $LANG['delete'],
		'L_RESET' => $LANG['reset']
	));
	
	//Message non lu par autre membre que user_id view_status => 0.
	//Message lu par autre membre que user_id view_status => 1.
	
	//Création du tableau des rangs.
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	
	$is_guest_in_convers = false;
	//Gestion des rangs.
	$ranks_cache = RanksCache::load()->get_ranks();
	$page = retrieve(GET, 'p', 0); //Redéfinition de la variable $page pour prendre en compte les redirections.
	$quote_last_msg = ($page > 1) ? 1 : 0; //On enlève 1 au limite si on est sur une page > 1, afin de récupérer le dernier msg de la page précédente.
	$i = 0;
	$j = 0;
	$result = $Sql->query_while("SELECT msg.id, msg.user_id, msg.timestamp, msg.view_status, m.login, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_ban, m.user_groups, s.user_id AS connect, msg.contents
	FROM " . DB_TABLE_PM_MSG . " msg
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = msg.user_id
	LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "' AND s.user_id <> -1
	WHERE msg.idconvers = '" . $pm_id_get . "'
	ORDER BY msg.timestamp
	" . $Sql->limit($Pagination->get_first_msg($pagination_msg, 'p'), $pagination_msg), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$row['user_id'] = (int)$row['user_id'];
		$is_admin = ($row['user_id'] === -1);
		if ($is_admin)
			$row['level'] = 2;
		
		if( !$is_guest_in_convers )
			$is_guest_in_convers = empty($row['login']);
		
		//Rang de l'utilisateur.
		$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
		$user_group = $user_rank;
		$user_rank_icon = '';
		if ($row['level'] === '2') //Rang spécial (admins).
		{
			$user_rank = $ranks_cache[-2][0];
			$user_group = $user_rank;
			$user_rank_icon = $ranks_cache[-2][1];
		}
		elseif ($row['level'] === '1') //Rang spécial (modos).
		{
			$user_rank = $ranks_cache[-1][0];
			$user_group = $user_rank;
			$user_rank_icon = $ranks_cache[-1][1];
		}
		else
		{
			foreach ($ranks_cache as $msg => $ranks_info)
			{
				if ($msg >= 0 && $msg <= $row['user_msg'])
				{
					$user_rank = $ranks_info[0];
					$user_rank_icon = $ranks_info[1];
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
					$user_groups .= !empty($array_group_info['img']) ? '<img src="../images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'];
			}
		}
		else
			$user_groups = $LANG['group'] . ': ' . $user_group;
			
		//Membre en ligne?
		$user_online = !empty($row['connect']) ? 'online' : 'offline';
		
		//Avatar
		if (empty($row['user_avatar']))
			$user_avatar = UserAccountsConfig::load()->is_default_avatar_enabled() ? '<img src="../templates/' . get_utheme() . '/images/' .  UserAccountsConfig::load()->get_default_avatar_name() . '" alt="" />' : '';
		else
			$user_avatar = '<img src="' . $row['user_avatar'] . '" alt=""	/>';
			
		//Affichage du sexe et du statut (connecté/déconnecté).
		$user_sex = '';
		if ($row['user_sex'] == 1)
			$user_sex = $LANG['sex'] . ': <img src="../templates/' . get_utheme() . '/images/man.png" alt="" /><br />';
		elseif ($row['user_sex'] == 2)
			$user_sex = $LANG['sex'] . ': <img src="../templates/' . get_utheme() . '/images/woman.png" alt="" /><br />';
		
		//Nombre de message.
		//Affichage du nombre de message.
		if ($row['user_msg'] >= 1)
			$user_msg = '<a href="../member/membermsg' . url('.php?id=' . $row['user_id'], '') . '" class="small_link">' . $LANG['message_s'] . '</a>: ' . $row['user_msg'];
		else
			$user_msg = '<a href="../member/membermsg' . url('.php?id=' . $row['user_id'], '') . '" class="small_link">' . $LANG['message'] . '</a>: 0';
		
		//Localisation.
		if (!empty($row['user_local']))
		{
			$user_local = $LANG['place'] . ': ' . $row['user_local'];
			$user_local = strlen($row['user_local']) > 15 ? TextHelper::substr_html($user_local, 0, 15) . '...<br />' : $user_local . '<br />';
		}
		else $user_local = '';

		//Reprise du dernier message de la page précédente.
		$row['contents'] = ($quote_last_msg == 1 && $i == 0) ? '<span class="text_strong">' . $LANG['quote_last_msg'] . '</span><br /><br />' . $row['contents'] : $row['contents'];
		$i++;
		
		$Template->assign_block_vars('pm.msg', array(
			'C_MODERATION_TOOLS' => (($User->get_attribute('user_id') === $row['user_id'] && $row['id'] === $convers['last_msg_id']) && ($row['view_status'] === '0')), //Dernier mp éditable. et si le destinataire ne la pas encore lu
			'ID' => $row['id'],
			'CONTENTS' => FormatingHelper::second_parse($row['contents']),
			'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format', $row['timestamp']),
			'CLASS_COLOR' => ($j%2 == 0) ? '' : 2,
			'USER_ONLINE' => '<img src="../templates/' . get_utheme() . '/images/' . $user_online . '.png" alt="" class="valign_middle" />',
			'USER_PSEUDO' => ($is_admin) ? $LANG['admin'] : (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 13) : $LANG['guest']),
			'USER_RANK' => ($is_admin) ? '' : (($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned']),
			'USER_IMG_ASSOC' => ($is_admin) ? '' : $user_assoc_img,
			'USER_AVATAR' => ($is_admin) ? '' : $user_avatar,
			'USER_GROUP' => ($is_admin) ? '' : $user_groups,
			'USER_DATE' => ($is_admin) ? '' : $LANG['registered_on'] . ': ' . gmdate_format('date_format_short', $row['registered']),
			'USER_SEX' => ($is_admin) ? '' : $user_sex,
			'USER_MSG' => ($is_admin) ? '' : $user_msg,
			'USER_LOCAL' => ($is_admin) ? '' : $user_local,
			'USER_MAIL' => ($is_admin) ? '' : ( !empty($row['user_mail']) && ($row['user_show_mail'] == '1' ) ) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail']  . '" title="' . $row['user_mail']  . '" /></a>' : '',
			'USER_MSN' => ($is_admin) ? '' : (!empty($row['user_msn'])) ? '<a href="mailto:' . $row['user_msn'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/msn.png" alt="' . $row['user_msn']  . '" title="' . $row['user_msn']  . '" /></a>' : '',
			'USER_YAHOO' => ($is_admin) ? '' : (!empty($row['user_yahoo'])) ? '<a href="mailto:' . $row['user_yahoo'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/yahoo.png" alt="' . $row['user_yahoo']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
			'USER_SIGN' => ($is_admin) ? '' : (!empty($row['user_sign'])) ? '____________________<br />' . FormatingHelper::second_parse($row['user_sign']) : '',
			'USER_WEB' => ($is_admin) ? '' : (!empty($row['user_web'])) ? '<a href="' . $row['user_web'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="' . $row['user_web']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
			'WARNING' => ($is_admin) ? '' : $row['user_warning'] . '%',
			'U_USER_ID' => ($is_admin) ? '' : url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
			'U_ANCHOR' => 'pm' . url('.php?id=' . $pm_id_get . (!empty($page) ? '&amp;p=' . $page : ''), '-0-' . $pm_id_get . (!empty($page) ? '-' . $page : '') . '.php') . '#m' . $row['id'],
			'U_QUOTE' => ($is_admin) ? '' : ('<a href="pm' . url('.php?quote=' . $row['id'] . '&amp;id=' . $pm_id_get . (!empty($page) ? '&amp;p=' . $page : ''), '-0-' . $pm_id_get . (!empty($page) ? '-' . $page : '-0') . '-' . $row['id'] . '.php') . '#quote" title="' . $LANG['quote'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/quote.png" alt="" /></a>'),
			'U_USER_PM' => ($is_admin) ? '' : '<a href="../member/pm' . url('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/pm.png" alt="" /></a>',
		));
		
		//Marqueur de suivis du sujet.
		if (!empty($row['track']))
			$track = true;
		$j++;
	}
	$Sql->query_close($result);

	//Récupération du message quoté.
	if (!empty($quote_get))
	{
		$quote_msg = $Sql->query_array(DB_TABLE_PM_MSG, 'user_id', 'contents', "WHERE id = '" . $quote_get . "'", __LINE__, __FILE__);
		$pseudo = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $quote_msg['user_id'] . "'", __LINE__, __FILE__);
		
		$contents = '[quote=' . $pseudo . ']' . FormatingHelper::unparse($quote_msg['contents']) . '[/quote]';
	}
	else
		$contents = '';

	if ($convers['user_id'] > 0 && !$is_guest_in_convers)
	{
		$Template->put_all(array(
			'KERNEL_EDITOR' => display_editor(),
		));
		
		$Template->assign_block_vars('post_pm', array(
			'CONTENTS' => $contents,
			'U_PM_ACTION_POST' => url('.php?id=' . $pm_id_get . '&amp;token=' . $Session->get_token(), '-0-' . $pm_id_get . '.php?token=' . $Session->get_token())
		));
		
		//Gestion des erreurs
		$get_error = retrieve(GET, 'error', '');
		switch ($get_error)
		{
			case 'e_incomplete':
				$errstr = $LANG['e_incomplete'];
				$type = E_USER_NOTICE;
				break;
			case 'e_pm_del':
				$errstr = $LANG['e_pm_del'];
				$type = E_USER_WARNING;
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$Errorh->handler($errstr, $type);
	}
	
	$Template->pparse('pm');
}
else //Liste des conversation, dans la boite du membre.
{
	$Template->set_filenames(array(
		'pm'=> 'member/pm.tpl'
	));

	$nbr_pm = PrivateMsg::count_conversations($User->get_attribute('user_id'));
	
	//On crée une pagination si le nombre de MP est trop important.
	
	$Pagination = new DeprecatedPagination();

	$pagination_pm = 25;
	$pagination_msg = 25;
	
	$limit_group = $User->check_max_value(PM_GROUP_LIMIT, UserAccountsConfig::load()->get_max_private_messages_number());
	$unlimited_pm = $User->check_level(MODO_LEVEL) || ($limit_group === -1);
	$pm_max = $unlimited_pm ? $LANG['illimited'] : $limit_group;
	
	$Template->assign_block_vars('convers', array(
		'NBR_PM' => $pagination_pm,
		'PM_POURCENT' => '<strong>' . $nbr_pm . '</strong> / <strong>' . $pm_max . '</strong>',
		'PAGINATION' => $Pagination->display('pm' . url('.php?p=%d', '-0-0-%d.php'), $nbr_pm, 'p', $pagination_pm, 3),
		'U_MARK_AS_READ' => '<a href="pm.php?read=1" class="small_link">' . $LANG['mark_pm_as_read'] . '</a>',
		'U_USER_ACTION_PM' => url('.php?del_convers=1&amp;p=' . $page . '&amp;token=' . $Session->get_token()),
		'U_USER_VIEW' => '<a href="' . url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1') . '">' . $LANG['member_area'] . '</a>',
		'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
		'U_POST_NEW_CONVERS' => '<a href="pm' . url('.php?post=1', '') . '" title="' . $LANG['post_new_convers'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/post.png" alt="' . $LANG['post_new_convers'] . '" title="' . $LANG['post_new_convers'] . '" class="valign_middle" /></a>'
	));
	
	//Aucun message privé.
	if ($nbr_pm == 0)
	{
		$Template->assign_block_vars('convers.no_pm', array(
			'L_NO_PM' => $LANG['no_pm']
		));
	}
	$nbr_waiting_pm = 0;
	if (!$unlimited_pm && $nbr_pm > $limit_group)
	{
		$nbr_waiting_pm = $nbr_pm - $limit_group; //Nombre de messages privés non visibles.
		//Gestion erreur.
		if ($nbr_waiting_pm > 0)
			$Errorh->handler(sprintf($LANG['e_pm_full'], $nbr_waiting_pm), E_USER_WARNING);
	}
	
	$Template->put_all(array(
		'THEME' => get_utheme(),
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
		'L_PRIVATE_MSG' => $LANG['private_message'],
		'L_PM_BOX' => $LANG['pm_box'],
		'L_TITLE' => $LANG['title'],
		'L_PARTICIPANTS' => $LANG['participants'],
		'L_MESSAGE' => $LANG['replies'],
		'L_LAST_MESSAGE' => $LANG['last_message'],
		'L_STATUS' => $LANG['status'],
		'L_DELETE' => $LANG['delete'],
		'L_READ' => $LANG['read'],
		'L_TRACK' => $LANG['pm_track'],
		'L_NOT_READ' => $LANG['not_read']
	));

	//Conversation présente chez les deux membres: user_convers_status => 0.
	//Conversation supprimée chez l'expediteur: user_convers_status => 1.
	//Conversation supprimée chez le destinataire: user_convers_status => 2.
	$i = 0;
	$j = 0;
	$result = $Sql->query_while("SELECT pm.id, pm.title, pm.user_id, pm.user_id_dest, pm.user_convers_status, pm.nbr_msg, pm.last_user_id, pm.last_msg_id, pm.last_timestamp, msg.view_status, m.login AS login, m1.login AS login_dest, m2.login AS last_login
	FROM " . DB_TABLE_PM_TOPIC . "  pm
	LEFT JOIN " . DB_TABLE_PM_MSG . " msg ON msg.id = pm.last_msg_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = pm.user_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m1 ON m1.user_id = pm.user_id_dest
	LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = pm.last_user_id
	WHERE
	(
		" . $User->get_attribute('user_id') . " IN (pm.user_id, pm.user_id_dest)
	)
	AND
	(
		pm.user_convers_status = 0
		OR
		(
			(pm.user_id_dest = '" . $User->get_attribute('user_id') . "' AND pm.user_convers_status = 1)
			OR
			(pm.user_id = '" . $User->get_attribute('user_id') . "' AND pm.user_convers_status = 2)
		)
	)
	ORDER BY pm.last_timestamp DESC
	" . $Sql->limit($Pagination->get_first_msg($pagination_pm, 'p'), $pagination_pm), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On saute l'itération si la limite est dépassé, si ce n'est pas un message privé du système.
		if ($row['user_id'] != -1)
		{
			$j++;
			if (!$unlimited_pm && ($nbr_waiting_pm - $j) >= 0)
				continue;
		}
		
		$view = false;
		$track = false;
		if ($row['last_user_id'] == $User->get_attribute('user_id')) //Le membre est le dernier posteur.
		{
			$view = true;
			if ($row['view_status'] === '0') //Le déstinataire n'a pas encore lu le message.
				$track = true;
		}
		else //Le membre n'est pas le dernier posteur.
		{
			if ($row['view_status'] === '1') //Le membre a déjà lu le message.
				$view = true;
		}
	
		$img_announce = 'announce';
		//Vérifications des messages Lu/non Lus.
		if ($view === false) //Nouveau message (non lu).
			$img_announce = 'new_' . $img_announce;
		if ($track === true) //Marqueur de reception du message
			$img_announce = $img_announce . '_track';
			
		//Ancre vers vers le dernier message posté.
		$last_page = ceil( $row['nbr_msg'] / $pagination_msg);
		$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
		$last_page = ($last_page > 1) ? 'p=' . $last_page . '&amp;' : '';
		
		if ($row['user_id'] == -1)
			$author = $LANG['admin'];
		elseif (!empty($row['login']))
			$author = '<a href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="small_link">' . $row['login'] . '</a>';
		else
			$author = '<strike>' . $LANG['guest'] . '</strike>';
			
		$participants = ($row['login_dest'] != $User->get_attribute('login')) ? $row['login_dest'] : $author;
		$user_id_dest = $row['user_id_dest'] != $User->get_attribute('user_id') ? $row['user_id_dest'] : $row['user_id'];
		$participants = !empty($participants) ? '<a href="../member/member' . url('.php?id=' . $user_id_dest, '-' . $user_id_dest . '.php') . '">' . $participants . '</a>' : '<strike>' . $LANG['admin']. '</strike>';
		
		//Affichage du dernier message posté.
		$last_msg = '<a href="pm' . url('.php?' . $last_page . 'id=' . $row['id'], '-0-' . $row['id'] . $last_page_rewrite . '.php') . '#m' . $row['last_msg_id'] . '" title=""><img src="../templates/' . get_utheme() . '/images/ancre.png" alt="" /></a>' . ' ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br />';
		$last_msg .= ($row['user_id'] == -1) ? $LANG['by'] . ' ' . $LANG['admin'] : $LANG['by'] . ' <a href="../member/member' . url('.php?id=' . $row['last_user_id'], '-' . $row['last_user_id'] . '.php') . '" class="small_link">' . $row['last_login'] . '</a>';

		$Template->assign_block_vars('convers.list', array(
			'INCR' => $i,
			'ID' => $row['id'],
			'ANNOUNCE' => '../templates/' . get_utheme() . '/images/' . $img_announce,
			'TITLE' => $row['title'],
			'MSG' => ($row['nbr_msg'] - 1),
			'U_PARTICIPANTS' => (($row['user_convers_status'] != 0) ? '<strike>' . $participants . '</strike>' : $participants),
			'U_CONVERS'	=> url('.php?id=' . $row['id'], '-0-' . $row['id'] . '.php'),
			'U_AUTHOR' => $LANG['by'] . ' ' . $author,
			'U_LAST_MSG' => $last_msg
		));
		$i++;
	}
	$Sql->query_close($result);
	
	$Template->pparse('pm');
}

	
include('../kernel/footer.php');

?>