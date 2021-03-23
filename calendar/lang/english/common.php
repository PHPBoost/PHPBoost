<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 23
 * @since       PHPBoost 4.0 - 2013 08 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

// Module titles
$lang['module.title'] = 'Calendar';

$lang['items'] = 'events';
$lang['item'] = 'event';

$lang['an.item'] = 'an event';
$lang['the.item'] = 'the event';
$lang['my.items'] = 'My events';
$lang['member.items'] = 'Events published by';

// Configuration
$lang['calendar.default.content'] = 'Event default content';

// Messages
$lang['calendar.notice.no.event'] = 'No event';
$lang['calendar.notice.no.pending.event'] = 'No pending event';
$lang['calendar.suscribe.notice.expired.event.date'] = 'The event is finished, you can not suscribe.';
$lang['calendar.unsuscribe.notice.expired.event.date'] = 'The event is started or finished, you can not unsuscribe.';

// Titles
$lang['calendar.event.delete'] = 'Delete event';
$lang['calendar.event.delete.occurrence'] = 'Occurrence';
$lang['calendar.event.delete.serie'] = 'All events of the serie';
$lang['calendar.event.add'] = 'Add event';
$lang['calendar.event.edit'] = 'Event edition';
$lang['calendar.events.of'] = 'Events of';
$lang['calendar.events.of.month'] = 'Events of';
$lang['calendar.event'] = 'Event';
$lang['calendar.repetition'] = 'Repetition';
$lang['calendar.pending.events'] = 'Pending events';
$lang['calendar.events.manager'] = 'Events manager';
$lang['calendar.events.list'] = 'Events list';
$lang['calendar.cancelled.event'] = 'This event has been canceled';

// Labels
$lang['calendar.labels.location'] = 'Location';
$lang['calendar.labels.map.displayed'] = 'Display address on a map';
$lang['calendar.labels.thumbnail'] = 'Add a thumbnail';
$lang['calendar.labels.registration.authorized'] = 'Active members registration for the event';
$lang['calendar.labels.registration.limit'] = 'Limit subscribers number';
$lang['calendar.labels.remaining.place'] = 'Only one place left!';
$lang['calendar.labels.remaining.places'] = 'Only :missing_number places left!';
$lang['calendar.labels.max.registered.members'] = 'Maximum participant number';
$lang['calendar.labels.max.participants.reached'] = 'Maximum participants number has been reached.';
$lang['calendar.labels.last.registration.date.enabled'] = 'Set a limit registration date';
$lang['calendar.labels.last.registration.date'] = 'Last registration date';
$lang['calendar.labels.remaining.day'] = 'Last day to suscribe !';
$lang['calendar.labels.remaining.days'] = 'Only :days_left days left to suscribe !';
$lang['calendar.labels.registration.closed'] = 'Event registration is closed.';
$lang['calendar.labels.repeat.type'] = 'Repeat';
$lang['calendar.labels.repeat.number'] = 'Repeat number';
$lang['calendar.labels.repeat.times'] = 'times';
$lang['calendar.labels.repeat.never'] = 'Never';
$lang['calendar.labels.events.number'] = ':items_number events';
$lang['calendar.labels.one.event'] = '1 event';
$lang['calendar.labels.date'] = 'Event dates';
$lang['calendar.labels.start.date'] = 'Start date';
$lang['calendar.labels.end.date'] = 'End date';
$lang['calendar.labels.contribution.explain'] = 'You are not authorized to create an event, however you can contribute by submitting one.';
$lang['calendar.labels.birthday'] = 'Birthday';
$lang['calendar.labels.birthday.of'] = 'Birthday of';
$lang['calendar.labels.participants'] = 'Participants';
$lang['calendar.labels.no.one'] = 'No one';
$lang['calendar.labels.suscribe'] = 'Suscribe';
$lang['calendar.labels.unsuscribe'] = 'Unsuscribe';
$lang['calendar.labels.cancel'] = 'Cancel the event';

// Configuration
$lang['calendar.config.events.management'] = 'Events management';
$lang['calendar.config.set.to.zero'] = 'Set to zero to disable';
$lang['calendar.config.category.color'] = 'Color';
$lang['calendar.config.items.number.per.page'] = 'Events number per page';
$lang['calendar.config.event.color'] = 'Events color';
$lang['calendar.config.members.birthday.enabled'] = 'Display members birthday';
$lang['calendar.config.birthday.color'] = 'Birthday color';

$lang['calendar.authorizations.display.registered.users'] = 'Display registered users permissions';
$lang['calendar.authorizations.register'] = 'Register permissions';

// SEO
$lang['calendar.seo.description.root'] = 'All :site\'s events.';
$lang['calendar.seo.description.pending'] = 'All pending events.';
$lang['calendar.seo.description.member'] = 'All :author\'s events.';
$lang['calendar.seo.description.events.list'] = ':site\'s events list.';

// Feed name
$lang['calendar.feed.name'] = 'Events';

// Messages
$lang['calendar.message.success.add'] = 'The event <b>:title</b> has been added';
$lang['calendar.message.success.edit'] = 'The event <b>:title</b> has been modified';
$lang['calendar.message.success.delete'] = 'The event <b>:title</b> has been deleted';

// Errors
$lang['calendar.error.invalid.date'] = 'Invalid date';
$lang['calendar.error.user.born.field.disabled'] = 'The field <b>Date of birth</b> is not displayed in members profile. Please enable its display it in the <a href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Profile field management</a> to allow members to fill the field date of birth and display their birthday date in the calendar.';
?>
