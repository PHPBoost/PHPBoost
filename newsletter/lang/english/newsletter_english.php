<?php
/*##################################################
 *                             newsletter_english.php
 *                            -------------------
 *   begin                : July 11 2006
 *   copyright          : (C) 2006 ben.popeye
 *   email                : ben.popeye@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/


####################################################
#                                                           English                                                                             #
####################################################

//Admin
$LANG['newsletter'] = 'Newsletter';
$LANG['newsletter_select_type'] = 'You have to choose a message type';
$LANG['newsletter_select_type_text'] = 'Simple text';
$LANG['newsletter_select_type_text_explain'] = '<span style="color:green;"><strong>For all</strong></span><br />You can\'t format your text.';
$LANG['newsletter_select_type_bbcode_explain'] = '<span style="color:green;"><strong>For all</strong></span><br />You can format your message with the BBCode language, used everywhere on PHPBoost.';
$LANG['newsletter_select_type_html_explain'] = '<span style="color:red;"><strong>Experienced users only</strong></span><br />You can format you want at your message.';
$LANG['newsletter_write_type'] = 'Write a newsletter';
$LANG['newsletter_unscubscribe_text'] = 'To unsubscribe to the newsletter, please click here.';
$LANG['newsletter_mail_from'] = 'E-mail address which sends newsletter';
$LANG['newsletter_send'] = 'Send';
$LANG['newsletter_error'] = 'The newsletter couldn\'t be sent to the following user: ';
$LANG['newsletter_go_to_archives'] = 'Click here to see the newsletter archives.';
$LANG['newsletter_subscribe_link'] = 'Don\'t forget to put <em>[UNSUBSCRIBE_LINK]</em> into your message where you want to place a link for user to unsubscribe. It\'s important to put it into your message to respect user\'s freedom on the internet.';
$LANG['newsletter_back'] = 'Come back to the newsletter.';
$LANG['newsletter_confirm'] = 'The newsletter was successfully sent .';
$LANG['newsletter_nbr_subscribers'] = 'Number of subscribers:';
$LANG['newsletter_test'] = 'Send me a test';
$LANG['newsletter_sent_successful'] = 'Your newsletter has been sent to all subscribers!';
$LANG['send_newsletter'] = 'Send a newsletter';
$LANG['newsletter_member_list'] = 'Member list';
$LANG['newsletter_test_sent'] = 'A trial newsletter has been sent to the mail address %s, you will have a preview of your newsletter.';
$LANG['newsletter_bbcode_warning'] = 'When you send a newsletter written in BBCode, this language will be transformed into HTML. But some tags are not defined in mail providers, we advice you to preview your newsletter by sending a test.';

//Newsletter
$LANG['newsletter'] = 'Newsletter';
$LANG['subscribe'] = 'Subscribe';
$LANG['unsubscribe'] = 'Unsubscribe';
$LANG['newsletter_add_success'] = 'Your email address has been added successfully in the list.';
$LANG['newsletter_add_failure'] = 'Error: Your email address is already in the list.';
$LANG['newsletter_del_success'] = 'Your email address has been deleted successfully from the list.';
$LANG['newsletter_del_failure'] = 'Error: Your email address isn\'t in the list.';
$LANG['newsletter_msg_html'] = 'That message has been sent in HTML language, click here to see it.';
$LANG['newsletter_nbr'] = 'Number of subscribers : %d';
$LANG['newsletter_no_archives'] = 'No archive available';
$LANG['newsletter_archives'] = 'Newsletter archives';
$LANG['newsletter_archives_explain'] = 'You will find here the precedent newsletters.<br />
To receive them automatically please register to the mailing list.';
$LANG['newsletter_email_address_is_not_valid'] = 'The email address you have given hasn\'t the good syntax. Please try again';
$LANG['newsletter_error_list'] = 'The newsletter hasn\'t been sent to following addresses : <em>%s</em>';
$LANG['archives'] = 'Archives';

//Config
$LANG['newsletter_config'] = 'Newsletter configuration';
$LANG['newsletter_sender_mail'] = 'Sending address';
$LANG['newsletter_name'] = 'Newsletter name';
$LANG['newsletter_confirm_delete_user'] = 'Are you sure you want to remove this member from the mailing list ?';
$LANG['newsletter_email_address'] = 'Email address';
$LANG['newsletter_del_member_success'] = 'The email address %s has been successfully removed from the list';
$LANG['newsletter_member_does_not_exists'] = 'The email address you want to delete doesn\'t exist';
?>
