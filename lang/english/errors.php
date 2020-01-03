<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 03
 * @since       PHPBoost 1.5 - 2006 06 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

####################################################
#                     English                      #
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
$LANG['e_upload_no_selected_file'] = 'No file has been selected';
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
