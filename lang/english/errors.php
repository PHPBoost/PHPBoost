<?php
/*##################################################
 *                                 errors.php
 *                            -------------------
 *   begin                : June 27, 2006
 *   last modified		: August 1st, 2009 - Forensic
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : mickaelhemri@gmail.com
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
#                                                           English                                                                              #
####################################################

//Erreurs
$LANG['error'] = 'Error';
$LANG['unknow_error'] = 'Unknown error';
$LANG['e_auth'] = 'You haven\'t the required level !';
$LANG['e_unexist_module'] = 'This module doesn\'t exist !';
$LANG['e_uninstalled_module'] = 'This module isn\'t installed !';
$LANG['e_incomplete'] = 'All the required fields must be filled !';
$LANG['e_auth_post'] = 'You have to log in to post!';
$LANG['e_readonly'] = 'You can\'t execute this action because you have been set in read only status !';
$LANG['e_unexist_cat'] = 'This category doesn\'t exist !';
$LANG['e_unexist_file'] = 'This file doesn\'t exist !';
$LANG['e_unexist_page'] = 'This page doesn\'t exist !';
$LANG['e_mail_format'] = 'Invalid e-mail address !';
$LANG['e_unexist_member'] = 'This login doesn\'t exist !';
$LANG['e_unauthorized'] = 'You aren\'t authorized to post !';
$LANG['e_flood'] = 'You can\'t post yet, retry in a few moments';
$LANG['e_l_flood'] = 'You can\'t post more than %d link(s) in your message';
$LANG['e_link_pseudo'] = 'Your login can\'t contain weblink';
$LANG['e_php_version_conflict'] = 'Inadequate PHP version';

//Cache
$LANG['e_cache_modules'] = 'Cache -> Cache module generation has failed !';

//Upload
$LANG['e_upload_max_dimension'] = 'Max file dimensions exceeded';
$LANG['e_upload_max_weight'] = 'Max file weight exceeded';
$LANG['e_upload_invalid_format'] = 'Invalid file format';
$LANG['e_upload_error'] = 'Error while uploading file';
$LANG['e_unlink_disabled'] = 'Files suppression function not supported by your server';
$LANG['e_upload_failed_unwritable'] = 'Impossible to upload because writing in this directory is not allowed';
$LANG['e_upload_already_exist'] = 'File already exists, overwrite is not allowed';
$LANG['e_max_data_reach'] = 'Max size reached, delete old files';

//Members
$LANG['e_pass_mini'] = 'Minimal length of the new password: 6 characters';
$LANG['e_pass_same'] = 'The passwords must be identical';
$LANG['e_pseudo_auth'] = 'The entered username is already used !';
$LANG['e_mail_auth'] = 'The entered e-mail is already used !';
$LANG['e_mail_invalid'] = 'The entered e-mail isn\'t valid !';
$LANG['e_unexist_member'] = 'This member doesn\'t exist !';
$LANG['e_member_ban'] = 'You have been banned! You can retry to connect in';
$LANG['e_member_ban_w'] = 'You have been banned for your behaviour! Contact the administator if you think it\'s an error.';
$LANG['e_unactiv_member'] = 'You still have %d attempt(s) remaining after that you will have to wait 5 minutes to obtain 2 new attempts (10mins for 5) !';
$LANG['e_nomore_test_connect'] = 'You have used all your log in attempts and your account is locked for 5 minutes';

//Extend fields
$LANG['e_exist_field'] = 'A field with the same name already exists !';

//Groups
$LANG['e_already_group'] = 'Member already in group';

//Forget
$LANG['e_mail_forget'] = 'The E-mail entered do not match the one in our database !';
$LANG['e_forget_mail_send'] = 'An e-mail has been sent to you with an activation key to confirm the change !';
$LANG['e_forget_confirm_change'] = 'Password changed successfully!<br /> You can connect with the new password which has been sent to you by email.';
$LANG['e_forget_echec_change'] = 'Failure: password can\'t be changed';

//Register
$LANG['e_incorrect_verif_code'] = 'Incorrect verification code !';

//Mps
$LANG['e_pm_full'] = 'Your private message box is full, You have <strong>%d</strong> waiting conversation(s), delete old posts to read it/them';
$LANG['e_pm_full_post'] = 'Your private message box is full, delete old posts to post new';
$LANG['e_unexist_user'] = 'The selected user doesn\'t exist !';
$LANG['e_pm_del'] = 'The recipient has deleted the conversation, you can\'t post anymore';
$LANG['e_pm_noedit'] = 'The recipient has already read your message, you can\'t edit it anymore';
$LANG['e_pm_nodel'] = 'The recipient has already read your message, you can\'t delete it anymore';

//PHP Error Handler
$LANG['e_notice'] = 'Notice';
$LANG['e_warning'] = 'Warning';
$LANG['e_unknow'] = 'Unknow';
$LANG['infile'] = 'in file';
$LANG['atline'] = 'at line';

// Too Many Connections
$LANG['too_many_connections'] = 'Too many connections';
$LANG['too_many_connections_explain'] = 'The maximum number of connections that the database could handle has been reached.<br />Please, try again in a few seconds.';
?>
