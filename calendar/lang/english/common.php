<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 10
 * @since       PHPBoost 4.0 - 2013 08 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

$lang['calendar.module.title'] = 'Calendar';

$lang['calendar.item']  = 'event';
$lang['calendar.items'] = 'events';
$lang['calendar.no.category'] = 'No category';

// TreeLinks
$lang['item']  = 'event';
$lang['items'] = 'events';

// Titles
$lang['calendar.item.add']         = 'Add event';
$lang['calendar.item.edit']        = 'Event edition';
$lang['calendar.item.delete']      = 'Delete event';
$lang['calendar.my.items']         = 'My events';
$lang['calendar.member.items']     = 'Events published by';
$lang['calendar.pending.items']    = 'Pending events';
$lang['calendar.filter.items']     = 'Filter events';
$lang['calendar.items.management'] = 'Events management';
$lang['calendar.items.list']       = 'Events list';

// Labels
$lang['calendar.items.of.day']             = 'Events of';
$lang['calendar.items.of.month']           = 'Events of';
$lang['calendar.items.of.month.alt']       = 'Events of';
$lang['calendar.location']                 = 'Location';
$lang['calendar.cancelled.item']           = 'This event has been canceled';
$lang['calendar.remaining.place']          = 'Only one place left!';
$lang['calendar.remaining.places']         = 'Only :missing_number places left!';
$lang['calendar.remaining.day']            = 'Last day to suscribe !';
$lang['calendar.remaining.days']           = 'Only :days_left days left to suscribe !';
$lang['calendar.registration.closed']      = 'Event registration is closed.';
$lang['calendar.max.participants.reached'] = 'Maximum participants number has been reached.';
$lang['calendar.dates']                    = 'Event dates';
$lang['calendar.start.date']               = 'Start date';
$lang['calendar.end.date']                 = 'End date';
$lang['calendar.birthday']                 = 'Birthday';
$lang['calendar.birthday.of']              = 'Birthday of';
$lang['calendar.participants']             = 'Participants';
$lang['calendar.no.one']                   = 'No one';
$lang['calendar.suscribe']                 = 'Suscribe';
$lang['calendar.unsuscribe']               = 'Unsuscribe';
$lang['calendar.repetition']               = 'Repetition';
$lang['calendar.repeat.times']             = 'times';

// Form
$lang['calendar.delete.occurrence']           = 'Occurrence';
$lang['calendar.delete.serie']                = 'All events of the serie';
$lang['calendar.form.cancel']                 = 'Cancel the event';
$lang['calendar.form.repeat.type']            = 'Repeat';
$lang['calendar.form.repeat.number']          = 'Repeat number';
$lang['calendar.form.display.map']            = 'Display address on a map';
$lang['calendar.form.enable.registration']    = 'Active members registration for the event';
$lang['calendar.form.registration.limit']     = 'Limit subscribers number';
$lang['calendar.form.max.registered']         = 'Maximum participant number';
$lang['calendar.form.registration.deadline']  = 'Set a limit registration date';
$lang['calendar.form.last.date.registration'] = 'Last registration date';

$lang['calendar.authorizations.display.registered.users'] = 'Display registered users permissions';
$lang['calendar.authorizations.register']                 = 'Register permissions';

// Messages
$lang['calendar.suscribe.notice.expired.event.date']   = 'The event is finished, you can not suscribe.';
$lang['calendar.unsuscribe.notice.expired.event.date'] = 'The event is started or finished, you can not unsuscribe.';

// Categories
$lang['calendar.category.color'] = 'Color';

// Configuration
$lang['calendar.config.event.color']       = 'Events color';
$lang['calendar.config.display.birthdays'] = 'Display members birthday';
$lang['calendar.config.birthday.color']    = 'Birthday color';

// SEO
$lang['calendar.seo.description.root']        = 'All :site\'s events.';
$lang['calendar.seo.description.pending']     = 'All pending events.';
$lang['calendar.seo.description.member']      = 'All :author\'s events.';
$lang['calendar.seo.description.events.list'] = '    :site\'s events list.';

// Feed name
$lang['calendar.feed.name'] = 'Events';

// Messages helper
$lang['calendar.message.success.add']    = 'The event <b>:title</b> has been added';
$lang['calendar.message.success.edit']   = 'The event <b>:title</b> has been modified';
$lang['calendar.message.success.delete'] = 'The event <b>:title</b> has been deleted';

// Errors
$lang['calendar.error.invalid.date']             = 'Invalid date';
$lang['calendar.error.user.born.field.disabled'] = 'The field <b>Date of birth</b> is not displayed in members profile. Please enable its display it in the <a class="offload" href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Profile field management</a> to allow members to fill the field date of birth and display their birthday date in the calendar.';
?>
