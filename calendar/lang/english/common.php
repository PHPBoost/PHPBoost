<?php
/*##################################################
 *                              common.php
 *                            -------------------
 *   begin                : August 20, 2013
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
# English                                          #
####################################################

$lang = array();

//Module title
$lang['module_title'] = 'Calendar';

//Messages divers
$lang['calendar.notice.no_current_action'] = 'No events scheduled for this date';
$lang['calendar.notice.no_event'] = 'No event';

//Actions
$lang['calendar.actions.confirm.del_event'] = 'Delete event?';

//Titles
$lang['calendar.titles.admin.config'] = 'Configuration';
$lang['calendar.titles.admin.authorizations'] = 'Authorizations';
$lang['calendar.titles.add_event'] = 'Add event';
$lang['calendar.titles.edit_event'] = 'Edit event';
$lang['calendar.titles.delete_event'] = 'Delete event';
$lang['calendar.titles.delete_occurrence'] = 'Occurrence';
$lang['calendar.titles.delete_all_events_of_the_serie'] = 'All events of the serie';
$lang['calendar.titles.edit_occurrence'] = 'Edit occurrence';
$lang['calendar.titles.edit_all_events_of_the_serie'] = 'Edit all events of the serie';
$lang['calendar.titles.event_edition'] = 'Event edition';
$lang['calendar.titles.event_removal'] = 'Event removal';
$lang['calendar.titles.events'] = 'Events';
$lang['calendar.titles.event'] = 'Event';

//Labels
$lang['calendar.labels.title'] = 'Title';
$lang['calendar.labels.contents'] = 'Description';
$lang['calendar.labels.location'] = 'Location';
$lang['calendar.labels.created_by'] = 'Created by';
$lang['calendar.labels.category'] = 'Category';
$lang['calendar.labels.registration_authorized'] = 'Active members registration for the event';
$lang['calendar.labels.max_registred_members'] = 'Maximum participant number';
$lang['calendar.labels.max_registred_members.explain'] = 'Set to 0 for no limit';
$lang['calendar.labels.repeat_type'] = 'Repeat';
$lang['calendar.labels.repeat_number'] = 'Repeat number';
$lang['calendar.labels.repeat.never'] = 'Never';
$lang['calendar.labels.repeat.daily'] = 'Daily';
$lang['calendar.labels.repeat.weekly'] = 'Weekly';
$lang['calendar.labels.repeat.monthly'] = 'Monthly';
$lang['calendar.labels.repeat.yearly'] = 'Yearly';
$lang['calendar.labels.date'] = 'Date';
$lang['calendar.labels.start_date'] = 'Start date';
$lang['calendar.labels.end_date'] = 'End date';
$lang['calendar.labels.approved'] = 'Approved';
$lang['calendar.labels.approved'] = 'Not approved';
$lang['calendar.labels.contribution'] = 'Contribution';
$lang['calendar.labels.contribution.explain'] = 'You aren\'t authorized to add an event, however you can contribute by submitting one. Your contribution will be processed by a moderator.';
$lang['calendar.labels.contribution.description'] = 'Contribution counterpart';
$lang['calendar.labels.contribution.description.explain'] = 'Tell us why you want us to add this event. This field is not required, but it may help the moderator to make his decision.';
$lang['calendar.labels.contribution.entitled'] = '[Calendar] :title';
$lang['calendar.labels.birthday_title'] = 'Birthday of';
$lang['calendar.labels.participants'] = 'Participants';
$lang['calendar.labels.suscribe'] = 'Suscribe';
$lang['calendar.labels.unsuscribe'] = 'Unsuscribe';

//Explications
$lang['calendar.explain.date'] = '<span class="text_small">(mm/dd/yy)</span>';

//Administration
$lang['calendar.config.manage_events'] = 'Events management';
$lang['calendar.config.category.color'] = 'Color';
$lang['calendar.config.category.manage'] = 'Manage categories';
$lang['calendar.config.category.add'] = 'Add category';
$lang['calendar.config.category.edit'] = 'Edit category';
$lang['calendar.config.category.delete'] = 'Delete category';
$lang['calendar.config.items_number_per_page'] = 'Events number per page';
$lang['calendar.config.comments_enabled'] = 'Enable comments';
$lang['calendar.config.members_birthday_enabled'] = 'Display members birthday';
$lang['calendar.config.birthday_color'] = 'Birthday color';

$lang['calendar.config.authorizations.read'] = 'Read permissions';
$lang['calendar.config.authorizations.write'] = 'Write permissions';
$lang['calendar.config.authorizations.contribution'] = 'Contribution permissions';
$lang['calendar.config.authorizations.moderation'] = 'Moderation permissions';

$lang['calendar.authorizations.display_registered_users'] = 'Display registered users permissions';
$lang['calendar.authorizations.register'] = 'Register permissions';

//Sort fields title and mode
$lang['calendar.sort_filter.title'] = 'Filter by :';
$lang['calendar.sort_mode.asc'] = 'Ascendant';
$lang['calandar.sort_mode.desc'] = 'Descendant';
$lang['calendar.config.sort_field.category'] = 'Categories';
$lang['calendar.config.sort_field.title'] = 'Title';
$lang['calendar.config.sort_field.author'] = 'Author';
$lang['calendar.config.sort_field.start_date'] = 'Start date';

//SEO
$lang['calendar.seo.description.root'] = 'All events of :site.';

//Feed name
$lang['calendar.feed.name'] = 'Events';
$lang['syndication'] = 'RSS feed';

//Success
$lang['calendar.success.config'] = 'The configuration has been modified';

//Errors
$lang['calendar.error.e_unexist_event'] = 'The selected event doesn\'t exist';
$lang['calendar.error.e_invalid_date'] = 'Invalid date';
$lang['calendar.error.e_invalid_start_date'] = 'Invalid start date';
$lang['calendar.error.e_invalid_end_date'] = 'Invalid end date';
?>
