<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 31
 * @since       PHPBoost 3.0 - 2011 03 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

####################################################
#                    English                       #
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
$lang['admin.default-contents'] = 'Default content of a BBCode or HTML newsletter';

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
$lang['newsletter.contents.explain'] = 'Use <b>:user_display_name</b> to display the member\'s nickname if needed (will be replaced by visitor for non-member members).';

//Messages
$lang['stream.message.success.add'] = 'The stream <b>:name</b> has been added';
$lang['stream.message.success.edit'] = 'The stream <b>:name</b> has been modified';
$lang['stream.message.success.delete'] = 'The stream <b>:name</b> has been deleted';
$lang['stream.message.delete_confirmation'] = 'Do you really want to delete the stream :name?';

//Errors
$lang['error.sender-mail-not-configured'] = 'The newsletter sender mail has not been configured by the administrator, please try again later.';
$lang['error.sender-mail-not-configured-for-admin'] = 'The newsletter sender mail has not been configured. Please <a href="' . NewsletterUrlBuilder::configuration()->rel() . '">configure</a> it before sending a newsletter.';
$lang['admin.stream-not-existed'] = 'The requested stream does not exist';
$lang['error-subscriber-not-existed'] = 'This registrant does not exist';
$lang['error-subscriber-exists'] = 'This email address is already subscribed to the site\'s newsletters';
$lang['error-archive-not-existed'] = 'This archive does not exist';
$lang['newsletter.success-send-test'] = 'The test email has been sent';
$lang['newsletter.message.success.add'] = 'The newsletter has been sent';
$lang['newsletter.message.success.delete'] = 'The archive has been deleted';

//Register extended field
$lang['extended_fields.newsletter.name'] = 'Subscribe to newsletters';
$lang['extended_fields.newsletter.description'] = 'Select the newsletter you wish to be registered';

//SEO
$lang['newsletter.seo.suscribe'] = 'Enter your email address and choose the newsletters you wish to receive.';
$lang['newsletter.seo.unsuscribe'] = 'Choose the newsletters that you do not want to receive anymore.';
$lang['newsletter.seo.suscribers.list'] = 'List of subscribers to the newsletter :name.';
$lang['newsletter.seo.home'] = 'List of site :site newsletters.';
$lang['newsletter.seo.archives'] = 'List of newsletters :name archives.';
?>
