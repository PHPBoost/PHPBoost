<?php
/*##################################################
 *                           admin-extended-fields-common.php
 *                            -------------------
 *   begin                : December 17, 2010
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

$lang['extended-field-add'] = 'Add member field';
$lang['extended-field-edit'] = 'Edit member field';
$lang['extended-field'] = 'Member fields';
$lang['extended-fields-management'] = 'Member field management';
$lang['extended-fields-sucess-edit'] = 'The field is updated successfuly.';
$lang['extended-fields-sucess-add'] = 'The field is added successfuly.';
$lang['extended-fields-sucess-delete'] = 'The field is deleted successfuly.';
$lang['extended-fields-error-already-exist'] = 'The extended field are already exist.';
$lang['extended-fields-error-phpboost-config'] = 'The fields used by default PHPBoost can not multiple created, please choose another type field.';


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

// Regex
$lang['regex.figures'] = 'Numbers';
$lang['regex.letters'] = 'Letters';
$lang['regex.figures-letters'] = 'Numbers and letters';
$lang['regex.website'] = 'Website';
$lang['regex.mail'] = 'Mail';
$lang['regex.personnal-regex'] = 'Custom regular expression';


$lang['field.yes'] = 'Yes';
$lang['field.no'] = 'No';

$lang['field.success'] = 'Success';
$lang['field.delete_field'] = 'Would you really want to delete this field ?';
$lang['field.position'] = 'Position';

$lang['field.is-required'] = 'Required';
$lang['field.is-not-required'] = 'Not required';


//Install
$lang['field-install.default-lang'] = 'Default language';
$lang['field-install.default-lang-explain'] = 'Select the language you want to use';

$lang['field-install.default-theme'] = 'Default theme';
$lang['field-install.default-theme-explain'] = 'Select the theme you want to use';

$lang['field-install.default-editor'] = 'Default text editor';
$lang['field-install.default-editor-explain'] = 'Select the editor which with you want to format text';

$lang['field-install.timezone'] = 'Timezone';
$lang['field-install.timezone-explain'] = 'Select the timezone corresponding to the place you live';

$lang['field-install.sex'] = 'Sex';
$lang['field-install.sex-explain'] = '';

$lang['field-install.date-birth'] = 'Birth date';
$lang['field-install.date-birth-explain'] = 'MM/DD/YYYY';

$lang['field-install.avatar'] = 'Avatar';
$lang['field-install.avatar-explain'] = '';

$lang['field-install.website'] = 'Website';
$lang['field-install.website-explain'] = 'Please enter a valid url';

$lang['field-install.location'] = 'Localization';
$lang['field-install.location-explain'] = '';

$lang['field-install.job'] = 'Job';
$lang['field-install.job-explain'] = '';

$lang['field-install.entertainement'] = 'Hobbies';
$lang['field-install.entertainement-explain'] = '';

$lang['field-install.signing'] = 'Signature';
$lang['field-install.signing-explain'] = 'The signature appears beyond all your messages';

$lang['field-install.biography'] = 'Biography';
$lang['field-install.biography-explain'] = 'A few lines describing you';

$lang['field-install.msn'] = 'MSN';
$lang['field-install.msn-explain'] = '';

$lang['field-install.yahoo'] = 'Yahoo';
$lang['field-install.yahoo-explain'] = '';

?>
