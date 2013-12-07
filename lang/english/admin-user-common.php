<?php
/*##################################################
 *                           admin-user-common.php
 *                            -------------------
 *   begin                : August 14, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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
#                     English                       #
 ####################################################
 
$lang = array();

// Title 
$lang['members.config-members'] = 'Members configuration';
$lang['members.members-management'] = 'Members management';
$lang['members.add-member'] = 'Add member';
$lang['members.members-punishment'] = 'Members punishment';
$lang['members.edit-member'] = 'Edit member';
$lang['members.rules'] = 'Rules';

//Configuration
$lang['members.config.registration-activation'] = 'Enable the registration of members';
$lang['members.config.type-activation'] = 'User account activation type';
$lang['members.config.unactivated-accounts-timeout'] = 'Number of days after which the unactivated members are cleared';
$lang['members.config.unactivated-accounts-timeout-explain'] = 'Leave blank to skip this option (not included if validated by administrator)';
$lang['members.config.upload-avatar-server-authorization'] = 'Allow avatar upload on the server';
$lang['members.config.activation-resize-avatar'] = 'Enable automatic image resizing';
$lang['members.activation-resize-avatar-explain'] = 'Warning your server must have the GD extension loaded';
$lang['members.config.maximal-width-avatar'] = 'Maximum width of the avatar';
$lang['members.config.maximal-width-avatar-explain'] = 'Default 120';
$lang['members.config.maximal-height-avatar'] = 'Maximum height of the avatar';
$lang['members.config.maximal-height-avatar-explain'] = 'Default 120';
$lang['members.config.maximal-weight-avatar'] = 'Maximum weight of the avatar in KB';
$lang['members.config.maximal-weight-avatar-explain'] = 'Default 20';
$lang['members.config.default-avatar-activation'] = 'Enable default avatar';
$lang['members.config.default-avatar-activation-explain'] = 'Put an avatar to members who do not have one';
$lang['members.config.default-avatar-link'] = 'Address of the default avatar';
$lang['members.default-avatar-link-explain'] = 'Put it in the images folder of your theme';
$lang['members.config.authorization-read-member-profile'] = 'Here you define the permissions to read the list of members as well as some personal information such as their emails.';
$lang['members.config.welcome-message'] = 'Message to all members';
$lang['members.config.welcome-message-content'] = 'Welcome message displayed in the panel member';

//Other fieldset configuration title
$lang['members.config.avatars-management'] = 'Avatars management';
$lang['members.config.authorization'] = 'Authorizations';

//Other fieldset add and edit title
$lang['members.member-management'] = 'Member management';
$lang['members.punishment-management'] = 'Punishment management';

//Activation type
$lang['members.config.type-activation.auto'] = 'Automatic';
$lang['members.config.type-activation.mail'] = 'Mail';
$lang['members.config.type-activation.admin'] = 'Administrator';

//Rules
$lang['members.rules.registration-agreement-description'] = 'Enter below the agreement to display when members register, they have to accept it to register. Leave blank for no agreement.';
$lang['members.rules.registration-agreement'] = 'Registration agreement';

//Other
$lang['members.valid'] = 'Valid';

############## Extended Field ##############

$lang['extended-field-add'] = 'Add profile field';
$lang['extended-field-edit'] = 'Edit profile field';
$lang['extended-field'] = 'Profile fields';
$lang['extended-fields-management'] = 'Profile field management';
$lang['extended-fields-error-already-exist'] = 'The field already exists.';
$lang['extended-fields-error-phpboost-config'] = 'The fields used by default PHPBoost can not be created more than one time, please choose another type of field.';

//Type 
$lang['type.short-text'] = 'Short text (max 255 characters)';
$lang['type.long-text'] = 'Long text (unlimited)';
$lang['type.half-text'] = 'Medium text';
$lang['type.simple-select'] = 'Single selection (between several values)';
$lang['type.multiple-select'] = 'Multiple selection (between several values)';
$lang['type.simple-check'] = 'Single choice (between several values)';
$lang['type.multiple-check'] = 'Multiple choice (between several values)';
$lang['type.date'] = 'Date';
$lang['type.user-theme-choice'] = 'Theme';
$lang['type.user-lang-choice'] = 'Language';
$lang['type.user_born'] = 'Date of birth';
$lang['type.user-editor'] = 'Editor';
$lang['type.user-timezone'] = 'Timezone choice';
$lang['type.user-sex'] = 'Sex';
$lang['type.avatar'] = 'Avatar';

$lang['default-field'] = 'Default field';

$lang['field.name'] = 'Name';
$lang['field.description'] = 'Description';
$lang['field.type'] = 'Field type';
$lang['field.regex'] = 'Entry form control';
$lang['field.regex-explain'] = 'You can control the form of input used. For instance, if an email address is expected, you can verify that its form is correct.<br />For advanced users, it is possible to write your own regular expression (<acronym="Perl Compatible Regular Expression">PCRE</acronym>) to control the user input.';
$lang['field.predefined-regex'] = 'Predefined pattern';
$lang['field.required'] = 'Required field';
$lang['field.required_explain'] = 'Required in the member profile and registration area';
$lang['field.possible-values'] = 'Possible values';
$lang['field.possible-values-explain'] = 'Separate each value with |';
$lang['field.default-values'] = 'Default values';
$lang['field.default-values-explain'] = 'Separate each value with |';
$lang['field.default-possible-values'] = 'Yes|No';
$lang['field.read_authorizations'] = 'Read permissions of the field in the profile';
$lang['field.actions_authorizations'] = 'Read permissions of the field when creating or editing a profile';

// Regex
$lang['regex.figures'] = 'Numbers';
$lang['regex.letters'] = 'Letters';
$lang['regex.figures-letters'] = 'Numbers and letters';
$lang['regex.word'] = 'Word';
$lang['regex.website'] = 'Website';
$lang['regex.mail'] = 'Mail';
$lang['regex.personnal-regex'] = 'Custom regular expression';


$lang['field.yes'] = 'Yes';
$lang['field.no'] = 'No';

$lang['field.success'] = 'Success';
$lang['field.delete_field'] = 'Do you really want to delete this field?';
$lang['field.position'] = 'Position';

$lang['field.is-required'] = 'Required';
$lang['field.is-not-required'] = 'Not required';
?>