<?php
/*##################################################
 *                            common.php
 *                            -------------------
 *   begin                : August 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 #						English						#
 ####################################################
 
//Title 
$lang['module_title'] = 'Contact';
$lang['module_config_title'] = 'Contact module configuration';

//Contact form
$lang['contact.form.message'] = 'Message';

//Admin
$lang['admin.config.title'] = 'Form title';
$lang['admin.config.informations_bloc'] = 'Informations bloc';
$lang['admin.config.display_informations_bloc'] = 'Display the informations bloc';
$lang['admin.config.informations_content'] = 'Informations bloc content';
$lang['admin.config.informations.explain'] = 'This bloc permits to display informations (i.e. a contact phone number) on the left, top, right or bottom the contact form.';
$lang['admin.config.informations_position'] = 'Informations bloc position';
$lang['admin.config.informations.position_left'] = 'Left';
$lang['admin.config.informations.position_top'] = 'Top';
$lang['admin.config.informations.position_right'] = 'Right';
$lang['admin.config.informations.position_bottom'] = 'Bottom';
$lang['admin.authorizations.read']  = 'Permission to display the contact form';
$lang['admin.authorizations.display_field']  = 'Permission to display the field';

//Fields
$lang['admin.fields.manage'] = 'Fields management';
$lang['admin.fields.manage.page_title'] = 'Contact module form fields management';
$lang['admin.fields.title.add_field'] = 'Add a new field';
$lang['admin.fields.title.add_field.page_title'] = 'Add a new field in the contact form';
$lang['admin.fields.title.edit_field'] = 'Field edition';
$lang['admin.fields.title.edit_field.page_title'] = 'Field edition in the contact form';
$lang['admin.fields.action.add_field'] = 'Add a field';
$lang['admin.fields.action.edit_field'] = 'Edit field';
$lang['admin.fields.action.delete_field'] = 'Delete field';
$lang['admin.fields.delete_field.confirm'] = 'Delete this field?';
$lang['admin.fields.update_fields_position'] = 'Change fields position';
$lang['admin.fields.no_field'] = 'No field';
$lang['admin.fields.move_field_up'] = 'Move field up';
$lang['admin.fields.move_field_down'] = 'Move field down';

//Field
$lang['admin.field.name'] = 'Name';
$lang['admin.field.description'] = 'Description';
$lang['admin.field.type'] = 'Field type';
$lang['admin.field.regex'] = 'Entry form control';
$lang['admin.field.regex-explain'] = 'You can control the form of input used. For instance, if an email address is expected, you can verify that its form is correct.<br />For advanced users, it is possible to write your own regular expression (<acronym="Perl Compatible Regular Expression">PCRE</acronym>) to control the user input.';
$lang['admin.field.predefined-regex'] = 'Predefined pattern';
$lang['admin.field.required'] = 'Required field';
$lang['admin.field.possible-values'] = 'Possible values';
$lang['admin.field.possible_values.is_default'] = 'Is default';
$lang['admin.field.possible_values.email'] = 'Mail address(es)';
$lang['admin.field.possible_values.email.explain'] = 'It is possible to put more than one mail address separated by a semi-colon';
$lang['admin.field.possible_values.recipient'] = 'Recipient(s)';
$lang['admin.field.possible_values.recipient.explain'] = 'The mail will ve sent to the selected recipient(s) if the recipients field is not displayed';
$lang['admin.field.display'] = 'Displayed';
$lang['admin.field.not_display'] = 'Not displayed';
$lang['admin.field.yes'] = 'Yes';
$lang['admin.field.no'] = 'No';

//Field type
$lang['field.type.short-text'] = 'Short text (max 255 characters)';
$lang['field.type.long-text'] = 'Long text (unlimited)';
$lang['field.type.half-text'] = 'Medium text';
$lang['field.type.simple-select'] = 'Single selection (between several values)';
$lang['field.type.multiple-select'] = 'Multiple selection (between several values)';
$lang['field.type.simple-check'] = 'Single choice (between several values)';
$lang['field.type.multiple-check'] = 'Multiple choice (between several values)';
$lang['field.type.date'] = 'Date';

// Regex
$lang['regex.figures'] = 'Numbers';
$lang['regex.letters'] = 'Letters';
$lang['regex.figures-letters'] = 'Numbers and letters';
$lang['regex.word'] = 'Word';
$lang['regex.website'] = 'Website';
$lang['regex.mail'] = 'Mail';
$lang['regex.personnal-regex'] = 'Custom regular expression';

//Messages
$lang['message.field_name_already_used'] = 'The entered field name is already used!';
$lang['message.success_saving_config'] = 'The configuration has been saved successfully';
$lang['message.success_mail'] = 'Thank you, your e-mail has been sent successfully';
$lang['message.error_mail'] = 'Sorry, your e-mail couldn\'t be sent';
?>
