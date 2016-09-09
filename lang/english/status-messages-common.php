<?php
/*##################################################
 *                                status-messages-common.php
 *                            -------------------
 *   begin                : April 12, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

$lang['success'] = 'Success';
$lang['error'] = 'Error';

$lang['error.fatal'] = 'Fatal';
$lang['error.notice'] = 'Notice';
$lang['error.warning'] = 'Warning';
$lang['error.question'] = 'Question';
$lang['error.unknow'] = 'Unknow';

//PHPBoost errors
$lang['error.auth'] = 'You don\'t have the required level!';
$lang['error.auth.guest'] = 'Protected content. Please suscribe or connect to access this page.';
$lang['error.page.forbidden'] = 'This folder access is forbidden!';
$lang['error.page.unexist'] = 'This page doesn\'t exist!';
$lang['error.action.unauthorized'] = 'Unauthorized action!';
$lang['error.module.uninstalled'] = 'This module isn\'t installed!';
$lang['error.module.unactivated'] = 'This module isn\'t activated!';
$lang['error.invalid_archive_content'] = 'The content of the archive is invalid!';
$lang['error.404.message'] = 'It seems that a tornado has passed through here. <br />Unfortunately nothing more to see.';
$lang['error.403.message'] = 'It seems that a tornado has passed through here. <br />Access is forbidden to the public.';

$lang['csrf_invalid_token'] = 'Invalid session token. Please try to reload the page because the operation has not been performed.';

//Element
$lang['element.already_exists'] = 'The item already exists.';
$lang['element.unexist'] = 'The item you requested does not exist.';
$lang['element.not_visible'] = 'This element is not yet or is no more approved, it is not displayed for the other users.';

$lang['misfit.php'] = 'Inadequate PHP version';
$lang['misfit.phpboost'] = 'Inadequate PHPBoost version';

//Process
$lang['process.success'] = 'The operation is a success';
$lang['process.error'] = 'An error occurred during the operation';

$lang['confirm.delete'] = 'Do you really want to delete this item ?';

$lang['message.success.config'] = 'The configuration has been modified';
$lang['message.success.position.update'] = 'The position of the items has been updated';

$lang['message.delete_install_and_update_folders'] = 'For security reasons we recommand you to delete the <b>install</b> and <b>update</b> folders and all their contents, hackers could manage to run the installation script and you could lose data !';
$lang['message.delete_install_or_update_folders'] = 'For security reasons we recommand you to delete the <b>:folder</b> folder and all its contents, hackers could manage to run the installation script and you could lose data !';

//Captcha
$lang['captcha.validation_error'] = 'The visual confirmation field has not been properly filled!';
$lang['captcha.is_default'] = 'The captcha you want to uninstall or disable is set on your site, you must select another captcha in the content management first.';
$lang['captcha.last_installed'] = 'Last captcha, you can not delete or disable it. Please install another one first.';

//Form
$lang['form.explain_required_fields'] = 'The fields marked with a * are required !';
$lang['form.doesnt_match_regex'] = 'The entered value does not fit the proper format';
$lang['form.doesnt_match_date_regex'] = 'The entered value has to be a valid date';
$lang['form.doesnt_match_url_regex'] = 'The entered value has to be a valid url';
$lang['form.doesnt_match_mail_regex'] = 'The entered value has to be a valid mail';
$lang['form.doesnt_match_tel_regex'] = 'The entered value has to be a valid phone number';
$lang['form.doesnt_match_number_regex'] = 'The value entered must be a number';
$lang['form.doesnt_match_picture_file_regex'] = 'The value entered must correspond to a picture';
$lang['form.doesnt_match_length_intervall'] = 'The entered value does not fit the specified length (:lower_bound <= value <= :upper_bound)';
$lang['form.doesnt_match_length_min'] = 'The entered value must be at least :lower_bound characters';
$lang['form.doesnt_match_length_max'] = 'The entered value must be max :upper_bound characters';
$lang['form.doesnt_match_integer_intervall'] = 'The entered value does not fit the specified interval (:lower_bound <= value <= :upper_bound)';
$lang['form.doesnt_match_integer_min'] = 'The entered value must be superior or equal to :lower_bound';
$lang['form.doesnt_match_integer_max'] = 'The entered value must be inferior or equal to :upper_bound';
$lang['form.doesnt_match_medium_password_regex'] = 'The password must contain at least one lower case letter and one upper case letter or one lower case letter and a digit';
$lang['form.doesnt_match_strong_password_regex'] = 'The password must contain at least one lower case letter, one upper case letter and a digit';
$lang['form.invalid_url'] = 'The url is not valid';
$lang['form.invalid_picture'] = 'The file is not a picture';
$lang['form.unexisting_file'] = 'The file has not been found, its url must be incorrect';
$lang['form.has_to_be_filled'] = 'The field ":name" has to be filled';
$lang['form.validation_error'] = 'Please correct the form errors';
$lang['form.fields_must_be_equal'] = 'Fields ":field1" and ":field2" must be equal';
$lang['form.fields_must_not_be_equal'] = 'Fields ":field1" and ":field2" must have different values';
$lang['form.first_field_must_be_inferior_to_second_field'] = 'Field ":field2" must have a value inferior to field ":field1"';
$lang['form.first_field_must_be_superior_to_second_field'] = 'Field ":field2" must have a value superior to field ":field1"';

//User
$lang['user.not_authorized_during_maintain'] = 'You are not authorized to connect during the maintenance';
$lang['user.not_exists'] = 'User not exists !';
$lang['user.auth.passwd_flood'] = ':remaining_tries tries are remaining. After that, you\'ll have to wait 5 minutes to have 2 more tries (10min for 5)!';
$lang['user.auth.passwd_flood_max'] = 'You have failed, too many authentication attempts, your account is locked for 5 minutes.';

//Extended fields
$lang['extended_field.avatar_upload_invalid_format'] = 'Invalid avatar file format';
$lang['extended_field.avatar_upload_max_dimension'] = 'Max avatar file dimensions exceeded';

//BBcode
$lang['bbcode_member'] = 'Message for members';
$lang['bbcode_moderator'] = 'Message for moderators';
?>
