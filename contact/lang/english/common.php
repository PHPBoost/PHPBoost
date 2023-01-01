<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 20
 * @since       PHPBoost 4.0 - 2013 08 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                      English                     #
####################################################

$lang['contact.module.title']        = 'Contact';

// Form
$lang['contact.form.message']         = 'Message';
$lang['contact.send.another.email']   = 'Send another email';
$lang['contact.tracking.number']      = 'tracking number';
$lang['contact.acknowledgment.title'] = 'Confirmation';
$lang['contact.acknowledgment']       = 'Your message has been successfully sent.';

// Configuration
$lang['contact.form.title']                      = 'Form title';
$lang['contact.informations.enabled']            = 'Display the informations bloc';
$lang['contact.informations.clue']               = 'This area is used to display additional information on the left, top, right or below the contact form.';
$lang['contact.informations.content']            = 'Informations bloc content';
$lang['contact.informations.position']           = 'Informations bloc position';
$lang['contact.informations.position.left']      = 'Left';
$lang['contact.informations.position.top']       = 'Top';
$lang['contact.informations.position.right']     = 'Right';
$lang['contact.informations.position.bottom']    = 'Bottom';
$lang['contact.tracking.number.enabled']         = 'Generate a tracking number for each email sent';
$lang['contact.date.in.tracking.number.enabled'] = 'Display day date in the tracking number';
$lang['contact.date.in.tracking.number.clue']    = 'Generates a tracking number like <b>yyyymmdd-number</b>';
$lang['contact.sender.acknowledgment.enabled']   = 'Send a copy of the email to the sender';
$lang['contact.authorizations.read']             = 'Permission to display the contact form';
$lang['contact.authorizations.display.field']    = 'Permission to display the field';
    // Default
$lang['contact.fieldset.title']     = 'Contact the site\'s managers';
$lang['contact.email.address']      = 'Email address';
$lang['contact.email.address.clue'] = 'Must be valid if you want to be answered';
$lang['contact.subject']            = 'Subject';
$lang['contact.subject.clue']       = 'Sum up in a few words the subject of your request';
$lang['contact.recipients']         = 'Recipient(s)';
$lang['contact.recipients.admins']  = 'Administrators';
$lang['contact.message']            = 'Message';

// Map
$lang['contact.map.location']        = 'Location on a map';
$lang['contact.map.enabled']         = 'Display the map';
$lang['contact.map.position']        = 'Position of the map';
$lang['contact.map.position.top']    = 'Above the form';
$lang['contact.map.position.bottom'] = 'Below the form';
$lang['contact.map.markers']         = 'Marker.s';

// Fields
$lang['contact.fields.add.field']        = 'Add a new field';
$lang['contact.fields.add.field.title']  = 'Add a new field in the contact form';
$lang['contact.fields.edit.field']       = 'Field edition';
$lang['contact.fields.edit.field.title'] = 'Field edition in the contact form';

// Field
$lang['contact.possible.values.email']          = 'Email addresses';
$lang['contact.possible.values.email.clue']     = 'It is possible to put more than one email address separated by a comma';
$lang['contact.possible.values.subject']        = 'Subject';
$lang['contact.possible.values.recipient']      = 'Recipient.s';
$lang['contact.possible.values.recipient.clue'] = 'The email will ve sent to the selected recipient.s if the recipients field is not displayed';

// SEO
$lang['contact.seo.description'] = ':site\'s contact form.';

// Alert messages
$lang['contact.message.success.add']             = 'The field <b>:name</b> has been added';
$lang['contact.message.success.edit']            = 'The field <b>:name</b> has been modified';
$lang['contact.message.field.name.already.used'] = 'The entered field name is already used!';
$lang['contact.message.success.email']           = 'Thank you, your email has been sent successfully.';
$lang['contact.message.acknowledgment']          = 'You should receive a confirmation email in a few minutes.';
$lang['contact.message.error.email']             = 'Sorry, your email couldn\'t be sent.';
?>
