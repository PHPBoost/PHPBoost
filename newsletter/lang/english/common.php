<?php
/*##################################################
 *                             common.php
 *                            -------------------
 *   begin                :  March 11, 2011
 *   copyright            : (C) 2011 MASSY Kevin
 *   email                : kevin.massy@phpboost.com
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

//Title
$lang['newsletter.home'] = 'Home';
$lang['newsletter'] = 'Newsletter';
$lang['newsletter.archives'] = 'Archive';
$lang['newsletter.subscribers'] = 'list of subscribers';
$lang['newsletter.streams'] = 'Feed management';
$lang['newsletter.streams.manage'] = 'Manage feeds';

//Other title
$lang['subscribe.newsletter'] = 'Subscribe to newsletters';
$lang['subscriber.edit'] = 'Post a registrant';
$lang['archives.list'] = 'Archive List';
$lang['newsletter-add'] = 'Add newsletter';
$lang['newsletter.subscribe_newsletters'] = 'Subscribe to a newsletter';
$lang['newsletter.unsubscribe_newsletters'] = 'Unsubscribe to a newsletter';
$lang['stream.add'] = 'Add Feed';
$lang['stream.edit'] = 'Edit Feed';
$lang['stream.delete'] = 'Delete feed';
$lang['stream.delete.description'] = 'You are about to delete the stream. Two solutions are available to you. You can either move all of its contents (newsletters and streams) in another stream or delete the whole stream. <strong>Note that this action is irreversible!</ strong>';
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
$lang['auth.archives-moderation'] = 'Archives moderation authorizations';
$lang['auth.subscribers-read'] = 'Subscribers read authorizations';
$lang['auth.subscribers-moderation'] = 'Subscribers moderation authorizations';
$lang['auth.subscribe'] = 'Authorizations to register for newsletters';
$lang['auth.create-newsletter'] = 'Add newsletter authorizations';
$lang['auth.manage-streams'] = 'Streams management authorizations';

//Subscribe
$lang['subscribe.mail'] = 'Mail';
$lang['subscribe.newsletter_choice'] = 'Choose which newsletters you wish to subscribe';

//Subscribers
$lang['subscribers.list'] = 'Subscribers list';
$lang['subscribers.pseudo'] = 'Username';
$lang['subscribers.mail'] = 'Mail';
$lang['subscribers.delete'] = 'Do you really want to delete this person listed ?';
$lang['subscribers.no_users'] = 'None subscribers';

//Unsubcribe
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
$lang['newsletter.types.forall'] = 'Every users';
$lang['newsletter.types.text'] = 'Text';
$lang['newsletter.types.text_explain'] = 'You can\'t make any formatting of the message.';
$lang['newsletter.types.bbcode'] = 'BBCode';
$lang['newsletter.types.bbcode_explain'] = 'You can format text using the BBCode, the simplified formatting language used throughout the portal.';
$lang['newsletter.types.html'] = 'HTML';
$lang['newsletter.types.forexpert'] = 'Only expert users';
$lang['newsletter.types.html_explain'] = 'You can format the text as you wish, but you need to know HTML language.';
$lang['newsletter.types.next'] = 'Next';

//Other
$lang['newsletter.no_newsletters'] = 'No newsletter Available';
$lang['unsubscribe_newsletter'] = 'Unsubscribe to this newsletter';
$lang['newsletter.view_archives'] = 'View Archives';
$lang['newsletter.view_subscribers'] = 'View record';
$lang['newsletter.title'] = 'Title of the newsletter';
$lang['newsletter.contents'] = 'Content';

//Messages
$lang['stream.message.success.add'] = 'The stream <b>:name</b> has been added';
$lang['stream.message.success.edit'] = 'The stream <b>:name</b> has been modified';
$lang['stream.message.success.delete'] = 'The stream <b>:name</b> has been deleted';
$lang['stream.message.delete_confirmation'] = 'Do you really want to delete the stream :name?';

//Errors
$lang['error.sender-mail-not-configured'] = 'The newsletter sender mail has not been configured by the administrator, please try again later.';
$lang['error.sender-mail-not-configured-for-admin'] = 'The newsletter sender mail has not been configured. Please <a href="' . NewsletterUrlBuilder::configuration()->rel() . '">configure</a> it before sending a newsletter.';
$lang['admin.stream-not-existed'] = 'The requested stream does not exist';
$lang['error-subscriber-not-existed'] = 'This registrant is not exist';
$lang['error-archive-not-existed'] = 'This archive does not exist';
$lang['newsletter.success-send-test'] = 'The test email has been sent';
$lang['newsletter.message.success.add'] = 'The newsletter has been sent';
$lang['newsletter.message.success.delete'] = 'The archive has been deleted';

//Register extended field
$lang['extended_fields.newsletter.name'] = 'Subscribe to newsletters';
$lang['extended_fields.newsletter.description'] = 'Select the newsletter you wish to be registered';
?>
