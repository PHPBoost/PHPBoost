<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 04
 * @since       PHPBoost 1.5 - 2006 06 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

// Errors
$lang['e_incomplete'] = 'All the required fields must be filled!';
$lang['e_readonly']   = 'You can\'t perform this action because your account is read-only!';
$lang['e_flood']      = 'You can\'t post yet, retry in a few moments';
$lang['e_l_flood']    = 'You can\'t post more than %d link(s) in your message';

// Upload
$lang['e_upload_max_dimension']     = 'Max file dimensions exceeded';
$lang['e_upload_max_weight']        = 'Maximum file size exceeded';
$lang['e_upload_invalid_format']    = 'Invalid file format';
$lang['e_upload_php_code']          = 'Invalid file content, php code is forbidden';
$lang['e_upload_error']             = 'Error while uploading file';
$lang['e_unlink_disabled']          = 'File deleting function not supported by your server';
$lang['e_upload_failed_unwritable'] = 'Impossible to upload because writing in this directory is not allowed';
$lang['e_upload_already_exist']     = 'File already exists, overwrite is not allowed';
$lang['e_upload_no_selected_file']  = 'No file has been selected';
$lang['e_max_data_reach']           = 'Max size reached, delete old files';

// Members
$lang['e_display_name_auth'] = 'The entered display name is already used!';
$lang['e_pseudo_auth']       = 'The entered username is already used!';
$lang['e_mail_auth']         = 'The entered e-mail address is already used!';
$lang['e_member_ban']        = 'You have been banned! You can retry to connect in';
$lang['e_member_ban_w']      = 'You have been banned for your behaviour! Contact the administator if you think it\'s an error.';
$lang['e_unactiv_member']    = 'Your account has not been activated yet!';

// Groups
$lang['e_already_group'] = 'Member is already in this group';

// PM
$lang['e_pm_full']      = 'Your private message box is full, You have <strong>%d</strong> waiting conversation(s), delete old posts to read it/them';
$lang['e_pm_full_post'] = 'Your private message box is full, delete old messages to create new ones';
$lang['e_unexist_user'] = 'The selected user doesn\'t exist!';
$lang['e_pm_del']       = 'The recipient has deleted the conversation, you can\'t post anymore';
$lang['e_pm_noedit']    = 'The recipient has already read your message, you can\'t edit it anymore';
$lang['e_pm_nodel']     = 'The recipient has already read your message, you can\'t delete it anymore';
?>
