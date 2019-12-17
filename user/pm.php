<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 01
 * @since       PHPBoost 1.5 - 2006 07 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
define('TITLE', $LANG['title_pm']);
$Bread_crumb->add($LANG['user'], UserUrlBuilder::profile(AppContext::get_current_user()->get_id())->rel());
$Bread_crumb->add($LANG['title_pm'], UserUrlBuilder::personnal_message()->rel());
require_once('../kernel/header.php');
$current_user = AppContext::get_current_user();

//Interdit aux non membres.
if (!$current_user->check_level(User::MEMBER_LEVEL))
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$request = AppContext::get_request();

$pm_get = (int)retrieve(GET, 'pm', 0);
$pm_id_get = (int)retrieve(GET, 'id', 0);
$pm_del_convers = (bool)retrieve(GET, 'del_convers', false);
$quote_get = (int)retrieve(GET, 'quote', 0);
$page = (int)retrieve(GET, 'p', 0);
$post = (bool)retrieve(GET, 'post', false);
$pm_edit = (int)retrieve(GET, 'edit', 0);
$pm_del = (int)retrieve(GET, 'del', 0);
$read = (bool)retrieve(GET, 'read', false);
$convers = (bool)retrieve(POST, 'convers', false);
$prw_convers = (bool)retrieve(POST, 'prw_convers', false);
$prw = (bool)retrieve(POST, 'prw', false);
$pm_post = (bool)retrieve(POST, 'pm', false);
$edit_pm = (bool)retrieve(POST, 'edit_pm', false);

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('contents');

$user_accounts_config = UserAccountsConfig::load();

$_NBR_ELEMENTS_PER_PAGE = 25;

//Marque les messages privés comme lus
if ($read)
{
	$nbr_pm = PrivateMsg::count_conversations($current_user->get_id());
	$max_pm_number = $user_accounts_config->get_max_private_messages_number();
	$limit_group = $current_user->check_max_value(PM_GROUP_LIMIT, $max_pm_number);
	$unlimited_pm = $current_user->check_level(User::MODERATOR_LEVEL) || ($limit_group === -1);

	$nbr_waiting_pm = 0;
	if (!$unlimited_pm && $nbr_pm > $limit_group)
		$nbr_waiting_pm = $nbr_pm - $limit_group; //Nombre de messages privés non visibles.

	$j = 0;
	$result = PersistenceContext::get_querier()->select("SELECT pm.last_msg_id, pm.user_view_pm
	FROM " . DB_TABLE_PM_TOPIC . "  pm
	LEFT JOIN " . DB_TABLE_PM_MSG . " msg ON msg.idconvers = pm.id AND msg.id = pm.last_msg_id
	WHERE :user_id IN (pm.user_id, pm.user_id_dest) AND pm.last_user_id <> :user_id AND msg.view_status = 0
	ORDER BY pm.last_timestamp DESC ", array(
		'user_id' => $current_user->get_id()
	));
	while ($row = $result->fetch())
	{
		//On saute l'itération si la limite est dépassé.
		$j++;
		if (!$unlimited_pm && ($nbr_waiting_pm - $j) >= 0)
			continue;
		PersistenceContext::get_querier()->update(DB_TABLE_PM_MSG, array('view_status' => 1), 'WHERE id = :id', array('id' => $row['last_msg_id']));
	}
	$result->dispose();

	if ($nbr_waiting_pm < 0)
		$nbr_waiting_pm = 0;

	PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('unread_pm' => $nbr_waiting_pm), 'WHERE user_id = :id', array('id' => $current_user->get_id()));

	if ($nbr_waiting_pm != $nbr_pm)
		SessionData::recheck_cached_data_from_user_id($current_user->get_id());

	AppContext::get_response()->redirect(UserUrlBuilder::personnal_message());
}

if ($convers && empty($pm_edit) && empty($pm_del)) //Envoi de conversation.
{
	$title = retrieve(POST, 'title', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
	$login = retrieve(POST, 'login', '');

	$limit_group = $current_user->check_max_value(PM_GROUP_LIMIT, $user_accounts_config->get_max_private_messages_number());
	//Vérification de la boite de l'expéditeur.
	if (PrivateMsg::count_conversations($current_user->get_id()) >= $limit_group && (!$current_user->check_level(User::MODERATOR_LEVEL) && !($limit_group === -1))) //Boîte de l'expéditeur pleine.
		AppContext::get_response()->redirect('/user/pm' . url('.php?post=1&error=e_pm_full_post', '', '&') . '#message_helper');

	if (!empty($title) && !empty($contents) && !empty($login))
	{
		//On essaye de récupérer le user_id, si le membre n'a pas cliqué une fois la recherche AJAX terminée.
		$user_id_dest = 0;
		try {
			$user_id_dest = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'user_id', 'WHERE display_name = :name', array('name' => stripslashes($login)));
		} catch (RowNotFoundException $ex) {}

		if (!empty($user_id_dest) && $user_id_dest != $current_user->get_id())
		{
			$contents = FormatingHelper::strparse($contents, array(), false);

			//Envoi de la conversation, vérification de la boite si pleine => erreur
			list($pm_convers_id, $pm_msg_id) = PrivateMsg::start_conversation($user_id_dest, $title, $contents, $current_user->get_id());

			//Envoi d'un mail si l'utilisateur a activé l'option
			$pmtomail_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_pmtomail');
			if (!empty($pmtomail_field) && $pmtomail_field['display'])
			{
				$contents = $contents . '<br /><br />' . $current_user->get_display_name() . '<br /><br /><a href="' . GeneralConfig::load()->get_complete_site_url() . '/user/pm' . url('.php?id=' . $pm_convers_id, '-0-' . $pm_convers_id, '&') . '#m' . $pm_msg_id . '">' . $LANG['pm_conversation_link'] . '</a>';
				$send_mail = false;
				try {
					$send_mail = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER_EXTENDED_FIELDS, 'user_pmtomail', 'WHERE user_id = :id', array('id' => $user_id_dest));
				} catch (RowNotFoundException $ex) {}

				if ($send_mail)
				{
					$email_dest = '';
					try {
						$email_dest = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'email', 'WHERE user_id = :id', array('id' => $user_id_dest));
					} catch (RowNotFoundException $ex) {}

					if ($email_dest)
						AppContext::get_mail_service()->send_from_properties($email_dest, $LANG['new_pm'] . ' : ' . stripslashes($title), ContentSecondParser::export_html_text($contents), '', Mail::SENDER_USER);
				}
			}

			//Succès redirection vers la conversation.
			AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm_convers_id, '-0-' . $pm_convers_id, '&') . '#m' . $pm_msg_id);
		}
		else //Destinataire non trouvé.
			AppContext::get_response()->redirect('/user/pm' . url('.php?post=1&error=e_unexist_user', '', '&') . '#message_helper');
	}
	else //Champs manquants.
		AppContext::get_response()->redirect('/user/pm' . url('.php?post=1&error=e_incomplete', '', '&') . '#message_helper');
}
elseif (!empty($post) || (!empty($pm_get) && $pm_get != $current_user->get_id()) && $pm_get > '0') //Interface pour poster la conversation.
{
	$tpl = new FileTemplate('user/pm.tpl');

	$tpl->put_all(array(
		'KERNEL_EDITOR' => $editor->display(),
		'L_REQUIRE_RECIPIENT' => $LANG['require_recipient'],
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
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

	$login = '';
	if (!empty($pm_get))
	{
		try  {
			$login = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'display_name', 'WHERE user_id = :id', array('id' => $pm_get));
		} catch (RowNotFoundException $ex) {}
	}

	$tpl->assign_block_vars('post_convers', array(
		'U_PM_BOX' => '<a href="pm.php' . '">' . $LANG['pm_box'] . '</a>',
		'LOGIN' => $login
	));

	$limit_group = $current_user->check_max_value(PM_GROUP_LIMIT, $user_accounts_config->get_max_private_messages_number());
	$nbr_pm = PrivateMsg::count_conversations($current_user->get_id());
	if (!$current_user->check_level(User::MODERATOR_LEVEL) && !($limit_group === -1) && $nbr_pm >= $limit_group)
		$tpl->put('message_helper', MessageHelper::display($LANG['e_pm_full_post'], MessageHelper::WARNING));
	else
	{
		//Gestion des erreurs
		$get_error = retrieve(GET, 'error', '');
		switch ($get_error)
		{
			case 'e_unexist_user':
				$errstr = LangLoader::get_message('user.not_exists', 'status-messages-common');
				$type = MessageHelper::WARNING;
				break;
			case 'e_pm_full_post':
				$errstr = $LANG['e_pm_full_post'];
				$type = MessageHelper::WARNING;
				break;
			case 'e_incomplete':
				$errstr = $LANG['e_incomplete'];
				$type = MessageHelper::NOTICE;
			break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$tpl->put('message_helper', MessageHelper::display($errstr, $type));
	}

	$tpl->assign_block_vars('post_convers.user_id_dest', array(
	));

	$tpl->display();
}
elseif ($prw_convers && empty($mp_edit)) //Prévisualisation de la conversation.
{
	$title = retrieve(POST, 'title', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
	$login = retrieve(POST, 'login', '');

	$tpl = new FileTemplate('user/pm.tpl');

	$tpl->put_all(array(
		'KERNEL_EDITOR' => $editor->display(),
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
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

	$tpl->assign_block_vars('post_convers', array(
		'U_PM_BOX' => '<a href="pm.php' . '">' . $LANG['pm_box'] . '</a>',
		'LOGIN' => $login,
		'TITLE' => stripslashes($title),
		'CONTENTS' => $contents
	));

	$tpl->assign_block_vars('post_convers.show_convers', array(
		'DATE' => Date::to_format(Date::DATE_NOW, Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
		'CONTENTS' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents)))
	));

	$tpl->assign_block_vars('post_convers.user_id_dest', array(
	));

	$tpl->display();
}
elseif ($prw && empty($pm_edit) && empty($pm_del)) //Prévisualisation du message.
{
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);

	//On récupère les info de la conversation.
	$convers_title = '';
	try {
		$convers_title = PersistenceContext::get_querier()->get_column_value(DB_TABLE_PM_TOPIC, 'title', 'WHERE id = :id', array('id' => $pm_id_get));
	} catch (RowNotFoundException $ex) {}

	$tpl = new FileTemplate('user/pm.tpl');

	$tpl->put_all(array(
		'KERNEL_EDITOR' => $editor->display(),
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));

	$tpl->assign_block_vars('show_pm', array(
		'DATE' => Date::to_format(Date::DATE_NOW, Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
		'CONTENTS' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents))),
		'U_PM_BOX' => '<a href="pm.php' . '">' . $LANG['pm_box'] . '</a>',
		'U_TITLE_CONVERS' => '<a href="pm' . url('.php?id=' . $pm_id_get, '-0-' . $pm_id_get) . '">' . $convers_title . '</a>'
	));

	$tpl->assign_block_vars('post_pm', array(
		'CONTENTS' => $contents,
		'U_PM_ACTION_POST' => url('.php?id=' . $pm_id_get . '&amp;token=' . AppContext::get_session()->get_token())
	));

	$tpl->display();
}
elseif ($pm_post && !empty($pm_id_get) && empty($pm_edit) && empty($pm_del)) //Envoi de messages.
{
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
	if (!empty($contents))
	{
		//user_view_pm => nombre de messages non lu par l'un des 2 participants.

		//On récupère les info de la conversation.
		try {
			$convers = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_TOPIC, array('title', 'user_id', 'user_id_dest', 'user_convers_status', 'nbr_msg', 'user_view_pm', 'last_user_id'), 'WHERE id = :id', array('id' => $pm_id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		//Récupération de l'id du destinataire.
		$user_id_dest = ($convers['user_id_dest'] == $current_user->get_id()) ? $convers['user_id'] : $convers['user_id_dest'];

		if ($convers['user_convers_status'] == '0' && $user_id_dest > '0') //On vérifie que la conversation n'a pas été supprimée chez le destinataire, et que ce n'est pas un mp automatique du site.
		{
			//Vu par exp et pas par dest  => 1
			//Vu par dest et pas par exp  => 2
			if ($convers['user_id'] == $current_user->get_id()) //Le membre est le créateur de la conversation.
				$status = 1;
			elseif ($convers['user_id_dest'] == $current_user->get_id()) //Le membre est le destinataire de la conversation.
				$status = 2;

			$contents = FormatingHelper::strparse($contents, array(), false);

			//Envoi du message privé.
			$pm_msg_id = PrivateMsg::send($user_id_dest, $pm_id_get, $contents, $current_user->get_id(), $status);

			//Calcul de la page vers laquelle on redirige.
			$last_page = ceil( ($convers['nbr_msg'] + 1) / 25);
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? '&p=' . $last_page : '';

			//Envoi d'un mail si l'utilisateur a activé l'option
			$pmtomail_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_pmtomail');
			if (!empty($pmtomail_field) && $pmtomail_field['display'])
			{
				$contents = $contents . '<br /><br /><a href="' . GeneralConfig::load()->get_complete_site_url() . '/user/pm' . url('.php?id=' . $pm_id_get . $last_page, '-0-' . $pm_id_get . $last_page_rewrite, '&') . '#m' . $pm_msg_id . '">' . $LANG['pm_conversation_link'] . '</a>';
				$send_email = 0;
				try {
					$send_email = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER_EXTENDED_FIELDS, 'user_pmtomail', 'WHERE user_id = :id', array('id' => $user_id_dest));
				} catch (RowNotFoundException $ex) {}

				if ($send_email)
				{
					$email_dest = '';
					try {
						$email_dest = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'email', 'WHERE user_id = :id', array('id' => $user_id_dest));
					} catch (RowNotFoundException $ex) {}

					if ($email_dest)
						AppContext::get_mail_service()->send_from_properties($email_dest, $LANG['new_pm'] . ' : ' . stripslashes($convers['title']), $contents);
				}
			}

			AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm_id_get . $last_page, '-0-' . $pm_id_get . $last_page_rewrite, '&') . '#m' . $pm_msg_id);
		}
		else //Le destinataire a supprimé la conversation.
			AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm_id_get . '&error=e_pm_del', '-0-' . $pm_id_get . '-0&error=e_pm_del', '&') . '#message_helper');
	}
	else //Champs manquants.
		AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm_id_get . '&error=e_incomplete', '-0-' . $pm_id_get . '-0&error=e_incomplete', '&') . '#message_helper');
}
elseif ($pm_del_convers) //Suppression de conversation.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	//Conversation présente chez les deux membres: user_convers_status => 0.
	//Conversation supprimée chez l'expediteur: user_convers_status => 1.
	//Conversation supprimée chez le destinataire: user_convers_status => 2.
	$result = PersistenceContext::get_querier()->select("SELECT id, user_id, user_id_dest, user_convers_status, last_msg_id
	FROM " . DB_TABLE_PM_TOPIC . "
	WHERE
	(
		:user_id IN (user_id, user_id_dest)
	)
	AND
	(
		user_convers_status = 0
		OR
		(
			(user_id_dest = :user_id AND user_convers_status = 1)
			OR
			(user_id = :user_id AND user_convers_status = 2)
		)
	)
	ORDER BY last_timestamp DESC", array(
		'user_id' => $current_user->get_id()
	));

	while ($row = $result->fetch())
	{
		$del_convers = $request->has_postparameter($row['id']) ? trim($request->get_postvalue($row['id'])) : '';
		if ($del_convers == 'on')
		{
			$del_convers = false;
			if ($row['user_id'] == $current_user->get_id()) //Expediteur.
			{
				$expd = true;
				if ($row['user_convers_status'] == 2)
					$del_convers = true;
			}
			elseif ($row['user_id_dest'] == $current_user->get_id()) //Destinataire
			{
				$expd = false;
				if ($row['user_convers_status'] == 1)
					$del_convers = true;
			}

			$view_status = 0;
			try {
				$view_status = PersistenceContext::get_querier()->get_column_value(DB_TABLE_PM_MSG, 'view_status', 'WHERE id = :id', array('id' => $row['last_msg_id']));
			} catch (RowNotFoundException $ex) {}

			$update_nbr_pm = ($view_status == '0');
			PrivateMsg::delete_conversation($current_user->get_id(), $row['id'], $expd, $del_convers, $update_nbr_pm);

			//Suppression du message privé dans la boite du destinataire s'il ne la pas encore lu.
			if ($view_status != '1')
			{
				$id_msg = 0;
				try {
					$id_msg = PersistenceContext::get_querier()->get_column_value(DB_TABLE_PM_MSG, 'MIN(id)', 'WHERE idconvers = :id', array('id' => $row['id']));
				} catch (RowNotFoundException $ex) {}

				if ($id_msg) //Suppression du message.
				{
					PrivateMsg::delete($row['user_id_dest'], $id_msg, $row['id']);
					PrivateMsg::delete_conversation($row['user_id_dest'], $row['id'], false, PrivateMsg::DEL_PM_CONVERS, true);
				}
			}
		}
	}
	$result->dispose();

	SessionData::recheck_cached_data_from_user_id($current_user->get_id());

	AppContext::get_response()->redirect('/user/pm' . url('.php?pm=' . $current_user->get_id(), '-' . $current_user->get_id(), '&'));
}
elseif (!empty($pm_del)) //Suppression du message privé, si le destinataire ne la pas encore lu.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	try {
		$pm = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_MSG, array('idconvers', 'contents', 'view_status'), 'WHERE id = :id AND user_id = :user_id', array('id' => $pm_del, 'user_id' => $current_user->get_id()));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	if (!empty($pm['idconvers'])) //Permet de vérifier si le message appartient bien au membre.
	{
		//On récupère les info de la conversation.
		try {
			$convers = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_TOPIC, array('title', 'user_id', 'user_id_dest', 'last_msg_id'), 'WHERE id = :id', array('id' => $pm['idconvers']));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		if ($pm_del == $convers['last_msg_id']) //On édite uniquement le dernier message.
		{
			if ($convers['user_id'] == $current_user->get_id()) //Expediteur.
			{
				$expd = true;
				$pm_to = $convers['user_id_dest'];
			}
			elseif ($convers['user_id_dest'] == $current_user->get_id()) //Destinataire
			{
				$expd = false;
				$pm_to = $convers['user_id'];
			}

			if ($pm['view_status'] != '1')
			{
				$id_first = 0;
				try {
					$id_first = PersistenceContext::get_querier()->get_column_value(DB_TABLE_PM_MSG, 'MIN(id)', 'WHERE idconvers = :id', array('id' => $pm['idconvers']));
				} catch (RowNotFoundException $ex) {}

				if ($pm_del > $id_first) //Suppression du message.
				{
					$pm_last_msg = PrivateMsg::delete($pm_to, $pm_del, $pm['idconvers']);
					AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm['idconvers'], '-0-' . $pm['idconvers'], '&') . '#m' . $pm_last_msg);
				}
				elseif ($pm_del == $id_first) //Suppression de la conversation.
				{
					PrivateMsg::delete_conversation($pm_to, $pm['idconvers'], $expd, PrivateMsg::DEL_PM_CONVERS, true);
					AppContext::get_response()->redirect('/user/pm.php');
				}
			}
			else //Le membre a déjà lu le message on ne peux plus le supprimer.
			{
				$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'),
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
	try {
		$pm = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_MSG, array('idconvers', 'contents', 'view_status'), 'WHERE id = :id AND user_id = :user_id', array('id' => $pm_edit, 'user_id' => $current_user->get_id()));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	if (!empty($pm['idconvers'])) //Permet de vérifier si le message appartient bien au membre.
	{
		//On récupère les info de la conversation.
		try {
			$conversation = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_TOPIC, array('title', 'user_id', 'user_id_dest'), 'WHERE id = :id', array('id' => $pm['idconvers']));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$view = false;
		if ($pm['view_status'] == '1') //Le membre a déjà lu le message => échec.
			$view = true;

		//Le destinataire n'a pas lu le message => on peut éditer.
		if ($view === false)
		{
			$id_first = 0;
			try {
				$id_first = PersistenceContext::get_querier()->get_column_value(DB_TABLE_PM_MSG, 'MIN(id)', 'WHERE idconvers = :id', array('id' => $pm['idconvers']));
			} catch (RowNotFoundException $ex) {}

			if ($convers XOR $edit_pm)
			{
				$contents = stripslashes(retrieve(POST, 'contents', '', TSTRING_PARSE));
				$title = retrieve(POST, 'title', '');

				if ($edit_pm && !empty($contents))
				{
					if ($pm_edit > $id_first) //Maj du message.
						PersistenceContext::get_querier()->update(DB_TABLE_PM_MSG, array('contents' => $contents, 'timestamp' => time()), 'WHERE id = :id', array('id' => $pm_edit));
					else //Echec.
					{
						$error_controller = PHPBoostErrors::unexisting_page();
						DispatchManager::redirect($error_controller);
					}
				}
				elseif ($convers && !empty($title)) //Maj de la conversation, si il s'agit du premier message.
				{
					if ($pm_edit == $id_first)
					{
						PersistenceContext::get_querier()->update(DB_TABLE_PM_TOPIC, array('title' => $title, 'last_timestamp' => time()), 'WHERE id = :id AND last_msg_id = :last_msg_id', array('id' => $pm['idconvers'], 'last_msg_id' => $pm_edit));
						PersistenceContext::get_querier()->update(DB_TABLE_PM_MSG, array('contents' => $contents, 'timestamp' => time()), 'WHERE id = :id', array('id' => $pm_edit));
					}
					else //Echec.
					{
						$error_controller = PHPBoostErrors::unexisting_page();
						DispatchManager::redirect($error_controller);
					}
				}
				else //Champs manquants.
				{
					$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'),
                        $LANG['e_incomplete']);
                    DispatchManager::redirect($controller);
				}

				//Succès redirection vers la conversation.
				AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm['idconvers'], '-0-' . $pm['idconvers'], '&') . '#m' . $pm_edit);
			}
			else //Interface d'édition
			{
				$tpl = new FileTemplate('user/pm.tpl');

				$tpl->put_all(array(
					'KERNEL_EDITOR' => $editor->display(),
					'L_REQUIRE_MESSAGE' => $LANG['require_text'],
					'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
					'L_EDIT' => LangLoader::get_message('edit', 'common'),
					'L_PRIVATE_MESSAGE' => $LANG['private_message'],
					'L_MESSAGE' => $LANG['message'],
					'L_SUBMIT' => $LANG['update'],
					'L_PREVIEW' => $LANG['preview'],
					'L_RESET' => $LANG['reset']
				));

				$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
				$title = retrieve(POST, 'title', '', TSTRING_UNCHANGE);

				$tpl->assign_block_vars('edit_pm', array(
					'CONTENTS' => ($prw_convers XOR $prw) ? $contents : FormatingHelper::unparse($pm['contents']),
					'U_ACTION_EDIT' => url('.php?edit=' . $pm_edit . '&amp;token=' . AppContext::get_session()->get_token()),
					'U_PM_BOX' => '<a href="pm.php' . '">' . $LANG['pm_box'] . '</a>'
				));

				if ($prw_convers XOR $prw)
				{
					$tpl->assign_block_vars('edit_pm.show_pm', array(
						'DATE' => Date::to_format(Date::DATE_NOW, Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
						'CONTENTS' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents))),
					));
				}

				if ($id_first == $pm_edit) //Premier message de la convers => Edition de celle-ci
				{
					$tpl->put_all(array(
						'SUBMIT_NAME' => 'convers',
						'L_TITLE' => $LANG['title'],
					));

					$tpl->assign_block_vars('edit_pm.title', array(
						'TITLE' => ($prw_convers XOR $prw) ? stripslashes($title) : stripslashes($conversation['title'])
					));
				}
				else
					$tpl->put_all(array(
						'SUBMIT_NAME' => 'edit_pm',
					));

				$tpl->display();
			}
		}
		else //Le membre a déjà lu le message on ne peux plus éditer.
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'),
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
	$tpl = new FileTemplate('user/pm.tpl');

	//On récupère les info de la conversation.
	try {
		$convers = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_TOPIC, array('id', 'title', 'user_id', 'user_id_dest', 'nbr_msg', 'last_msg_id', 'last_user_id', 'user_view_pm'), 'WHERE id = :id AND :user_id IN (user_id, user_id_dest)', array('id' => $pm_id_get, 'user_id' => $current_user->get_id()));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	//Vérification des autorisations.
	if (empty($convers['id']) || ($convers['user_id'] != $current_user->get_id() && $convers['user_id_dest'] != $current_user->get_id()))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if ($convers['user_view_pm'] > 0 && $convers['last_user_id'] != $current_user->get_id()) //Membre n'ayant pas encore lu la conversation.
	{
		$user_unread_pm = 0;
		try {
			$user_unread_pm = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'unread_pm', 'WHERE user_id=:id', array('id' => $current_user->get_id()));
		} catch (RowNotFoundException $e) {}

		if (!empty($user_unread_pm))
		{
			$unread_pm = max(($user_unread_pm - (int)$convers['user_view_pm']), 0);
			PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('unread_pm' => $unread_pm), 'WHERE user_id = :id', array('id' => $current_user->get_id()));
		}

		PersistenceContext::get_querier()->update(DB_TABLE_PM_TOPIC, array('user_view_pm' => 0), 'WHERE id = :id', array('id' => $pm_id_get));
		PersistenceContext::get_querier()->update(DB_TABLE_PM_MSG, array('view_status' => 1), 'WHERE idconvers = :id AND user_id <> :user_id', array('id' => $convers['id'], 'user_id' => $current_user->get_id()));
		SessionData::recheck_cached_data_from_user_id($current_user->get_id());
	}

	//On crée une pagination si le nombre de MP est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $convers['nbr_msg'], $_NBR_ELEMENTS_PER_PAGE);
	$pagination->set_url(new Url('/user/pm.php?id=' . $pm_id_get . '&amp;p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$tpl->assign_block_vars('pm', array(
		'C_PAGINATION' => $pagination->has_several_pages(),
		'PAGINATION' => $pagination->display(),
		'U_PM_BOX' => '<a href="pm.php' . '">' . $LANG['pm_box'] . '</a>',
		'U_TITLE_CONVERS' => '<a href="pm' . url('.php?id=' . $pm_id_get, '-0-' . $pm_id_get) . '">' . stripslashes($convers['title']) . '</a>'
	));

	$tpl->put_all(array(
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'L_RESPOND' => $LANG['respond'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_EDIT' => LangLoader::get_message('edit', 'common'),
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'L_RESET' => $LANG['reset']
	));

	//Message non lu par autre membre que user_id view_status => 0.
	//Message lu par autre membre que user_id view_status => 1.
	$is_guest_in_convers = false;
	$page = (int)retrieve(GET, 'p', 0); //Redéfinition de la variable $page pour prendre en compte les redirections.
	$quote_last_msg = ($page > 1) ? 1 : 0; //On enlève 1 au limite si on est sur une page > 1, afin de récupérer le dernier msg de la page précédente.
	$i = 0;
	$j = 0;
	$result = PersistenceContext::get_querier()->select("SELECT
		msg.id, msg.user_id, msg.timestamp, msg.view_status, msg.contents,
		m.display_name, m.level, m.email, m.show_email, m.registration_date AS registered, m.posted_msg, m.warning_percentage, m.delay_banned, m.groups,
		ext_field.user_avatar,
		s.user_id AS connect
	FROM " . DB_TABLE_PM_MSG . " msg
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = msg.user_id
	LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = msg.user_id
	LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.timestamp > :timestamp AND s.user_id <> -1
	WHERE msg.idconvers = :idconvers
	ORDER BY msg.timestamp
	LIMIT :number_items_per_page OFFSET :display_from",
		array(
			'timestamp' => (time() - SessionsConfig::load()->get_active_session_duration()),
			'idconvers' => $pm_id_get,
			'number_items_per_page' => ($_NBR_ELEMENTS_PER_PAGE + $quote_last_msg),
			'display_from' => max(($pagination->get_display_from() - $quote_last_msg), 0)
		)
	);

	while ($row = $result->fetch())
	{
		$row['user_id'] = (int)$row['user_id'];
		$is_admin = ($row['user_id'] === -1);
		if ($is_admin)
			$row['level'] = 2;

		if ( !$is_guest_in_convers )
			$is_guest_in_convers = empty($row['display_name']);

		//Avatar
		$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . $current_user->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');

		//Reprise du dernier message de la page précédente.
		$row['contents'] = ($quote_last_msg == 1 && $i == 0) ? '<span class="text-strong">' . $LANG['quote_last_msg'] . '</span><br /><br />' . $row['contents'] : $row['contents'];
		$i++;

		$group_color = User::get_group_color($row['groups'], $row['level']);

		$date = new Date($row['timestamp'],Timezone::SERVER_TIMEZONE);

		$tpl->assign_block_vars('pm.msg', array_merge(
			Date::get_array_tpl_vars($date,'date'),
			array(
			'C_CURRENT_USER_MESSAGE' => AppContext::get_current_user()->get_display_name() == $row['display_name'],
			'C_MODERATION_TOOLS' => ($row['id'] === $convers['last_msg_id']) && !$row['view_status'], //Dernier mp éditable. et si le destinataire ne la pas encore lu
			'C_VISITOR' => $is_admin,
			'C_AVATAR' => $row['user_avatar'] || ($user_accounts_config->is_default_avatar_enabled()),
			'C_GROUP_COLOR' => !empty($group_color),
			'ID' => $row['id'],
			'CONTENTS' => FormatingHelper::second_parse($row['contents']),
			'USER_AVATAR' => $user_avatar,
			'PSEUDO' => $is_admin ? $LANG['admin'] : (!empty($row['display_name']) ? $row['display_name'] : $LANG['guest']),
			'LEVEL_CLASS' => UserService::get_level_class($row['level']),
			'GROUP_COLOR' => $group_color,
			'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
			'L_LEVEL' => (($row['warning_percentage'] < '100' || (time() - $row['delay_banned']) < 0) ? UserService::get_level_lang($row['level'] !== null ? $row['level'] : '-1') : LangLoader::get_message('banned', 'user-common'))
			)
		));

		//Marqueur de suivis du sujet.
		if (!empty($row['track']))
			$track = true;
		$j++;
	}
	$result->dispose();

	//Récupération du message quoté.
	if (!empty($quote_get))
	{
		try {
			$quote_msg = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_MSG, array('user_id', 'contents'), 'WHERE id = :id', array('id' => $quote_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$pseudo = '';
		try {
			$pseudo = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'display_name', 'WHERE user_id = :id', array('id' => $quote_msg['user_id']));
		} catch (RowNotFoundException $ex) {}

		$contents = '[quote' . ($pseudo ? '=' . $pseudo : '') . ']' . FormatingHelper::unparse(stripslashes($quote_msg['contents'])) . '[/quote]';
	}
	else
		$contents = '';

	if ($convers['user_id'] > 0 && !$is_guest_in_convers)
	{
		$tpl->put_all(array(
			'KERNEL_EDITOR' => $editor->display(),
		));

		$tpl->assign_block_vars('post_pm', array(
			'CONTENTS' => $contents,
			'U_PM_ACTION_POST' => url('.php?id=' . $pm_id_get, '-0-' . $pm_id_get)
		));

		//Gestion des erreurs
		$get_error = retrieve(GET, 'error', '');
		switch ($get_error)
		{
			case 'e_incomplete':
				$errstr = $LANG['e_incomplete'];
				$type = MessageHelper::NOTICE;
				break;
			case 'e_pm_del':
				$errstr = $LANG['e_pm_del'];
				$type = MessageHelper::WARNING;
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$tpl->put('message_helper', MessageHelper::display($errstr, $type));
	}

	$tpl->display();
}
else //Liste des conversation, dans la boite du membre.
{
	$tpl = new FileTemplate('user/pm.tpl');

	$nbr_pm = PrivateMsg::count_conversations($current_user->get_id());

	//On crée une pagination si le nombre de MP est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_pm, $_NBR_ELEMENTS_PER_PAGE);
	$pagination->set_url(new Url('/user/pm.php?p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$limit_group = $current_user->check_max_value(PM_GROUP_LIMIT, $user_accounts_config->get_max_private_messages_number());
	$unlimited_pm = $current_user->check_level(User::MODERATOR_LEVEL) || ($limit_group === -1);
	$pm_max = $unlimited_pm ? $LANG['illimited'] : $limit_group;

	$tpl->assign_block_vars('convers', array(
		'C_PAGINATION' => $pagination->has_several_pages(),
		'PAGINATION' => $pagination->display(),
		'NBR_PM' => $nbr_pm,
		'PM_POURCENT' => '<strong>' . $nbr_pm . '</strong> / <strong>' . $pm_max . '</strong>',
		'U_MARK_AS_READ' => 'pm.php?read=1',
		'L_MARK_AS_READ' => $LANG['mark_pm_as_read'],
		'U_USER_ACTION_PM' => url('.php?del_convers=1&amp;p=' . $page . '&amp;token=' . AppContext::get_session()->get_token()),
		'U_PM_BOX' => '<a href="pm.php' . '">' . $LANG['pm_box'] . '</a>',
		'U_POST_NEW_CONVERS' => 'pm' . url('.php?post=1', ''),
		'L_POST_NEW_CONVERS' => $LANG['post_new_convers']
	));

	//Aucun message privé.
	if ($nbr_pm == 0)
	{
		$tpl->assign_block_vars('convers.no_pm', array(
			'L_NO_PM' => LangLoader::get_message('no_item_now', 'common')
		));
	}
	$nbr_waiting_pm = 0;
	if (!$unlimited_pm && $nbr_pm > $limit_group)
	{
		$nbr_waiting_pm = $nbr_pm - $limit_group; //Nombre de messages privés non visibles.
		//Gestion erreur.
		if ($nbr_waiting_pm > 0)
		{
			$tpl->put('message_helper', MessageHelper::display(sprintf($LANG['e_pm_full'], $nbr_waiting_pm), MessageHelper::WARNING));
		}
	}

	$tpl->put_all(array(
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
		'L_PRIVATE_MSG' => $LANG['private_message'],
		'L_PM_BOX' => $LANG['select_all_messages'],
		'L_SELECT_ALL_MESSAGES' => $LANG['pm_box'],
		'L_TITLE' => $LANG['title'],
		'L_PARTICIPANTS' => $LANG['participants'],
		'L_MESSAGE' => $LANG['replies'],
		'L_LAST_MESSAGE' => $LANG['last_message'],
		'L_STATUS' => $LANG['status'],
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'L_READ' => $LANG['read'],
		'L_TRACK' => $LANG['pm_track'],
		'L_NOT_READ' => $LANG['not_read']
	));

	//Conversation présente chez les deux membres: user_convers_status => 0.
	//Conversation supprimée chez l'expediteur: user_convers_status => 1.
	//Conversation supprimée chez le destinataire: user_convers_status => 2.
	$i = 0;
	$j = 0;
	$result = PersistenceContext::get_querier()->select("SELECT
		pm.id, pm.title, pm.user_id, pm.user_id_dest, pm.user_convers_status, pm.nbr_msg, pm.last_user_id, pm.last_msg_id, pm.last_timestamp,
		msg.view_status,
		m.display_name AS login, m.level AS level, m.groups AS user_groups,
		m1.display_name AS login_dest,  m1.level AS dest_level, m1.groups AS dest_groups,
		m2.display_name AS last_login, m2.level AS last_level, m2.groups AS last_groups
	FROM " . DB_TABLE_PM_TOPIC . "  pm
	LEFT JOIN " . DB_TABLE_PM_MSG . " msg ON msg.id = pm.last_msg_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = pm.user_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m1 ON m1.user_id = pm.user_id_dest
	LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = pm.last_user_id
	WHERE
	(
		:user_id IN (pm.user_id, pm.user_id_dest)
	)
	AND
	(
		pm.user_convers_status = 0
		OR
		(
			(pm.user_id_dest = :user_id AND pm.user_convers_status = 1)
			OR
			(pm.user_id = :user_id AND pm.user_convers_status = 2)
		)
	)
	ORDER BY pm.last_timestamp DESC
	LIMIT :number_items_per_page OFFSET :display_from",
		array(
			'user_id' => $current_user->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)
	);
	while ($row = $result->fetch())
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
		if ($row['last_user_id'] == $current_user->get_id()) //Le membre est le dernier posteur.
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

		$announce = 'message-announce';
		//Vérifications des messages Lu/non Lus.
		if ($view === false) //Nouveau message (non lu).
			$announce = $announce . '-new';
		if ($track === true) //Marqueur de reception du message
			$announce = $announce . '-track';

		//Ancre vers vers le dernier message posté.
		$last_page = ceil( $row['nbr_msg'] / $_NBR_ELEMENTS_PER_PAGE);
		$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
		$last_page = ($last_page > 1) ? 'p=' . $last_page . '&amp;' : '';

		$group_color = User::get_group_color($row['user_groups'], $row['level']);

		if ($row['user_id'] == -1)
			$author = $LANG['admin'];
		elseif (!empty($row['login']))
			$author = '<a href="' . UserUrlBuilder::profile($row['user_id'])->rel() . '" class="'.UserService::get_level_class($row['level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['login'] . '</a>';
		else
			$author = '<strike>' . $LANG['guest'] . '</strike>';

		$participants = ($row['login_dest'] != $current_user->get_display_name()) ? $row['login_dest'] : $author;
		$user_id_dest = $row['user_id_dest'] != $current_user->get_id() ? $row['user_id_dest'] : $row['user_id'];
		$participants_group_color = ($participants != $LANG['admin'] && $participants != '<strike>' . $LANG['guest'] . '</strike>') ? User::get_group_color($row['dest_groups'], $row['dest_level']) : '';

		switch ($author)
		{
			case $LANG['admin']:
				$participants_level_class = UserService::get_level_class(User::ADMIN_LEVEL);
				break;

			case '<strike>' . $LANG['guest'] . '</strike>':
				$participants_level_class = '';
				break;

			default:
				$participants_level_class = UserService::get_level_class($row['dest_level']);
				break;
		}

		$participants = !empty($participants) ? '<a href="' . UserUrlBuilder::profile($user_id_dest)->rel() . '" class="' . $participants_level_class . '"' . (!empty($participants_group_color) ? ' style="color:' . $participants_group_color . '"' : '') . '>' . $participants . '</a>' : '<strike>' . $LANG['admin']. '</strike>';

		//Affichage du dernier message posté.
		$last_group_color = User::get_group_color($row['last_groups'], $row['last_level']);
		$last_msg = '<a href="pm' . url('.php?' . $last_page . 'id=' . $row['id'], '-0-' . $row['id'] . $last_page_rewrite) . '#m' . $row['last_msg_id'] . '" class="far fa-hand-point-right"></a>' . ' ' . $LANG['on'] . ' ' . Date::to_format($row['last_timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . '<br />';
		$last_msg .= ($row['user_id'] == -1) ? $LANG['by'] . ' ' . $LANG['admin'] : $LANG['by'] . ' <a href="' . UserUrlBuilder::profile($row['last_user_id'])->rel() . '" class="small '.UserService::get_level_class($row['last_level']).'"' . (!empty($last_group_color) ? ' style="color:' . $last_group_color . '"' : '') . '>' . $row['last_login'] . '</a>';

		$tpl->assign_block_vars('convers.list', array(
			'INCR' => $i,
			'ID' => $row['id'],
			'ANNOUNCE' => $announce,
			'TITLE' => stripslashes($row['title']),
			'MSG' => ($row['nbr_msg'] - 1),
			'U_PARTICIPANTS' => (($row['user_convers_status'] != 0) ? '<strike>' . $participants . '</strike>' : $participants),
			'U_CONVERS'	=> url('.php?id=' . $row['id'], '-0-' . $row['id']),
			'U_AUTHOR' => $LANG['by'] . ' ' . $author,
			'U_LAST_MSG' => $last_msg
		));
		$i++;
	}
	$result->dispose();

	$tpl->display();
}

include('../kernel/footer.php');

?>
