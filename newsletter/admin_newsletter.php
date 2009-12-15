<?php
/*##################################################
 *                               admin_newsletter.php
 *                            -------------------
 *   begin                : July 10, 2006
 *   copyright            : (C) 2006 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('newsletter'); //Chargement de la langue du module.
define('TITLE', $LANG['newsletter']);
require_once('../admin/admin_header.php');

//On recupère les variables.
$type = retrieve(GET, 'type', '', TSTRING_UNCHANGE);
$send = retrieve(POST, 'send', false);
$send_test = retrieve(POST, 'send_test', false);
$mail_contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
$mail_object = trim(retrieve(POST, 'title', '', TSTRING_UNCHANGE));
$member_list = retrieve(GET, 'member_list', false);
$del_member = retrieve(GET, 'del_member', 0);

$Template->set_filenames(array(
	'admin_newsletter'=> 'newsletter/admin_newsletter.tpl'
));

$Template->assign_vars(array(
	'L_NEWSLETTER' => $LANG['newsletter'],
	'L_SEND_NEWSLETTER' => $LANG['send_newsletter'],
	'L_CONFIG_NEWSLETTER' => $LANG['newsletter_config'],
	'L_USER_LIST' => $LANG['newsletter_member_list']
));

$Cache->load('newsletter');

//Liste des membres
if ($member_list)
{
	$Template->assign_block_vars('member_list', array());

	if ($del_member > 0)
	{
		$member_mail = $Sql->query("SELECT mail FROM " . PREFIX . "newsletter WHERE id = '" . $del_member . "'", __LINE__, __FILE__);
		if (!empty($member_mail))
		{
			$Sql->query_inject("DELETE FROM " . PREFIX . "newsletter WHERE id = '" . $del_member . "'", __LINE__, __FILE__);
			$Errorh->handler(sprintf($LANG['newsletter_del_member_success'], $member_mail), E_USER_NOTICE);
		}
		else
			$Errorh->handler($LANG['newsletter_member_does_not_exists'], E_USER_WARNING);
	}
	$result = $Sql->query_while ("SELECT id, mail FROM " . PREFIX . "newsletter ORDER by id", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
		$Template->assign_block_vars('member_list.line', array(
			'MAIL' => $row['mail'],
			'U_DELETE' => url('admin_newsletter.php?member_list=1&amp;del_member=' . $row['id'])
		));
}
//Si on envoie avec un certain type
elseif (!empty($type) && $send && !$send_test && !empty($mail_object) && !empty($mail_contents))
{
	$nbr = $Sql->count_table(PREFIX . 'newsletter', __LINE__, __FILE__);

	switch ($type)
	{
		case 'html':
			$error_mailing_list = NewsletterService::send_html($mail_object, $mail_contents);
			break;
		case 'bbcode':
			$error_mailing_list = NewsletterService::send_bbcode($mail_object, $mail_contents);
			break;
		default:
			$type = 'text';
			$error_mailing_list = NewsletterService::send_text($mail_object, $mail_contents);
	}

	//On envoie une confirmation
	$Template->assign_block_vars('end', array());
	$Template->assign_vars(array(
		'L_ARCHIVES' => $LANG['newsletter_go_to_archives'],
		'L_BACK' => $LANG['newsletter_back'],
		'L_NEWSLETTER' => $LANG['newsletter'],
	));

	if (count($error_mailing_list) == 0) //Aucune erreur
		$Errorh->handler($LANG['newsletter_sent_successful'], E_USER_NOTICE);
	else
		$Errorh->handler(sprintf($LANG['newsletter_error_list'], implode(', ', $error_mailing_list)), E_USER_NOTICE);
}
elseif (!empty($type)) //Rédaction
{
	if ($type == 'bbcode')
	{
		$Template->assign_vars(array(
			'KERNEL_EDITOR' => display_editor()
		));
	}
	else
	{
		$type = ($type == 'html') ? 'html' : 'text';
	}

	$nbr = $Sql->count_table(PREFIX . "newsletter", __LINE__, __FILE__);

	$Template->assign_block_vars('write', array(
		'TYPE' => $type,
		'SUBSCRIBE_LINK' => ($type == 'html') ? $LANG['newsletter_subscribe_link'] : '',
		'NBR_SUBSCRIBERS' => $nbr,
		'MESSAGE' => stripslashes($mail_contents),
		'TITLE' => $mail_object,
		'PREVIEW_BUTTON' => $type == 'bbcode' ? '<input value="' . $LANG['preview'] . '" onclick="XMLHttpRequest_preview();" class="submit" type="button">' : ''
	));
	$Template->assign_vars(array(
		'L_WRITE_TYPE' => $LANG['newsletter_write_type'],
		'L_TITLE' => $LANG['title'],
		'L_MESSAGE' => $LANG['message'],
		'L_SEND' => $LANG['newsletter_send'],
		'L_NEWSLETTER_TEST' => $LANG['newsletter_test'],
		'L_NBR_SUBSCRIBERS' => $LANG['newsletter_nbr_subscribers'],
	));

	if ($type == 'bbcode')
		$Template->assign_block_vars('write.bbcode_explain', array(
			'L_WARNING' => $LANG['newsletter_bbcode_warning']
		));

	if (empty($mail_object) && $send_test)
		$Errorh->handler($LANG['require_title'], E_USER_WARNING);
	elseif (empty($mail_contents) && $send_test)
		$Errorh->handler($LANG['require_text'], E_USER_WARNING);
	elseif ($send_test) //Si on doit envoyer un test
	{
		switch ($type)
		{
			case 'html':
				NewsletterService::send_html($mail_object, $mail_contents, $User->get_attribute('user_mail'));
				break;
			case 'bbcode':
				NewsletterService::send_bbcode($mail_object, $mail_contents, $User->get_attribute('user_mail'));
				break;
			default:
				NewsletterService::send_text($mail_object, $mail_contents, $User->get_attribute('user_mail'));
			break;
		}
		$Errorh->handler(sprintf($LANG['newsletter_test_sent'], $User->get_attribute('user_mail')), E_USER_NOTICE);
	}
}
//On fait choisir un type
else
{
	$Template->assign_block_vars('select_type', array(
		'L_SELECT_TYPE' => $LANG['newsletter_select_type'],
		'L_SELECT_TYPE_TEXT' => $LANG['newsletter_select_type_text'],
		'L_SELECT_TYPE_EXPLAIN_TEXT' => $LANG['newsletter_select_type_text_explain'],
		'L_SELECT_TYPE_BBCODE' => $LANG['select_type_bbcode'],
		'L_SELECT_TYPE_EXPLAIN_BBCODE' => $LANG['newsletter_select_type_bbcode_explain'],
		'L_SELECT_TYPE_HTML' => $LANG['select_type_html'],
		'L_SELECT_TYPE_EXPLAIN_HTML' => $LANG['newsletter_select_type_html_explain'],
		'L_NEXT' => $LANG['next']
	));
}

$Template->assign_vars(array(
	'L_REQUIRE_TITLE' => $LANG['require_title'],
	'L_REQUIRE_TEXT' => $LANG['require_text'],
	'L_REQUIRE_MAIL' => $LANG['require_mail'],
	'L_CONFIRM_DELETE' => addslashes($LANG['newsletter_confirm_delete_user']),
	'L_MAIL' => $LANG['newsletter_email_address'],
	'L_DELETE' => $LANG['delete']
));

$Template->pparse('admin_newsletter');


require_once('../admin/admin_footer.php');

?>