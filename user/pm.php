<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 14
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

// Forbidden to visitors.
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

// Mark PM as read
if ($read)
{
	$nbr_pm = PrivateMsg::count_conversations($current_user->get_id());
	$max_pm_number = $user_accounts_config->get_max_private_messages_number();
	$limit_group = $current_user->check_max_value(PM_GROUP_LIMIT, $max_pm_number);
	$unlimited_pm = $current_user->check_level(User::MODERATOR_LEVEL) || ($limit_group === -1);

	$nbr_waiting_pm = 0;
	if (!$unlimited_pm && $nbr_pm > $limit_group)
		$nbr_waiting_pm = $nbr_pm - $limit_group; // Hidden PM number.

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
		// Skip the iteration if the limit is exceeded.
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

if ($convers && empty($pm_edit) && empty($pm_del)) // Sending conversation.
{
	$title = retrieve(POST, 'title', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
	$login = retrieve(POST, 'login', '');

	$limit_group = $current_user->check_max_value(PM_GROUP_LIMIT, $user_accounts_config->get_max_private_messages_number());
	// Checking sender email
	if (PrivateMsg::count_conversations($current_user->get_id()) >= $limit_group && (!$current_user->check_level(User::MODERATOR_LEVEL) && !($limit_group === -1))) // Sender email full.
		AppContext::get_response()->redirect('/user/pm' . url('.php?post=1&error=e_pm_full_post', '', '&') . '#message_helper');

	if (!empty($title) && !empty($contents) && !empty($login))
	{
		// Trying to retrieve user_id, if user didn't click once Ajax research is over
		$user_id_dest = 0;
		try {
			$user_id_dest = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'user_id', 'WHERE display_name = :name', array('name' => stripslashes($login)));
		} catch (RowNotFoundException $ex) {}

		if (!empty($user_id_dest) && $user_id_dest != $current_user->get_id())
		{
			$contents = FormatingHelper::strparse($contents, array(), false);

			// Sending email, Checking if email box is full => error
			list($pm_convers_id, $pm_msg_id) = PrivateMsg::start_conversation($user_id_dest, $title, $contents, $current_user->get_id());

			// Sending email if recipient checked the option
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

			// Redirect to conversation.
			AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm_convers_id, '-0-' . $pm_convers_id, '&') . '#m' . $pm_msg_id);
		}
		else // Recipient not found.
			AppContext::get_response()->redirect('/user/pm' . url('.php?post=1&error=e_unexist_user', '', '&') . '#message_helper');
	}
	else // Missing fields.
		AppContext::get_response()->redirect('/user/pm' . url('.php?post=1&error=e_incomplete', '', '&') . '#message_helper');
}
elseif (!empty($post) || (!empty($pm_get) && $pm_get != $current_user->get_id()) && $pm_get > '0') // Message form interface
{
	$Bread_crumb->add($LANG['post_new_convers'], '');

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
		// Errors management
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
elseif ($prw_convers && empty($mp_edit)) // Conversation preview.
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
elseif ($prw && empty($pm_edit) && empty($pm_del)) // Message preview
{
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);

	// Retrieving conversation infos.
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
elseif ($pm_post && !empty($pm_id_get) && empty($pm_edit) && empty($pm_del)) // Sending messages.
{
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
	if (!empty($contents))
	{
		// user_view_pm => number of messages unread by one of both user

		// Retrieving conversation infos.
		try {
			$convers = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_TOPIC, array('title', 'user_id', 'user_id_dest', 'user_convers_status', 'nbr_msg', 'user_view_pm', 'last_user_id'), 'WHERE id = :id', array('id' => $pm_id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		// Retrieving recipient id
		$user_id_dest = ($convers['user_id_dest'] == $current_user->get_id()) ? $convers['user_id'] : $convers['user_id_dest'];

		if ($convers['user_convers_status'] == '0' && $user_id_dest > '0') // Checking if conversation is not deleted by recipient, and if it's not an automatic message from the site
		{
			// Read by sender but not by recipient => 1
			// Read by recipient but not by sender => 2
			if ($convers['user_id'] == $current_user->get_id()) // User is creator of the conversation.
				$status = 1;
			elseif ($convers['user_id_dest'] == $current_user->get_id()) // User is recipient of the conversation.
				$status = 2;

			$contents = FormatingHelper::strparse($contents, array(), false);

			// Sending PM
			$pm_msg_id = PrivateMsg::send($user_id_dest, $pm_id_get, $contents, $current_user->get_id(), $status);

			// Define redirect page.
			$last_page = ceil( ($convers['nbr_msg'] + 1) / 25);
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? '&p=' . $last_page : '';

			// Sending email if recipient checked the option
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
		else // The recipient has deleted the conversation.
			AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm_id_get . '&error=e_pm_del', '-0-' . $pm_id_get . '-0&error=e_pm_del', '&') . '#message_helper');
	}
	else // Missing fields
		AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm_id_get . '&error=e_incomplete', '-0-' . $pm_id_get . '-0&error=e_incomplete', '&') . '#message_helper');
}
elseif ($pm_del_convers) // Conversation removal.
{
	AppContext::get_session()->csrf_get_protect(); // csrf protection

	// Conversation exists for both users: user_convers_status => 0.
	// Conversation deleted on sender side: user_convers_status => 1.
	// Conversation deleted on recipient side: user_convers_status => 2.
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
			if ($row['user_id'] == $current_user->get_id()) // Sender
			{
				$expd = true;
				if ($row['user_convers_status'] == 2)
					$del_convers = true;
			}
			elseif ($row['user_id_dest'] == $current_user->get_id()) // Recipient
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

			// Deleting the message if the recipient hasn't read yet
			if ($view_status != '1')
			{
				$id_msg = 0;
				try {
					$id_msg = PersistenceContext::get_querier()->get_column_value(DB_TABLE_PM_MSG, 'MIN(id)', 'WHERE idconvers = :id', array('id' => $row['id']));
				} catch (RowNotFoundException $ex) {}

				if ($id_msg) // Deleting message
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
elseif (!empty($pm_del)) // Deleting message if recipient hasn't read yet
{
	AppContext::get_session()->csrf_get_protect(); // csrf protection

	try {
		$pm = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_MSG, array('idconvers', 'contents', 'view_status'), 'WHERE id = :id AND user_id = :user_id', array('id' => $pm_del, 'user_id' => $current_user->get_id()));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	if (!empty($pm['idconvers'])) // Check if message belong to the user
	{
		// Retrieving conversation infos
		try {
			$convers = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_TOPIC, array('title', 'user_id', 'user_id_dest', 'last_msg_id'), 'WHERE id = :id', array('id' => $pm['idconvers']));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		if ($pm_del == $convers['last_msg_id']) // Edit the last message
		{
			if ($convers['user_id'] == $current_user->get_id()) // Sender
			{
				$expd = true;
				$pm_to = $convers['user_id_dest'];
			}
			elseif ($convers['user_id_dest'] == $current_user->get_id()) // Recipient
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

				if ($pm_del > $id_first) // Deleting message.
				{
					$pm_last_msg = PrivateMsg::delete($pm_to, $pm_del, $pm['idconvers']);
					AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm['idconvers'], '-0-' . $pm['idconvers'], '&') . '#m' . $pm_last_msg);
				}
				elseif ($pm_del == $id_first) // Deleting conversation.
				{
					PrivateMsg::delete_conversation($pm_to, $pm['idconvers'], $expd, PrivateMsg::DEL_PM_CONVERS, true);
					AppContext::get_response()->redirect('/user/pm.php');
				}
			}
			else // User has already read the message, it can't be deleted anymore
			{
				$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'),
                    $LANG['e_pm_nodel']);
                DispatchManager::redirect($controller);
			}
		}
		else // Failure
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	else // Failure
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif (!empty($pm_edit)) // Edit PM, if recipient hasn't read it yet
{
	try {
		$pm = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_MSG, array('idconvers', 'contents', 'view_status'), 'WHERE id = :id AND user_id = :user_id', array('id' => $pm_edit, 'user_id' => $current_user->get_id()));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	if (!empty($pm['idconvers'])) // Check if message belongs to the user
	{
		// Retrieve the conversation infos
		try {
			$conversation = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_TOPIC, array('title', 'user_id', 'user_id_dest'), 'WHERE id = :id', array('id' => $pm['idconvers']));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$view = false;
		if ($pm['view_status'] == '1') // User has already read the message => failure
			$view = true;

		// Recipient hasn't read the message yet => edit available
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
					if ($pm_edit > $id_first) // Message update
						PersistenceContext::get_querier()->update(DB_TABLE_PM_MSG, array('contents' => $contents, 'timestamp' => time()), 'WHERE id = :id', array('id' => $pm_edit));
					else // Failure
					{
						$error_controller = PHPBoostErrors::unexisting_page();
						DispatchManager::redirect($error_controller);
					}
				}
				elseif ($convers && !empty($title)) // Conversation update, if it's first message.
				{
					if ($pm_edit == $id_first)
					{
						PersistenceContext::get_querier()->update(DB_TABLE_PM_TOPIC, array('title' => $title, 'last_timestamp' => time()), 'WHERE id = :id AND last_msg_id = :last_msg_id', array('id' => $pm['idconvers'], 'last_msg_id' => $pm_edit));
						PersistenceContext::get_querier()->update(DB_TABLE_PM_MSG, array('contents' => $contents, 'timestamp' => time()), 'WHERE id = :id', array('id' => $pm_edit));
					}
					else // Failure
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

				// Success => redirect to the conversation.
				AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm['idconvers'], '-0-' . $pm['idconvers'], '&') . '#m' . $pm_edit);
			}
			else // Edition interface
			{
				$tpl = new FileTemplate('user/pm.tpl');

				$tpl->put_all(array(
					'KERNEL_EDITOR' => $editor->display(),
					'L_REQUIRE_MESSAGE' => $LANG['require_text'],
					'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
					'L_EDIT' => LangLoader::get_message('edit', 'common'),
					'L_PRIVATE_MESSAGE' => $LANG['private_message'],
					'L_MESSAGE' => $LANG['message'],
					'L_SUBMIT' => $LANG['validate'],
					'L_PREVIEW' => $LANG['preview'],
					'L_RESET' => $LANG['reset']
				));

				$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
				$title = retrieve(POST, 'title', '', TSTRING_UNCHANGE);

				$Bread_crumb->add(LangLoader::get_message('edit', 'common'));

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

				if ($id_first == $pm_edit) // First message of the conversation => Edit
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
		else // User has already read the message => edition is disabled
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'),
                $LANG['e_pm_noedit']);
            DispatchManager::redirect($controller);
		}
	}
	else // Failure
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif (!empty($pm_id_get)) // Messages associated with the conversation.
{
	$tpl = new FileTemplate('user/pm.tpl');

	// Retrieve the conversation infos
	try {
		$convers = PersistenceContext::get_querier()->select_single_row(DB_TABLE_PM_TOPIC, array('id', 'title', 'user_id', 'user_id_dest', 'nbr_msg', 'last_msg_id', 'last_user_id', 'user_view_pm'), 'WHERE id = :id AND :user_id IN (user_id, user_id_dest)', array('id' => $pm_id_get, 'user_id' => $current_user->get_id()));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}
	$Bread_crumb->add(stripslashes($convers['title']), '');

	// Checking authorizations.
	if (empty($convers['id']) || ($convers['user_id'] != $current_user->get_id() && $convers['user_id_dest'] != $current_user->get_id()))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if ($convers['user_view_pm'] > 0 && $convers['last_user_id'] != $current_user->get_id()) // User hasn't read the conversation yet
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

	// Creating pagination if the number of PM is too high
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

	// Message not read by the other user view_status => 0.
	// Message read by the other user view_status => 1.
	$is_guest_in_convers = false;
	$page = (int)retrieve(GET, 'p', 0); // Reddefine the $page variable to take redirects into account
	$quote_last_msg = ($page > 1) ? 1 : 0; // Substracting 1 to retrieve the last message from the previous page
	$i = 0;
	$j = 0;
	$result = PersistenceContext::get_querier()->select("SELECT
		msg.id, msg.user_id, msg.timestamp, msg.view_status, msg.contents,
		m.display_name, m.level, m.email, m.show_email, m.registration_date AS registered, m.posted_msg, m.warning_percentage, m.delay_banned, m.user_groups,
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

		// Resumption of the last message from the previous page.
		$row['contents'] = ($quote_last_msg == 1 && $i == 0) ? '<span class="text-strong">' . $LANG['quote_last_msg'] . '</span><br /><br />' . $row['contents'] : $row['contents'];
		$i++;

		$group_color = User::get_group_color($row['user_groups'], $row['level']);

		$date = new Date($row['timestamp'],Timezone::SERVER_TIMEZONE);

		$tpl->assign_block_vars('pm.msg', array_merge(
			Date::get_array_tpl_vars($date,'date'),
			array(
			'C_CURRENT_USER_MESSAGE' => AppContext::get_current_user()->get_display_name() == $row['display_name'],
			'C_MODERATION_TOOLS' => ($row['id'] === $convers['last_msg_id']) && !$row['view_status'], // Last editable PM if recipient has'nt read it yet
			'C_VISITOR' => $is_admin,
			'C_AVATAR' => $row['user_avatar'] || $user_accounts_config->is_default_avatar_enabled(),
			'C_GROUP_COLOR' => !empty($group_color),
			'ID' => $row['id'],
			'CONTENTS' => FormatingHelper::second_parse($row['contents']),
			'USER_AVATAR' => $row['user_avatar'] ? Url::to_rel($row['user_avatar']) : $user_accounts_config->get_default_avatar(),
			'PSEUDO' => $is_admin ? $LANG['admin'] : (!empty($row['display_name']) ? $row['display_name'] : $LANG['guest']),
			'LEVEL_CLASS' => UserService::get_level_class($row['level']),
			'GROUP_COLOR' => $group_color,
			'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
			'L_LEVEL' => (($row['warning_percentage'] < '100' || (time() - $row['delay_banned']) < 0) ? UserService::get_level_lang($row['level'] !== null ? $row['level'] : '-1') : LangLoader::get_message('banned', 'user-common'))
			)
		));

		// Subject tracking marker
		if (!empty($row['track']))
			$track = true;
		$j++;
	}
	$result->dispose();

	// Retrieving the quoted message
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

		// Errors management
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
else // Conversation list in the user email box
{
	$tpl = new FileTemplate('user/pm.tpl');

	$nbr_pm = PrivateMsg::count_conversations($current_user->get_id());

	// Creating pagination if the number of messages is to high
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
		'C_PAGINATION'       => $pagination->has_several_pages(),

		'PAGINATION'         => $pagination->display(),
		'NBR_PM'             => $nbr_pm,
		'PM_POURCENT'        => '<strong>' . $nbr_pm . '</strong> / <strong>' . $pm_max . '</strong>',
		'L_MARK_AS_READ'     => $LANG['mark_pm_as_read'],
		'L_POST_NEW_CONVERS' => $LANG['post_new_convers'],

		'U_MARK_AS_READ'     => 'pm.php?read=1',
		'U_USER_ACTION_PM'   => url('.php?del_convers=1&amp;p=' . $page . '&amp;token=' . AppContext::get_session()->get_token()),
		'U_PM_BOX'           => '<a href="pm.php' . '">' . $LANG['pm_box'] . '</a>',
		'U_POST_NEW_CONVERS' => 'pm' . url('.php?post=1', '')
	));

	// No PM
	if ($nbr_pm == 0)
	{
		$tpl->assign_block_vars('convers.no_pm', array(
			'L_NO_PM' => LangLoader::get_message('no_item_now', 'common')
		));
	}
	$nbr_waiting_pm = 0;
	if (!$unlimited_pm && $nbr_pm > $limit_group)
	{
		$nbr_waiting_pm = $nbr_pm - $limit_group; // Hidden PM number

		// Errors management
		if ($nbr_waiting_pm > 0)
		{
			$tpl->put('message_helper', MessageHelper::display(sprintf($LANG['e_pm_full'], $nbr_waiting_pm), MessageHelper::WARNING));
		}
	}

	$tpl->put_all(array(
		'L_REQUIRE_MESSAGE'     => $LANG['require_text'],
		'L_REQUIRE_TITLE'       => $LANG['require_title'],
		'L_DELETE_MESSAGE'      => $LANG['alert_delete_msg'],
		'L_PRIVATE_MSG'         => $LANG['private_message'],
		'L_PM_BOX'              => $LANG['select_all_messages'],
		'L_SELECT_ALL_MESSAGES' => $LANG['pm_box'],
		'L_TITLE'               => $LANG['title'],
		'L_PARTICIPANTS'        => $LANG['participants'],
		'L_MESSAGE'             => $LANG['replies'],
		'L_LAST_MESSAGE'        => $LANG['last_message'],
		'L_STATUS'              => $LANG['status'],
		'L_DELETE'              => LangLoader::get_message('delete', 'common'),
		'L_READ'                => $LANG['read'],
		'L_TRACK'               => $LANG['pm_track'],
		'L_NOT_READ'            => $LANG['not_read']
	));

	// Conversation exists for both users: user_convers_status => 0.
	// Conversation deleted on sender side: user_convers_status => 1.
	// Conversation deleted on recipient side: user_convers_status => 2.
	$i = 0;
	$j = 0;
	$result = PersistenceContext::get_querier()->select("SELECT
		pm.id, pm.title, pm.user_id, pm.user_id_dest, pm.user_convers_status, pm.nbr_msg, pm.last_user_id, pm.last_msg_id, pm.last_timestamp,
		msg.view_status,
		m.display_name AS login, m.level AS level, m.user_groups AS m_user_groups,
		m1.display_name AS login_dest,  m1.level AS dest_level, m1.user_groups AS dest_groups,
		m2.display_name AS last_login, m2.level AS last_level, m2.user_groups AS last_groups
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
			'user_id'               => $current_user->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from'          => $pagination->get_display_from()
		)
	);
	while ($row = $result->fetch())
	{
		// Skip the iteration if the limit is exceeded, if it is not a private message from the system
		if ($row['user_id'] != -1)
		{
			$j++;
			if (!$unlimited_pm && ($nbr_waiting_pm - $j) >= 0)
				continue;
		}

		$view = false;
		$track = false;
		if ($row['last_user_id'] == $current_user->get_id()) // The user is the last poster
		{
			$view = true;
			if ($row['view_status'] === '0') // The recipient hasn't read the message yet
				$track = true;
		}
		else // The user isn't the last poster
		{
			if ($row['view_status'] === '1') // The recipient has already read the message
				$view = true;
		}

		$announce = 'message-announce';
		// Checking read/unread messages
		if ($view === false) // New message (not read).
			$announce = $announce . '-new';
		if ($track === true) // Message reception marker
			$announce = $announce . '-track';

		// Anchor to the last message
		$last_page = ceil( $row['nbr_msg'] / $_NBR_ELEMENTS_PER_PAGE);
		$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
		$last_page = ($last_page > 1) ? 'p=' . $last_page . '&amp;' : '';

		$group_color = User::get_group_color($row['m_user_groups'], $row['level']);

		if ($row['user_id'] == -1)
			$author = $LANG['admin'];
		elseif (!empty($row['login']))
			$author = '<a href="' . UserUrlBuilder::profile($row['user_id'])->rel() . '" class="'.UserService::get_level_class($row['level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['login'] . '</a>';
		else
			$author = '<del>' . $LANG['guest'] . '</del>';

		$participants = ($row['login_dest'] != $current_user->get_display_name()) ? $row['login_dest'] : $author;
		$user_id_dest = $row['user_id_dest'] != $current_user->get_id() ? $row['user_id_dest'] : $row['user_id'];
		$participants_group_color = ($participants != $LANG['admin'] && $participants != '<del>' . $LANG['guest'] . '</del>') ? User::get_group_color($row['dest_groups'], $row['dest_level']) : '';

		switch ($author)
		{
			case $LANG['admin']:
				$participants_level_class = UserService::get_level_class(User::ADMIN_LEVEL);
				break;

			case '<del>' . $LANG['guest'] . '</del>':
				$participants_level_class = '';
				break;

			default:
				$participants_level_class = UserService::get_level_class($row['dest_level']);
				break;
		}

		$participants = !empty($participants) ? '<a href="' . UserUrlBuilder::profile($user_id_dest)->rel() . '" class="' . $participants_level_class . '"' . (!empty($participants_group_color) ? ' style="color:' . $participants_group_color . '"' : '') . '>' . $participants . '</a>' : '<del>' . $LANG['admin']. '</del>';

		// Display of last message
		$last_group_color = User::get_group_color($row['last_groups'], $row['last_level']);
		$last_msg = '<a href="pm' . url('.php?' . $last_page . 'id=' . $row['id'], '-0-' . $row['id'] . $last_page_rewrite) . '#m' . $row['last_msg_id'] . '" class="far fa-hand-point-right"></a>' . ' ' . $LANG['on'] . ' ' . Date::to_format($row['last_timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . '<br />';
		$last_msg .= ($row['user_id'] == -1) ? $LANG['by'] . ' ' . $LANG['admin'] : $LANG['by'] . ' <a href="' . UserUrlBuilder::profile($row['last_user_id'])->rel() . '" class="small '.UserService::get_level_class($row['last_level']).'"' . (!empty($last_group_color) ? ' style="color:' . $last_group_color . '"' : '') . '>' . $row['last_login'] . '</a>';

		$tpl->assign_block_vars('convers.list', array(
			'INCR'           => $i,
			'ID'             => $row['id'],
			'ANNOUNCE'       => $announce,
			'TITLE'          => stripslashes($row['title']),
			'MSG'            => ($row['nbr_msg'] - 1),
			'U_PARTICIPANTS' => (($row['user_convers_status'] != 0) ? '<del>' . $participants . '</del>' : $participants),
			'U_CONVERS'	     => url('.php?id=' . $row['id'], '-0-' . $row['id']),
			'U_AUTHOR'       => $LANG['by'] . ' ' . $author,
			'U_LAST_MSG'     => $last_msg
		));
		$i++;
	}
	$result->dispose();

	$tpl->display();
}

include('../kernel/footer.php');

?>
