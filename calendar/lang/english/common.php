<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 04
 * @since       PHPBoost 4.0 - 2013 08 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

//Module title
$lang['module_title'] = 'Calendar';
$lang['module_config_title'] = 'Calendar configuration';

//Config
$lang['calendar.default.contents'] = 'Event default content';

//Messages divers
$lang['calendar.notice.no_current_action'] = 'No events scheduled for this date';
$lang['calendar.notice.no_pending_event'] = 'No pending event';
$lang['calendar.notice.suscribe.event_date_expired'] = 'The event is finished, you can not suscribe.';
$lang['calendar.notice.unsuscribe.event_date_expired'] = 'The event is finished, you can not unsuscribe.';

//Titles
$lang['calendar.titles.add_event'] = 'Add event';
$lang['calendar.titles.delete_event'] = 'Delete event';
$lang['calendar.titles.delete_occurrence'] = 'Occurrence';
$lang['calendar.titles.delete_all_events_of_the_serie'] = 'All events of the serie';
$lang['calendar.titles.event_edition'] = 'Event edition';
$lang['calendar.titles.event_removal'] = 'Event removal';
$lang['calendar.titles.events_of'] = 'Events of';
$lang['calendar.titles.event'] = 'Event';
$lang['calendar.titles.repetition'] = 'Repetition';
$lang['calendar.pending'] = 'Pending events';
$lang['calendar.manage'] = 'Manage events';
$lang['calendar.events_list'] = 'Events list';
$lang['calendar.cancelled'] = 'This event has been canceled';

//Labels
$lang['calendar.labels.location'] = 'Location';
$lang['calendar.labels.map_displayed'] = 'Display address on a map';
$lang['calendar.labels.created_by'] = 'Created by';
$lang['calendar.labels.picture'] = 'Add a picture';
$lang['calendar.labels.registration_authorized'] = 'Active members registration for the event';
$lang['calendar.labels.remaining_place'] = 'Only one place left!';
$lang['calendar.labels.remaining_places'] = 'Only :missing_number places left!';
$lang['calendar.labels.max_registered_members'] = 'Maximum participant number';
$lang['calendar.labels.max_registered_members.explain'] = '0 for no limit';
$lang['calendar.labels.max_participants_reached'] = 'Maximum participants number has been reached.';
$lang['calendar.labels.last_registration_date_enabled'] = 'Set a limit registration date';
$lang['calendar.labels.last_registration_date'] = 'Last registration date';
$lang['calendar.labels.remaining_day'] = 'Last day to suscribe !';
$lang['calendar.labels.remaining_days'] = 'Only :days_left days left to suscribe !';
$lang['calendar.labels.registration_closed'] = 'Event registration is closed.';
$lang['calendar.labels.repeat_type'] = 'Repeat';
$lang['calendar.labels.repeat_number'] = 'Repeat number';
$lang['calendar.labels.repeat_times'] = 'times';
$lang['calendar.labels.repeat.never'] = 'Never';
$lang['calendar.labels.events_number'] = ':events_number events';
$lang['calendar.labels.one_event'] = '1 event';
$lang['calendar.labels.start_date'] = 'Start date';
$lang['calendar.labels.end_date'] = 'End date';
$lang['calendar.labels.contribution.explain'] = 'You are not authorized to create an event, however you can contribute by submitting one.';
$lang['calendar.labels.birthday'] = 'Birthday';
$lang['calendar.labels.birthday_title'] = 'Birthday of';
$lang['calendar.labels.participants'] = 'Participants';
$lang['calendar.labels.no_one'] = 'No one';
$lang['calendar.labels.suscribe'] = 'Suscribe';
$lang['calendar.labels.unsuscribe'] = 'Unsuscribe';
$lang['calendar.labels.cancel'] = 'Cancel the event';

//Administration
$lang['calendar.config.events.management'] = 'Events management';
$lang['calendar.config.category.color'] = 'Color';
$lang['calendar.config.items_number_per_page'] = 'Events number per page';
$lang['calendar.config.event_color'] = 'Events color';
$lang['calendar.config.members_birthday_enabled'] = 'Display members birthday';
$lang['calendar.config.birthday_color'] = 'Birthday color';

$lang['calendar.authorizations.display_registered_users'] = 'Display registered users permissions';
$lang['calendar.authorizations.register'] = 'Register permissions';

//SEO
$lang['calendar.seo.description.root'] = 'All :site\'s events.';
$lang['calendar.seo.description.pending'] = 'All pending events.';
$lang['calendar.seo.description.events_list'] = ':site\'s events list.';

//Feed name
$lang['calendar.feed.name'] = 'Events';

//Messages
$lang['calendar.message.success.add'] = 'The event <b>:title</b> has been added';
$lang['calendar.message.success.edit'] = 'The event <b>:title</b> has been modified';
$lang['calendar.message.success.delete'] = 'The event <b>:title</b> has been deleted';

//Errors
$lang['calendar.error.e_invalid_date'] = 'Invalid date';
$lang['calendar.error.e_invalid_start_date'] = 'Invalid start date';
$lang['calendar.error.e_invalid_end_date'] = 'Invalid end date';
$lang['calendar.error.e_user_born_field_disabled'] = 'The field <b>Date of birth</b> is not displayed in members profile. Please enable its display it in the <a href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Profile field management</a> to allow members to fill the field date of birth and display their birthday date in the calendar.';
?>
