<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 01
 * @since       PHPBoost 4.0 - 2013 08 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                      English                     #
####################################################

//Title
$lang['module_title'] = 'Contact';
$lang['module_config_title'] = 'Contact module configuration';

//Contact form
$lang['contact.form.message'] = 'Message';
$lang['contact.send_another_mail'] = 'Send another mail';
$lang['contact.tracking_number'] = 'tracking number';
$lang['contact.acknowledgment_title'] = 'Confirmation';
$lang['contact.acknowledgment'] = 'Your message has been successfully sent.';

//Admin
$lang['admin.config.title'] = 'Form title';
$lang['admin.config.informations_bloc'] = 'Informations bloc';
$lang['admin.config.informations_enabled'] = 'Display the informations bloc';
$lang['admin.config.informations_content'] = 'Informations bloc content';
$lang['admin.config.informations.explain'] = 'This bloc permits to display informations (i.e. a contact phone number) on the left, top, right or bottom the contact form.';
$lang['admin.config.informations_position'] = 'Informations bloc position';
$lang['admin.config.informations.position_left'] = 'Left';
$lang['admin.config.informations.position_top'] = 'Top';
$lang['admin.config.informations.position_right'] = 'Right';
$lang['admin.config.informations.position_bottom'] = 'Bottom';
$lang['admin.config.tracking_number_enabled'] = 'Generate a tracking number for each email sent';
$lang['admin.config.date_in_date_in_tracking_number_enabled'] = 'Display day date in the tracking number';
$lang['admin.config.date_in_date_in_tracking_number_enabled.explain'] = 'Allows to generate a tracking number like <b>yyyymmdd-number</b>';
$lang['admin.config.sender_acknowledgment_enabled'] = 'Send a copy of the email to the sender';
$lang['admin.authorizations.read']  = 'Permission to display the contact form';
$lang['admin.authorizations.display_field']  = 'Permission to display the field';

//Map
$lang['admin.config.map'] = 'Location on a map';
$lang['admin.config.map_enabled'] = 'Display the map';
$lang['admin.config.map_position'] = 'Position of the map';
$lang['admin.config.map.position_top'] = 'Above the form';
$lang['admin.config.map.position_bottom'] = 'Below the form';
$lang['admin.config.map.markers'] = 'Address(es)';

//Fields
$lang['admin.fields.manage'] = 'Fields management';
$lang['admin.fields.manage.page_title'] = 'Contact module form fields management';
$lang['admin.fields.title.add_field'] = 'Add a new field';
$lang['admin.fields.title.add_field.page_title'] = 'Add a new field in the contact form';
$lang['admin.fields.title.edit_field'] = 'Field edition';
$lang['admin.fields.title.edit_field.page_title'] = 'Field edition in the contact form';

//Field
$lang['field.possible_values.email'] = 'Mail address(es)';
$lang['field.possible_values.email.explain'] = 'It is possible to put more than one mail address separated by a comma';
$lang['field.possible_values.subject'] = 'Subject';
$lang['field.possible_values.recipient'] = 'Recipient(s)';
$lang['field.possible_values.recipient.explain'] = 'The mail will ve sent to the selected recipient(s) if the recipients field is not displayed';

//SEO
$lang['contact.seo.description'] = ':site\'s contact form.';

//Messages
$lang['message.success.add'] = 'The field <b>:name</b> has been added';
$lang['message.success.edit'] = 'The field <b>:name</b> has been modified';
$lang['message.field_name_already_used'] = 'The entered field name is already used!';
$lang['message.success_mail'] = 'Thank you, your e-mail has been sent successfully.';
$lang['message.acknowledgment'] = 'You should receive a confirmation email in a few minutes.';
$lang['message.error_mail'] = 'Sorry, your e-mail couldn\'t be sent.';
?>
