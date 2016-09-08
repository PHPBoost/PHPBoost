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

//Errors
$LANG['e_incomplete'] = 'All the required fields must be filled!';
$LANG['e_readonly'] = 'You can\'t perform this action because your account is read-only!';
$LANG['e_flood'] = 'You can\'t post yet, retry in a few moments';
$LANG['e_l_flood'] = 'You can\'t post more than %d link(s) in your message';

//Upload
$LANG['e_upload_max_dimension'] = 'Max file dimensions exceeded';
$LANG['e_upload_max_weight'] = 'Maximum file size exceeded';
$LANG['e_upload_invalid_format'] = 'Invalid file format';
$LANG['e_upload_php_code'] = 'Invalid file content, php code is forbidden';
$LANG['e_upload_error'] = 'Error while uploading file';
$LANG['e_unlink_disabled'] = 'File deleting function not supported by your server';
$LANG['e_upload_failed_unwritable'] = 'Impossible to upload because writing in this directory is not allowed';
$LANG['e_upload_already_exist'] = 'File already exists, overwrite is not allowed';
$LANG['e_max_data_reach'] = 'Max size reached, delete old files';

//Members
$LANG['e_display_name_auth'] = 'The entered display name is already used!';
$LANG['e_pseudo_auth'] = 'The entered username is already used!';
$LANG['e_mail_auth'] = 'The entered e-mail address is already used!';
$LANG['e_member_ban'] = 'You have been banned! You can retry to connect in';
$LANG['e_member_ban_w'] = 'You have been banned for your behaviour! Contact the administator if you think it\'s an error.';
$LANG['e_unactiv_member'] = 'Your account has not been activated yet!';

//Groups
$LANG['e_already_group'] = 'Member is already in this group';

//Mps
$LANG['e_pm_full'] = 'Your private message box is full, You have <strong>%d</strong> waiting conversation(s), delete old posts to read it/them';
$LANG['e_pm_full_post'] = 'Your private message box is full, delete old messages to create new ones';
$LANG['e_unexist_user'] = 'The selected user doesn\'t exist!';
$LANG['e_pm_del'] = 'The recipient has deleted the conversation, you can\'t post anymore';
$LANG['e_pm_noedit'] = 'The recipient has already read your message, you can\'t edit it anymore';
$LANG['e_pm_nodel'] = 'The recipient has already read your message, you can\'t delete it anymore';
?>
