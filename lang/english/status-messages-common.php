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
$lang['error.unknow'] = 'Unknow';

$lang['csrf_invalid_token'] = 'Invalid session token. Please retry because the operation has not been performed.';

//Process
$lang['process.success'] = 'The operation is a success';
$lang['process.error'] = 'An error occurred during the operation';

$lang['confirm.delete'] = 'Do you really want to delete this item ?';

$lang['message.success.config'] = 'The configuration has been modified';

//Captcha
$lang['captcha.validation_error'] = 'The visual confirmation field has not been properly filled!';
$lang['captcha.is_default'] = 'The captcha you want to uninstall or disable is set on your site, you must select another captcha in the content management first.';
$lang['captcha.last_installed'] = 'Last captcha, you can not delete or disable it. Please install another one first.';

//Form
$lang['form.doesnt_match_regex'] = 'The entered value does not fit the proper format';
$lang['form.doesnt_match_url_regex'] = 'The entered value has to be a valid url';
$lang['form.doesnt_match_mail_regex'] = 'The entered value has to be a valid mail';
$lang['form.doesnt_match_length_intervall'] = 'The entered value does not fit the specified length';
$lang['form.doesnt_match_integer_intervall'] = 'The entered value does not fit the specified interval (:lower_bound <= value <= :upper_bound)';
$lang['form.has_to_be_filled'] = 'The field ":name" has to be filled';
$lang['form.validation_error'] = 'Please correct the form errors';
$lang['form.fields_must_be_equal'] = 'Fields ":field1" and ":field2" must be equal';
$lang['form.fields_must_not_be_equal'] = 'Fields ":field1" and ":field2" must have different values';

//User
$lang['user.not_exists'] = 'User not exists !';
$lang['user.auth.passwd_flood'] = ':remaining_tries tries are remaining. After that, you\'ll have to wait 5 minutes to have 2 more tries (10min for 5)!';
$lang['user.auth.passwd_flood_max'] = 'You have failed, too many authentication attempts, your account is locked for 5 minutes.';
?>