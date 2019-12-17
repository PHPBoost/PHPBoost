<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 26
 * @since       PHPBoost 3.0 - 2012 04 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

####################################################
#                     English                      #
####################################################

$lang['success'] = 'Success';
$lang['error'] = 'Error';

$lang['error.fatal'] = 'Fatal';
$lang['error.notice'] = 'Notice';
$lang['error.warning'] = 'Warning';
$lang['error.question'] = 'Question';
$lang['error.unknow'] = 'Unknow';

$lang['message.close_ephemeral_message'] = 'Close the message';

//PHPBoost errors
$lang['error.auth'] = 'You don\'t have the required level!';
$lang['error.auth.guest'] = 'Protected content. Please suscribe or connect to access this page.';
$lang['error.auth.registration_disabled'] = 'Registration is disabled on the site.';
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

$lang['confirm.delete'] = 'Do you really want to delete this item?';
$lang['confirm.delete.elements'] = 'Do you really want to delete these items?';

$lang['message.success.config'] = 'The configuration has been modified';
$lang['message.success.position.update'] = 'The position of the items has been updated';

$lang['message.download.file.error'] = 'File :filename download failed';

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
$lang['form.doesnt_match_authorized_extensions_regex'] = 'File extension is not authorized. Valid extensions : :extensions.';
$lang['form.doesnt_match_picture_file_regex'] = 'The value entered must correspond to a picture';
$lang['form.doesnt_match_length_intervall'] = 'The entered value does not fit the specified length (:lower_bound <= value <= :upper_bound)';
$lang['form.doesnt_match_length_min'] = 'The entered value must be at least :lower_bound characters';
$lang['form.doesnt_match_length_max'] = 'The entered value must be max :upper_bound characters';
$lang['form.doesnt_match_integer_intervall'] = 'The entered value does not fit the specified interval (:lower_bound <= value <= :upper_bound)';
$lang['form.doesnt_match_integer_min'] = 'The entered value must be superior or equal to :lower_bound';
$lang['form.doesnt_match_integer_max'] = 'The entered value must be inferior or equal to :upper_bound';
$lang['form.doesnt_match_medium_password_regex'] = 'The password must contain at least one lower case letter and one upper case letter or one lower case letter and a digit';
$lang['form.doesnt_match_strong_password_regex'] = 'The password must contain at least one lower case letter, one upper case letter and a digit';
$lang['form.doesnt_match_very_strong_password_regex'] = 'The password must contain at least one lower case letter, one upper case letter, one digit and a special character';
$lang['form.invalid_url'] = 'The url is not valid';
$lang['form.invalid_picture'] = 'The file is not a picture';
$lang['form.unexisting_file'] = 'The file has not been found, its url must be incorrect';
$lang['form.has_to_be_filled'] = 'The field ":name" has to be filled';
$lang['form.validation_error'] = 'Please correct the form errors';
$lang['form.fields_must_be_equal'] = 'Fields ":field1" and ":field2" must be equal';
$lang['form.fields_must_not_be_equal'] = 'Fields ":field1" and ":field2" must have different values';
$lang['form.first_field_must_be_inferior_to_second_field'] = 'Field ":field2" must have a value inferior to field ":field1"';
$lang['form.first_field_must_be_superior_to_second_field'] = 'Field ":field2" must have a value superior to field ":field1"';
$lang['form.first_field_must_not_be_contained_in_second_field'] = 'Value of field ":field1" must not be present in field ":field2"';
$lang['form.login_and_mail_must_not_be_contained_in_second_field'] = 'Your mail or your login must not be present in field ":field2"';
$lang['form.doesnt_match_mail_authorized_domains_regex'] = 'This mail domain address is not authorized, please choose another mail address';

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

//Locked content
$lang['content.is_locked.description'] = 'The item is currently open by :user_display_name, you can not access it, try again later.';
$lang['content.is_locked.another_user'] = 'another user';

//Upload
$lang['upload.success'] = 'The archive has successfully been uploaded';
$lang['upload.invalid_format'] = 'The archive format is invalid';
$lang['upload.error'] = 'Error whle uploading file';
$lang['upload.max_file_size_exceeded'] = 'The maximum file size must not exceed :max_file_size.';

?>
