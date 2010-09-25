<?php
/*##################################################
 *                                 errors.php
 *                            -------------------
 *   begin                : June 27, 2006
 *   last modified		: October 3rd 2009 - JMNaylor
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : mickaelhemri@gmail.com
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


 ####################################################
#                                                           English                                                                              #
 ####################################################

$lang = array();

//Errors
$lang['error'] = 'Error';
$lang['unknow_error'] = 'Unknown error';
$lang['e_auth'] = 'You haven\'t the required level !';
$lang['e_unexist_module'] = 'This module doesn\'t exist !';
$lang['e_uninstalled_module'] = 'This module isn\'t installed !';
$lang['e_unactivated_module'] = 'This module isn\'t activated !';
$lang['e_incomplete'] = 'All the required fields must be filled !';
$lang['e_auth_post'] = 'You have to log in to post!';
$lang['e_readonly'] = 'You can\'t perform this action because you have been set in read only status !';
$lang['e_unexist_cat'] = 'This category doesn\'t exist !';
$lang['e_unexist_file'] = 'This file doesn\'t exist !';
$lang['e_unexist_page'] = 'This page doesn\'t exist !';
$lang['e_mail_format'] = 'Invalid e-mail address !';
$lang['e_unexist_member'] = 'This login doesn\'t exist !';
$lang['e_unauthorized'] = 'You aren\'t authorized to post !';
$lang['e_flood'] = 'You can\'t post yet, retry in a few moments';
$lang['e_l_flood'] = 'You can\'t post more than %d link(s) in your message';
$lang['e_link_pseudo'] = 'Your login can\'t contain weblinks';
$lang['e_php_version_conflict'] = 'Inadequate PHP version';

//Cache
$lang['e_cache_modules'] = 'Cache -> Cache module generation has failed !';

//Upload
$lang['e_upload_max_dimension'] = 'Max file dimensions exceeded';
$lang['e_upload_max_weight'] = 'Maximum file size exceeded';
$lang['e_upload_invalid_format'] = 'Invalid file format';
$lang['e_upload_error'] = 'Error while uploading file';
$lang['e_unlink_disabled'] = 'File suppression function not supported by your server';
$lang['e_upload_failed_unwritable'] = 'Impossible to upload because writing in this directory is not allowed';
$lang['e_upload_already_exist'] = 'File already exists, overwrite is not allowed';
$lang['e_max_data_reach'] = 'Max size reached, delete old files';

//Members
$lang['e_pass_mini'] = 'Minimum length of the new password: 6 characters';
$lang['e_pass_same'] = 'The passwords must be identical';
$lang['e_pseudo_auth'] = 'The entered username is already used !';
$lang['e_mail_auth'] = 'The entered e-mail is already used !';
$lang['e_mail_invalid'] = 'The entered e-mail isn\'t valid !';
$lang['e_unexist_member'] = 'This member doesn\'t exist !';
$lang['e_member_ban'] = 'You have been banned! You can retry to connect in';
$lang['e_member_ban_w'] = 'You have been banned for your behaviour! Contact the administator if you think it\'s an error.';
$lang['e_unactiv_member'] = 'You still have %d attempt(s) remaining after that you will have to wait 5 minutes to obtain 2 new attempts (10mins for 5) !';
$lang['e_nomore_test_connect'] = 'You have used all your log in attempts and your account is locked for 5 minutes';

//Extend fields
$lang['e_exist_field'] = 'A field with the same name already exists !';

//Groups
$lang['e_already_group'] = 'Member already in group';

//Forget
$lang['e_mail_forget'] = 'The E-mail entered does not match the one in our database !';
$lang['e_forget_mail_send'] = 'An e-mail has been sent to you with an activation key to confirm the change !';
$lang['e_forget_confirm_change'] = 'Password changed successfully!<br /> You can login with the new password which has been sent to you by email.';
$lang['e_forget_echec_change'] = 'Failure: password can\'t be changed';

//Register
$lang['e_incorrect_verif_code'] = 'Incorrect verification code !';

//Mps
$lang['e_pm_full'] = 'Your private message box is full, You have <strong>%d</strong> waiting conversation(s), delete old posts to read it/them';
$lang['e_pm_full_post'] = 'Your private message box is full, delete old messages to create new ones';
$lang['e_unexist_user'] = 'The selected user doesn\'t exist !';
$lang['e_pm_del'] = 'The recipient has deleted the conversation, you can\'t post anymore';
$lang['e_pm_noedit'] = 'The recipient has already read your message, you can\'t edit it anymore';
$lang['e_pm_nodel'] = 'The recipient has already read your message, you can\'t delete it anymore';

//PHP Error Handler
$lang['e_fatal'] = 'Fatal';
$lang['e_notice'] = 'Notice';
$lang['e_warning'] = 'Warning';
$lang['e_unknow'] = 'Unknow';
$lang['infile'] = 'in file';
$lang['atline'] = 'at line';

// Too Many Connections
$lang['too_many_connections'] = 'Too many connections';
$lang['too_many_connections_explain'] = 'The maximum number of connections that the database can handle has been reached.<br />Please, try again in a few seconds.';
?>
