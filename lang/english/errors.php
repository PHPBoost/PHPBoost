<?php
/*##################################################
 *                                 errors.php
 *                            -------------------
 *   begin                : June 27, 2006
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
$LANG['unknow_error'] = 'Unknow error';
$LANG['e_auth'] = 'You havn\'t the required level';
$LANG['e_unexist_module'] = 'This module doesn\'t exist';
$LANG['e_incomplete'] = 'All the require fields must be filled !';
$LANG['e_auth_post'] = 'You have to log in to post!';
$LANG['e_readonly'] = 'You can execute this action, because you have been set in read only status';
$LANG['e_unexist_cat'] = 'This category doesn\'t exist';
$LANG['e_unexist_file'] = 'This file doesn\'t exist';
$LANG['e_unexist_page'] = 'This page doesn\'t exist';
$LANG['e_mail_format'] = 'Invalid mail!';
$LANG['e_unexist_member'] = 'This login doesn\'t exist!';
$LANG['e_unauthorized'] = 'You aren\'t authorized to post !';
$LANG['e_flood'] = 'You can\'t post yet, retry in a few moments';
$LANG['e_l_flood'] = 'You can\'t post more than %d link(s) in your message';
$LANG['e_link_pseudo'] = 'Your login can\'t contain weblink';

//Cache
$LANG['e_cache_modules'] = 'Cache -> Generation of module cache has failed!';

//Upload
$LANG['e_upload_max_dimension'] = 'Exceed max file dimension';
$LANG['e_upload_max_weight'] = 'Exceed max file weight';
$LANG['e_upload_invalid_format'] = 'Invalid file format';
$LANG['e_upload_error'] = 'Error during file upload';
$LANG['e_unlink_disabled'] = 'Function of deleting files not supported by server';
$LANG['e_upload_failed_unwritable'] = 'Impossible to upload, writing in this directory is unauthorized';
$LANG['e_upload_already_exist'] = 'File already exist, overwrite unauthorized';
$LANG['e_max_data_reach'] = 'Max size reach, delete old files';

//Members
$LANG['e_pass_mini'] = 'Minimal length of new the password: 6 characters';
$LANG['e_pass_same'] = 'The passwords must be identical';
$LANG['e_pseudo_auth'] = 'The entered username is already used!';
$LANG['e_mail_auth'] = 'The entered mail is already used!';
$LANG['e_mail_invalid'] = 'The entered mall isn\t valid!';
$LANG['e_unexist_member'] = 'No member found with this login!';
$LANG['e_member_ban'] = 'You have been banned! You can retry to connect in';
$LANG['e_member_ban_w'] = 'You have been banned for your comportment! Contact the administator if you think it\'s an error';
$LANG['e_unactiv_member'] = 'Your account was not activated yet!';
$LANG['e_test_connect'] = 'Rest %d test remaining after that you will have to wait 5 minutes to obtain 2 new tests (10min for 5)!';
$LANG['e_nomore_test_connect'] = 'You have used all your tests of connection, your account is locked during 5 minutes';

//Extend fields
$LANG['e_exist_field'] = 'Field with same name already exist!';

//Groups
$LANG['e_already_group'] = 'Member already in group';

//Forget
$LANG['e_mail_forget'] = 'The mail entered not correspond with user\'s mail !';
$LANG['e_forget_mail_send'] = 'A mall had been just sent to you, with an activation key to confirm change!';
$LANG['e_forget_confirm_change'] = 'Password changed successfully!<br/> You can connect with the new password which was transmitted to you by email.';
$LANG['e_forget_echec_change'] = 'Echec password can\'t be changed';

//Register
$LANG['e_incorrect_verif_code'] = 'Incorrect verification code!';

//Mps
$LANG['e_pm_full'] = 'Your private message box is full, You have <strong>%d</strong> waiting conversation(s), delete old conversation to read them';
$LANG['e_pm_full_post'] = 'Your private message box is full, delete old conversation to post new';
$LANG['e_unexist_user'] = 'The selected user doesn\'t exist!';
$LANG['e_pm_del'] = 'The recipient has delete the conversation, you can\'t post anymore';
$LANG['e_pm_noedit'] = 'The recipient has already read your message, you can\'t edit it anymore';
$LANG['e_pm_nodel'] = 'The recipient has already read your message, you can\'t delete it anymore';

?>
