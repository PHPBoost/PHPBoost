<?php
/*##################################################
 *                           admin-members-common.php
 *                            -------------------
 *   begin                : August 14, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

//Messages 
$lang['members.config.success-saving'] = 'The configuration was successfully saved';
$lang['members.add-member.success'] = 'The member was added successfully';
$lang['members.member-edit.success'] = 'The member has been changed';
$lang['members.rules.success-saving'] = 'The rules has been updated';

// Title 
$lang['members.config-members'] = 'Members configuration';
$lang['members.members-management'] = 'Members management';
$lang['members.add-member'] = 'Add member';
$lang['members.members-punishment'] = 'Members punishment';
$lang['members.edit-member'] = 'Edit member';
$lang['members.rules'] = 'Rules';

//Configuration
$lang['members.config.registration-activation'] = 'Enable the registration of members';
$lang['members.config.type-activation'] = 'User account activation Member';
$lang['members.config.unactivated-accounts-timeout'] = 'Time after which the members are not activated erased';
$lang['members.config.unactivated-accounts-timeout-explain'] = 'Leave blank to skip this option (not included if validated by administrator)';
$lang['members.config.captcha-activation'] = 'Visual verification code';
$lang['members.config.captcha-difficulty'] = 'Difficulty of the verification code';
$lang['members.config.theme-choice-permission'] = 'Allowed members to choose their theme';
$lang['members.config.upload-avatar-server-authorization'] = 'Allow upload an avatar on the server';
$lang['members.config.activation-resize-avatar'] = 'Enable automatic image resizing';
$lang['members.activation-resize-avatar-explain'] = 'Warning your server must have the GD extension loaded';
$lang['members.config.maximal-width-avatar'] = 'Maximum width of the avatar';
$lang['members.config.maximal-width-avatar-explain'] = 'Default 120';
$lang['members.config.maximal-height-avatar'] = 'Maximum height of the avatar';
$lang['members.config.maximal-height-avatar-explain'] = 'Default 120';
$lang['members.config.maximal-weight-avatar'] = 'Maximum weight of the avatar in KB';
$lang['members.config.maximal-weight-avatar-explain'] = 'Default 20';
$lang['members.config.default-avatar-activation'] = 'Enable default avatar';
$lang['members.config.default-avatar-activation-explain'] = 'Met an avatar to members who do not';
$lang['members.config.default-avatar-link'] = 'Address of the default avatar';
$lang['members.default-avatar-link-explain'] = 'Put in the images folder of your theme';
$lang['members.config.authorization-read-member-profile'] = 'Here you define the permissions to read the list of members as well as some personal information such as their emails.';
$lang['members.config.welcome-message'] = 'Message to all members';
$lang['members.config.welcome-message-content'] = 'Welcome message displayed in the panel member';

//Other fieldset configuration title
$lang['members.config.avatars-management'] = 'Avatars management';
$lang['members.config.authorization'] = 'Authorizations';

//Add and edit member
$lang['members.pseudo'] = 'Pseudo';
$lang['members.mail'] = 'email address';
$lang['members.hide-mail'] = 'Hide email';
$lang['members.password'] = 'Password';
$lang['members.confirm-password'] = 'Confirm password';
$lang['members.rank'] = 'Rank';
$lang['members.rank.member'] = 'Member';
$lang['members.rank.modo'] = 'Moderator';
$lang['members.rank.admin'] = 'Administrator';
$lang['members.approbation'] = 'Approbation';
$lang['members.groups'] = 'Groups';
$lang['members.caution'] = 'Caution';
$lang['members.readonly'] = 'Read only';
$lang['members.bannish'] = 'Bannish';

//Other fieldset add and edit title
$lang['members.member-management'] = 'Member mangement';
$lang['members.punishment-management'] = 'Punishment management';

//Activation type
$lang['members.config.type-activation.auto'] = 'Automatique';
$lang['members.config.type-activation.mail'] = 'Mail';
$lang['members.config.type-activation.admin'] = 'Administrator';

//Rules
$lang['members.rules.registration-agreement-description'] = 'Enter below the settlement to display when the registration of members, they will accept to register. Leave blank for no regulation.';
$lang['members.rules.registration-agreement'] = 'Registration agreement';

//Other
$lang['members.valid'] = 'Valid';
?>