<?php
/*##################################################
 *                             newsletter_common.php
 *                            -------------------
 *   begin                :  March 11, 2011
 *   copyright            : (C) 2011 MASSY Kévin
 *   email                : soldier.weasel@gmail.com
 *
 *  
 ###################################################
 *
 *   This program is a free software. You can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #						English						#
 ####################################################

$lang = array();

//Title
$lang['newsletter.home'] = 'Home';
$lang['newsletter'] = 'Newsletter';
$lang['newsletter.config'] = 'Configuration';
$lang['newsletter.archives'] = 'Archive';
$lang['newsletter.subscribers'] = 'list of subscribers';
$lang['newsletter.streams'] = 'Feed management';

//Other title
$lang['subscribe.newsletter'] = 'Subscribe to newsletters';
$lang['subscriber.edit'] = 'Post a registrant';
$lang['archives.list'] = 'Archive List';
$lang['newsletter-add'] = 'Add newsletter';
$lang['newsletter.subscribe_newsletters'] = 'Subscribe to a newsletter';
$lang['newsletter.unsubscribe_newsletters'] = 'Unsubscribe to a newsletter';
$lang['streams.add'] = 'Add Feed';
$lang['streams.edit'] = 'Edit Feed';
$lang['newsletter.list_newsletters'] = 'List of newsletters';

//Admin
$lang['admin.mail-sender'] = 'Mailing address';
$lang['admin.mail-sender-explain'] = 'Valid email Address';
$lang['admin.newsletter-name'] = 'Name the newsletter';
$lang['admin.newsletter-name-explain'] = 'Subject of email sent';

//Authorizations
$lang['admin.newsletter-authorizations'] = 'Authorizations';
$lang['auth.read'] = 'Read authorizations';
$lang['auth.archives-read'] = 'Archives read authorizations';
$lang['auth.subscribers-read'] = 'Subscribers read authorizations';
$lang['auth.subscribers-moderation'] = 'Subscribers moderation authorizations';
$lang['auth.subscribe'] = 'Authorizations to register for newsletters';
$lang['auth.create-newsletter'] = 'Add newsletter authorizations';

//Categories
$lang['streams.name'] = 'Bame';
$lang['streams.description'] = 'Description';
$lang['streams.picture'] = 'Image representation';
$lang['streams.visible'] = 'View';
$lang['streams.picture-preview'] = 'Preview the image stream';
$lang['streams.auth.read'] = 'Feed access authorizations';
$lang['streams.auth.subscribers-read'] = 'Subscribers read authorizations';
$lang['streams.auth.subscribers-moderation'] = 'Subscribers moderation authorizations';
$lang['streams.auth.create-newsletter'] = 'Create newsletter authorizations';
$lang['streams.auth.subscribe'] = 'Authorizations to register for newsletter';
$lang['streams.auth.archives-read'] = 'Read archive authorizations';
$lang['streams.active-advanced-authorizations'] = 'Enable advanced permissions flow';
$lang['streams.visible-no'] = 'No';
$lang['streams.visible-yes'] = 'Yes';
$lang['streams.no_cats'] = 'No feed';

//Subscribe
$lang['subscribe.mail'] = 'Mail';
$lang['subscribe.newsletter_choice'] = 'Choose which newsletters you wish to subscribe';

//Subscribers
$lang['subscribers.list'] = 'Subscribers list';
$lang['subscribers.pseudo'] = 'Username';
$lang['subscribers.mail'] = 'Mail';
$lang['subscribers.delete'] = 'Do you really want to delete this person listed ?';
$lang['subscribers.no_users'] = 'None subscribers';

// Unsubcribe
$lang['newsletter.delete_all_streams'] = 'Unsubscribe from all streams';
$lang['unsubscribe.newsletter'] = 'Unsubscribe from newsletters';
$lang['unsubscribe.newsletter_choice'] = 'Select the newsletters you want to stay or subscribe';

//Archives
$lang['archives.stream_name'] = 'Feed name';
$lang['archives.name'] = 'Newsletter name';
$lang['archives.date'] = 'Publication date';
$lang['archives.nbr_subscribers'] = 'Number of registered';
$lang['archives.not_archives'] = 'No archive available';

//Add newsletter
$lang['add.choice_streams'] = 'Choose the flow or you want to send this newsletter';
$lang['add.send_test'] = 'Send a test email';
$lang['add.add_newsletter'] = 'Add newsletter';

//Types newsletters
$lang['newsletter.types.choice'] = 'Please select a type of message';
$lang['newsletter.types.null'] = '--';
$lang['newsletter.types.text'] = 'Text';
$lang['newsletter.types.text_explain'] = '<span style="color:green;"><strong>Pour tous</strong></span><br />Vous ne pourrez procéder à aucune mise en forme du message.';
$lang['newsletter.types.bbcode'] = 'BBCode';
$lang['newsletter.types.bbcode_explain'] = '<span style="color:green;"><strong>Pour tous</strong></span><br />Vous pouvez formater le texte grâce au BBCode, le langage de mise en forme simplifiée adopté sur tout le portail.';
$lang['newsletter.types.html'] = 'HTML';
$lang['newsletter.types.html_explain'] = '<span style="color:red;"><strong>Utilisateurs expérimentés seulement</strong></span><br />Vous pouvez mettre en forme le texte à votre guise, mais vous devez connaître le langage html.';
$lang['newsletter.types.next'] = 'Next';

//Other
$lang['newsletter.page'] = 'Page';
$lang['newsletter.no_newsletters'] = 'No newsletter Available';
$lang['unsubscribe_newsletter'] = 'Unsubscribe to this newsletter';
$lang['newsletter.view_archives'] = 'View Archives';
$lang['newsletter.view_subscribers'] = 'View record';
$lang['newsletter.title'] = 'Title of the newsletter';
$lang['newsletter.contents'] = 'Content';
$lang['newsletter.visitor'] = 'Visitor';
$lang['newsletter.submit'] = 'OK';

//Errors
$lang['admin.success-saving-config'] = 'You have successfully changed the configuration';
$lang['admin.success-add-stream'] = 'Category added successfully';
$lang['admin.stream-not-existed'] = 'The requested category does not exist';
$lang['admin.success-add-stream'] = 'The category has been added';
$lang['admin.success-edit-stream'] = 'The category has been changed';
$lang['admin.success-delete-stream'] = 'The category has been deleted';
$lang['success-subscribe'] = 'You have successfully registered for newsletters';
$lang['success-unsubscribe'] = 'You have successfully unsubscribed from newsletters';
$lang['success-delete-subscriber'] = 'You removed the subscriber successfully';
$lang['success-edit-subscriber'] = 'You have edited the registrant successfully';
$lang['error-subscriber-not-existed'] = 'This registrant is not exist';
$lang['error-archive-not-existed'] = 'This archive does not exist';
$lang['newsletter.success-add'] = 'The newsletter has been added and sent';
$lang['newsletter.success-send-test'] = 'The test email has been sent';

//Authorizations
$lang['newsletter.not_level'] = 'You do not have permissions';
$lang['errors.not_authorized_read'] = 'You do not have permission to view this page';
$lang['errors.not_authorized_subscribe'] = 'You do not have permissions to register';
$lang['errors.not_authorized_read_subscribers'] = 'You do not have permissions to view the subscribers';
$lang['errors.not_authorized_moderation_subscribers'] = 'You do not have permissions to moderate and manage enrollees';
$lang['errors.not_authorized_create_newsletters'] = 'You do not have permissions to create a newsletter';
$lang['errors.not_authorized_read_archives'] = 'You do not have permissions to view archived';

//Register extended field
$lang['extended_fields.newsletter.name'] = 'Subscribe to newsletters';
$lang['extended_fields.newsletter.description'] = 'Select the newsletter you wish to be registered';
?>