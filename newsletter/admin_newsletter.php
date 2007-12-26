<?php
/*##################################################
 *                               admin_newsletter.php
 *                            -------------------
 *   begin                : July 10, 2006
 *   copyright          : (C) 2006 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *  
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

include_once('../includes/admin_begin.php');
include_once('../newsletter/lang/' . $CONFIG['lang'] . '/newsletter_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
define('TITLE', $LANG['newsletter']);
include_once('../includes/admin_header.php');

//On recupère les variables.
$type = !empty($_GET['type']) ? trim($_GET['type']) : '' ;
$send = !empty($_POST['send']) ? true : false ;
$send_test = !empty($_POST['send_test']) ? true : false ;
$mail_contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
$mail_object = !empty($_POST['title']) ? trim($_POST['title']) : '';
$mail_object = get_magic_quotes_gpc() ? addslashes($mail_object) : $mail_object;
$member_list = !empty($_GET['member_list']) ? true : false;
$del_member = !empty($_GET['del_member']) ? numeric($_GET['del_member']) : 0;

$template->set_filenames(array(
	'admin_newsletter' => '../templates/' . $CONFIG['theme'] . '/newsletter/admin_newsletter.tpl'
));	

$template->assign_vars(array(
	'L_NEWSLETTER' => $LANG['newsletter'],
	'L_SEND_NEWSLETTER' => $LANG['send_newsletter'],
	'L_CONFIG_NEWSLETTER' => $LANG['newsletter_config'],
	'L_MEMBER_LIST' => $LANG['newsletter_member_list']
));

$cache->load_file('newsletter');
include('newsletter.class.php');
$newsletter_sender = new Newsletter_sender($sql->req);

//Liste des membres
if( $member_list )
{
	$template->assign_block_vars('member_list', array());
	
	if( $del_member > 0 )
	{
		$member_mail = $sql->query("SELECT mail FROM ".PREFIX."newsletter WHERE id = '" . $del_member . "'", __LINE__, __FILE__);
		if( !empty($member_mail) )
		{
			$sql->query_inject("DELETE FROM ".PREFIX."newsletter WHERE id = '" . $del_member . "'", __LINE__, __FILE__);
			$errorh->error_handler(sprintf($LANG['newsletter_del_member_success'], $member_mail), E_USER_NOTICE, '', '', 'member_list.');
		}
		else
			$errorh->error_handler($LANG['newsletter_member_does_not_exists'], E_USER_WARNING, '', '', 'member_list.');
	}
	$result = $sql->query_while("SELECT id, mail FROM ".PREFIX."newsletter ORDER by id", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
		$template->assign_block_vars('member_list.line', array(
			'MAIL' => $row['mail'],
			'U_DELETE' => transid('admin_newsletter.php?member_list=1&amp;del_member=' . $row['id'])
		));
}
//Si on envoie avec un certain type
elseif( !empty($type) && $send && !$send_test && !empty($mail_object) && !empty($mail_contents) )
{
	$nbr = $sql->count_table('newsletter', __LINE__, __FILE__);
	
	switch($type)
	{
		case 'html':
			$error_mailing_list = $newsletter_sender->send_html($mail_object, $mail_contents);
			break;
		case 'bbcode':
			$error_mailing_list = $newsletter_sender->send_bbcode($mail_object, $mail_contents);
			break;
		default:
			$type = 'text';
			$error_mailing_list = $newsletter_sender->send_text($mail_object, $mail_contents);
	}
	
	//On envoie une confirmation
	$template->assign_block_vars('end', array());
	$template->assign_vars(array(		
		'L_ARCHIVES' => $LANG['newsletter_go_to_archives'],
		'L_BACK' => $LANG['newsletter_back'],
		'L_NEWSLETTER' => $LANG['newsletter'],
	));
	
	if( count($error_mailing_list) == 0 ) //Aucune erreur
		$errorh->error_handler($LANG['newsletter_sent_successful'], E_USER_NOTICE, '', '', 'end.');
	else
		$errorh->error_handler(sprintf($LANG['newsletter_error_list'], implode(', ', $error_mailing_list)), E_USER_NOTICE, '', '', 'end.');
}
elseif( !empty($type) ) //Rédaction
{
	if( $type == 'bbcode' )
	{
		include_once('../includes/bbcode.php');
		$template->assign_var_from_handle('BBCODE', 'bbcode');
	}
	else
	{
		$type = ($type == 'html') ? 'html' : 'text';
	}
	
	$nbr = $sql->count_table("newsletter", __LINE__, __FILE__);	
		
	$template->assign_block_vars('write', array(
		'TYPE' => $type,
		'SUBSCRIBE_LINK' => ($type == 'html') ? $LANG['newsletter_subscribe_link'] : '',
		'NBR_SUBSCRIBERS' => $nbr,
		'MESSAGE' => stripslashes($mail_contents),
		'TITLE' => $mail_object,
		'PREVIEW_BUTTON' => $type == 'bbcode' ? '<input value="' . $LANG['preview'] . '" onclick="XMLHttpRequest_preview(this.form);" class="submit" type="button">' : ''
	));
	$template->assign_vars(array(
		'L_WRITE_TYPE' => $LANG['newsletter_write_type'],
		'L_TITLE' => $LANG['title'],
		'L_MESSAGE' => $LANG['message'],
		'L_SEND' => $LANG['newsletter_send'],
		'L_NEWSLETTER_TEST' => $LANG['newsletter_test'],
		'L_NBR_SUBSCRIBERS' => $LANG['newsletter_nbr_subscribers'],
	));
	
	if( $type == 'bbcode' )
		$template->assign_block_vars('write.bbcode_explain', array(
			'L_WARNING' => $LANG['newsletter_bbcode_warning']
		));
	
	if( empty($mail_object) && $send_test )
		$errorh->error_handler($LANG['require_title'], E_USER_WARNING, '', '', 'write.');
	elseif( empty($mail_contents) && $send_test )
		$errorh->error_handler($LANG['require_text'], E_USER_WARNING, '', '', 'write.');
	elseif( $send_test ) //Si on doit envoyer un test
	{
		switch($type)
		{
			case 'html':
				$newsletter_sender->send_html($mail_object, $mail_contents, $session->data['user_mail']);
				break;
			case 'bbcode':
				$newsletter_sender->send_bbcode($mail_object, $mail_contents, $session->data['user_mail']);
				break;
			default:
				$newsletter_sender->send_text($mail_object, $mail_contents, $session->data['user_mail']);
			break;
		}
		$errorh->error_handler(sprintf($LANG['newsletter_test_sent'], $session->data['user_mail']), E_USER_NOTICE, '', '', 'write.');
	}
}
//On fait choisir un type
else
{
	$template->assign_block_vars('select_type', array(
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

$template->assign_vars(array(
	'L_REQUIRE_TITLE' => $LANG['require_title'],
	'L_REQUIRE_TEXT' => $LANG['require_text'],
	'L_REQUIRE_MAIL' => $LANG['require_mail'],
	'L_CONFIRM_DELETE' => addslashes($LANG['newsletter_confirm_delete_user']),
	'L_MAIL' => $LANG['newsletter_email_address'],
	'L_DELETE' => $LANG['delete']
));

$template->pparse('admin_newsletter'); 


include_once('../includes/admin_footer.php');

?>