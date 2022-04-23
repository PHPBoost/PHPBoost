<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 23
 * @since       PHPBoost 3.0 - 2011 03 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

$lang['newsletter.module.title'] = 'Newsletter';

// Configuration
$lang['newsletter.email.sender']      = 'Emailing address';
$lang['newsletter.email.sender.clue'] = 'Valid email Address';
$lang['newsletter.name']              = 'Name of the newsletter';
$lang['newsletter.name.clue']         = 'Subject of email sent';
$lang['newsletter.streams.per.page']  = 'Streams number per page';
$lang['newsletter.default.content']   = 'Default content of a BBCode or HTML newsletter';

// Authorizations
$lang['newsletter.authorizations.streams.read']           = 'Read authorizations';
$lang['newsletter.authorizations.streams.manage']         = 'Streams management authorizations';
$lang['newsletter.authorizations.streams.subscribe']      = 'Authorizations to register for newsletters';
$lang['newsletter.authorizations.archives.manage']        = 'Archives read authorizations';
$lang['newsletter.authorizations.archives.moderation']    = 'Archives moderation authorizations';
$lang['newsletter.authorizations.subscribers.read']       = 'Subscribers read authorizations';
$lang['newsletter.authorizations.subscribers.moderation'] = 'Subscribers moderation authorizations';
$lang['newsletter.authorizations.item.write']             = 'Add newsletter authorizations';

// Streams
$lang['newsletter.streams.management'] = 'Feed management';
$lang['newsletter.stream.add']         = 'Add Feed';
$lang['newsletter.stream.edit']        = 'Edit Feed';
$lang['newsletter.stream.delete']      = 'Delete feed';
$lang['newsletter.stream.delete.clue'] = 'You are about to delete the stream. Two solutions are available to you. You can either move all of its content (newsletters and streams) in another stream or delete the whole stream. <strong>Note that this action is irreversible!</ strong>';
$lang['newsletter.items.list']         = 'List of newsletters';

//Hooks
$lang['newsletter.specific_hook.newsletter_subscribe'] = 'Newsletters subscription';
$lang['newsletter.specific_hook.newsletter_subscribe.description.single'] = 'User <a href=":user_profile_url">:user_display_name</a> subscribed to newsletter :streams_list';
$lang['newsletter.specific_hook.newsletter_subscribe.description'] = 'User <a href=":user_profile_url">:user_display_name</a> subscribed to the following newsletters: :streams_list';
$lang['newsletter.specific_hook.newsletter_unsubscribe'] = 'Newsletters unsubscription';
$lang['newsletter.specific_hook.newsletter_unsubscribe.description.single'] = 'User <a href=":user_profile_url">:user_display_name</a> unsubscribed to newsletter :streams_list';
$lang['newsletter.specific_hook.newsletter_unsubscribe.description'] = 'User <a href=":user_profile_url">:user_display_name</a> unsubscribed to the following newsletters: :streams_list';
$lang['newsletter.specific_hook.newsletter_unsubscribe.all'] = 'User <a href=":user_profile_url">:user_display_name</a> unsubscribed from all newsletters';

// Subscription
$lang['newsletter.subscribe.streams']     = 'Subscribe to newsletters';
$lang['newsletter.subscribe.item']        = 'Subscribe to a newsletter';
$lang['newsletter.subscribe.item.clue']   = 'Choose which newsletters you want to subscribe';
$lang['newsletter.subscriber.edit']       = 'Post a registrant';
$lang['newsletter.subscriber.email']      = 'Email';
$lang['newsletter.unsubscribe.item']      = 'Unsubscribe to a newsletter';
$lang['newsletter.unsubscribe.item.clue'] = 'Select the newsletters you want to stay or subscribe';
$lang['newsletter.delete.all.streams']    = 'Unsubscribe from all streams';
$lang['newsletter.unsubscribe.items']     = 'Unsubscribe from newsletters';

// Newsletters
$lang['newsletter.subscribers.list']     = 'list of subscribers';
$lang['newsletter.see.subscribers.list'] = 'See the list';
    //Archives
$lang['newsletter.archives']           = 'Archives';
$lang['newsletter.archives.list']      = 'Archive List';
$lang['newsletter.see.archives']       = 'View Archives';
$lang['newsletter.stream.name']        = 'Feed name';
$lang['newsletter.item.name']          = 'Newsletter name';
$lang['newsletter.archives.date']      = 'Publication date';
$lang['newsletter.subscribers.number'] = 'Number of registered';
    // Types
$lang['newsletter.types.choice']      = 'Please select a type of message';
$lang['newsletter.types.for.all']     = 'Every users';
$lang['newsletter.types.text']        = 'Text';
$lang['newsletter.types.text.clue']   = 'You can\'t make any formatting of the message.';
$lang['newsletter.types.bbcode']      = 'BBCode';
$lang['newsletter.types.bbcode.clue'] = 'You can format text using the BBCode, the simplified formatting language used throughout the portal.';
$lang['newsletter.types.html']        = 'HTML';
$lang['newsletter.types.for.experts'] = 'Only expert users';
$lang['newsletter.types.html.clue']   = 'You can format the text as you wish, but you need to know HTML language.';
    // Add
$lang['newsletter.title']          = 'Title of the newsletter';
$lang['newsletter.content']        = 'Content';
$lang['newsletter.content.clue']   = 'Use <b>:user_display_name</b> to display the member\'s nickname if needed (will be replaced by visitor for non-member members).';
$lang['newsletter.choose.streams'] = 'Choose the flow or you want to send this newsletter';
$lang['newsletter.send.test']      = 'Send a test email';
$lang['newsletter.add.item']       = 'Add newsletter';

// Messages
$lang['newsletter.stream.success.add']         = 'The stream <b>:name</b> has been added';
$lang['newsletter.stream.success.edit']        = 'The stream <b>:name</b> has been modified';
$lang['newsletter.stream.success.delete']      = 'The stream <b>:name</b> has been deleted';
$lang['newsletter.stream.delete.confirmation'] = 'Do you really want to delete the stream :name?';
$lang['newsletter.success.send.test']          = 'The test email has been sent';
$lang['newsletter.item.success.add']           = 'The newsletter has been sent';
$lang['newsletter.archive.success.delete']     = 'The archive has been deleted';

// Errors
$lang['newsletter.sender.email.not.configured.for.admin'] = 'The newsletter sender email has not been configured. Please <a class="offload" href ="' . NewsletterUrlBuilder::configuration()->rel() . '">configure</a> it before sending a newsletter.';
$lang['newsletter.sender.email.not.configured'] = 'The newsletter sender email has not been configured by the administrator, please try again later.';
$lang['newsletter.stream.not.exists']           = 'The requested stream does not exist';
$lang['newsletter.subscriber.not.exists']       = 'This registrant does not exist';
$lang['newsletter.subscriber.already.exists']   = 'This email address is already subscribed to the site\'s newsletters';
$lang['newsletter.archive.not.exists']          = 'This archive does not exist';

// Register extended fields
$lang['newsletter.extended.fields.subscribed.items'] = 'Subscribe to newsletters';
$lang['newsletter.extended.fields.select.items']     = 'Select the newsletter you wish to be registered';

// S.E.O.
$lang['newsletter.seo.suscribe']        = 'Enter your email address and choose the newsletters you wish to receive.';
$lang['newsletter.seo.unsuscribe']      = 'Choose the newsletters that you do not want to receive anymore.';
$lang['newsletter.seo.suscribers.list'] = 'List of subscribers to the newsletter :name.';
$lang['newsletter.seo.home']            = 'List of site :site newsletters.';
$lang['newsletter.seo.archives']        = 'List of newsletters :name archives.';
?>
