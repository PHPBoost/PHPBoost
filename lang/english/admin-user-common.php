<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 25
 * @since       PHPBoost 3.0 - 2010 08 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

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
$lang['members.config.allow_users_to_change_display_name'] = 'Allow members to change their Display name';
$lang['members.config.allow_users_to_change_email'] = 'Allow members to change their Email';
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
$lang['members.config.authorization-read-member-profile'] = 'Here you define the permissions to read the list of groups and members as well as some personal information such as their emails, messages and profiles.';
$lang['members.config.welcome-message'] = 'Message to all members';
$lang['members.config.welcome-message-content'] = 'Welcome message displayed in the panel member';

//Security Configuration
$lang['members.config-security'] = 'Security';
$lang['security.config.internal-password-min-length'] = 'Passwords minimal length';
$lang['security.config.internal-password-strength'] = 'Passwords strength';
$lang['security.config.internal-password-strength.weak'] = 'Weak';
$lang['security.config.internal-password-strength.medium'] = 'Medium (letters and digits)';
$lang['security.config.internal-password-strength.strong'] = 'Strong (lower case, upper case and digits)';
$lang['security.config.internal-password-strength.very-strong'] = 'Very strong (lower case, upper case, digits and special characters)';
$lang['security.config.login-and-email-forbidden-in-password'] = 'Forbid email and login in password';
$lang['security.config.forbidden-mail-domains'] = 'List of prohibited domain names';
$lang['security.config.forbidden-mail-domains.explain'] = 'Domains prohibited in users\' email addresses (separated by commas). Example: <b>domain.com</b>';

$lang['members.config-authentication'] = 'Authentication configuration';

//Other fieldset configuration title
$lang['members.config.avatars-management'] = 'Avatars management';
$lang['members.config.authorization'] = 'Authorizations';

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

$lang['fields.management'] = 'Profile field management';
$lang['fields.action.add_field'] = 'Add a field';

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
$lang['type.user_pmtomail'] = 'Mail notification when receiving PM';
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
$lang['field.possible_values.is_default'] = 'Is default';
$lang['field.possible_values.delete_default'] = 'Delete default value';
$lang['field.default-value'] = 'Default value';
$lang['field.read_authorizations'] = 'Read permissions of the field in the profile';
$lang['field.actions_authorizations'] = 'Read permissions of the field when creating or editing a profile';
$lang['field.refresh'] = 'Refresh';
$lang['field.display'] = 'Displayed';
$lang['field.not_display'] = 'Not displayed';

// Regex
$lang['regex.figures'] = 'Numbers';
$lang['regex.letters'] = 'Letters';
$lang['regex.figures-letters'] = 'Numbers and letters';
$lang['regex.word'] = 'Word';
$lang['regex.website'] = 'Website';
$lang['regex.mail'] = 'Mail';
$lang['regex.phone-number'] = 'Phone number';
$lang['regex.personnal-regex'] = 'Custom regular expression';

//Messages
$lang['message.success.add'] = 'The profile field <b>:name</b> has been added';
$lang['message.success.edit'] = 'The profile field <b>:name</b> has been modified';
?>
