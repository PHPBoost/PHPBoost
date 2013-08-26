<?php
/*##################################################
 *                           user-common.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

 ####################################################
 #                     English                      #
 ####################################################

$lang['user'] = 'User';
$lang['users'] = 'Users';
$lang['profile'] = 'Profile';
$lang['profile_of'] = 'Profile of :name';
$lang['profile.edit'] = 'Edit profile';
$lang['messages'] = 'User messages';
$lang['maintain'] = 'Maintain';

$lang['profile.edit.password.error'] = 'The new password is not correct';

//Contribution
$lang['contribution.confirmed'] = 'Your contribution has been saved.';
$lang['contribution.confirmed.messages'] = '<p>You can follow it into the <a href="' . UserUrlBuilder::contribution_panel()->absolute() . '">contribution panel</a> 
and possibly discuss with the validators if their choice is not straightforward.</p><p>Thank you for participating in the life of the site!</p>';


//User fields
$lang['pseudo'] = 'Login';
$lang['pseudo.explain'] = 'Minimum length of the login: 3 characters';
$lang['password'] = 'Password';
$lang['password.new'] = 'New password';
$lang['password.old'] = 'Old password';
$lang['password.old.explain'] = 'Complete only if amended';
$lang['password.confirm'] = 'Confirm the password';
$lang['password.explain'] = 'Minimum length of password: 6 characters';
$lang['email'] = 'Email';
$lang['email.hide'] = 'Hide email';
$lang['theme'] = 'Theme';
$lang['theme.preview'] = 'Theme preview';
$lang['text-editor'] = 'Text editor';
$lang['lang'] = 'Lang';
$lang['timezone'] = 'Timezone';
$lang['timezone.choice'] = 'Timezone choice';
$lang['timezone.choice.explain'] = 'Adjusts the time at your location';
$lang['level'] = 'Level';
$lang['approbation'] = 'Approbation';

$lang['registration_date'] = 'Registration date';
$lang['last_connection'] = 'Last connection';
$lang['number-messages'] = 'Messages number';
$lang['private_message'] = 'Private message';
$lang['delete-account'] = 'Delete account';
$lang['avatar'] = 'Avatar';

//Groups
$lang['groups'] = 'Groups';
$lang['groups.select'] = 'Group select';
$lang['no_member'] = 'No member in this group';

//Other
$lang['caution'] = 'Caution';
$lang['readonly'] = 'Read only';
$lang['banned'] = 'Banned';
$lang['connect'] = 'Connect';
$lang['autoconnect'] = 'Auto connect';
$lang['year'] = 'year';
$lang['years'] = 'years';

// Ranks
$lang['rank'] = 'Rank';
$lang['visitor'] = 'Visitor';
$lang['member'] = 'Member';
$lang['moderator'] = 'Moderator';
$lang['administrator'] = 'Administrator';

//Forget password
$lang['forget-password'] = 'Forgotten password';
$lang['forget-password.select'] = 'Select the field you want to informat (email or login)';
$lang['forget-password.success'] = 'An email has been sent with a link to change your password';
$lang['forget-password.error'] = 'Information provided are not correct, please correct it and try again';
$lang['change-password'] = 'Change password';
$lang['forget-password.mail.content'] = 'Dear(e) :pseudo,

You are receiving this email because you (or someone claiming to be) have requested a new password to be sent to your account :host. 
If you have not asked to change your password, please ignore it. If you continue to receive it, please contact the site administrator.

To change your password, click on the link below and follow the directions on the site.

:change_password_link

If you have problems, please contact the site administrator.

:signature';

//Registration 
$lang['registration'] = 'Registration';

$lang['registration.validation.mail.explain'] = 'You will need to activate your account in the email sent to you before beeing able to connect to the site';
$lang['registration.validation.administrator.explain'] = 'An administrator will need to activate your account before beeing able to connect to the site';

$lang['registration.confirm.success'] = 'Your account has successfully been validated';
$lang['registration.confirm.error'] = 'A problem occurred during your activation, make sure your key is valid';

$lang['registration.success.administrator-validation'] = 'You have registered successfully. However, an administrator must validate your account before you can use it';
$lang['registration.success.mail-validation'] = 'You have registered successfully. However you must click the activation link in the email you were sent';

$lang['registration.email.automatic-validation'] = 'You can now connect to your account directly on the site.';
$lang['registration.email.mail-validation'] = 'You have to click on this link to activate your account: :validation_link';
$lang['registration.email.administrator-validation'] = 'Warning: Your account must be activated by an administrator. Thanks for your patience.';
$lang['registration.email.mail-administrator-validation'] = 'Dear(e) :pseudo,

We are pleased to inform you that your account :site_name has been validated by an administrator.

You can now login to the site using the credentials provided in the previous email.

:signature';

$lang['registration.pending-approval'] = 'A new member has registered. His account must be approved before it can be used.';
$lang['registration.subject-mail'] = 'Confirmation of registration :site_name';
$lang['registration.content-mail'] = 'Dear(e) :pseudo,

First of all, thank you for being registered on :site_name. Now you are part of the site members.
By registering on :site_name, you get access to the member area that offers several advantages. You can, among other things, be automatically recognized throughout the site to post messages, change the language and / or the default theme, edit your profile, access to classes for members only ... In short you access to all the community site.

To connect, you must retain your username and password.

Here are your identifiers.

Login : :login
Password : :password

:accounts_validation_explain

:signature';

$lang['agreement'] = 'Agreement';
$lang['agreement.agree'] = 'I agree this agreement';
$lang['agreement.agree.required'] = 'You must agree for registration';
?>